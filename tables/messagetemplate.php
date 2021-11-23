<?php defined('_JEXEC') or die('Restricted access');
 
class EngineTableMessagetemplate extends JTable
{
	function __construct(&$db) 
	{
		parent::__construct('#__eng_message_template', 'id', $db);
	}
}
