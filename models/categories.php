<?php defined('_JEXEC') or die;

class EngineModelCategories extends JModelList
{
	public $config = array();
	
	public function __construct($config = array())
	{
		$this->config = array(
			'component' => 'com_' . COMPONENT,
			'crud' => 'category',
			'cruds' => 'categories',
			'fields' => array(
				array( 
					'label' => JText::_('ordering'),
					'column' => 'ordering',
					'sort' => true
				), 
				array( 
					'label' => JText::_('JGLOBAL_CHECK_ALL'),
					'column' => 'check',
				), 
				array(
					'label' => JText::_('Naam'),
					'column' => 'name',
					'sort' => true,
					'link' => true
				),
                array(
					'label' => JText::_('Crud'),
					'column' => 'crud',
					'sort' => true,
				),
				array( 
					'label' => JText::_('JGRID_HEADING_ID'),
					'column' => 'id',
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
        $app = JFactory::getApplication()->input;
        $crud = $app->get("crud");
        foreach($items as $item)
        {
            $item->href = "index.php?option=com_engine&view=category&layout=edit&id=". (int)$item->id. "&crud=".(string)$crud;
        }
		return $items;
	}
	
	public function getListQuery()
	{
	    $db = JFactory::getDbo();

        $crud = Input4U::get('crud', 'GET', 'feature');
		$query = parent::getListQuery();
		
		$query->select("*");
		$query->from($db->quoteName('#__eng_category'));
		$query->where($db->quoteName("crud")." = ".$db->quote($crud));

		$db = $this->getDbo();

		$search = $this->getState('filter.search');
		if (!empty($search))
		{
            $trueSearch = '%'.$db->escape($search,true).'%';
            $query->where($db->quoteName("name")." LIKE ".$db->quote($trueSearch,false));
		}
		
		// Column ordering
		$orderCol = $this->state->get('list.ordering', 'created');
		$orderDirn = $this->state->get('list.direction', 'desc');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}

	protected function populateState($ordering = 'created', $direction = 'desc')
	{
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		parent::populateState($ordering, $direction);
	}
}