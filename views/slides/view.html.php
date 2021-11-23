<?php defined('_JEXEC') or die;

class EngineViewSlides extends JViewLegacy
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
        JToolBarHelper::title(JText::_('Slider'));
        JToolBarHelper::addNew('slide.add');
        JToolBarHelper::editList('slide.edit');
        JToolBarHelper::divider();
        if ($this->state->get('filter.published') == -2){
            JToolbarHelper::deleteList('Weet u zeker dat u deze item(s) wilt verwijderen?', 'slides.delete', 'JTOOLBAR_EMPTY_TRASH');
        } else {
            JToolbarHelper::trash('slides.trash');
        }
        JToolbarHelper::publish('slides.publish', 'JTOOLBAR_PUBLISH', true);
        JToolbarHelper::unpublish('slides.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        JToolbarHelper::archiveList('slides.archive');

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_PUBLISHED'),
            'filter.published',
            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
        );
	}
}	