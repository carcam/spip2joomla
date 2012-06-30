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
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_spip2joomla'.DS.'tables');
require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'spip2joomla.php');
 
/**
 * Spip 2 Joomla
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class spip2joomlasModelrubriques extends JModel
{
	var $_params;	
	var $_data;
	var $_joomla_id;
	var $_pagination;
	var $_isDBChecked;
	
/**
* Constructor
*
*/
function __construct()
{
	global $mainframe;

	parent::__construct();

	$this->_params = &JComponentHelper::getParams( 'com_spip2joomla' );
		
	// Get the pagination request variables
	$limit = $mainframe->getUserStateFromRequest('global.list.limit','limit', $mainframe->getCfg('list_limit'));
	$limitstart = $mainframe->getUserStateFromRequest($option.'limitstart','limitstart', 0);
	// set the state pagination variables
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
	
	function _buildQuery()
   {
   	$params = &JComponentHelper::getParams( 'com_spip2joomla' );
   	if(empty($this->_id))
   	{
   		$query = " SELECT * "
   	         . " FROM " . $this->_params->get( 'db_prefix' ) ."rubriques"
   	         . " WHERE id_parent = 0";
   	} else
   	{
	      $query = " SELECT * "
   	         . " FROM " . $this->_params->get( 'db_prefix' ) ."rubriques"
   	         . " WHERE id_rubrique = " . $this->_id
					." AND id_parent = 0";
   	}
   	
      return $query;
   }
    
	function getData()
   {
        $db = $this->_dbconnect();
		if (!$db) return false; 	
        
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
   	if(empty($this->_pagination))
   	{
   		//import the pagination library
   		jimport('joomla.html.pagination');
   		
   		//prepare the pagination values
   		$total= $this->getTotal();
   		$limitstart=$this->getState('limitstart');
   		$limit=$this->getState('limit');
   		
  	 	if (!$total) return new JPagination(0,$limitstart,$limit);;
   		
  	 	//create the pagination object
   		$this->_pagination=new JPagination($total,$limitstart,$limit);
   	}
   	return $this->_pagination;
   }
   function getJoomlaId($rubrique_id)
   {
   	$query= 'SELECT joomla_id '
   			. ' FROM #__spip2joomla_rubriques '
   			. ' WHERE rubrique_id='. $rubrique_id
   			. ' AND joomla_container = \'section\' ';
   	$joomla_id= $this->_getList( $query );

   	return $joomla_id['0']->joomla_id;
   }
   
	function exportCategory2Joomla($rubrique)
	{
		//We prepare the data		
		$category['title']=$rubrique->titre;
		$category['description']=$rubrique->descriptif;
		$category['scope']='content';
		$category['image_position']='left';

		//We get the rubrique logo
		$logoFilter= 'rubon' . $rubrique->id_rubrique;
		$logoPath= spip2joomlasHelper::getSPIPLogo($logoFilter);
		$logoFilename= JFile::getName($logoPath);
		
		$category['image']=$logoFilename;

		if ($rubrique->id_parent == 0) //If the category comes from a Rubrique
		{
			$category['section']= $this->getJoomlaId($rubrique->id_secteur);
		}
		else //If on the contrary, it comes from a Subrubrique
		{
			$category['section']= $this->getJoomlaId($rubrique->id_secteur);
		}

		//We get the row structure
		$row =& JTable::getInstance('category');
		$result = 1;

		//We create the category
		if (!$row->bind($category)) {
			JError::raiseError(500, $row->getError() );
			$result = 0;
		}
		if (!$row->store()) {
			JError::raiseError(500, $row->getError() );
			$result = 0;
		}
		$row->checkin();
		
		//we assign the relations table data
		$joomlarubrique['rubrique_id']=$rubrique->id_rubrique;
		$joomlarubrique['joomla_container']='Category';
		$joomlarubrique['joomla_id']=$row->id;
		$joomlarubrique['spip_url']=$rubrique->url_prope;
		
		//We get the relations table row structure
		$rubrique2joomla=& JTable::getInstance('spiprubriques', 'Table');
		if (!$rubrique2joomla->bind($joomlarubrique)) {
			JError::raiseError(500, $row->getError() );
			$result = 0;
		}
		if (!$rubrique2joomla->store()) {
			JError::raiseError(500, $row->getError() );
			$result = 0;
		}	
		return $result;
	}
	
   function exportSection2Joomla($rubrique)
	{
		//We prepare the data		
		$section['title']=$rubrique->titre;
		$section['description']=$rubrique->descriptif;
		$section['scope']='content';
		
		//We get the rubrique logo
		$logoFilter= 'rubon' . $rubrique->id_rubrique;
		$logoPath= spip2joomlasHelper::getSPIPLogo($logoFilter);
		$logoFilename= JFile::getName($logoPath);
		
		$section['image']=$logoFilename;

		//We get the row structure
		$row =& JTable::getInstance('section');
		//We get optimistic
		$result = 1;
				
		if (!$row->bind($section)) {
			JError::raiseError(500, $row->getError() );
			$result = 0;
		}
		
		if (!$row->store()) {
			JError::raiseError(500, $row->getError() );
			$result = 0;
		}
		$row->checkin();
		
		//we assign the relations table data
		$joomlarubrique['rubrique_id']=$rubrique->id_rubrique;
		$joomlarubrique['joomla_container']='Section';
		$joomlarubrique['joomla_id']=$row->id;
		$joomlarubrique['spip_url']=$rubrique->url_prope;
		
		//We get the relations table row structure
		$rubrique2joomla=& JTable::getInstance('spiprubriques', 'Table');
		if (!$rubrique2joomla->bind($joomlarubrique)) {
			JError::raiseError(500, $row->getError() );
			$result = 0;
		}
		if (!$rubrique2joomla->store()) {
			JError::raiseError(500, $row->getError() );
			$result = 0;
		}

		$this->exportCategory2Joomla($rubrique);
	}
	
   function spipSections2Joomla()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (count( $cids ))		{
			foreach($cids as $cid) {
				$this->_id = $cid;
				//We get the SPIP Rubrique
				$spipSection= & $this->getData();
				if (!$spipSection) return "";
				
				$result=$this->exportSection2Joomla($spipSection[0]);
			}						
		}				
		
		return $result;
		}
		
	function spipCategories2Joomla()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
		if (count( $cids ))		{
			foreach($cids as $cid) {
				$this->_id = $cid;
				//We get the SPIP Rubrique
				$spipCategory= & $this->getData();
				if (!$spipCategory) return "";
				
				$result=$this->exportCategory2Joomla($spipCategory[0]);

			}						
		}				
		
		return $result;
		}
}