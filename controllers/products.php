<?php defined('_JEXEC') or die;

class EngineControllerProducts extends JControllerAdmin
{
	public $view_list = "products";
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Product', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}