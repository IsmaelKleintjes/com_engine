<?php defined('_JEXEC') or die;

class EngineViewOrders extends JViewLegacy
{
    public $overview;

    public function display($tpl = null)
    {
        // We don't need toolbar in the modal window.
        if ($this->getLayout() !== 'modal') {

            $this->state = $this->get("State");
            $this->addToolbar();
            $this->sidebar = JHtmlSidebar::render();
        }

        $overview = new JHtmlOverview($this);
        echo $overview->show();
    }

    public function addToolbar()
    {
        JToolbarHelper::title('Orders');

        JToolbarHelper::addNew('order.add');
        JToolbarHelper::editList('order.edit');
        if ($this->state->get('filter.published') == -2){
            JToolbarHelper::deleteList('Weet u het zeker?', 'orders.delete', 'JTOOLBAR_EMPTY_TRASH');
        } else {
            JToolbarHelper::trash('orders.trash');
        }
        JToolbarHelper::publish('orders.publish', 'JTOOLBAR_PUBLISH', true);
        JToolbarHelper::unpublish('orders.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        JToolbarHelper::archiveList('orders.archive');

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_PUBLISHED'),
            'filter_published',
            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->get('State')->get('filter.published'), true)
        );
    }
}	