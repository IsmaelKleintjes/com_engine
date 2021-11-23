<?php defined('_JEXEC') or die;

class EngineControllerNewsletters extends JControllerAdmin
{
	public $view_list = "newsletters";

	public function getModel($name = 'Newsletter', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}