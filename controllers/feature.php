<?php defined('_JEXEC') or die('Restricted access');
 
class EngineControllerFeature extends JControllerForm
{
	protected $view_list = 'features';
	
	public function getModel($name = 'feature', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

    public function save()
    {
        $app = JFactory::getApplication()->input;
        $session =& JFactory::getSession();
        $task = $app->get("task");
        if($task == 'save2new')
        {
            $data =  JRequest::getVar('jform', array(), 'post');
            $cat_id = $data['cat_id'];
            $session->set("feature.cat_id", $cat_id);
        }

        parent::save();
    }

    public function updateDB()
    {
        $model = $this->getModel();
        Database4U::importForm($model);

        $this->setRedirect($_SERVER['HTTP_REFERER'], 'Database updated', 'success');
    }
}