<?php defined('_JEXEC') or die('Restricted access');
 
class EngineViewMessagetemplate extends JViewLegacy
{
	public function display($tpl = null) 
	{

		$form = $this->get('Form');
		$item = $this->get('Item');

		$this->form = $form;
		$this->item = $item;

        $session = JFactory::getSession();

        $session->set('messageTemplateId', $this->item->id);


        $this->detail = new JHtmlDetail($this);
		$this->addToolBar();

		parent::display($tpl);
	}

	protected function addToolBar() 
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);
		$isNew = ($this->item->id == 0);
        JToolbarHelper::title($isNew ? JText::_('Nieuwe berichten template')
									 : JText::_('Wijzig berichten template'));
		JToolbarHelper::apply('messagetemplate.apply');
		JToolbarHelper::save('messagetemplate.save');
		JToolbarHelper::save2new('messagetemplate.save2new');
		JToolbarHelper::cancel('messagetemplate.cancel');
	}
}