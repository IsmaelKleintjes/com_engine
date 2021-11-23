<?php defined('_JEXEC') or die;

class EngineControllerContact extends JControllerForm
{
	protected $view_list = "contacts";
	
	public function getModel($name = 'Contact', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

}