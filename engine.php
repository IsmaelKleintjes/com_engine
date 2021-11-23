<?php defined('_JEXEC') or die;

#	FRAMEWORK
$app = JFactory::getApplication();
$doc = JFactory::getDocument();

setlocale(LC_TIME, array('Dutch_Netherlands', 'Dutch', 'nl_NL', 'nl', 'nl_NL.ISO8859-1', 'nld_NLD'));

#	DEFINES
define('COMPONENT','engine');
define('DS','/');

#	INCLUDES
jimport('joomla.application.component.controllerlegacy');

require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'app4u.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'engine.php');
require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'input4u.php';
include_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'message4u.php' );
include_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'price4u.php' );
include_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'file4u.php' );
include_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'media4u.php' );
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'language4u.php');
#require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'pdf4u.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'database4u.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'import4u.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'array4u.php');
require_once(JPATH_SITE . DS . 'components' . DS . 'com_engine' . DS . 'helpers' . DS . 'route4u.php');

include_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'html' . DS . 'detail4u.php' );
include_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'html' . DS . 'image4u.php' );
include_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'html' . DS . 'imagePop4u.php' );
include_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'html' . DS . 'overview4u.php' );

#	SUBMENU
$app = JFactory::getApplication();
$vName = $app->input->get('view');
EngineHelper::addSubmenu($vName);

if (!JFactory::getUser()->authorise('core.manage', 'com_' . COMPONENT)) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
$doc->addScript(JUri::root(false) . 'media/system/js/polyfill.xpath.js');
$doc->addScript(JUri::root(false) . 'media/system/js/tabs-state.js');

$controller = JControllerLegacy::getInstance(COMPONENT);
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();