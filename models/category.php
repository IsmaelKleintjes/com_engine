<?php defined( '_JEXEC' ) or die;

class EngineModelCategory extends JModelAdmin
{
	public function getTable($type = 'category', $prefix = 'EngineTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{
        $crud = Input4U::get("crud", "GET");
		$data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.category.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}

        $data->crud = $crud;
		return $data;
	}

	public function getForm($data = array(), $loadData = true)
	{
		return $this->loadForm('com_' . COMPONENT . '.category', 'category', array('control' => 'jform', 'load_data' => $loadData));
	}
	
	public function save($data)
	{	
		if(!parent::save(EngineHelper::dataBeforeSave($data))) {
			return false;
		}

		$categoryId = $this->getState('category.id');
		language4UHelper::save($categoryId, 'category');

		return true;
	}
	
	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		\Joomla\Utilities\ArrayHelper::toInteger($pks);

		if (empty($pks)) {
			$this->setError(JText::_('COM_CONTENT_NO_ITEM_SELECTED'));
			return false;
		}

		$db = $this->getDbo();

        $query = $db->getQuery(true);
        $query->update('#__eng_category');
        $query->set($db->quoteName('featured').' = '.(int)$value);
        $query->where($db->quoteName('id'),'IN ('.implode(',', $pks).')');
        $db->setQuery($query);
        $db->execute();

		$this->cleanCache();

		return true;
	}

}
