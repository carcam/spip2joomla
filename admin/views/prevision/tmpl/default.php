<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<table class="adminlist">
    <thead>
        <tr>
            <th colspan="2">
                <?php echo $this->items['title']; ?>
            </th>
        </tr>            
    </thead>
	 <tfoot>
		<tr>
			<td colspan="2"><a href='index2.php?option=com_spip2joomla&view=articles'><?php echo JText::_( 'Back' ) ?></a></td>
		</tr>
	 </tfoot>
<tr><th><?php echo JText::_( 'date' ) ?>:</th><td><?php echo $this->items['created']; ?></td></tr>

<tr><th><?php echo JText::_( 'auteur' ) ?>:</th><td><?php echo $this->items['created_by']; ?></td></tr>
<tr><th><?php echo JText::_( 'alias' ) ?>:</th><td><?php echo $this->items['alias']; ?></td></tr>
<tr><th><?php echo JText::_( 'introtexte' ) ?>:</th><td><?php echo $this->items['introtext']; ?></td></tr>
<tr><th><?php echo JText::_( 'texte' ) ?>:</th><td><?php echo $this->items['fulltext']; ?></td></tr>
</table>





<input type="hidden" name="option" value="com_spip2joomla" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="articles" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>

