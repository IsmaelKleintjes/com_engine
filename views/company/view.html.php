<?php defined('_JEXEC') or die('Restricted access');

class EngineViewCompany extends JViewLegacy
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
        JToolbarHelper::title($isNew ? JText::_('Nieuw item')
            : JText::_('Wijzig item'));
        JToolbarHelper::apply('company.apply');
        JToolbarHelper::save('company.save');
        JToolbarHelper::save2new('company.save2new');
        JToolbarHelper::cancel('company.cancel');
    }
}