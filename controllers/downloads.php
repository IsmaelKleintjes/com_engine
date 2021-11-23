<?php defined('_JEXEC') or die;

class EngineControllerDownloads extends JControllerAdmin
{
	public $view_list = "downloads";
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Download', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}