<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

class EngineViewCategory extends JViewLegacy
{
	public function display($tpl = null) 
	{
		$form = $this->get('Form');
		$item = $this->get('Item');
		
		$this->form = $form;
		$this->item = $item;

        $this->detail = new JHtmlDetail($this);
		$this->addToolBar(); 

		parent::display($tpl);
	}

	protected function addToolBar() 
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);
		JToolBarHelper::title('Categorie');
		JToolbarHelper::apply('category.apply');
		JToolbarHelper::cancel('category.cancel');
	}
}