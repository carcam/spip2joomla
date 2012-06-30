<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table class="adminlist">
    <thead>
        <tr>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php // echo count( $this->items ); ?>);" />
            </th>
            <th width="2">
                <?php echo JText::_( 'Id' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Filename' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Preview' ); ?>
            </th>
        </tr>            
    </thead>
    <tr></tr>
    <?php
    $k = 0;
    for ($i=0, $n=count( $this->items ); $i < $n; $i++)
    {
        $row =& $this->items[$i];

	$checked = JHTML::_( 'grid.id', $i, $row->id );
	$link = JRoute::_( 'index.php?option=com_spip2joomla>controller=spip2joomla>task=edit>cid[]='. $row->id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $checked; ?>
            </td>
            <td>
                <?php echo $i; ?>
            </td>
            <td>
                <?php echo $row; ?>

            </td>
            <td>
                <?php echo '<center><img src="' . $row . '" height="50"></center>'; ?>

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
 
</form>
