<?php defined('_JEXEC') or die('Restricted access');
 
class EngineModelMessagetemplate extends JModelAdmin
{
	public function getTable($type = 'Messagetemplate', $prefix = 'EngineTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.messagetemplate.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}

	public function getForm($data = array(), $loadData = true)
	{
		return $this->loadForm('com_' . COMPONENT . '.messagetemplate', 'messagetemplate', array('control' => 'jform', 'load_data' => $loadData));
	}
	
	public function save($data)
	{	
		return parent::save(EngineHelper::dataBeforeSave($data));
	}

    public function test($messageTemplateId)
    {
        $data = Input4U::getArray('jform');

        $session = JFactory::getSession();
        $session->set('test_email',$data['test_email']);
        $session->set('test_message_id',$data['test_message_id']);

        if( strlen($data['test_message_id']) && !empty($data['test_email']) )
        {
            Message4U::send(array(
                'trigger' => $data['test_message_id'],
                'to' => array($data['test_email']),
                'replaces' => array(
                    'BASE' => JUri::root()
                )
            ));


            return true;
        }
        return false;
    }

}
