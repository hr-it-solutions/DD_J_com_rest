<?php
/**
 * @version    1-1-0-0 // Y-m-d 2017-04-24
 * @author     HR IT-Solutions Florian Häusler https://www.hr-it-solutions.com
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

/**
 * Class DD_RestViewDashboard
 *
 * @since  Version  1.1.0.0
 */
class DD_RestViewDashboard extends JViewLegacy
{
	protected $items;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since  Version  1.1.0.0
	 */
	public function display($tpl = null)
	{
		$this->items = $this->get('items');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		$this->addToolbar();
		$this->addSidebar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   Version  1.1.0.0
	 */
	protected function addToolbar()
	{
		$canDo = JHelperContent::getActions('com_dd_rest');

		JToolBarHelper::title(JText::_('COM_DD_REST_TOOLBARTITLE_LOCATIONS'), 'grid');

		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_dd_rest');
		}

	}

	/**
	 * Add the sidebar
	 *
	 * @return  void
	 *
	 * @since   Version  1.1.0.0
	 */
	protected function addSidebar()
	{
		DD_RestHelper::addSubmenu('dashboard');
		$this->sidebar = JHtml::_('sidebar.render');
	}
}
