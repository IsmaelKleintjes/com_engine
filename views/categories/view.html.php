<?php defined('_JEXEC') or die;

class EngineViewCategories extends JViewLegacy
{
	public $overview;
	
	public function display($tpl = null)
	{
        $app = JFactory::getApplication()->input;
        $crud = $app->get("crud");
		$this->state = $this->get("State");
		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}
		$overview = new JHtmlOverview($this);
		echo $overview->show(array(
                                array(
                                    'name' => 'crud',
                                    'value' => $crud
                                ),
                              )
                            );
    }
	
	public function addToolbar()
	{
        JToolbarHelper::title(JText::_('Categorieën'));
		
        JToolbarHelper::addNew('category.add');
        JToolbarHelper::editList('category.edit');
        JToolbarHelper::divider();
        JToolbarHelper::deleteList('Weet u het zeker?', 'categories.delete');

        JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter.published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('published' => false, 'unpublished' => false)), 'value', 'text', $this->state->get('filter.published'), true)
		);
	}
}	