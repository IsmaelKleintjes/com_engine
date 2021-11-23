<?php defined('_JEXEC') or die;

class EngineControllerBlanks extends JControllerAdmin
{
	public $view_list = "blanks";
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Blank', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}