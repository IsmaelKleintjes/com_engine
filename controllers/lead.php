<?php defined('_JEXEC') or die;

class EngineControllerLead extends JControllerForm
{
	protected $view_list = "leads";
	
	public function getModel($name = 'Lead', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}	
}