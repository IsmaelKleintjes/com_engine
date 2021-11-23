<?php defined('_JEXEC') or die('Restricted access');
 
class EngineTableMessage extends JTable
{
	function __construct(&$db) 
	{
		parent::__construct('#__eng_message', 'id', $db);
	}
}
