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

		$data_component   = $input->get("com", '', 'STRING');
		$data_type        = $input->get("type", '', 'STRING');
		$data_id          = $input->get("id", 0, 'INTEGER');

		if ($data_component)
		{
			$RestHelper = new DD_RestHelper;
			$json = $RestHelper->getComponentJson($data_component, $data_type, $data_id);

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
				'type' => $data_type,
				'json' => $json
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

			default: JResponse::setHeader('Content-Type', 'application/json', true);

				if ($callback == "not set")
				{
					echo json_encode($results, JSON_HEX_APOS);
				}
				else
				{
					JResponse::setHeader('Access-Control-Allow-Origin:', '*');
					echo $callback . '(' . json_encode($results, JSON_HEX_APOS) . ')';
				}

				$app->close();
				break;
		}

		return false;

	}
}
