<?php defined('_JEXEC') or die;

class EngineModelOrders extends JModelList
{
	public $config = array();
	
	public function __construct($config = array())
	{
		$this->config = array(
			'component' => 'com_' . COMPONENT,
			'crud' => 'order',
			'cruds' => 'orders',
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
					'label' => JText::_('product_id'),
					'column' => 'product_id',
					'sort' => true,
                    'link' => true
				),
                            array(
					'label' => JText::_('user_id'),
					'column' => 'user_id',
					'sort' => true
				),
                            array(
					'label' => JText::_('shipping_address'),
					'column' => 'shipping_address',
					'sort' => true
				),
                            array(
					'label' => JText::_('email'),
					'column' => 'email',
					'sort' => true
				),
                            array(
					'label' => JText::_('phone'),
					'column' => 'phone',
					'sort' => true
				),
                            array(
					'label' => JText::_('zipcode'),
					'column' => 'zipcode',
					'sort' => true
				),
                            array(
					'label' => JText::_('city'),
					'column' => 'city',
					'sort' => true
				),
                            array(
					'label' => JText::_('country'),
					'column' => 'country',
					'sort' => true
				),
                            array(
					'label' => JText::_('shipping_name'),
					'column' => 'shipping_name',
					'sort' => true
				),
                            array(
					'label' => JText::_('amount'),
					'column' => 'amount',
					'sort' => true
				),
                            array(
					'label' => JText::_('price'),
					'column' => 'price',
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
                ->from($db->quoteName('#__order'));

		return $query;
	}

	protected function populateState($ordering = 'ordering', $direction = 'desc')
	{
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search'));
		$this->setState('filter.published', $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published'));

		parent::populateState($ordering, $direction);
	}
	
}