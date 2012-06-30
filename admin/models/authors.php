<?php
/**
 * Model for Spip 2 Joomla Component
 * 
 *
 * @author    Carlos M. Cámara Mora
 * @link http://www.gnumla.com
 * @license    GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
 
/**
 * Spip 2 Joomla
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class spip2joomlasModelauthors extends JModel
{
	var $_params;
	var $_id;
    /**
     * Auteurs data array
     *
     * @var array
     */
	var $_data;
    
	var $_total = null;
 
	var $_isDBChecked;

    /**
     * Authors group default value
     *
     * @var int
     */
	var $groupid=19; //Garstud
		
	/**
	* Pagination object
   * @var object
   */
	var $_pagination = null;

	function __construct()
	{
        parent::__construct();
 
        global $mainframe, $option;
 
   		$this->_params = &JComponentHelper::getParams( 'com_spip2joomla' );
        
   		// Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        
		$this->_isDBChecked = false;
	}
	
	function _dbconnect()
	{
		$params = &JComponentHelper::getParams( 'com_spip2joomla' );
    	
    	$option = array(); //prevent problems
 
		$option['driver']   = 'mysql';            // Database driver name
		$option['host']     = $params->get( 'db_host' );    // Database host name
		$option['user']     = $params->get( 'db_user' );       // User for database authentication
		$option['password'] = $params->get( 'db_pass' );   // Password for database authentication
		$option['database'] = $params->get( 'db_name' );      // Database name
		$option['prefix']   = $params->get( 'db_prefix' );             // Database prefix (may be empty)
		$this->groupid = $params->get( 'spip_group' ); //Garstud	

		$db = & JDatabase::getInstance( $option );

	        // necessary to check if the SPIP database is correctly configurated
		if ( JError::isError($db)) {
			if (!$this->_isDBChecked) { //if already tested, do not enqueue a second JError Warning
				//jexit('SPIP Database connection Error: ' . $db->toString() . '<br />Check Host or database, or MySql account in Spip2Joomla parameters !' );
				JError::raiseWarning(500, 'SPIP Database connection Error: ' . $db->toString() . '<br />Check Host or database, or MySql account in Spip2Joomla parameters !' );
				$this->_isDBChecked = true;
			}
			return false;
		}
				
		return $db;
	}

 
    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    function _buildQuery()
    {
    	if(empty($this->_id))
   	{
   		$query = ' SELECT a.id_auteur AS id_auteur,'
        			. ' a.email AS email,'
        			. ' a.login AS login,'
        			. ' a.nom AS nom,'
        			. ' a.pass AS pass'
            	. ' FROM ' . $this->_params->get( 'db_prefix' ) .'auteurs AS a '
            	. ' ORDER BY a.id_auteur'
            	;
   	} else
   	{
	      $query = ' SELECT a.id_auteur AS id_auteur,'
        			. ' a.email AS email,'
        			. ' a.login AS login,'
        			. ' a.nom AS nom,'
        			. ' a.pass AS pass'
            	. ' FROM ' . $this->_params->get( 'db_prefix' ) .'auteurs AS a '
   	         . 'WHERE a.id_auteur = ' . $this->_id
   	         ;
   	}
   	
        
        return $query;
    }
 
    /**
     * Retrieves the hello data
     * @return array Array of objects containing the data from the database
     */
    function getData()
    {
 
		$db = $this->_dbconnect();
		if (!$db) return false; 

        // Lets load the data if it doesn't already exist
 		$query = $this->_buildQuery();
 		$db->setQuery($query,$this->getState('limitstart'),$this->getState('limit'));
		if ( !$db->loadObjectList() ) {
			JError::raiseWarning(500, 'SPIP Table connection Error: ' . $db->toString() . '<br />Check SPIP table Prefix in Spip2Joomla parameters !' );
			return false;
		}
		$this->_data = $db->loadObjectList();
        return $this->_data;
    }

	function getTotal()
  {
  		$db = $this->_dbconnect();
		if (!$db) return false; 	  
	  
        // Load the content if it doesn't already exist
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $db->setQuery($query);
			if ( !$db->query() ) {
				return false;
			}
            $this->_total = $db->getNumRows();    
        }
        return $this->_total;
  }
   
   function getPagination()
  {
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
  }
 
    
 	function exportUser2Joomla($auteur)
	{
	 global $mainframe;


		// Get required system objects
		$user 		= clone(JFactory::getUser());

		$config		=& JFactory::getConfig();
		$authorize	=& JFactory::getACL();
		$document   =& JFactory::getDocument();
		
		// Initialize new usertype setting
		$newUsertype = 'Manager';


		// Bind the post array to the user object
		if (!$user->bind( JRequest::get('post'), 'usertype' )) {
			JError::raiseError( 500, $user->getError());
		}

		// Set some initial user values
		$user->set('id', 0);
		$user->set('usertype', 'Registered');
		$user->set('gid', $this->groupid); // Garstud : $authorize->get_group_id( '', $newUsertype, 'ARO' ));
		$user->set('username', $auteur->login);
		$user->set('name', $auteur->nom);
		$user->set('email', $auteur->email);
		$user->set('password',$auteur->pass);

		$date =& JFactory::getDate();
		$user->set('registerDate', $date->toMySQL());

		// If there was an error with registration, set the message and display form
		if ( !$user->save() )
		{
			return false;
		}else{			
			$spip2joomla_auteur['auteur_id']=$auteur->id_auteur;
			$spip2joomla_auteur['joomla_id']=$user->id;
			$spip2joomla_table=& JTable::getInstance('spipauteurs', 'Table');
			if (!$spip2joomla_table->bind($spip2joomla_auteur)) {
				JError::raiseError(500, $row->getError() );
			}
			if (!$spip2joomla_table->store()) {
			JError::raiseError(500, $row->getError() );
			}		
			return true;			
		}
	}
	function spipUsers2Joomla()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
		if (count( $cids ))		{
			foreach($cids as $cid) {
				$this->_id = $cid;
				//We get the SPIP article
				$auteur = & $this->getData();
				if (!$auteur) return "";
				
				$result = $this->exportUser2Joomla($auteur[0]);
			}						
		}		
				
		return $result;
	}
}

