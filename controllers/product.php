<?php defined('_JEXEC') or die;

class EngineControllerProduct extends JControllerForm
{
	protected $view_list = "products";
	
	public function getModel($name = 'Product', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

}