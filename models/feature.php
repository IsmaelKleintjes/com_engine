<?php defined('_JEXEC') or die('Restricted access');
 
class EngineModelFeature extends JModelAdmin
{	
	public function getTable($type = 'Feature', $prefix = 'EngineTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.feature.data', array());

		if (empty($data)) {
			$data = $this->getItem();
			
			// Prime some default values.
			if ($this->getState('feature.id') == 0) {
				$app = JFactory::getApplication();

			}
		}
        $session = JFactory::getSession();
        if($session->get("feature.cat_id"))
        {

            $data->cat_id = $session->get("feature.cat_id");
            $session->clear("feature.cat_id");
        }
		return $data;
	}

	public function getForm($data = array(), $loadData = true)
	{
		return $this->loadForm('com_' . COMPONENT . '.feature', 'feature', array('control' => 'jform', 'load_data' => $loadData));
	}

    public function save($data)
    {
        if(!$data['alias']) {
            $data['alias'] = JFilterOutput::stringURLUnicodeSlug( $data['title'] );
        }

        if($this->checkAliasExists($data['alias'], $data['cat_id'], $data['id'])) {
            JFactory::getApplication()->enqueueMessage('De alias van deze feature bestaat al, dit kan ook een object zijn welke naar de prullenbak is verplaatst.', 'error');
            return false;
        }

        return parent::save(EngineHelper::dataBeforeSave($data));
    }

    public function checkAliasExists($alias, $catId, $featureId)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('alias');
        $query->from($db->qn('#__eng_feature'));
        $query->where($db->qn('alias') . ' = ' . $db->q($alias));
        $query->where($db->qn('cat_id') . ' = ' . (int)$catId);
        $query->where($db->qn('id') . ' <> ' . (int)$featureId);

        $db->setQuery($query, 0, 1);
        $alias = $db->loadResult();

        return (strlen($alias));
    }

    public function getItemByTitle($title, $catId = 0)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select("*");
        $query->from("#__eng_feature");
        $query->where("title = '{$db->escape($title)}'");
        $query->where("published = 1");

        if($catId > 0){
            $query->where("cat_id = '{$db->escape($catId)}'");
        }

        $db->setQuery($query);
        return $db->loadObject();
    }

    public function getById($id = null)
    {
        if(!$id) {
            return false;
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select("*");
        $query->from($db->qn("#__eng_feature"));
        $query->where($db->qn("id") . " = " .(int)$id);

        $db->setQuery($query);
        $item = $db->loadObject();

        return $item;
    }


	public function getImportItem($title, $catId)
    {
        if(!strlen($title)){
            return null;
        }

        $feature = $this->getItemByTitle($title, $catId);

        if($feature->id > 0){
            return $feature->id;
        }

        $db = JFactory::getDBO();
        $insert = (object) array(
            'id' => 0,
            'title' => $title,
            'cat_id' => $catId
        );

        $db->insertObject('#__eng_feature', $insert, 'id');

        $featureId = $insert->id;

        return $featureId;
    }

	public function featured($pks, $value = 0)
	{
		return EngineHelper::modelFeatured( $pks, $value, '#__eng_feature' );
	}
}
