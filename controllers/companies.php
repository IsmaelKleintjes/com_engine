<?php defined('_JEXEC') or die;

class EngineControllerCompanies extends JControllerAdmin
{
	public $view_list = "companies";
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Company', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}