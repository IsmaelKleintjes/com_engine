<?php defined('_JEXEC') or die;

class EngineControllerLeads extends JControllerAdmin
{
	public $view_list = "leads";
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Lead', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}