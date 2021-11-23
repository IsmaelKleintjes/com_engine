<?php defined('_JEXEC') or die;

class EngineModelSQLUpdates extends JModelList
{
	public $config = array();

	public function __construct($config = array())
	{
		$this->config = array(
			'component' => 'com_' . COMPONENT,
			'crud' => 'sqlupdate',
			'cruds' => 'sqlupdates',
			'fields' => array(
                array(
                    'label' => JText::_('JGLOBAL_CHECK_ALL'),
                    'column' => 'check',
                ),
                array(
                    'label' => JText::_('Omschrijving'),
                    'column' => 'short_description',
                    'sort' => true,
                    'link' => true
                ),
                array(
                    'label' => JText::_('Geëxporteerd'),
                    'column' => 'exported',
                    'sort' => true,
                ),
                array(
                    'label' => JText::_('Aangemaakt door'),
                    'column' => 'name',
                    'sort' => true,
                ),
                array(
                    'label' => JText::_('Aangemaakt op'),
                    'column' => 'created',
                    'sort' => true,
                    'format' => 'datetime'
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

        foreach($items as $item){
            $item->exported = $item->exported == 1 ? JText::_('JYES') : JText::_('JNO');
        }

        return $items;
    }

	public function getListQuery()
	{
	    $userId = JFactory::getUser()->id;

		$query = parent::getListQuery();
		$db = $this->getDbo();

		$query->select("sqlUpdate.*");
		$query->from($db->qn("#__eng_sqlupdate", "sqlUpdate"));

		$query->select("IF (xUser.user_id = ".(int)$userId.", 1, 0) AS exported");
		$query->leftJoin($db->qn("#__eng_sqlupdate_x_user", "xUser") . " ON " . $db->qn("xUser.sql_id") . " = " . $db->qn("sqlUpdate.id"));

		$query->select("jUser.name");
		$query->leftJoin($db->qn("#__users", "jUser") . " ON " . $db->qn("jUser.id") . " = " . $db->qn("sqlUpdate.created_by"));

		$query->group("sqlUpdate.id");

		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = '%'.$db->escape($search).'%';
			$query->where("sqlUpdate.short_description LIKE ".$db->quote($search));
		}

		$orderCol = $this->state->get('list.ordering', 'sqlUpdate.created');
		$orderDirn = $this->state->get('list.direction', 'desc');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}

	public function export()
    {
        $sqlIds = Input4U::getArray('cid');
        $codes = '';

        if(!empty($sqlIds)) {

            $sqlUpdateModel = JModelLegacy::getInstance('SQLUpdate', 'EngineModel');
            $sqlUpdates = $this->getByIds($sqlIds);

            foreach ($sqlUpdates as $sqlUpdate) {
                $codes .= $sqlUpdate->code . PHP_EOL . PHP_EOL;

                $sqlUpdateModel->saveXref($sqlUpdate->id);
            }
        }

        $session = JFactory::getSession();
        $session->set('exportCode', $codes);

        return true;
    }

    public function getByIds($sqlIds)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select("*");
        $query->from($db->qn("#__eng_sqlupdate"));
        $query->where($db->qn("id") . " IN (".implode(',', $sqlIds).")");
        $query->order($db->qn("created") . " ASC");

        $db->setQuery($query);
        $items = $db->loadObjectList();

        return $items;
    }

	protected function populateState($ordering = 'created', $direction = 'desc')
	{
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search'));

		parent::populateState($ordering, $direction);
	}

}