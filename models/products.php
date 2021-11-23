<?php defined('_JEXEC') or die;

class EngineModelProducts extends JModelList
{
	public $config = array();
	
	public function __construct($config = array())
	{
		$this->config = array(
			'component' => 'com_' . COMPONENT,
			'crud' => 'product',
			'cruds' => 'products',
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
					'column' => 'name',
					'sort' => true,
                    'link' => true
				),
                            array(
					'label' => JText::_('Prijs'),
					'column' => 'price',
					'sort' => true
				),
                            array(
					'label' => JText::_('Beschrijving'),
					'column' => 'description',
					'sort' => true
				),
                            array(
					'label' => JText::_('Afbeelding'),
					'column' => 'image',
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
        
        protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*')
                ->from($db->quoteName('#__product'));

		return $query;
	}

	protected function populateState($ordering = 'ordering', $direction = 'desc')
	{
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search'));
		$this->setState('filter.published', $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published'));

		parent::populateState($ordering, $direction);
	}
	
}