<?php defined('_JEXEC') or die;

class EngineViewMessages extends JViewLegacy
{
	public $overview;
	public $mailtemplates;
	
	public function display($tpl = null)
	{
		$this->state = $this->get("State");
		
		$mailtemplates = JModelLegacy::getInstance("messagetemplates", "EngineModel");
		$this->mailtemplates = $mailtemplates->getAll();
		
		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}
		
		$overview = new JHtmlOverview($this);
		echo $overview->show();
	}
	
	public function addToolbar()
	{
		JToolBarHelper::title(JText::_('Berichten'));
		
		JToolBarHelper::addNew('message.add');
		JToolBarHelper::editList('message.edit');
		JToolbarHelper::deleteList('Weet u het zeker?', 'messages.delete', 'JTOOLBAR_DELETE');

		JHtmlSidebar::addFilter(
			JText::_('- Selecteer template -'),
			'filter_templates',
			JHtml::_('select.options', $this->mailtemplates, 'id', 'title', $this->get('state')->get('filter.templates'))
		);	
	}
}	