<?php defined('_JEXEC') or die('Restricted access');
 
class EngineViewMessage extends JViewLegacy
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
		JToolBarHelper::title($isNew ? JText::_('Nieuw bericht')
									 : JText::_('Wijzig bericht'));
		JToolbarHelper::apply('message.apply');
		JToolbarHelper::save('message.save');
		JToolbarHelper::save2new('message.save2new');
		JToolbarHelper::cancel('message.cancel');
	}
}