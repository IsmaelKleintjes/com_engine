<?php defined('_JEXEC') or die;

class EngineModelFeatures extends JModelList
{
	public $config = array();		
	
	public function __construct($config = array())
	{
		$this->config = array(
			'component' => 'com_' . COMPONENT,
			'crud' => 'feature',
			'cruds' => 'features',
			'fields' => array(
				array(
					'label' => JText::_('JGRID_HEADING_ORDERING'),
					'column' => 'e.ordering',
					'sort' => true
				),
				array( 
					'label' => JText::_('JGLOBAL_CHECK_ALL'),
					'column' => 'check',
				), 
				array(
					'label' => JText::_('JSTATUS'),
					'column' => 'pubfeat'
				), 
				array( 
					'label' => JText::_('Naam'),
					'column' => 'e.title',
					'sort' => true,
					'link' => true
				),
				array(
					'label' => JText::_('Categorie'),
					'column' => 'category',
					'sort' => true
				),
				array( 
					'label' => JText::_('JDATE'),
					'column' => 'e.created',
					'format' => 'date',
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
			$item->url = 'index.php?option=com_' . COMPONENT . '&amp;task=feature.edit&amp;id='. (int)$item->id;
		}
		return $items;
	}
	
	public function getFilter()
    {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('name, id');
		$query->from($db->quoteName('#__eng_category'));
		$query->where($db->quoteName("crud")." = ".$db->quote("feature"));
		$query->order($db->quoteName('id').'ASC');

		$db->setQuery($query);
		return $db->loadObjectList();

	}
	
	public function getListQuery()
	{
	    $db = JFactory::getDbo();

		$query = parent::getListQuery();
		
		$query->select('e.*, c.name AS category');
		$query->from($db->quoteName('#__eng_feature','e'));
		$query->leftJoin($db->quoteName('#__eng_category','c').' ON '.$db->quoteName('e.cat_id').' = '.$db->quoteName('c.id'));
		$query->order($db->quoteName('c.name').' ASC');
		
		$db = $this->getDbo();
		
		$categories = $this->getState('filter.category_id');
		if(!empty($categories)){
			$query->where($db->quoteName("e.cat_id")." = ".(int)$categories);
		}
		
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$trueSearch = '%'.$db->escape($search).'%';
			$query->where("CONCAT(e.title, c.name) LIKE ".$db->quote($trueSearch));
		}
		
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('e.published = ' . (int) $published);
		}
		elseif (!$published) {
			$query->where('(e.published = 0 OR e.published = 1)');
		}		
		
		// Column ordering
		$orderCol = $this->state->get('list.ordering', 'ordering');
		$orderDirn = $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;

	}

    public function getItemsByCategory($catId)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select( '*' );
        $query->from( $db->qn('#__eng_feature') );
        $query->where( $db->qn('published') . ' = 1');
        $query->where($db->qn('cat_id') . ' = ' . (int)$catId);
        $query->order($db->qn('title') . ' ASC');

        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
	protected function populateState($ordering = 'ordering', $direction = 'asc')
	{
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published');
		$this->setState('filter.published', $published);
		
		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);
		
		parent::populateState($ordering, $direction);
	}
	
}