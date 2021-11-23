<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.database.table');
 
class EngineTableCategory extends JTable
{
	function __construct(&$db) 
	{
		parent::__construct('#__eng_category', 'id', $db);
	}
}

