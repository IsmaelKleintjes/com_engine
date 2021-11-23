<?php defined('_JEXEC') or die('Restricted access');
 
class EngineControllerMessages extends JControllerAdmin
{
	protected $view_list = 'messages';
	
	public function getModel($name = 'Message', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}