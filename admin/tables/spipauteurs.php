<?php
/**
 * @package    spip2joomla
 * @subpackage Components
 * @link 
 * @license    GNU/GPL
*/
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableSpipAuteurs extends JTable
{
	/**
     * Primary Key
     *
     * @var int
     */
    var $id = null;
 
    /**
     * @var int
     */
    var $auteur_id = null;
      
    /**
     * @var int
     */
    var $joomla_id = null;
    
 
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__spip2joomla_auteurs', 'id', $db);
    }

}
