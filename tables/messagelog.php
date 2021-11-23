<?php defined('_JEXEC') or die('Restricted access');
 
class EngineTableMessagelog extends JTable
{
	function __construct(&$db) 
	{
		parent::__construct('#__eng_message_log', 'id', $db);
	}
}
