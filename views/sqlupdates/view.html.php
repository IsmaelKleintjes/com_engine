<?php defined('_JEXEC') or die;

class EngineViewSQLUpdates extends JViewLegacy
{
	public function display($tpl = null)
	{
        $this->state = $this->get("State");
        $this->addToolbar();
        $this->sidebar = JHtmlSidebar::render();

		$overview = new JHtmlOverview($this);
		echo $overview->show();
	}
	
	public function addToolbar()
	{
		JToolBarHelper::title('SQL updates', 'database');
		
		JToolBarHelper::addNew('sqlupdate.add');
		JToolBarHelper::editList('sqlupdate.edit');
		JToolbarHelper::deleteList('Weet u het zeker?', 'sqlupdates.delete');
		JToolBarHelper::custom( 'sqlupdates.export','database' ,'database', 'Exporteer', true);
	}
}	