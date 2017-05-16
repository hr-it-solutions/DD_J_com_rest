<?php
/**
 * @version    1-1-0-1 // Y-m-d 2017-04-24
 * @author     HR IT-Solutions Florian Häusler https://www.hr-it-solutions.com
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/lib/jwt/src/JWT.php';

use \Firebase\JWT\JWT;

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
		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_' . $component . '/models');

		return JModelLegacy::getInstance($type, $prefix);
	}

	public function getJWT()
	{
		$nbf = time();

		$key = '7e716d0e702df0505fc72e2b89467910'; // "example_key";
		$token = array(
			"iss" => "http://" . $_SERVER['HTTP_HOST'], /*"http://dev1.hr-it-solutions.net"*/
			"aud" => "http://devreborn.dev4.hr-it-solutions.net", // The "aud" (audience) claim identifies the recipients that the JWT is intended for. Each principal intended to process the JWT MUST identify itself with a value in the audience claim. If the principal processing the claim does not identify itself with a value in the aud claim when this claim is present, then the JWT MUST be rejected.
			"iat" => time() - 60 * 10,/*1356999524*/ // The "iat" (issued at) claim identifies the time at which the JWT was issued.
			"nbf" => $nbf // Not before (nbf) - Similarly, the not-before time claim identifies the time on which the JWT will start to be accepted for processing.
		);

		/**
		 * IMPORTANT:
		 * You must specify supported algorithms for your application. See
		 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
		 * for a list of spec-compliant algorithms.
		 */
		$jwt = JWT::encode($token, $key);

		return $jwt;
	}
}
