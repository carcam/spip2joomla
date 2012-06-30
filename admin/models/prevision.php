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

require_once ( JPATH_COMPONENT .DS.'models'.DS.'articles.php' );


/**
 * Spip 2 Joomla
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */


class spip2joomlasModelprevision extends JModel
{
	var $_id;
	
	function __construct()
	{
    parent::__construct();
    
    $this->_params = &JComponentHelper::getParams( 'com_spip2joomla' );
    require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'spip2joomla.php');	
 
    $array = JRequest::getVar('cid',  0, '', 'array');
    $this->setId((int)$array[0]);
    
    $this->_isDBChecked = false;
	}
	
	function _dbconnect() {
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
	
	function setId($id)
	{
    // Set id and wipe data
    $this->_id        = $id;
    $this->_data    = null;
	}

	
	function _buildQuery() {
   	$spipVersion=$this->_params->get( 'spip_version' );
   	if(empty($this->_id))
   	{
   		$query= ' SELECT a.id_article AS id_article,'
   				.' a.surtitre AS surtitre,'
   				.' a.titre AS titre,'
   				.' a.soustitre AS soustitre,'
   				.' a.id_secteur AS id_secteur,'
   				.' a.id_rubrique AS id_rubrique,'
   				.' a.descriptif AS descriptif,'
   				.' a.date AS date,'
   				.' a.texte AS texte,'
   				.' a.chapo AS chapo,'
   				.' a.ps AS ps,';
   				
   				// The only changes among versions is the url_propre field that does not exist on 2.x
   				if($spipVersion == 2)
   					$query= $query . ' a.url_site AS url_propre';
   				else
   					$query= $query . ' a.url_propre AS url_propre';
   					
   	         $query.=' FROM ' . $this->_params->get( 'db_prefix' ) .'articles AS a '
   	         .' ORDER BY a.id_article '
   	         ;
   	} else
   	{
	      $query= ' SELECT a.id_article AS id_article,'
   				.' a.surtitre AS surtitre,'
   				.' a.titre AS titre,'
   				.' a.soustitre AS soustitre,'
   				.' a.id_secteur AS id_secteur,'
   				.' a.id_rubrique AS id_rubrique,'
   				.' a.descriptif AS descriptif,'
   				.' a.date AS date,'
   				.' a.texte AS texte,'
   				.' a.chapo AS chapo,'
   				.' a.ps AS ps,';
   				
   				// The only changes among versions is the url_propre field that does not exist on 2.x
   				if($spipVersion == 2)
   					$query= $query . ' a.url_site AS url_propre';
   				else
   					$query= $query . ' a.url_propre AS url_propre';
   					
   	         $query.=' FROM ' . $this->_params->get( 'db_prefix' ) .'articles AS a '
   	         . 'WHERE a.id_article = ' . $this->_id
   	         ;

   	}

      return $query;
   }
	function &getData()
   {
   	$db = $this->_dbconnect();
		if (!$db) return false;
		
 
	 	$query = $this->_buildQuery();
	 		
	 		$db->setQuery($query);
	 		
			if ( !$db->loadObjectList() ) {
				JError::raiseWarning(500, 'SPIP Table connection Error: ' . $db->toString() . '<br />Check SPIP table Prefix in Spip2Joomla parameters !' );
				return false;
			}
			
			$this->_spipdata = $db->loadObjectList();
			
			$this->_data= $this->_translateData($this->_spipdata[0]); 	 		
	 		
         return $this->_data;
   }

   
   

   
   function _translateData($spipArticle)
   {
   	$article['title']=$spipArticle->titre;
		$article['introtext'] = "<h3>". $spipArticle->surtitre ."</h3>";
		$article['introtext'] .= "<h3>". $spipArticle->soustitre ."</h3>";
		
		//We get the article Logo
		$logoPath=spip2joomlasHelper::getArticleLogo($spipArticle->id_article);
		$logoInsertion='<img src="'. $logoPath . '" width="150" align="left"';

		$article['introtext'] .= $logoInsertion;
		
		$article['introtext'] .= spip2joomlasHelper::translateSPIPformat($spipArticle->chapo);

		if($spipVersion == 2)
			$texto=$spipArticle->texte;
		else
			$texto=iconv('UTF-8', 'UTF-8', $spipArticle->texte);
			
		$article['fulltext']= spip2joomlasHelper::translateSPIPformat($texto);
		$ps="<span>". spip2joomlasHelper::translateSPIPformat($spipArticle->ps) ."</span>";
		$article['fulltext'].=$ps;
		$article['created']=$spipArticle->date;
		$article['sectionid']= spip2joomlasModelarticles::getJoomlaId($spipArticle->id_secteur,'section');
		$article['catid']= spip2joomlasModelarticles::getJoomlaId($spipArticle->id_rubrique,'category');
		$article['alias']=$spipArticle->url_propre;
		
		$article['metadesc']= $spipArticle->descriptif;
		
		$spipArticle->id_auteur=spip2joomlasModelarticles::getIdAuteurSpip($spipArticle->id_article);

		$article['created_by']=spip2joomlasModelarticles::getJoomlaAuteurId($spipArticle->id_auteur);
		return $article;
   }
  
}