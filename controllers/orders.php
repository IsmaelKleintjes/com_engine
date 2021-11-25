<?php defined('_JEXEC') or die;

class EngineControllerOrders extends JControllerAdmin
{
	public $view_list = "orders";
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Order', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}