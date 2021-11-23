<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

class EngineViewMessagelog extends JViewLegacy
{
	public function display($tpl = null) 
	{
        $this->item = $this->get("Item");
        $this->detail = new JHtmlDetail($this);
		$this->addToolBar(); 

		parent::display($tpl);
	}

	protected function addToolBar() 
	{
        $input = JFactory::getApplication()->input;
        $input->set('hidemainmenu', true);
        JToolBarHelper::title(JText::_('Berichten log'));
        JToolbarHelper::cancel('messagelog.cancel', JText::_('Terug'));
	}
}