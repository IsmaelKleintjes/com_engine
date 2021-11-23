<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.database.table');
 
class EngineTableProduct extends JTable
{
	function __construct($db) 
	{
		parent::__construct('#__product', 'id', $db);
	}
}

