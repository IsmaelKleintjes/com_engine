<?php defined('_JEXEC') or die('Restricted access');
 
class EngineViewFeature extends JViewLegacy
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
		$isNew = ($this->item->id == 0);
        JToolbarHelper::title($isNew ? JText::_('Nieuw kenmerk')
									 : JText::_('Wijzig kenmerk'));
		JToolbarHelper::apply('feature.apply');
		JToolbarHelper::save('feature.save');
		JToolbarHelper::save2new('feature.save2new');
		JToolbarHelper::cancel('feature.cancel');
	}
}