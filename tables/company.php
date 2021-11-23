<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.database.table');
 
class EngineTableCompany extends JTable
{
	function __construct($db) 
	{
		parent::__construct('#__company', 'id', $db);
	}
}

