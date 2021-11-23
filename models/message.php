<?php defined('_JEXEC') or die('Restricted access');
 
class EngineModelMessage extends JModelAdmin
{
	public function getTable($type = 'Message', $prefix = 'EngineTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.message.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}

	public function getForm($data = array(), $loadData = true)
	{
		return $this->loadForm('com_' . COMPONENT . '.message', 'message', array('control' => 'jform', 'load_data' => $loadData));
	}
	
	public function save($data)
	{	
		if(!parent::save(EngineHelper::dataBeforeSave($data))) {
			return false;
		}

		$mailId = $this->getState('message.id');
		language4UHelper::save($mailId, 'message');

		return true;
	}
	
	public function sendMail($id)
	{		
		$email = Input4U::get("email");
		Message4U::send(array(
			'id' => (int)$id,
			'subject' => '',
			'to' => array( $email ),
			'replaces' => array(
				'BASE' => JUri::base()."../",
			)
		));
		
		return true;
	}
}
