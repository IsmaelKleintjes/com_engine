<?php defined('_JEXEC') or die;

class EngineModelCompanies extends JModelList
{
	public $config = array();
	
	public function __construct($config = array())
	{
		$this->config = array(
			'component' => 'com_' . COMPONENT,
			'crud' => 'company',
			'cruds' => 'companies',
			'fields' => array(
				array(
                    'label' => JText::_('Ordering'),
                    'column' => 'ordering',
					'sort' => true,
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
					'label' => JText::_('Naam'),
					'column' => 'company_name',
					'sort' => true,
                    'link' => true
				),
                            array(
					'label' => JText::_('Telefoonnummer'),
					'column' => 'phone_number',
					'sort' => true
				),
                            array(
					'label' => JText::_('Emailadres'),
					'column' => 'emailadres',
					'sort' => true
				),
                            array(
					'label' => JText::_('Postcode'),
					'column' => 'zip_code',
					'sort' => true
				),
                            array(
					'label' => JText::_('Adres'),
					'column' => 'adress',
					'sort' => true
				),
				array( 
					'label' => JText::_('JDATE'),
					'column' => 'created_at',
					'sort' => true,
                    'format' => 'date'
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
        /*
	public function getListQuery()
	{
	    $db = JFactory::getDbo();
		$query = parent::getListQuery();
		
		$query->select("*");
		$query->from($db->quoteName('#__company'));

		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$trueSearch = '%'.$db->escape($search).'%';
			$query->where("CONCAT(name) LIKE ".$db->quote($trueSearch));
		}

        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where($db->quoteName('published').' = ' . (int)$published);
        }
        elseif (!$published) {
            $query->where($db->quoteName('published').' = 1');
        }

		$orderCol = $this->state->get('list.ordering', 'ordering');
		$orderDirn = $this->state->get('list.direction', 'desc');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}*/
        
        protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*')
                ->from($db->quoteName('#__company'));

		return $query;
	}

	protected function populateState($ordering = 'ordering', $direction = 'desc')
	{
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search'));
		$this->setState('filter.published', $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published'));

		parent::populateState($ordering, $direction);
	}
	
}