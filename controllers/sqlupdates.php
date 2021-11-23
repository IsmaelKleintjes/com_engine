<?php defined('_JEXEC') or die('Restricted access');
 
class EngineControllerSQLUpdates extends JControllerAdmin
{
	public $view_list = "sqlupdates";

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

	public function getModel($name = 'SQLUpdate', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function export()
    {
        $this->getModel('SQLUpdates')->export();
        return $this->setRedirect('index.php?option=com_engine&view=sqlexport&layout=edit');
    }
}