<?php defined('_JEXEC') or die('Restricted access');
 
class EngineModelSQLupdate extends JModelAdmin
{	
	public function getTable($type = 'SQLupdate', $prefix = 'EngineTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.sqlupdate.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	public function getForm($data = array(), $loadData = true)
	{
		return $this->loadForm('com_' . COMPONENT . '.sqlupdate', 'sqlupdate', array('control' => 'jform', 'load_data' => $loadData));
	}
	
	public function save($data)
	{
		return parent::save(EngineHelper::dataBeforeSave($data));
	}

	public function saveXref($sqlId)
    {
        $userId = JFactory::getUser()->id;

        if(!$this->checkXrefExist()) {
            $oXref = new stdClass();
            $oXref->id = 0;
            $oXref->sql_id = $sqlId;
            $oXref->user_id = $userId;

            JFactory::getDbo()->insertObject('#__eng_sqlupdate_x_user', $oXref);
        }

        return true;
    }

    public function checkXrefExist($sqlId)
    {
        $userId = JFactory::getUser()->id;

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select("*");
        $query->from($db->qn("#__eng_sqlupdate_x_user"));
        $query->where($db->qn("sql_id") . " = " . (int)$sqlId);
        $query->where($db->qn("user_id") . " = " . (int)$userId);

        $db->setQuery($query);
        $item = $db->loadObject();

        return $item;
    }
}
