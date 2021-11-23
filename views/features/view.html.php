<?php defined('_JEXEC') or die;

class EngineViewFeatures extends JViewLegacy
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
		JToolbarHelper::title('Feature');

        JToolbarHelper::addNew('feature.add');
        JToolbarHelper::editList('feature.edit');
		if ($this->state->get('filter.published') == -2){
            JToolbarHelper::deleteList('Weet u het zeker?', 'features.delete', 'JTOOLBAR_EMPTY_TRASH');
		} else {
            JToolbarHelper::trash('features.trash');
		}
        JToolbarHelper::publish('features.publish', 'JTOOLBAR_PUBLISH', true);
        JToolbarHelper::unpublish('features.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        JToolbarHelper::archiveList('features.archive');

		JToolBarHelper::custom( 'feature.updateDB','refresh' ,'refresh', 'Update DB', false);
		
        $filterOptions = $this->get("Filter");

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_CATEGORY'),
			'filter_category_id',
			JHtml::_('select.options', $filterOptions, 'id', 'name', $this->get('state')->get('filter.category_id'))
		);
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->get('State')->get('filter.published'), true)
		);
	}
}	