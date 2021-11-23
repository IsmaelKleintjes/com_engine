<?php defined('_JEXEC') or die('Restricted access');
 
class EngineControllerFeatures extends JControllerAdmin
{
	public $view_list = "features";
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('unfeatured', 'featured');
	}
	
	public function getModel($name = 'Feature', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}	
	
	public function featured()
	{
		return EngineHelper::controllerFeatured( $this );
	}
	
	public function saveOrderAjax()
	{
		return EngineHelper::saveOrderAjax($this);
	}
}