<?php defined('_JEXEC') or die('Restricted access');
 
class EngineControllerMessagelog extends JControllerForm
{
	protected $view_list = 'messagelogs';
	
	public function getModel($name = 'Messagelog', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

    public function resendEmail()
    {
        $id = Input4U::getInt('id', 'GET');

        $model = $this->getModel();

        $item = $model->getItem($id);

        $model->resendEmail($item);

        return $this->setRedirect($_SERVER['HTTP_REFERER'], 'De e-mail is succesvol opnieuw verzonden naar ' . $item->email_to . '.', 'success');
    }

    public function resendNotification()
    {
        $id = Input4U::getInt('id', 'GET');

        $model = $this->getModel();

        $item = $model->getItem($id);

        $model->resendNotification($item);

        return $this->setRedirect($_SERVER['HTTP_REFERER'], 'De notificatie is succesvol opnieuw verzonden naar ' . $item->user_name . '.', 'success');
    }
}