<?php
/**
 * @version    1-1-0-1 // Y-m-d 2017-04-24
 * @author     HR IT-Solutions Florian Häusler https://www.hr-it-solutions.com
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

/**
 * Class DD_RestHelper
 *
 * @since  Version 1.1.0.0
 */
class DD_RestHelper
{
	/**
	 * getComponentModel
	 *
	 * @param   string  $component  The component name without the prefix (e.g. 'content')
	 * @param   string  $type       The model type to instantiate (e.g. 'Article')
	 * @param   string  $prefix     Prefix for the model class name. Optional. (e.g. 'ContentModel')
	 *
	 * @since  Version 1.0.0
	 *
	 * @return JModelLegacy|boolean   A JModelLegacy instance or false on failure
	 */
	private function getComponentModel($component, $type, $prefix)
	{
		jimport('joomla.application.component.model');
		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_' . $component . '/models');

		return JModelLegacy::getInstance($type, $prefix);
	}
}
