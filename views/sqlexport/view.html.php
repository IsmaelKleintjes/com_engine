<?php defined('_JEXEC') or die('Restricted access');
 
class EngineViewSQLExport extends JViewLegacy
{
	public function display($tpl = null) 
	{
	    $session = JFactory::getSession();
        $this->exportCode = $session->get('exportCode');

		$this->addToolBar();

		parent::display($tpl);
	}

	protected function addToolBar() 
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);
		JToolBarHelper::title(JText::_('SQL export'), 'database');

		$bar = & JToolBar::getInstance('toolbar');
        $bar->appendButton( 'Link', 'cancel', 'Terug', 'index.php?option=com_engine&view=sqlupdates' );
	}
}