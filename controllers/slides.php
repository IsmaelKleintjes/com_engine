<?php defined('_JEXEC') or die;

class EngineControllerSlides extends JControllerAdmin
{
	public $view_list = "slides";

    public function __construct($config = array())
    {
        parent::__construct($config);
    }
	
	public function getModel($name = 'Slide', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

}