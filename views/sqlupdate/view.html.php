<?php defined('_JEXEC') or die('Restricted access');
 
class EngineViewSQLUpdate extends JViewLegacy
{
	public function display($tpl = null) 
	{
		$this->item = $this->get("Item");
        $this->form = $this->get("Form");

        $this->detail = new JHtmlDetail($this);
		$this->addToolBar();

		parent::display($tpl);
	}

	protected function addToolBar() 
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);
		$isNew = ($this->item->id == 0);
		JToolBarHelper::title($isNew ? JText::_('Nieuwe sql')
									 : JText::_('Wijzig sql'), 'database');
		JToolbarHelper::apply('sqlupdate.apply');
		JToolbarHelper::save('sqlupdate.save');
		JToolbarHelper::save2new('sqlupdate.save2new');
		JToolbarHelper::cancel('sqlupdate.cancel');
	}
}