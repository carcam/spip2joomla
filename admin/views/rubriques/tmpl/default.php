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
                <?php echo JText::_( 'Id_rubrique' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Titre' ); ?>
            </th>
            <th width="2">
                <?php echo JText::_( 'Id_secteur' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Descriptif' ); ?>
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
       
        $checked = JHTML::_( 'grid.id', $i, $row->id_rubrique );
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $checked; ?>
            </td>
            <td>
                <?php echo $row->id_rubrique; ?>
            </td>
            <td>
                <?php echo $row->titre; ?>
            </td>
  				<td>
                <?php echo $row->id_secteur; ?>
            </td>
            <td>
                <?php echo $row->descriptif; ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </table>
<!-- <h3><?php echo JText::_( 'Categories' ); ?></h3>
    <table class="adminlist">
    <thead>
			<tr><th colspan="5">Sections</th></tr>
        <tr>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
            </th>
            <th width="2">
                <?php echo JText::_( 'Id_rubrique' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Titre' ); ?>
            </th>
            <th width="2">
                <?php echo JText::_( 'Id_secteur' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Descriptif' ); ?>
            </th>
        </tr>            
    </thead>

 	<?php
    $k = 0;
    for ($i=0, $n=count( $this->items ); $i < $n; $i++)
    {
        $row =& $this->items[$i];
        if($row->id_parent!=0)
        {
			$checked = JHTML::_( 'grid.id', $i, $row->id_rubrique );
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $checked; ?>
            </td>
            <td>
                <?php echo $row->id_rubrique; ?>
            </td>
            <td>
                <?php echo $row->titre; ?>
            </td>
  				<td>
                <?php echo $row->id_secteur; ?>
            </td>
            <td>
                <?php echo $row->descriptif; ?>
            </td>
        </tr>
        <?php
        }
        $k = 1 - $k;
    }
    ?>
    </table> -->
</div>
 
<input type="hidden" name="option" value="com_spip2joomla" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="rubriques" />
<input type="hidden" name="view" value="rubriques" />

<?php echo JHTML::_( 'form.token' ); ?>
</form>

