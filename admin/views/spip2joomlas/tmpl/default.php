<?php defined('_JEXEC') or die('Restricted access'); ?>

<table class="adminform">
	<tbody>
	<tr>
	<td valign="top" width="55%">
		<div id="cpanel">
			
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_spip2joomla&view=authors">
						<img src="<?php echo JURI::base(); ?>/components/com_spip2joomla/assets/auteurs.png" alt="<?php echo JText::_('SPIP2JOOMLA_AUTEURS'); ?>">					
						<span><?php echo JText::_('SPIP2JOOMLA_AUTEURS'); ?></span>
					</a>
				</div>
			</div>
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_spip2joomla&view=rubriques">
						<img src="<?php echo JURI::base(); ?>components/com_spip2joomla/assets/rubriquesSections.png" alt="<?php echo JText::_('SPIP2JOOMLA_RUBRIQUES'); ?>">					
						<span><?php echo JText::_('SPIP2JOOMLA_RUBRIQUES'); ?></span>
					</a>
				</div>
			</div>
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_spip2joomla&view=subrubriques">
						<img src="<?php echo JURI::base(); ?>components/com_spip2joomla/assets/rubriquesCategories.png" alt="<?php echo JText::_('SPIP2JOOMLA_SUBRUBRIQUES'); ?>">					
						<span><?php echo JText::_('SPIP2JOOMLA_SUBRUBRIQUES'); ?></span>
					</a>
				</div>
			</div>
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_spip2joomla&view=articles">
						<img src="<?php echo JURI::base(); ?>/components/com_spip2joomla/assets/articles.png" alt="<?php echo JText::_('SPIP2JOOMLA_ARTICLES'); ?>">					
						<span><?php echo JText::_('SPIP2JOOMLA_ARTICLES'); ?></span></a>
				</div>
			</div>
			<div style="float: left;">
				<div class="icon">
					<a href="index.php?option=com_spip2joomla&view=documents">
						<img src="<?php echo JURI::base(); ?>/components/com_spip2joomla/assets/documents.png" alt="<?php echo JText::_('SPIP2JOOMLA_DOCUMENTS'); ?>">					
						<span><?php echo JText::_('SPIP2JOOMLA_DOCUMENTS'); ?></span></a>
				</div>
			</div>
			
		</div>
	</td>
	</tr>
	</tbody>
</table>