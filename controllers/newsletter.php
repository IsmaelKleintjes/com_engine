<?php defined('_JEXEC') or die;

class EngineControllerNewsletter extends JControllerForm
{
	protected $view_list = "newsletters";
	
	public function getModel($name = 'Newsletter', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}