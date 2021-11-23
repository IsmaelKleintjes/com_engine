<?php defined('_JEXEC') or die('Restricted access');

class EngineModelBlank extends JModelAdmin
{
    public function getTable($type = 'Blank', $prefix = 'EngineTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.blank.data', array());

        if(empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getForm($data = array(), $loadData = true)
    {
        return $this->loadForm('com_' . COMPONENT . '.blank', 'blank', array('control' => 'jform', 'load_data' => $loadData));
    }

    public function save($data)
    {
        if(!parent::save(EngineHelper::dataBeforeSave($data))){
            return false;
        }

        $blankId = $this->getState('blank.id');

        return $blankId;
    }
}
