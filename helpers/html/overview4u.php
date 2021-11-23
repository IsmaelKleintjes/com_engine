<?php defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

/**
 * Class JHtmlOverview
 *
 * @version     1.0
 * @since       21-11-2016
 */
class JHtmlOverview extends JViewLegacy
{
	protected $config;
	protected $component;
	protected $crud;
	protected $cruds;
	protected $state;
	protected $user;
	protected $userId;
	protected $listOrder;
	protected $listDirn;
	protected $archived;
	protected $trashed;
	protected $saveOrder;
	protected $sortFields;

    /**
     * JHtmlOverview constructor.
     *
     * @param array $view
     * @param array $attr
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function __construct($view, $attr=array() )
	{
		$this->model		= $view->getModel();
		$this->config		= $this->model->config;
		
		$this->component	= $this->config['component'];
		$this->cruds		= $this->config['cruds'];
		$this->crud			= $this->config['crud'];
		$this->fields		= $this->config['fields'];
		$this->sortFields	= EngineHelper::getSortFields( $this->config );
		
		$this->sidebar 		= $view->sidebar;
		$this->items 		= $view->get('Items');
		$this->pagination 	= $view->get('Pagination');
		$this->state 		= $view->get('State');
		
		$this->user			= JFactory::getUser();
		$this->userId		= $this->user->get('id');
		
		$this->listOrder	= $this->escape($this->state->get('list.ordering'));
		$this->listDirn		= $this->escape($this->state->get('list.direction'));
		
		$this->archived		= $this->state->get('filter.state') == 2 ? true : false;
		$this->trashed		= $this->state->get('filter.state') == -2 ? true : false;

	}

    /**
     * Description comes later
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function script()
	{
		$script = "<script type=\"text/javascript\">";
		$script .= "\n Joomla.orderTable = function() { ";
		$script .= "\n 	table = document.getElementById(\"sortTable\"); ";
		$script .= "\n	direction = document.getElementById(\"directionTable\"); ";
		$script .= "\n 	order = table.options[table.selectedIndex].value; ";
		$script .= "\n 	if (order != '" . $this->listOrder . "') { ";
		$script .= "\n 		dirn = 'asc'; ";
		$script .= "\n 	} else { ";
		$script .= "\n 		dirn = direction.options[direction.selectedIndex].value; ";
		$script .= "\n 	} ";
		$script .= "\n	Joomla.tableOrdering(order, dirn, ''); ";
		$script .= "\n } ";
		$script .= "\n </script>";	
		
		return $script;
	}

    /**
     * Description comes later
     *
     * @param null $hiddenFields
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function show($hiddenFields = null)
	{
		$html = "";
		$html .= $this->script();
		$html .= "<form action='" . JRoute::_('index.php?option=' . $this->component . '&view=' . $this->cruds) . "' method='post' name='adminForm' id='adminForm'>";
		
		if(!empty( $this->sidebar)) { 
			$html .= "<div id='j-sidebar-container' class='span2'>";
				$html .= $this->sidebar;
			$html .= "</div>";
			$html .= "<div id='j-main-container' class='span10'>";
		} else {
			$html .= "<div id='j-main-container'>";
		}
		
		$html .= "<div id='filter-bar' class='btn-toolbar'>";
			$html .= $this->getFilterBar();
		$html .= "</div>";
		$html .= "<div class='clearfix'> </div>";
		
		$html .= '<table class="table table-striped" id="articleList">'; 
		$html .= '<thead>';
		$html .= '<tr>';
		
		foreach($this->fields as $field)
		{
			$html .= $this->showTableHead( $field ); 
		}
		
		$html .= '</tr>';
		$html .= '</thead>';
		$html .= '<tbody>';

		if(!empty($this->items)) {
		    foreach ($this->items as $i => $item)
            {
                $item->max_ordering = 0;

                $html .= '<tr class="row' . ($i % 2) . '" ' . $sortableGroup . '>';
                    $html .= $this->showTableBody( $item, $i );
                $html .= '</tr>';
            }
        }

		$html .= '</tbody>';
		$html .= '</table>';
		
		$html .= $this->pagination->getListFooter();

		$html .= '<input type="hidden" name="task" value="" />';
		$html .= '<input type="hidden" name="boxchecked" value="0" />';
		$html .= '<input type="hidden" name="filter_order" value="' . $this->listOrder . '" />';
		$html .= '<input type="hidden" name="filter_order_Dir" value="' . $this->listDirn . '" />';

        if(!empty($hiddenFields))
        {
            $html .= $this->addHiddenFields($hiddenFields);
        }
		$html .= JHtml::_('form.token');
		$html .= '</div>';
		$html .= '</form>';
		
		return $html;
	}

    /**
     * Description comes later
     *
     * @param $field
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function showTableHead($field )
	{
		$html = "";
		$columnKey = end(explode(".",$field['column']));
		switch($columnKey)
		{
			case 'ordering':
				$this->saveOrder = $this->listOrder==$field['column'];
				if ($this->saveOrder)
				{
					$this->saveOrderingUrl = 'index.php?option=' . $this->component . '&task=' . $this->cruds . '.saveOrderAjax&tmpl=component';
					JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($this->listDirn), $this->saveOrderingUrl);
				}
			
				$html .= '<th width="1%" class="nowrap center hidden-phone">';
				if($field['sort']) {
					$html .= JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', $field['column'], $this->listDirn, $this->listOrder, null, 'asc', $field['label']); 
				} else {
					$html .= '<i class="icon-menu-2"></i>';	
				}
				$html .= '</th>';
				break;	
			case 'check':
				$html .= '<th width="1%" class="hidden-phone">';
				$html .= '<input type="checkbox" name="checkall-toggle" value="" title="' . $field['label'] . '" onclick="Joomla.checkAll(this)" />';
				$html .= '</th>';
				break;
			case 'pubfeat':
			case 'featured':
			case 'published':
				$html .= '<th width="1%" style="min-width:55px" class="nowrap center">';
				if($field['sort']) {
					$html .= JHtml::_('grid.sort', $field['label'], $field['column'], $this->listDirn, $this->listOrder);
				} else {
					$html .= $field['label'];
				}
				$html .= '</th>';
				break;
            case 'modified':
			case 'created':
				$html .= '<th width="10%" class="nowrap hidden-phone">';
				if($field['sort']) {
					$html .= JHtml::_('grid.sort', $field['label'], $field['column'], $this->listDirn, $this->listOrder);
				} else {
					$html .= $field['label'];
				}
				$html .= '</th>';
				break;
			case 'id':
				$html .= '<th width="1%" class="nowrap hidden-phone">';
				if($field['sort']) {
					$html .= JHtml::_('grid.sort', $field['label'], $field['column'], $this->listDirn, $this->listOrder);
				} else {
					$html .= $field['label'];
				}
				$html .= '</th>';
				break;	
			default:
				$html .= '<th>';
				if($field['sort']) {
					$html .= JHtml::_('grid.sort', $field['label'], $field['column'], $this->listDirn, $this->listOrder);
				} else {
					$html .= $field['label'];
				}
				$html .= '</th>';
				break;
		}
		
		return $html;
	}

    /**
     * Description comes later
     *
     * @param $item
     * @param $i
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function showTableBody($item, $i )
	{
		$ordering   = ($listOrder == 'a.ordering');
		$canCreate  = $this->user->authorise('core.create', $this->component . '.' . $this->crud . '.'.$item->catid);
		$canEdit    = $this->user->authorise('core.edit', $this->component . '.' . $this->crud . '.'.$item->id);
		$canCheckin = $this->user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
		$canEditOwn = $this->user->authorise('core.edit.own', $this->component . '.' . $this->crud . '.'.$item->id) && $item->created_by == $userId;
		$canChange  = $this->user->authorise('core.edit.state', $this->component . '.' . $this->crud . '.'.$item->id) && $canCheckin;
		
		foreach($this->fields as $field)
		{
			$columnKey = end(explode(".",$field['column']));
			switch($columnKey)
			{
				case 'ordering':
					$html .= '<td class="order nowrap center hidden-phone">';
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$this->saveOrder) { 
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						}
						$html .= '<span class="sortable-handler hasTooltip ' . $disableClassName . '" title="' . $disabledLabel . '">';
							$html .= '<i class="icon-menu"></i>';
						$html .= '</span>';
						$html .= '<input type="text" style="display:none" name="order[]" size="5" value="' . $item->ordering . '" class="width-20 text-area-order " />';
					$html .= '</td>';
					break;	
				case 'check':
					$html .= '<td class="center hidden-phone">';
						$html .= JHtml::_('grid.id', $i, $item->id);
					$html .= '</td>';
					break;
				case 'pubfeat':
					$html .= '<td class="center">';
						$html .= '<div class="btn-group">';
							$html .= JHtml::_('jgrid.published', $item->published, $i, $this->cruds . '.', $canChange, 'cb');
							$html .= $this->featured( $item->featured, $i, $canChange);
						$html .= '</div>';
					$html .= '</td>';
					break;
				case 'featured':
					$html .= '<td class="center">';
						$html .= '<div class="btn-group">';
							$html .= $this->featured( $item->featured, $i, $canChange);
						$html .= '</div>';
					$html .= '</td>';
					break;
				case 'published':
					$html .= '<td class="center">';
						$html .= '<div class="btn-group">';
							$html .= JHtml::_('jgrid.published', $item->published, $i, $this->cruds . '.', $canChange, 'cb');
						$html .= '</div>';
					$html .= '</td>';
					break;
                case 'modified':
                case 'created':
                    $html .= '<td class="nowrap small hidden-phone">';
                    if(!empty($item->{$columnKey}) && $item->{$columnKey} != '0000-00-00 00:00:00' && $item->created != '0000-00-00') {
                        if(!isset($field["format"])){
                            $html .= JHtml::_('date', $item->{$columnKey}, JText::_('DATE_FORMAT_LC4'));
                        } else {
                            switch($field["format"]) {
                                case 'date':
                                    $html .= JHtml::_('date', $item->{$columnKey}, 'j-n-Y');
                                    break;
                                case 'datetime':
                                    $html .= JHtml::_('date', $item->{$columnKey}, 'j-n-Y H:i');
                                    break;
                            }
                        }
                    }
                    $html .= '</td>';
                    break;
				case 'id':
					$html .= '<td class="center hidden-phone">';
						$html .= (int) $item->id;
					$html .= '</td>';
					break;	
				default:
					$html .= '<td class="nowrap">';
						$html .= '<div class="pull-left">';
							$html .= $this->showValue( $item->{$columnKey}, $item->id, $field['link'], $item->href, $field['html'] );
						$html .= '</div>';
					$html .= '</td>';
					break;
			}
		}

		return $html;
	}

    /**
     * Adds hidden fields
     *
     * @param null $fields
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function addHiddenFields($fields = null)
    {
        $html = "";
        foreach($fields as $field)
        {
            $html .= "<input type='hidden' name='". $field['name'] . "' value='". $field['value'] ."'/>";
        }

        return $html;
    }

    /**
     * Shows some value
     *
     * @param $value
     * @param int $id
     * @param bool $link
     * @param $href
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function showValue($value, $id=0, $link=false, $href, $allowHtml = false )
	{
		$html = "";
        if($href)
        {
            $html .= '<a href="' . JRoute::_($href) . '" title="' . JText::_('JACTION_EDIT') . '">';
        }
        elseif($link)
        {
            $html .= '<a href="' . JRoute::_('index.php?option=' . $this->component . '&task=' . $this->crud . '.edit&id=' . $id) . '" title="' . JText::_('JACTION_EDIT') . '">';
        }
        if(!$allowHtml){
            $html .= $this->escape( $value );
        } else {
            $html .= $value;
        }
		if($link) {
			$html .= '</a>';
		}
        elseif($href)
        {
            $html .= '</a>';
        }
		return $html;
	}

    /**
     * Gets filterbar
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function getFilterBar()
	{ 
		$html = '';
		$html .= '<div class="filter-search btn-group pull-left">';
			$html .= '<label for="filter_search" class="element-invisible">' . JText::_('COM_FILTER_SEARCH_DESC') . '</label>';
			$html .= '<input type="text" name="filter_search" placeholder="' . JText::_('COM_FILTER_SEARCH_DESC') . '" id="filter_search" value="' . $this->escape($this->state->get('filter.search')) . '" title="' . JText::_('COM_FILTER_SEARCH_DESC') . '" />';
		$html .= '</div>';
		$html .= '<div class="btn-group pull-left hidden-phone">';
			$html .= '<button class="btn tip hasTooltip" type="submit" title="' . JText::_('JSEARCH_FILTER_SUBMIT') . '"><i class="icon-search"></i></button>';
			$html .= '<button class="btn tip hasTooltip" type="button" onclick="document.getElementById(\'filter_search\').value=\'\';this.form.submit();" title="' . JText::_('JSEARCH_FILTER_CLEAR') . '"><i class="icon-remove"></i></button>';
		$html .= '</div>';
		$html .= '<div class="btn-group pull-right hidden-phone">';
			$html .= '<label for="limit" class="element-invisible">' . JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC') . '</label>';
			$html .= $this->pagination->getLimitBox();
		$html .= '</div>';
		$html .= '<div class="btn-group pull-right hidden-phone">';
			$html .= '<label for="directionTable" class="element-invisible">' . JText::_('JFIELD_ORDERING_DESC') . '</label>';
			$html .= '<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">';
				$html .= '<option value="">' . JText::_('JFIELD_ORDERING_DESC') . '</option>';
				$html .= '<option value="asc" ' . ($this->listDirn == 'asc' ? 'selected="selected"' : '' ) . '>' . JText::_('JGLOBAL_ORDER_ASCENDING') . '</option>';
				$html .= '<option value="desc" ' . ($this->listDirn == 'desc' ? 'selected="selected"' : '' ) . '>' . JText::_('JGLOBAL_ORDER_DESCENDING') . '</option>';
			$html .= '</select>';
		$html .= '</div>';
		$html .= '<div class="btn-group pull-right">';
			$html .= '<label for="sortTable" class="element-invisible">' . JText::_('JGLOBAL_SORT_BY') . '</label>';
			$html .= '<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">';
				$html .= '<option value="">' . JText::_('JGLOBAL_SORT_BY') . '</option>';
				$html .= JHtml::_('select.options', $this->sortFields, 'value', 'text', $this->listOrder);
			$html .= '</select>';
		$html .= '</div>';
		
		return $html;
	}

    /**
     * Description comes later
     *
     * @param int $value
     * @param $i
     * @param bool $canChange
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function featured($value = 0, $i, $canChange = true)
	{
		JHtml::_('bootstrap.tooltip');

		// Array of image, task, title, action
		$states	= array(
			0	=> array('star-empty', $this->cruds . '.featured', 'OVERVIEW_UNFEATURED', 'OVERVIEW_TOGGLE_TO_FEATURE'),
			1	=> array('star', $this->cruds . '.unfeatured', 'OVERVIEW_FEATURED', 'OVERVIEW_TOGGLE_TO_UNFEATURE'),
		);
		$state	= Joomla\Utilities\ArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon	= $state[0];
		if ($canChange) {
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active' : '') . '" title="'.JText::_($state[3]).'"><i class="icon-'
					. $icon.'"></i></a>';
		}

		return $html;
	}

}