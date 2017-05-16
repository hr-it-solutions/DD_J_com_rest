<?php
/**
 * @version    1-1-0-0 // Y-m-d 2017-04-24
 * @author     HR IT-Solutions Florian Häusler https://www.hr-it-solutions.com
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

class DD_RestController extends JControllerLegacy {

	/**
	 * getAjax
	 * index.php?option=com_dd_rest&task=getAjax&format=json&com=content
	 *
	 * @since Version 1.1.0.0
	 *
	 * @return mixed
	 */
	public function getAjax()
	{
		$format = strtolower(JRequest::getWord('format', 'raw'));
		$app    = JFactory::getApplication();
		$input  = $app->input;
		$callback = $input->get('callback', 'not set');

		$data_component   = $input->get("com", false, 'STRING');
		$data_type        = $input->get("type", '', 'STRING');
		$data_id          = $input->get("id", 0, 'INTEGER');

		if ($data_component)
		{
			$RestHelper = new DD_RestHelper;
			$json = $RestHelper->getComponentData($data_component, $data_type, $data_id);

			if ($json)
			{
				$success = true;
			}
			else
			{
				$success = false;
			}

			$results = array(
				'success' => $success,
				'component' => $data_component,
				$data_type => $json
			);
		}
		else
		{
			$results = array('success' => false);
		}


		switch ($format)
		{
			case 'debug': echo '<pre>' . print_r($results, true) . '</pre>';
				$app->close();
				break;

			default:

				JResponse::setHeader('Content-Type', 'application/json', true);

				if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
				{
					if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])
						&& ($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'POST'
						|| $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'DELETE'
						|| $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'PUT' ))
					{
						header('Access-Control-Allow-Origin: *');
						header("Access-Control-Allow-Credentials: true");
						header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, X-Access-Token");
						header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
						header('Access-Control-Max-Age: 86400');

						echo 204;
					}

					echo 204;
					$app->close();
					break;
				}

				header('Access-Control-Allow-Origin: *');
				header("Access-Control-Allow-Credentials: true");
				header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, X-Access-Token");
				header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
				header('Access-Control-Max-Age: 86400');

				if ($callback == "not set")
				{
					echo json_encode($results, JSON_HEX_APOS);
				}
				else
				{
					header('Access-Control-Allow-Origin: *');
					echo $callback . '(' . json_encode($results, JSON_HEX_APOS) . ')';
				}

				$app->close();
				break;

		}

		return false;

	}

	/**
	 * login
	 * index.php?option=com_dd_rest&task=login
	 *
	 * @since Version 1.1.0.0
	 *
	 * @return mixed
	 */
	public function login()
	{
		$app = JFactory::getApplication();

		// Set false to allow access from everywhere
		$JoomlaParamBlockCrossAccess = false;

		// Set $JoomlaAllowDirectAccess to true to turn off CrossAccess Blocking AND Backend API Key Connection (Allows to open view by URL on same HOST e.g. Browser Access)
		$JoomlaAllowDirectAccess = true;

		// Set $credentials to true if expects credential requests (Cookies, Authentication, SSL certificates)
		$credentials = false;

		$allowedOrigins = array(
			'http://devreborn.dev4.hr-it-solutions.net',
			'http://dev4.hr-it-solutions.net'
		);

		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
		{
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])
				&& ($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'POST'))
			{
				// Cross Domain access check
				if (!$JoomlaAllowDirectAccess && $JoomlaParamBlockCrossAccess && !isset($_SERVER['HTTP_ORIGIN']))
				{
					return $this->close204($app);
				}

				// Origin check
				if (!$JoomlaAllowDirectAccess && !in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins))
				{
					// Origin is not allowed
					return $this->close204($app);
				}

				$origin = !$credentials || !$JoomlaAllowDirectAccess ? '*' : $_SERVER['HTTP_ORIGIN'];

				header('Access-Control-Allow-Origin: ' . $origin);

				if ($JoomlaAllowDirectAccess && $credentials)
				{
					header('Access-Control-Allow-Credentials: true');
				}

				header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
				header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, X-Access-Token, X-Auth-Token, X-Auth-Resource');
				header('Access-Control-Max-Age: 86400');

				// IE Cookies Support
				header('P3P: CP="CAO PSA OUR"');

				return $this->close204($app);
			}

			return $this->close204($app);
		}
		else
		{
			// Cross Domain access check
			if (!$JoomlaAllowDirectAccess && $JoomlaParamBlockCrossAccess && !isset($_SERVER['HTTP_ORIGIN']))
			{
				return $this->close204($app);
			}

			// Origin check
			if (!$JoomlaAllowDirectAccess && !in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins))
			{
				// Origin is not allowed
				return $this->close204($app);
			}

			$origin = !$credentials || !$JoomlaAllowDirectAccess ? '*' : $_SERVER['HTTP_ORIGIN'];

			header('Access-Control-Allow-Origin: ' . $origin);

			if ($JoomlaAllowDirectAccess && $credentials)
			{
				header('Access-Control-Allow-Credentials: true');
			}

			JResponse::setHeader('Content-Type', 'application/json', true);

			header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
			header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, X-Access-Token, X-Auth-Token, X-Auth-Resource');

			header('Access-Control-Max-Age: 86400');

			// IE Cookies Support
			header('P3P: CP="CAO PSA OUR"');

			// JSON Output
			$RestHelper = new DD_RestHelper;
			$jwt = $RestHelper->getJWT();
			$results = array(
				'success' => 'true',
				'credentials' => array (
					'token' => $jwt
				)
			);
			echo json_encode($results, JSON_HEX_APOS);

			$app->close();
		}

		return false;
	}

	/**
	 * close204
	 *
	 * @param   object  &$app  JFactory Application reference
	 *
	 * @return bool
	 */
	private function close204(&$app)
	{
		http_response_code(204);
		echo 204;
		$app->close();

		return false;
	}
}
