<?php defined('_JEXEC') or die;

class EngineControllerDownload extends JControllerForm
{
	protected $view_list = "downloads";
	
	public function getModel($name = 'Download', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}	
}