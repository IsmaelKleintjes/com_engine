<?php defined('_JEXEC') or die;

class EngineViewMessagelogs extends JViewLegacy
{
	public $overview;
	
	public function display($tpl = null)
	{
		$this->state = $this->get("State");
        $this->doc = JFactory::getDocument();
		
		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}

		$overview = new JHtmlOverview($this);
		echo $overview->show();
	}

	public function addToolbar()
	{
        JToolBarHelper::title(JText::_('Berichten log'));
        JToolBarHelper::editList('messagelog.edit', JText::_('Bekijken'));
	}
}	