<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.database.table');
 
class EngineTableOrder extends JTable
{
	function __construct($db) 
	{
		parent::__construct('#__order', 'id', $db);
	}
}

