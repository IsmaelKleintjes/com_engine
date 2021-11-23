<?php defined('_JEXEC') or die;

class EngineControllerSlide extends JControllerForm
{
	protected $view_list = "slides";
	
	public function getModel($name = 'Slide', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}