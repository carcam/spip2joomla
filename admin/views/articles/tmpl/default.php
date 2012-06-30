<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">

    <table class="adminlist">
    <thead>
        <tr>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
            </th>
            <th width="2">
                <?php echo JText::_( 'Id_article' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Surtitre' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Titre' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Soustitre' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Id_rubrique' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Descriptif' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'date' ); ?>
            </th>
        </tr>            
    </thead>
	 <tfoot>
		<tr>
			<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	 </tfoot>

	<?php    
    $k = 0;
    for ($i=0, $n=count( $this->items ); $i < $n; $i++)
    {
        $row =& $this->items[$i];
	$checked = JHTML::_( 'grid.id', $i, $row->id_article );
	$link = JRoute::_( 'index.php?option=com_spip2joomla&controller=articles&task=prevision&cid[]='. $row->id_article );
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $checked; ?>
            </td>
            <td>
                <?php echo $row->id_article; ?>
            </td>
				<td>
                <?php echo $row->surtitre; ?>
            </td>
            <td>
                <a href="<?php echo $link; ?>"><?php echo $row->titre; ?></a>
            </td>
  				<td>
                <?php echo $row->soustitre; ?>
            </td>
  				<td>
                <?php echo $row->id_rubrique; ?>
            </td>
            <td>
                <?php echo $row->descriptif; ?>
            </td>
            <td>
                <?php echo $row->date; ?>
            </td>
        </tr>

        <?php
        $k = 1 - $k;
    }
    ?>
    </table>


<?php


?>

<input type="hidden" name="option" value="com_spip2joomla" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="articles" />
<input type="hidden" name="view" value="articles" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />

<?php echo JHTML::_( 'form.token' ); ?>
</form>

