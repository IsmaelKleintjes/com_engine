<?php defined('_JEXEC') or die('Restricted access');
 
class EngineModelMessagelog extends JModelAdmin
{
	public function getTable($type = 'Messagelog', $prefix = 'EngineTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.messagelog.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}

	public function getForm($data = array(), $loadData = true)
	{
	    return $this->loadForm('com_' . COMPONENT . '.messagelog', 'messagelog', array('control' => 'jform', 'load_data' => $loadData));
	}
	
	public function save($data)
	{
		return parent::save(EngineHelper::dataBeforeSave($data));
	}

	public function getItem($id = null)
    {
        if(!$id) $id = Input4U::getInt('id', 'REQUEST');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('log.*');
        $query->select($db->qn('us.name', 'user_name'));
        $query->from($db->qn('#__eng_message_log', 'log'));
        $query->leftJoin($db->qn('#__users', 'us') . ' ON ' . $db->qn('log.user_id') . ' = ' . $db->qn('us.id'));
        $query->where($db->qn('log.id') . ' = ' . (int)$id);

        $db->setQuery($query, 0, 1);
        return $db->loadObject();
    }

    public function resendEmail($item)
    {
        Message4U::send(array(
            'id' => (int)$item->message_id,
            'user_id' => $item->user_id,
            'subject' => $item->email_subject,
            'to' => $item->email_to,
            'body' => $item->email_body,
            'type' => array(
                'is_email' => 1,
                'is_notification' => 0
            )
        ));
    }

    public function resendNotification($item)
    {
        Message4U::send(array(
            'id' => (int)$item->message_id,
            'user_id' => $item->user_id,
            'notification_subject' => $item->notification_subject,
            'notification_body' => $item->notification_body,
            'type' => array(
                'is_email' => 0,
                'is_notification' => 1
            )
        ));
    }
}
