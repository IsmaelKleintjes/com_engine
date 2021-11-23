<?php

class Media4U {

    protected static $table = "eng";
    protected static $map = "com_engine";
    protected static $component = "com_engine";

    public function create( $object, $object_id, $type=false )
    {
        $doc = JFactory::getDocument();

        $doc->addScript('https://code.jquery.com/ui/1.12.0/jquery-ui.min.js');
        $doc->addScript(JURI::root().'administrator/components/'.self::$component.'/assets/js/jquery.fancybox.js');
        $doc->addScript(JURI::root().'administrator/components/'.self::$component.'/assets/js/jquery.uploadifive.min.js');
        $doc->addScript(JURI::root().'administrator/components/'.self::$component.'/assets/js/media4u.js');
        $doc->addStyleSheet(JURI::root().'administrator/components/'.self::$component.'/assets/css/media4u.css');
        $doc->addStyleSheet(JURI::root().'administrator/components/'.self::$component.'/assets/css/jquery.fancybox.css');

        $quote = "'";
        if(!$type)
        {
            $type = "false";
            $quote = "";
        }

        $html .= "<script type='text/javascript'>url = '".JURI::base()."administrator/'; object = '".$object."'; object_id = '".$object_id."'; sizeLimit = '".self::return_kilobytes(ini_get('upload_max_filesize'))."KB'; fileType = ".$quote.$type.$quote.";</script>";
        $html .= "<input id='file_upload' name='file_upload' type='file' multiple>";
        $html .= "<br /><br /><div id='queue'></div>";

        return $html;
    }

    public function createVideo( $object, $object_id )
    {
        $html = "<script type='text/javascript'>object = '".$object."'; object_id = '".$object_id."';</script>";
        $html .= "<input type='text' id='videoUrl' />";
        $html .= "<button type='button' onclick='saveVideoUrl()' class='btn btn-small btn-success'><span class='icon-plus'></span></button>";

        return $html;
    }

    public function saveVideo()
    {
        $data = json_decode(JRequest::getVar("data"));

        $file = new stdClass;
        $file->table = $data->object;
        $file->object_id = $data->object_id;
        $file->type = 'video';
        $file->label = $data->url;
        $file->url = $data->url;
        $file->ordering = self::getOrder($file->object_id);


        $db = JFactory::getDBO();
        $db->insertObject("#__".self::$table."_media", $file);

        if (strpos($file->url,'youtu')) {
            $preview = "";
        } elseif(strpos($file->url,'vimeo')) {
            $preview = "";
        } else {
            $preview = "";
        }

        $json = array(
            "name" => $file->label,
            "id" => $db->insertId(),
            "error" => "false",
            "url" => $file->url,
            "preview" => $preview,
        );

        echo json_encode($json);
    }

    public function getTable( $object, $object_id, $width='24' , $height='24' )
    {
        $items = self::getItems( $object, $object_id );
        $images = JHtmlImage::getAll($object_id, $object, $width, $height, 1, 10, 'mediaXref');

        $html .= "<div id='uploadMessages'></div>";
        $html .= "<div id='uploadErrors'></div>";
        $html .= "<table class='table table-striped sortable'>";
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<td class='hidden-phone order' class='phone-width'><b>" . JText::_("Volgorde") . "</b></td>";
        $html .= "<td class='phone-width'><b>" . JText::_("Standaard afbeelding") . "</b></td>";
        $html .= "<td class='phone-width'><b>" . JText::_("Naam") . "</b></td>";
        $html .= "<td class='phone-width'><b>" . JText::_("Alt Tag") . "</b></td>";
        $html .= "<td class='phone-width'><b>" . JText::_("Voorbeeld") . "</b></td>";
        $html .= "<td class='phone-width'><b>" . JText::_("Verwijderen") . "</b></td>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
        $image = 0;
        foreach($items as $i => $item):
            if($item->default == 1){
                $class = "icon-star";
            } else {
                $class = "icon-star-empty";
            }
            $segments = explode("/", $item->url);
            $name = $segments[count($segments)-1];
            $html .= "<tr object_id='".$item->id."' class='row$i dndlist-sortable'>";
            $html .= "<td class='order hidden-phone'><span><i class='icon-menu icon-align-justify sortable-handler'></i></span></td>";
            if($item->type == 'image') {
                $html .= "<td class='phone-center'><div class='btn uploadDefault btn-small'><i class='".$class."'></i></div></td>";
            } else {
                $html .= "<td class='phone-center'></div></td>";
            }

            $html .= "<td>".$item->label."</td>";

            $html .= '<td>';
            $html .= '<input type="text" class="inputbox" value="' . $item->alt_tag . '" name="mediaxref[' . $item->id . '][alt_tag]"/>';
            $html .= '</td>';

            if($item->type == "image"){
                $html .= "<td><center>".$images[$image]->image."</center></td>";
                $image++;
            } else {
                $html .= "<td><center><a target='_blank' href='".$item->url."'>Bekijk video</a></center></td>";
            }
            $html .= "<td class='phone-center'>";
            $html .= "<div class='btn btn-danger btn-mini uploadDelete'><i class='icon-delete icon-trash icon-white'></i></div>";
            $html .= "</td>";
            $html .= "</tr>";
        endforeach;
        $html .= "</tbody>";
        $html .= "</table>";

        return $html;
    }

