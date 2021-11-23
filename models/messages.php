<?php defined('_JEXEC') or die;

class EngineModelMessages extends JModelList
{
	public $config = array();
	
	public function __construct($config = array())
	{
		$this->config = array(
			'component' => 'com_' . COMPONENT,
			'crud' => 'message',
			'cruds' => 'messages',
			'fields' => array(
				array( 
					'label' => JText::_('JGLOBAL_CHECK_ALL'),
					'column' => 'check',
				),
				array( 
					'label' => JText::_('Naam'),
					'column' => 'e.title',
					'sort' => true,
					'link' => true
				),
                array(
                    'label' => JText::_('Trigger'),
                    'column' => 'e.trigger',
                    'sort' => true
                ),
				array( 
					'label' => JText::_('Onderwerp'),
					'column' => 'e.email_subject',
					'sort' => true
				),
				array( 
					'label' => JText::_('Template'),
					'column' => 'template',
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
					'label' => JText::_('JGRID_HEADING_ID'),
					'column' => 'e.id',
					'sort' => true
				),
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
		foreach ($items as $item) 
		{
			$item->url = 'index.php?option=com_' . COMPONENT . '&amp;task=message.edit&amp;id='. (int)$item->id;
			$item->is_email = ($item->is_email == 1 ? 'Ja' : 'Nee');
            $item->is_notification = ($item->is_notification == 1 ? 'Ja' : 'Nee');
		}
		return $items;
	}
	
	public function getListQuery()
	{
	    $db = JFactory::getDbo();
		$query = parent::getListQuery();
		
		$query->select('e.*, c.title AS template');
		$query->from($db->quoteName('#__eng_message','e'));
		$query->leftJoin($db->quoteName('#__eng_message_template','c').' ON '.$db->quoteName('e.template_id').' = '.$db->quoteName('c.id'));
		
		$db = $this->getDbo();
		
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$trueSearch = '%'.$db->escape($search).'%';
			$query->where("CONCAT(e.title, c.title, e.trigger, e.email_subject) LIKE ".$db->quote($trueSearch));
		}
		
		$templates = $this->getState('filter.templates');
		if(!empty($templates))
		{
			$query->where("c.id = ".$templates);
		}
		
		// Column ordering
		$orderCol = $this->state->get('list.ordering', 'e.id');
		$orderDirn = $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}

	protected function populateState($ordering = null, $direction = null)
	{		
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$templates = $this->getUserStateFromRequest($this->context.'filter.templates', 'filter_templates');
		$this->setState('filter.templates', $templates);
		
		parent::populateState($ordering, $direction);
	}
	
}