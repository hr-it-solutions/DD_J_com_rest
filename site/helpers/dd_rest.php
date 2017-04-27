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
	 * getComponentJson
	 *
	 * @param   string   $component  The component name without the prefix  (e.g. 'content')
	 * @param   string   $type       The model type to instantiate          (e.g. 'article')
	 * @param   integer  $id         The id of the element
	 *
	 * @since  Version 1.0.0
	 *
	 * return
	 */
	public function getComponentData($component, $type, $id = 0)
	{
		$ModelClassPrefix = ucfirst($component) . 'Model';
		$ModelType = ucfirst($type);

		$model = $this->getComponentModel($component, $ModelType, $ModelClassPrefix);

		return $this->getComponentModelItem_s($model, $id);
	}

	/**
	 * Wrapper Method to get a single item or multiple items
	 *
	 * @param   JModelLegacy  $model  A JModel instance
	 * @param   integer       $id     The id of the element
	 *
	 * @return object|boolean  item(s) data object on success, boolean false on error
	 */
	private function getComponentModelItem_s($model, $id = 0)
	{
		if (method_exists($model, 'getItem'))
		{
			return $model->getItem($id);
		}
		elseif (method_exists($model, 'getItems'))
		{
			return $model->getItems();
		}
		else
		{
			return false;
		}
	}

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
		JModelLegacy::addIncludePath(JPATH_SITE . '/administrator/components/com_' . $component . '/models');

		return JModelLegacy::getInstance($type, $prefix);
	}
}
