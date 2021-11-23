<?php defined('_JEXEC') or die('Restricted access');
 
class EngineViewDashboard extends JViewLegacy
{
	function display($tpl = null) 
	{
		$this->mvcGroups = EngineHelper::getMVCgroups();

		$this->addToolbar();
		parent::display($tpl);
	}
	
	public function addToolbar()
	{
		JToolBarHelper::title(JText::_('Dashboard'), 'dashboard');
		JToolBarHelper::preferences('com_engine',700,700);
	}
}