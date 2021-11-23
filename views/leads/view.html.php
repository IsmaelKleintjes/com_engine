<?php defined('_JEXEC') or die;

class EngineViewLeads extends JViewLegacy
{
	public $overview;
	
	public function display($tpl = null)
	{

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
        JToolbarHelper::title(JText::_('Contact aanvragen'));

        JToolbarHelper::editList('lead.edit', 'Bekijken');
        JToolbarHelper::divider();
		if ($this->state->get('filter.published') == -2){
            JToolbarHelper::deleteList('Weet u zeker dat u deze item(s) wilt verwijderen?', 'leads.delete', 'JTOOLBAR_EMPTY_TRASH');
		} else {
            JToolbarHelper::trash('leads.trash');
		}

        JToolbarHelper::preferences( 'com_engine' );
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter.published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('published' => false, 'unpublished' => false, 'archived' => false)), 'value', 'text', $this->state->get('filter.published'), true)
		);
	}
}	