<?php
/**
 * @version    1-1-0-0 // Y-m-d 2017-04-24
 * @author     HR IT-Solutions Florian Häusler https://www.hr-it-solutions.com
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

class DD_RestController extends JControllerLegacy{

	/**
	 * The default view.
	 *
	 * @var    string
	 *
	 * @since  Version 1.1.0.1
	 */
	protected $default_view = 'dashboard';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  ContentController  This object to support chaining.
	 *
	 * @since   Version 1.1.0.1
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$view   = $this->input->get('view', 'location');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		return parent::display();
	}
}