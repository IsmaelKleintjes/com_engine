<?php defined('_JEXEC') or die;

class EngineModelSlides extends JModelList
{
	public $config = array();
	
	public function __construct($config = array())
	{
		$this->config = array(
			'component' => 'com_' . COMPONENT,
			'crud' => 'slide',
			'cruds' => 'slides',
			'fields' => array(
                array(
                    'label' => JText::_('JGRID_HEADING_ORDERING'),
                    'column' => 'ordering',
                    'sort' => true
                ),
                array(
					'label' => JText::_('JGLOBAL_CHECK_ALL'),
					'column' => 'check',
				),
                array(
                    'label' => JText::_('JSTATUS'),
                    'column' => 'published',
                    'sort' => true
                ),
				array(
                    'label' => JText::_('Titel'),
                    'column' => 'title',
                    'sort' => true,
                    'link' => true
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

	public function getListQuery()
	{
		$query = parent::getListQuery();
		
		$query->select("s.*");
		$query->from('#__eng_slide s');

		$db = $this->getDBO();

		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = '%'.$search.'%';
			$query->where("CONCAT(s.title) LIKE '".$search."'");
		}

        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('s.published = ' . (int) $published);
        }
        elseif (!$published) {
            $query->where('(s.published = 1 OR s.published = 0)');
        }

		$orderCol = $this->state->get('list.ordering', 'ordering');
		$orderDirn = $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}

	protected function populateState($ordering = 'ordering', $direction = 'asc')
	{
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search'));
		$this->setState('filter.published', $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published'));

		parent::populateState($ordering, $direction);
	}
	
}