<?php defined('_JEXEC') or die;

class EngineControllerCategories extends JControllerAdmin
{
	public $view_list = "categories";

	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('unfeatured', 'featured');
	}
	
	public function getModel($name = 'Category', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function featured()
	{
		return Com::controllerFeatured( $this );
	}

    public function delete()
    {
        $app = JFactory::getApplication()->input;
        $crud = $app->get("crud");
        
        parent::delete();
        return $this->setRedirect("index.php?option=com_engine&view=categories&crud=". $crud);
    }

}