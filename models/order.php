<?php defined('_JEXEC') or die('Restricted access');

class EngineModelOrder extends JModelAdmin
{
    public function getTable($type = 'Order', $prefix = 'EngineTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.order.data', array());

        if(empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getForm($data = array(), $loadData = true)
    {
        return $this->loadForm('com_' . COMPONENT . '.order', 'order', array('control' => 'jform', 'load_data' => $loadData));
    }

    public function save($data)
    {
        if(!parent::save(EngineHelper::dataBeforeSave($data))){
            return false;
        }

        $orderId = $this->getState('order.id');

        return $orderId;
    }
}
