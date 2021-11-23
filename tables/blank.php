<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.database.table');
 
class EngineTableBlank extends JTable
{
	function __construct(&$db) 
	{
		parent::__construct('#__engine_blank', 'id', $db);
	}
}

