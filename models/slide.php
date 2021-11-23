<?php defined( '_JEXEC' ) or die;

class EngineModelSlide extends JModelAdmin
{
	public function getTable($type = 'Slide', $prefix = 'EngineTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.slide.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}

	public function getForm($data = array(), $loadData = true)
	{
		return $this->loadForm('com_' . COMPONENT . '.slide', 'slide', array('control' => 'jform', 'load_data' => $loadData));
	}
	
	public function save($data)
	{
		return parent::save(EngineHelper::dataBeforeSave($data));
	}
}
