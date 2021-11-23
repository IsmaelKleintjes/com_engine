<?php defined('_JEXEC') or die;

class EngineViewMessagetemplates extends JViewLegacy
{
	public $overview;
	
	public function display($tpl = null)
	{
		$this->state = $this->get("State");
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}

		$oveview = new JHtmlOverview($this);
		echo $oveview->show();
	}
	
	public function addToolbar()
	{
		JToolbarHelper::title('Berichten templates');

        JToolbarHelper::addNew('messagetemplate.add');
        JToolbarHelper::editList('messagetemplate.edit');
        JToolbarHelper::deleteList('Weet u het zeker?', 'messagetemplates.delete', 'JTOOLBAR_DELETE');
	}
}	