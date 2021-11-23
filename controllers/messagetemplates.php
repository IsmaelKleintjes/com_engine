<?php defined('_JEXEC') or die('Restricted access');
 
class EngineControllerMessagetemplates extends JControllerAdmin
{
	protected $view_list = 'messagetemplates';
	
	public function getModel($name = 'Messagetemplate', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}