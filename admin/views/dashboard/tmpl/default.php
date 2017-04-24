<?php
/**
 * @version    1-1-0-0 // Y-m-d 2017-04-24
 * @author     HR IT-Solutions Florian Häusler https://www.hr-it-solutions.com
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;
?>
<div class="row-fluid">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container" class="span12">
	<?php endif; ?>
		<div class="row-fluid">
            <!-- Module Positions -->
			<?php
			$modules = JModuleHelper::getModules('dd_rest');
			if (count($modules)):

				$modules = array_chunk($modules, 2);

				foreach ($modules as $modulegroup) :

					echo '<div class="row-fluid">';

					foreach ($modulegroup as $module) :
						echo '<div class="span12">';
						echo JModuleHelper::renderModule($module);
						echo '</div>';
					endforeach;

					echo '</div>';

				endforeach;

			else :

				echo '<div class="alert alert-info">';
				echo JText::sprintf('COM_DD_REST_POSITION_DESCRIPTION', 'dd_rest');
				echo '</div>';

			endif;
			?>
            <hr>
            <!-- Component Description -->
            <div class="text-center">
                <p><?php echo JText::_('COM_DD_REST_XML_DESCRIPTION'); ?></p>
            </div>

            <!-- Component Version Info -->
            <div class="alert alert-success text-center">
                <?php echo JText::sprintf('COM_DD_REST_VERSION', DD_RestHelper::getComponentVersion()); ?>
            </div>

            <hr>
            <!-- Component Credits -->
            <div class="text-center">
                <p><small><?php echo nl2br(JText::sprintf('COM_DD_REST_CREDITS', DD_RestHelper::getComponentCoyright())); ?></small></p>
            </div>
		</div>
	</div>
</div>
