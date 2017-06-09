<?php
/**
 * @version    1-1-0-0 // Y-m-d 2017-04-24
 * @author     HR IT-Solutions Florian Häusler https://www.hr-it-solutions.com
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

/**
 * Class DD_RestController
 */
class DD_RestController extends JControllerLegacy
{

	// TODO Router format=json

	protected $app;

	// Set false to allow access from everywhere
	protected $blockCrossAccess = false;

	// Set $JoomlaAllowDirectAccess to true to turn off CrossAccess Blocking AND Backend API Key Connection (Allows to open view by URL on same HOST e.g. Browser Access)
	protected $allowDirectAccess = true;

	// Set $credentials to true if expects credential requests (Cookies, Authentication, SSL certificates)
	protected $credentials = false;

	// Allowed origins
	protected $allowedOrigins = array(
		'http://devreborn.dev4.hr-it-solutions.net',
		'http://dev4.hr-it-solutions.net',
	);

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
		$this->app    = JFactory::getApplication();
		$input  = $this->app->input;
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
				$this->app->close();
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
					$this->app->close();
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

				$this->app->close();
				break;

		}

		return false;

	}

	/**
	 * index.php?option=com_dd_rest&api=v1
	 *
	 * @since Version 1.1.0.0
	 *
	 * @return mixed
	 */
	public function v1()
	{
		$this->app = JFactory::getApplication();

		// Preflight Reqeust
		if (JInput::getMethod() == 'OPTIONS')
		{
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])
				&& ($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'POST'))
			{
				// Set Headers
				$this->setHeaders();

				return $this->close204();
			}

			return $this->close204();
		}
		// Reqeust
		else
		{
			header('content-type: application/json');

			// Set Headers
			$this->setHeaders();

			// Get results
			$RestHelper = new DD_RestHelper;
			$results = $RestHelper->getResults();

			echo json_encode($results, JSON_HEX_APOS);

			$this->app->close();
		}

		return false;
	}

	/**
	 * setHeades
	 *
	 * @return bool
	 */
	protected function setHeaders()
	{
		// Cross Domain access check
		if (!$this->allowDirectAccess && $this->blockCrossAccess && !isset($_SERVER['HTTP_ORIGIN']))
		{
			return $this->close204();
		}

		// Origin check
		if (!$this->allowDirectAccess && !in_array($_SERVER['HTTP_ORIGIN'], $this->allowedOrigins))
		{
			// Origin is not allowed
			return $this->close204();
		}

		$origin = !$this->credentials || !$this->allowDirectAccess ? '*' : $_SERVER['HTTP_ORIGIN'];

		header('Access-Control-Allow-Origin: ' . $origin);

		if ($this->allowDirectAccess && $this->credentials)
		{
			header('Access-Control-Allow-Credentials: true');
		}

		/* TODO
		$this->app->setHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS', true);
		$this->app->setHeader('Access-Control-Allow-Methods', 'Origin, X-Requested-With, Content-Type, Accept, X-Access-Token, X-Auth-Token, X-Auth-Resource', true);
		$this->app->setHeader('Access-Control-Max-Age', '86400', true);
		*/

		header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, X-Access-Token, X-Auth-Token, X-Auth-Resource');
		header('Access-Control-Max-Age: 86400');

		// IE Cookies Support
		header('P3P: CP="CAO PSA OUR"');
	}

	/**
	 * close204
	 *
	 * @return bool
	 */
	private function close204()
	{
		http_response_code(204);
		echo 204;
		$this->app->close();

		return false;
	}
}
