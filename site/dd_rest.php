<?php
/**
 * @version    1-1-0-0 // Y-m-d 2017-04-24
 * @author     HR IT-Solutions Florian Häusler https://www.hr-it-solutions.com
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/helpers/dd_rest.php';

$controller = JControllerLegacy::getInstance('DD_Rest');
$controller->execute(JFactory::getApplication()->input->get('api'));
$controller->redirect();
