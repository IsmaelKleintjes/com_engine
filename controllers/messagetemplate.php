<?php defined('_JEXEC') or die('Restricted access');
 
class EngineControllerMessagetemplate extends JControllerForm
{
	protected $view_list = 'messagetemplates';
	
	public function getModel($name = 'Messagetemplate', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	
	public function test()
	{
		$return = parent::save();
		if($return){
			$session = JFactory::getSession();
			$messageTemplateId = $session->get('messageTemplateId');

			$model = $this->getModel();
			$model->test($messageTemplateId);

			$data = Input4U::getArray('jform');

			return $this->setRedirect('index.php?option=com_engine&task=messagetemplate.edit&id='.(int)$data['id'],'Test e-mail is succesvol verstuurd naar '.$data['test_email']);
		}else{
			return $return;
		}
	}
}