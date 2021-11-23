<?php defined( '_JEXEC' ) or die;

class EngineModelDownload extends JModelAdmin
{
	public function getTable($type = 'Download', $prefix = 'EngineTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.download.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
        return $data;
	}

	public function getForm($data = array(), $loadData = true)
	{
		return $this->loadForm('com_' . COMPONENT . '.download', 'download', array('control' => 'jform', 'load_data' => $loadData));
	}
	
	public function save($data)
	{	
		return parent::save(EngineHelper::dataBeforeSave($data));
	}
	

}
