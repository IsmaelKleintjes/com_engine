<?php

jimport('PHPImageWorkshop.ImageWorkshop');
use PHPImageWorkshop\ImageWorkshop; // Use the namespace of ImageWorkshop

class File4U
{
    public $model;
    public $parentId;

    public static function uploadSingle(JModelAdmin $model, $parentId, $field, $folder, $imageOnly = true)
    {
        jimport('joomla.filesystem.folder');

        $files = Input4U::getFiles('jform');

        $file = $files[$field];

        $uploadPath = '/media/com_engine/' . $folder . '/' . $parentId . '/';

        if(Input4U::get('jform_' . $field . '_delete') == '1'){
            $model->parentSave(array('id' => $parentId, $field => ''));
        }

        if(Input4U::get('jform_' . $field . '_delete') == '1' || $file['error'] == 0){
            if(JFolder::exists(JPATH_ROOT . $uploadPath)){
                JFolder::delete(JPATH_ROOT . $uploadPath);
            }
        }

        if($file['error'] > 0){
            return true;
        }

        if($imageOnly){
            if(!Media4U::isImage($file['tmp_name'], $file["name"])){
                $model->setError('U kunt alleen afbeeldingen uploaden.');

                return false;
            }
        }

        if(!JFolder::exists(JPATH_ROOT . $uploadPath)){
            JFolder::create(JPATH_ROOT . $uploadPath);
        }

        //App::cdirs($uploadPath);

        $fileUrl = $uploadPath . $file['name'];

        if(!JFile::upload($file['tmp_name'], JPATH_ROOT . $fileUrl)){
            $model->setError('Er is iets fout gegaan tijdens het uploaden. Probeer het opnieuw!');
            return false;
        }

        return $model->parentSave(array('id' => $parentId, $field => $fileUrl));
    }

    public function uploadImage(JModelAdmin $model, $parentId, $field, $folder)
    {
        $files = Input4U::getFiles('jform');

        $file = $files[$field];

        if($file['error'] > 0){
            return true;
        }

        $allowedExtensions = array('jpg', 'png', 'jpeg');
        $info = pathinfo($file['name']);

        if(!in_array(strtolower($info['extension']), $allowedExtensions)){
            $model->setError(JText::_('__GLOBAL_IMAGE_ERROR_EXTENSION'));
            return false;
        }

        $secret = Input4U::getCfg("secret");
        $hash = md5($parentId.$folder.$secret);

        $uploadPath = '/media/' . $folder . '/' . $parentId . '/' . $hash . '/';

        if(!JFolder::exists(JPATH_ROOT . $uploadPath)){
            JFolder::create(JPATH_ROOT . $uploadPath);
        }

        $originalFileUrl = $uploadPath . $file['name'];

        if(!JFile::upload($file['tmp_name'], JPATH_ROOT . $originalFileUrl)){
            $model->setError('Er is iets fout gegaan tijdens het uploaden. Probeer het opnieuw!');
            return false;
        }

        $params = JComponentHelper::getParams('com_engine');
        $lowResWidth = $params->get('low_res_width');
        $lowResHeight = $params->get('low_res_height');

        //save low res
        if(!self::resizeImage($originalFileUrl, '/media/' . $folder . '/' . $parentId . '/', $lowResWidth, $lowResHeight, $file['name'])){
            $model->setError('Er is iets fout gegaan tijdens het uploaden. Probeer het opnieuw!');
            return false;
        }


        //save thumbnail
        if(!self::resizeImage($originalFileUrl, '/media/' . $folder . '/' . $parentId . '/', 600, 400, 'thumbnail.'.$info['extension'])){
            $model->setError('Er is iets fout gegaan tijdens het uploaden. Probeer het opnieuw!');
            return false;
        }

        return $model->parentSave(array('id' => $parentId, $field => $file['name'], 'hash' => $hash));
    }

    public function resizeImage($url, $folder, $width, $height, $filename)
    {
        if(empty($filename)) {
            $path_parts = pathinfo( $url );
            $filename = $path_parts['filename']."_".$width."x".$height.".".$path_parts['extension'];
        }


        $thumbnail = ImageWorkshop::initFromPath( JPATH_ROOT .'/'. $url);
        $thumbnail->resizeInPixel( $width, $height, true);

        $thumbnail->save( JPATH_ROOT . '/' . $folder, $filename );

        return true;
    }


    public function base64($field, $folder, $base64)
    {
        $file = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));

        if(!$file) {
            return false;
        }

        $uploadPath = '/files/' . $folder . '/' . $this->parentId . '/';

        if(!JFolder::exists(JPATH_ROOT . $uploadPath)){
            JFolder::create(JPATH_ROOT . $uploadPath);
        }

        $fileUrl = $uploadPath . $field . '.png';

        if(!JFile::write(JPATH_ROOT . $fileUrl, $file)){
            $this->model->setError(JText::_('__GLOBAL_IMAGE_ERROR_UPLOAD'));
            return false;
        }

        return $this->model->parentSave(array('id' => $this->parentId, $field => $fileUrl));
    }

    public function multiple()
    {

    }

    public static function getItemsByType($type)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->qn('#__engine_file'));
        $query->where($db->qn('type') . ' = ' . $db->q((string)$type));
        $query->order($db->qn('created') . ' ASC');

        $db->setQuery($query);
        return $db->loadObjectList();
    }

    public static function delete($id)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('url');
        $query->from($db->qn('#__engine_file'));
        $query->where($db->qn('id') . ' = ' . (int)$id);

        $db->setQuery($query);

        $url = $db->loadResult();

        if(strlen($url)){
            JFile::delete(JPATH_ROOT . $url);

            $pathinfo = pathinfo($url);

            JFolder::delete(JPATH_ROOT . $pathinfo['dirname']);
        }

        $query = $db->getQuery(true);

        $query->delete($db->qn('#__engine_file'));
        $query->where($db->qn('id') . ' = ' . (int)$id);

        $db->setQuery($query)->execute();
    }
}