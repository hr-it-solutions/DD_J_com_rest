<?php
/**
 * @version    1-1-0-0 // Y-m-d 2017-04-24
 * @author     HR IT-Solutions Florian Häusler https://www.hr-it-solutions.com
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

if (!JFactory::getUser()->authorise('core.manage', 'com_dd_rest'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::import('helpers.dd_rest', JPATH_COMPONENT_ADMINISTRATOR);

$controller	= JControllerLegacy::getInstance('DD_Rest');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
