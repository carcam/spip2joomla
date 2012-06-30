<?php
/**
 * Controller for importing Rubriques Spip 2 Joomla Component
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
 */
class spip2joomlasControllerRubriques extends spip2joomlasController
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

	function importSections()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$model = $this->getModel('rubriques');
 
    	if ($model->spipSections2Joomla()) {
        $msg = JText::_( 'Importing Sections Success!!' );
    	} else {
        $msg = JText::_( 'Error Importing Sections' );
    // Check the table in so it can be edited.... we are done with it anyway
    	}
    $link = 'index.php?option=com_sections&scope=content';
    $this->setRedirect($link, $msg);        
   }
   
  	function importCategories()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$model = $this->getModel('subrubriques');
 
    	if ($model->spipCategories2Joomla()) {
        $msg = JText::_( 'Importing Categories Success!!' );
    	} else {
        $msg = JText::_( 'Error Importing Categories' );
    // Check the table in so it can be edited.... we are done with it anyway
    	}
    $link = 'index.php?option=com_categories&section=com_content';
    $this->setRedirect($link, $msg);        
   }
    
}
?>

