<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.database.table');
 
class EngineTableContact extends JTable
{
	function __construct($db) 
	{
		parent::__construct('#__contact', 'id', $db);
	}
}

