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
class spip2joomlasControllerArticles extends spip2joomlasController
{
    /**
     * Method to display the view
     *
     * @access    public
     */     

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		//$this->registerTask( 'importArticles' );
	}

   function importArticles()
	{
	$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

	 $model = $this->getModel('articles');
 
    if ($model->spipArticles2Joomla()) {
        $msg = JText::_( 'REG_COMPLETE' );
    } else {
        $msg = JText::_( 'Error Saving USER' );
    }
 
    // Check the table in so it can be edited.... we are done with it anyway
    $link = 'index.php?option=com_content';
    $this->setRedirect($link, $msg);        
   }

	function prevision()
	{
	 JRequest::setVar( 'view', 'prevision' );
	 JRequest::setVar( 'layout', 'default'  );
	 JRequest::setVar('hidemainmenu', 1);
 
    parent::display();

	}
}
?>

