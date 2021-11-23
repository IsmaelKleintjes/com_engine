<?php defined('_JEXEC') or die('Restricted access');

class EngineModelProduct extends JModelAdmin
{
    public function getTable($type = 'Product', $prefix = 'EngineTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.product.data', array());

        if(empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getForm($data = array(), $loadData = true)
    {
        return $this->loadForm('com_' . COMPONENT . '.product', 'product', array('control' => 'jform', 'load_data' => $loadData));
    }

    public function save($data)
    {
        if(!parent::save(EngineHelper::dataBeforeSave($data))){
            return false;
        }

        $productId = $this->getState('product.id');

        return $productId;
    }
}
