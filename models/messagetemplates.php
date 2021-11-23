<?php defined('_JEXEC') or die;

class EngineModelMessagetemplates extends JModelList
{
	public $config = array();
	
	public function __construct($config = array())
	{
		$this->config = array(
			'component' => 'com_' . COMPONENT,
			'crud' => 'messagetemplate',
			'cruds' => 'messagetemplates',
			'fields' => array(
                array(
					'label' => JText::_('JGLOBAL_CHECK_ALL'),
					'column' => 'check',
				), 
				array( 
					'label' => JText::_('Naam'),
					'column' => 'title',
					'sort' => true,
					'link' => true
				),
				array( 
					'label' => JText::_('Afzender'),
					'column' => 'name_sender',
					'sort' => true
				),
				array( 
					'label' => JText::_('E-mail afzender'),
					'column' => 'email_sender',
					'sort' => true
				),
				array( 
					'label' => JText::_('JDATE'),
					'column' => 'created',
					'format' => 'date',
					'sort' => true
				), 
				array( 
					'label' => JText::_('JGRID_HEADING_ID'),
					'column' => 'id',
					'sort' => true,
				),
			)
		);
		
		if(empty($config['filter_fields'])) {
			$config['filter_fields'] = EngineHelper::getFilterFields( $this->config );
		}
		parent::__construct($config);
	}
	
	public function getAll()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select("DISTINCT id, title");
		$query->from($db->quoteName("#__eng_message_template"));
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	public function getItems()
	{
		$items = parent::getItems();
		foreach ($items as $item) 
		{
			$item->url = 'index.php?option=com_' . COMPONENT . '&amp;task=messagetemplate.edit&amp;id='. (int)$item->id;
		}
		return $items;
	}
	
	public function getListQuery()
	{
        $db = $this->getDbo();
        $query = parent::getListQuery();
		
		$query->select('*');
		$query->from($db->quoteName('#__eng_message_template'));

		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$trueSearch = '%'.$db->escape($search).'%';
			$query->where($db->quoteName("title")." LIKE ".$db->quote($trueSearch));
		}
		
		// Column ordering
		$orderCol = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));
		
		return $query;
	}

	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		parent::populateState($ordering, $direction);
	}
	
}