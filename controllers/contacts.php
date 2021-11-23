<?php defined('_JEXEC') or die;

class EngineControllerContacts extends JControllerAdmin
{
	public $view_list = "contacts";
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Contact', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}