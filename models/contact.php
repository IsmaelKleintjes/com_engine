<?php defined('_JEXEC') or die('Restricted access');

class EngineModelContact extends JModelAdmin
{
    public function getTable($type = 'Contact', $prefix = 'EngineTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.contact.data', array());

        if(empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getForm($data = array(), $loadData = true)
    {
        return $this->loadForm('com_' . COMPONENT . '.contact', 'contact', array('control' => 'jform', 'load_data' => $loadData));
    }

    public function save($data)
    {
        if(!parent::save(EngineHelper::dataBeforeSave($data))){
            return false;
        }

        $contactId = $this->getState('contact.id');

        return $contactId;
    }
}
