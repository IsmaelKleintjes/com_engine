<?php defined('_JEXEC') or die;

class EngineControllerCategory extends JControllerForm
{
	protected $view_list = "categories";
	
	public function getModel($name = 'Category', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

    public function add()
    {
       $input = JFactory::getApplication()->input;
       $crud = $input->get("crud");

       return $this->setRedirect("index.php?option=com_engine&view=category&layout=edit&crud=". $crud);
    }

    public function save()
    {
       $input = JFactory::getApplication()->input;
       $data = $input->post->get('jform', '', array());
       parent::save();
        return $this->setRedirect("index.php?option=com_engine&view=categories&crud=". $data['crud']);

    }

    public function edit()
    {
        $app = JFactory::getApplication()->input;
        $crud = $app->get("crud");
        $id = JRequest::getVar('cid');
        return $this->setRedirect("index.php?option=com_engine&view=category&layout=edit&id=". $id[0] ."&crud=". $crud);
    }

    public function cancel()
    {
        $input = JFactory::getApplication()->input;
        $data = $input->post->get('jform', '', array());
        return $this->setRedirect("index.php?option=com_engine&view=categories&crud=". $data['crud']);

    }

}