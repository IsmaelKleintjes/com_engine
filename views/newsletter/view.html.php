<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

class EngineViewNewsletter extends JViewLegacy
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
		$isNew = ($this->item->id == 0);
		JToolBarHelper::title($isNew ? "Nieuwe nieuwsbrief inschrijving" : "Nieuwsbrief inschrijving aanpassen");
		JToolbarHelper::apply('newsletter.apply');
		JToolbarHelper::save('newsletter.save');
		JToolbarHelper::save2new('newsletter.save2new');
		JToolbarHelper::cancel('newsletter.cancel');
	}
}