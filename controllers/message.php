<?php defined('_JEXEC') or die('Restricted access');
 
class EngineControllerMessage extends JControllerForm
{
	protected $view_list = 'messages';
	
	public function getModel($name = 'Message', $prefix = 'EngineModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function test()
	{
		$email = Input4U::get("email");
		if(strlen($email) > 0)
		{
			$sent = $this->getModel()->sendMail(Input4U::getInt("id"));
			if($sent)
			{
				$this->setRedirect($_SERVER["HTTP_REFERER"], "Er is een test e-mail verzonden naar " . Input4U::get("email"));
			} else {
				$this->setRedirect($_SERVER["HTTP_REFERER"], "Er is iets misgegaan met het verzenden van de test e-mail.");
			}
		} else {
			$this->setRedirect($_SERVER["HTTP_REFERER"], "U heeft geen e-mail adres ingevuld!");
		}
	}
}