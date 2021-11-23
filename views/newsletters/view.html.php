<?php defined('_JEXEC') or die;

class EngineViewNewsletters extends JViewLegacy
{
    public $overview;

    public function display($tpl = null)
    {
        $this->doc = JFactory::getDocument();
        $this->state = $this->get("State");

        if ($this->getLayout() !== 'modal') {
            $this->addToolbar();
            $this->sidebar = JHtmlSidebar::render();
        }

        $overview = new JHtmlOverview($this);
        echo $overview->show();
    }

    public function addToolbar()
    {
        JToolBarHelper::title('Nieuwsbrief inschrijvingen');

        JToolBarHelper::divider();
        if ($this->state->get('filter.published') == -2){
            JToolbarHelper::deleteList(JText::_('COM_PUBLIC_DELETE_DELETE'), 'newsletters.delete', 'JTOOLBAR_EMPTY_TRASH');
        } else {
            JToolbarHelper::trash('newsletters.trash');
        }

        JToolbarHelper::archiveList('newsletters.archive');

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_PUBLISHED'),
            'filter.published',
            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('published' => false, 'unpublished' => false)), 'value', 'text', $this->state->get('filter.published'), true)
        );
    }
}	