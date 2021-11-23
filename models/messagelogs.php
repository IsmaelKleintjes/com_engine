<?php defined('_JEXEC') or die;

class EngineModelMessagelogs extends JModelList
{
	public $config = array();
	
	public function __construct($config = array())
	{
		$this->config = array(
			'component' => 'com_' . COMPONENT,
			'crud' => 'messagelog',
			'cruds' => 'messagelogs',
			'fields' => array(
				array(
					'label' => 'E-mail naar',
					'column' => 'email_to',
					'sort' => true,
					'link' => true
				),
                array(
					'label' => JText::_('E-mail onderwerp'),
					'column' => 'email_subject',
					'sort' => true
				),

                array(
                    'label' => JText::_('E-mail geopend?'),
                    'column' => 'opened',
                    'sort' => true
                ),
                array(
                    'label' => JText::_('Notificatie onderwerp'),
                    'column' => 'notification_subject',
                    'sort' => true
                ),
                array(
                    'label' => JText::_('Notificatie gelezen?'),
                    'column' => 'notification_read',
                    'sort' => true
                ),
                array(
                    'label' => JText::_('E-mail'),
                    'column' => 'is_email',
                    'sort' => true
                ),
                array(
                    'label' => JText::_('Notificatie'),
                    'column' => 'is_notification',
                    'sort' => true
                ),
                array(
                    'label' => JText::_(''),
                    'column' => 'resend_email',
                    'html' => true
                ),
                array(
                    'label' => JText::_(''),
                    'column' => 'resend_notification',
                    'html' => true
                ),
				array(
					'label' => JText::_('Verzonden op'),
					'column' => 'created',
					'format' => 'datetime',
					'sort' => true
				),
				array( 
					'label' => JText::_('JGRID_HEADING_ID'),
					'column' => 'id',
					'sort' => true
				)
			)
		);
		
		if(empty($config['filter_fields'])) {
			$config['filter_fields'] = EngineHelper::getFilterFields( $this->config );
		}
		parent::__construct($config);
	}

	public function getItems()
    {
        $items = parent::getItems();

        if(!empty($items)) {
            foreach ($items as $item) {
                if($item->is_email){
                    $item->resend_email = '<a href="' . JRoute::_('index.php?option=com_engine&task=messagelog.resendEmail&id=' . $item->id) . '" class="hasTooltip" title="E-mail opnieuw verzenden"><i class="icon-envelope"></i><i class="icon-loop"></i></a>';
                }

                if($item->is_notification){
                    $item->resend_notification = '<a href="' . JRoute::_('index.php?option=com_engine&task=messagelog.resendNotification&id=' . $item->id) . '" class="hasTooltip" title="Notificatie opnieuw verzenden"><i class="icon-comment"></i><i class="icon-loop"></i></a>';
                }

                $item->opened = $item->opened == 1 ? JText::_('JYES') : JText::_('JNO');
                $item->notification_read = $item->notification_read == 1 ? JText::_('JYES') : JText::_('JNO');
                $item->is_email = ($item->is_email == 1 ? JText::_('JYES') : JText::_('JNO'));
                $item->is_notification = ($item->is_notification == 1 ? JText::_('JYES') : JText::_('JNO'));

            }
        }

        return $items;
    }

	public function getListQuery()
	{
	    $db = JFactory::getDBO();

		$query = parent::getListQuery();
		
		$query->select("mlog.*");
		$query->select($db->qn('us.name', 'user_name'));
		$query->from("#__eng_message_log AS mlog");
		$query->leftJoin($db->qn('#__users', 'us') . ' ON ' . $db->qn('mlog.user_id') . ' = ' . $db->qn('us.id'));
		
		$db = $this->getDBO();

		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = '%'.$search.'%';
			$query->where("CONCAT(mlog.mail_to, mlog.subject) LIKE '".$search."'");
		}

		$orderCol = $this->state->get('list.ordering', 'created');
		$orderDirn = $this->state->get('list.direction', 'desc');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}

	public function getItemsByUser($userId)
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);

        $query->select("mlog.*");
        $query->from("#__eng_message_log AS mlog");

        $query->where($db->qn('mlog.user_id') . ' = ' . (int)$userId);
        $query->order("created DESC");

        $db->setQuery($query);
        return $db->loadObjectList();
    }

	protected function populateState($ordering = 'created', $direction = 'desc')
	{
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search'));

		parent::populateState($ordering, $direction);
	}
	
}