<?php defined('_JEXEC') or die('Restricted access');
 
class EngineControllerMessagelogs extends JControllerAdmin
{
	public $view_list = "messagelogs";
	
	public function __construct($config = array())
    {
        parent::__construct($config);
        $this->registerTask('unfeatured', 'featured');
    }

	public function getModel($name = 'Messagelog', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}