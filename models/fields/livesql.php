<?php defined('JPATH_PLATFORM') or die;

class JFormFieldLivesql extends JFormField
{
	protected $type = 'Livesql';
	public $value;

	protected function getInput()
	{
		require_once(JPATH_ROOT.'/administrator/components/com_landing/helpers/app.php');
		$db = JFactory::getDbo();
		$query = $db->getQuery('true');
		$query->select('id, title');
		$query->from($db->quoteName('#__engine_feature'));
		$query->where($db->quoteName('cat_id').' = '.(int)$this->element['catid']);
		$db->setQuery($query);
		$items = $db->loadObjectList();
		
		return JHtml::_('select.genericlist',  $items, $this->name.'[]', 'multiple="multiple"', 'id', 'title', $this->value );
	}
} ?>