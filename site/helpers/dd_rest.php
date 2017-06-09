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
	 * getResults
	 *
	 * @return array
	 */
	public function getResults()
	{
		$role = 'guest';

		// GET POST DATA
		$json = file_get_contents('php://input');
		$obj = json_decode($json);

		// JSON Output
		$jwt = $this->getJWT();

		// Test this by direct access
		// print_r($obj);

		JPluginHelper::importPlugin('dd_rest');
		$dispatcher = JEventDispatcher::getInstance();
		$plg_results = $dispatcher->trigger('onRestRun', array(&$obj, &$role, $jwt));

		if (!empty($plg_results))
		{
			$results = $plg_results; // prepear plg_results in loop
		}
		else
		{
			$results = array(
				'success' => 'true',
				'credentials' => array (
					'role' => $role, /**J User Rolle**/
					'token' => $jwt
				)
			);
		}

		return $results;
	}



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

	/**
	 * getJWT
	 *
	 * @return string
	 */
	public function getJWT()
	{
		$nbf = time();

		// "example_key";
		$key = '7e716d0e702df0505fc72e2b89467910';

		/*
		* The "iss" (issuer) claim identifies the principal that issued the JWT.  The processing of this claim is generally application specific. The "iss" value is a case-sensitive string containing a StringOrURI value.  Use of this claim is OPTIONAL.
		* The "aud" (audience) claim identifies the recipients that the JWT is intended for. Each principal intended to process the JWT MUST identify itself with a value in the audience claim. If the principal processing the claim does not identify itself with a value in the aud claim when this claim is present, then the JWT MUST be rejected.
		* The "iat" (issued at) claim identifies the time at which the JWT was issued.
		* Not before (nbf) - Similarly, the not-before time claim identifies the time on which the JWT will start to be accepted for processing.
		*/
		$token = array(
			// TODO switch http / https
			"iss" => "http://" . JUri::getInstance()->getHost(),
			"aud" => "http://devreborn.dev4.hr-it-solutions.net",
			"iat" => time() - 60 * 10,/*1356999524*/
			"nbf" => $nbf
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
