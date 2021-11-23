<?php defined('_JEXEC') or die;

class EngineModelLeads extends JModelList
{
	public $config = array();
	
	public function __construct($config = array())
	{
		$this->config = array(
			'component' => 'com_' . COMPONENT,
			'crud' => 'lead',
			'cruds' => 'leads',
			'fields' => array(
                array(
					'label' => JText::_('JGLOBAL_CHECK_ALL'),
					'column' => 'check',
				),
				array(
					'label' => JText::_('Naam'),
					'column' => 'lead.name',
					'sort' => true,
                    'link' => true
				),
				array( 
					'label' => JText::_('JDATE'),
					'column' => 'created',
					'sort' => true,
                    'format' => 'datetime'
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
	    $db = JFactory::getDbo();
		$query = parent::getListQuery();
		
		$query->select("lead.*");
		$query->from($db->quoteName('#__eng_lead','lead'));

        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = '%'.$search.'%';
            $query->where("lead.name LIKE '".$search."'");
        }

        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where($db->quoteName('lead.published').' = ' . (int)$published);
        }
        elseif (!$published) {
            $query->where($db->quoteName('lead.published').' = 1');
        }

		$orderCol = $this->state->get('list.ordering', 'created');
		$orderDirn = $this->state->get('list.direction', 'desc');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}

	protected function populateState($ordering = 'created', $direction = 'desc')
	{
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search'));
		$this->setState('filter.published', $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published'));

		parent::populateState($ordering, $direction);
	}
	
}