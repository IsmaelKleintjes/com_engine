<?php defined( '_JEXEC' ) or die;

class EngineModelNewsletter extends JModelAdmin
{
	public function getTable($type = 'Newsletter', $prefix = 'EngineTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.newsletter.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}

	public function getForm($data = array(), $loadData = true)
	{
		return $this->loadForm('com_' . COMPONENT . '.newsletter', 'newsletter', array('control' => 'jform', 'load_data' => $loadData));
	}
	
	public function save($data)
	{	
		if(parent::save(EngineHelper::dataBeforeSave($data))){
            return true;
        }
         return false;
	}

    public function featured($pks, $value = 0)
    {
        // Sanitize the ids.
        $pks = (array) $pks;
        JArrayHelper::toInteger($pks);

        if (empty($pks)) {
            $this->setError(JText::_('COM_CONTENT_NO_ITEM_SELECTED'));
            return false;
        }

        $db = $this->getDbo();

        $db->setQuery(
            'UPDATE #__eng_newsletter' .
            ' SET featured = '.(int) $value.
            ' WHERE id IN ('.implode(',', $pks).')'
        );
        $db->query();

        $this->cleanCache();

        return true;
    }
}