    public function exists()
    {
        $object = Input4U::get('object', 'REQUEST');
        $objectId = Input4U::getInt('object_id', 'REQUEST');
        $filename = Input4U::get('filename', 'REQUEST');

        $dir = JPATH_SITE . '/media/' . self::$map . '/' . $object . '/' . $objectId;

        echo JFile::exists($dir . '/' . $filename) ? 1 : 0;
    }

    public function getItems( $object, $object_id )
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select( "*" );
        $query->from( "#__".self::$table."_media" );
        $query->where( "`table` = '".$db->escape($object)."'" );
        $query->where( "object_id = ".$db->escape($object_id) );
        $query->order( "ordering ASC" );

        $db->setQuery($query);
        return $db->loadObjectList();

    }

    public function return_kilobytes($val)
    {
        return substr($val,0,-1) * 1024;
    }

    public function getOrder($object_id)
    {
        $db = JFactory::getDBO();
        $query= $db->getQuery(true);

        $query->select("COUNT(id) AS count");
        $query->from("#__".self::$table."_media");
        $query->where("object_id = ".$object_id);

        $db->setQuery($query);
        return $db->loadObject()->count;
    }

    public function getSizes()
    {
        return array(
            // 0 = x, 1 = y
            array(640, 480),
            array(800, 600),
            array(1024, 768),
            array(250, 180)
        );
    }

    public function getCount( $id )
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select("COUNT(id) AS count");
        $query->from("#__".self::$table."_media");
        $query->where("object_id = ".$id);

        $db->setQuery($query);
        return $db->loadObject()->count;
    }

    public function save()
    {
        $file_type = explode("/", $_FILES[key($_FILES)]["type"]);
        $file = new stdClass;
        $data = explode("-", key($_FILES));
        $file->table = $_POST["object"];
        $file->object_id = $_POST["object_id"];
        $file->type = $file_type[0];
        $file->label = $_FILES[key($_FILES)]["name"];
        $file->ordering = self::getOrder($file->object_id);

        $pathinfo = pathinfo($file->label);

        $file->alt_tag = $pathinfo['filename'];

        $split = explode(".", $_FILES[key($_FILES)]["name"]);
        $extension = $split[count($split)-1];

        if(!self::isImage($_FILES[key($_FILES)]['tmp_name'], $_FILES[key($_FILES)]["name"])){
            echo json_encode(array("error" => "U kunt alleen afbeeldingen uploaden."));
            exit();
        }

        $count = self::getCount($file->object_id);
        if($count == 0){
            $file->default = 1;
        }

        $db = JFactory::getDBO();
        $dir = JPATH_SITE."/media/".self::$map."/".$file->table."/".$file->object_id;

        $url = $file->table."/".$file->object_id."/".$file->label;

        $removeId = 0;
        if(JFile::exists($dir ."/".$file->label)){
            $removeId = self::removeExistingImage($file->table, $file->object_id, $file->label);
        }

        if($db->insertObject("#__".self::$table."_media", $file)){
            $update = new stdClass;
            $update->id = $db->insertId();
            $update->url = "media/".self::$map."/".$url;

            if(JFile::upload($_FILES[key($_FILES)]["tmp_name"], $dir."/".$file->label)){
                self::image_fix_orientation($dir."/".$file->label);

                if($file->type == "image"){
                    $false = false;
                    foreach(self::getSizes() as $size){
                        if(!JHtmlImage::cache( 'media/'.self::$map.'/'.$url, '/cache/'.self::$map.'/'.$file->table.'/'.$file->object_id, $file->table, $size[0], $size[1] )){
                            $false = true;
                        }
                    }
                }

                if(isset($false) && $false){
                    echo json_encode(array("error" => "Kan cache niet creeëren!"));
                    exit();
                    die;
                } else {
                    $db->updateObject("#__".self::$table."_media", $update, "id");
                }
            } else {
                echo json_encode(array("error" => "Kan bestand niet uploaden!"));
                exit();
                die;
            }
        } else {
            echo json_encode(array("error" => "Kan bestand niet opslaan in de database!"));
            exit();
            die;
        }

        $name = explode(".", $file->label);
        $filename = "";
        foreach($name as $i => $part){
            if($i != count($name)-1){
                $filename .= $part.".";
            }
        }
        $realname = substr($filename, 0, -1)."_250x180.".$name[count($name)-1];
        $preview = substr($filename, 0, -1)."_250x180.".$name[count($name)-1];

        if(isset($file->default)){ $default = "1"; } else { $default = "0"; };

        $json = array(
            "name" => $_FILES[key($_FILES)]["name"],
            "id" => $update->id,
            "error" => "false",
            "default" => $default,
            'remove_id' => $removeId,
            'alt_tag' => $file->alt_tag
        );


        if($file->type == "image")
        {
            $json["url"] = "media/".self::$map."/".$file->table."/".$file->object_id."/".$realname;
            $json["preview"] = "media/".self::$map."/".$file->table."/".$file->object_id."/".$preview;
            $json['full_image'] = JUri::base() . "media/".self::$map."/".$file->table."/".$file->object_id."/".$file->label;
        } else {
            $json["url"] = "administrator/components/com_engine/assets/img/file.png";
            $json["preview"] = "administrator/components/com_engine/assets/img/file.png";
        }
        echo json_encode(
            $json
        );
    }

    public function image_fix_orientation($file)
    {
        ini_set('memory_limit', '2G');

        $exif = exif_read_data($file);

        if(!strlen($exif['Orientation'])){
            return;
        }

        $info = pathinfo($file);

        $extension = $info['extension'];

        switch($extension){
            default:
            case 'jpeg':
            case 'jpg': {
                $image = imagecreatefromjpeg($file);
                break;
            }
            case 'png': {
                $image = imagecreatefrompng($file);
                break;
            }
            case 'gif': {
                $image = imagecreatefromgif($file);
                break;
            }
        }

        switch ($exif['Orientation']) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;

            case 6:
                $image = imagerotate($image, -90, 0);
                break;

            case 8:
                $image = imagerotate($image, 90, 0);
                break;
        }

        switch($extension){
            default:
            case 'jpeg':
            case 'jpg': {
                imagejpeg($image, $file, 90);
                break;
            }
            case 'png': {
                imagepng($image, $file, 90);
                break;
            }
            case 'gif': {
                imagegif($image, $file);
                break;
            }
        }
    }

    public static function isImage($fullpath, $name)
    {
        $extension = strtolower(JFile::getExt($name));

        if(!in_array($extension, array('png', 'gif', 'jpg', 'jpeg'))){
            return false;
        }

        $mimetype = self::getMimeType($fullpath);

        $compare = 'image/' . ($extension == 'jpg' ? 'jpeg' : $extension);

        return ($compare == $mimetype);
    }

    public static function isCV($fullpath, $name)
    {
        $extension = strtolower(JFile::getExt($name));

        if(!in_array($extension, array('doc', 'docx', 'pdf'))){
            return false;
        }

        $mimetype = self::getMimeType($fullpath);

        if($mimetype == 'application/pdf' && $extension == 'pdf'){
            return true;
        }
        return (strpos($mimetype, 'officedocument') !== false);
    }

    public function removeExistingImage($table, $objectId, $label)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $url = 'media/' . self::$map . '/' . $table . '/' . (int)$objectId . '/' . $label;

        $query->select('*');
        $query->from($db->qn('#__eng_media'));
        $query->where($db->qn('url') . ' = ' . $db->q($db->escape($url)));
        $query->where($db->qn('table') . ' = ' . $db->q($db->escape($table)));
        $query->where($db->qn('object_id') . ' = ' . (int)$objectId);

        $db->setQuery($query);
        $item = $db->loadObject();

        if($item->id > 0){
            $query = $db->getQuery(true);
            $query->delete($db->qn('#__eng_media'));
            $query->where($db->qn('id') . ' = ' . (int)$item->id);

            $db->setQuery($query)->execute();
        }

        if(strlen($url) && JFile::exists(JPATH_SITE . '/' . $url)){
            JFile::delete(JPATH_SITE . '/' . $url);
        }

        $pathinfo = pathinfo($url);

        $searchUrl = JPATH_SITE . '/' . $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '_*.' . $pathinfo['extension'];

        foreach(glob($searchUrl) as $filename){
            JFile::delete($filename);
        }

        return $item->id;
    }

    public static function getMimeType($file)
    {
        if(function_exists('mime_content_type')){
            return preg_replace('~^(.+);.*$~', '$1', mime_content_type($file));
        } elseif(function_exists('finfo_fopen')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $result = null;

            if (is_resource($finfo) === true)
            {
                $result = finfo_file($finfo, $file);
            }

            finfo_close($finfo);

            return $result;
        } elseif(function_exists('exif_imagetype')) {
            return image_type_to_mime_type(exif_imagetype($file));
        } elseif(function_exists('mime_content_type')) {
            return preg_replace('~^(.+);.*$~', '$1', $file);
        } elseif(function_exists('getimagesize')) {
            $imagesize = getimagesize($file);

            return $imagesize['mime'];
        }

        return null;
    }

    public function delete($id = null)
    {

        if(!$id) $id = Input4U::getInt('id', 'REQUEST');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select("url, `table`");
        $query->from("#__".self::$table."_media");
        $query->where("id = ".$id);

        $db->setQuery($query);
        $url = $db->loadObject();

        $query = $db->getQuery(true);

        $query->delete("#__".self::$table."_media");
        $query->where("id = ".$id);

        $db->setQuery($query)->execute();

        if(JFile::exists(JPATH_SITE."/".$url->url)&&!empty($url->url)){
            JFile::delete(JPATH_SITE."/".$url->url);
        }

        $pathinfo = pathinfo($url->url);

        $searchUrl = JPATH_SITE . '/' . $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '_*.' . $pathinfo['extension'];

        foreach(glob($searchUrl) as $filename){
            JFile::delete($filename);
        }
    }

    public function setDefault()
    {
        $db = JFactory::getDBO();

        $objects = new stDClass;
        $objects->object_id = $_POST["object_id"];
        $objects->default = 0;

        $default = new stdClass;
        $default->id = $_POST["id"];
        $default->default = 1;

        $db->updateObject("#__".self::$table."_media", $objects, 'object_id');
        $db->updateObject("#__".self::$table."_media", $default, 'id');
    }

    public function getDefault( $object, $object_id )
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select("*");
        $query->from("#__".self::$table."_media");
        $query->where("`table` = '".$db->escape($object)."'");
        $query->where("`object_id` = '".$db->escape($object_id)."'");
        $query->order("`default` DESC, `ordering` ASC");

        $db->setQuery($query);
        $item = $db->loadObject();

        return $item;
    }

    public function saveOrder()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->update("#__".self::$table."_media");

        $items = new stdClass;
        foreach(json_decode(JRequest::getVar("data")) as $order => $id){
            $set .= "WHEN ".$id." THEN ".$order." ";
            $in .= $id.", ";
        }

        $query->set("ordering = CASE id ".$set."END");
        $query->where("id IN (".substr($in, 0, -2).")");

        $db->setQuery($query);
        $db->Query();
    }

    public function saveImage()
    {
        $label = JRequest::getVar('label');
        $id = JRequest::getVar('id');

        $db = JFactory::getDBO();

        $image = new stdClass;
        $image->id = $id;
        $image->label = $label;

        $db->updateObject("#__".self::$table."_media", $image, "id");
    }

    public function saveLabels($data)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        foreach($data as $id => $label){
            $set .= "WHEN ".$id." THEN '".$label."' ";
            $in .= $id.", ";
        }

        $query->update("#__".self::$table."_media");
        $query->set("label = CASE id ".$set."END");
        $query->where("id IN (".substr($in, 0, -2).")");

        $db->setQuery($query);
        return $db->Query();
    }

    public static function saveFromModel()
    {
        $mediaxref = Input4U::getArray('mediaxref', 'REQUEST');

        $db = JFactory::getDBO();

        foreach($mediaxref as $id => $media){
            $update = new stdClass();
            $update->id = $id;
            $update->alt_tag = $media['alt_tag'];

            $db->updateObject('#__eng_media', $update, 'id');
        }
    }
}
