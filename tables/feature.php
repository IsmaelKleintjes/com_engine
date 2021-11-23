<?php defined('_JEXEC') or die('Restricted access');

class EngineTableFeature extends JTable
{
	function __construct(&$db) 
	{
		parent::__construct('#__eng_feature', 'id', $db);
	}
}
