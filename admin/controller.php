<?php
/**
 * Controller for Spip 2 Joomla Component
 * 
 *
 * @author    Carlos M. Cámara Mora
 * @link http://www.gnumla.com
 * @license    GNU/GPL
 */
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
/**
 * Spip to Joomla Component Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class spip2joomlasController extends JController
{
    /**
     * Method to display the view
     *
     * @access    public
     */     

	function display()
	{
	 parent::display();
	}

	function importAuthors()
	{
	 $model = $this->getModel('authors');
 
    if ($model->spipUsers2Joomla()) {
        $msg = JText::_( 'All users were correctly imported' );
    } else {
        $msg = JText::_( 'Error Saving one or more users' );
    }
 
    // Check the table in so it can be edited.... we are done with it anyway
    $link = 'index.php?option=com_users';
    $this->setRedirect($link, $msg);        
   }


}
?>

