<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

class EngineViewDownload extends JViewLegacy
{
	public function display($tpl = null) 
	{
		$this->item = $this->get('Item');

        $this->detail = new JHtmlDetail($this);
		$this->addToolBar(); 

		parent::display($tpl);
	}

	protected function addToolBar() 
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);
        JToolbarHelper::title('Download aanvraag');
		JToolbarHelper::cancel('download.cancel', 'Terug');
	}
}