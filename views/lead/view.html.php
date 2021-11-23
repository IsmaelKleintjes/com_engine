<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

class EngineViewLead extends JViewLegacy
{
	public function display($tpl = null) 
	{
		$this->item = $this->get('Item');
        $this->form = $this->get('Form');

        $this->detail = new JHtmlDetail($this);
		$this->addToolBar(); 

		parent::display($tpl);
	}

	protected function addToolBar() 
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);
        JToolbarHelper::title('Contact aanvraag');
		JToolbarHelper::cancel('lead.cancel', 'Terug');
	}
}