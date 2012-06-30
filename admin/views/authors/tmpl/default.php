<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table class="adminlist">
    <thead>
        <tr>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
            </th>
            <th width="2">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Username' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Email' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Name' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Password' ); ?>
            </th>
        </tr>            
    </thead>
    <tfoot>
    		<tr>
    			<td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>
    		</tr>
    </tfoot>
    <?php
    $k = 0;
    for ($i=0, $n=count( $this->items ); $i < $n; $i++)
    {
        $row =& $this->items[$i];
	$checked = JHTML::_( 'grid.id', $i, $row->id_auteur );
	$link = JRoute::_( 'index.php?option=com_spip2joomla>controller=spip2joomla>task=edit>cid[]='. $row->id_auteur );
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $checked; ?>
            </td>
            <td>
                <?php echo $row->id_auteur; ?>
            </td>
            <td>
                <a href="<?php echo $link; ?>"><?php echo $row->login; ?></a>

            </td>
            <td>
                <?php echo $row->email; ?>
            </td>
            <td>
                <?php echo $row->nom; ?>
            </td>
            <td>
                <?php echo $row->pass; ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </table>

</div>
 
 
<input type="hidden" name="option" value="com_spip2joomla" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="spip2joomla" />
<input type="hidden" name="view" value="authors" />
 
</form>

