<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

class EngineViewSlide extends JViewLegacy
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
        $isNew = ($this->item->id == 0);
        JToolBarHelper::title($isNew ? "Nieuwe slide" : "Slide wijzigen");
        JToolbarHelper::apply('slide.apply');
        JToolbarHelper::save('slide.save');
        JToolbarHelper::save2new('slide.save2new');
        JToolbarHelper::cancel('slide.cancel');
	}
}