<?php
/**
 * @package    spip2joomla
 * @subpackage Components
 * @link 
 * @license    GNU/GPL
*/
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableSpipRubriques extends JTable
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
    var $rubrique_id = null;
    
    /**
     * @var char
     */
    var $joomla_container = null;
    
    /**
     * @var int
     */
    var $joomla_id = null;
    
    /**
     * @var char
     */
    var $spip_url = null;
 
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__spip2joomla_rubriques', 'id', $db);
    }

}
