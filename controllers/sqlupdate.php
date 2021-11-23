<?php defined('_JEXEC') or die('Restricted access');
 
class EngineControllerSQLUpdate extends JControllerForm
{
	protected $view_list = 'sqlupdates';
	
	public function getModel($name = 'SQLUpdate', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}