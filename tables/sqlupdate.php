<?php defined('_JEXEC') or die('Restricted access');
 
class EngineTableSQLUpdate extends JTable
{
	function __construct(&$db) 
	{
		parent::__construct('#__eng_sqlupdate', 'id', $db);
	}
}
