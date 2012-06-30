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
jimport('joomla.html.pagination');
jimport('joomla.filesystem.file');

require_once ( JPATH_COMPONENT .DS.'spipinc'.DS.'charsets.php' );
require_once ( JPATH_COMPONENT .DS.'spipinc'.DS.'filtres.php' );
require_once ( JPATH_COMPONENT .DS.'spipinc'.DS.'texte.php' );




require_once ( JPATH_COMPONENT .DS.'spipinc'.DS.'html.php' );
require_once ( JPATH_COMPONENT .DS.'spipinc'.DS.'lang.php' );

require_once ( JPATH_COMPONENT .DS.'spipinc'.DS.'urls.php' );
require_once ( JPATH_COMPONENT .DS.'spipinc'.DS.'utils.php' );

require_once ( JPATH_COMPONENT .DS.'spipurls'.DS.'html.php' );
require_once ( JPATH_COMPONENT .DS.'spipurls'.DS.'page.php' );
require_once ( JPATH_COMPONENT .DS.'spipurls'.DS.'propres.php' );
require_once ( JPATH_COMPONENT .DS.'spipurls'.DS.'standard.php' );



/**
 * Spip 2 Joomla
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
 
 // Regexp des raccouris, aussi utilisee pour la fusion de sauvegarde Spip
define('_RACCOURCI_LIEN', ",\[([^][]*)->(>?)([^]]*)\],msS");
define('_RACCOURCI_URL', ',^(\S*?)\s*(\d+)(\?.*?)?(#[^\s]*)?$,S');
define('_RACCOURCI_MODELE_DEBUT', '/^' . _RACCOURCI_MODELE .'/is');

class spip2joomlasModelarticles extends JModel
{
	var $_params;
	var $_id;
	var $_total;
	var $_pagination;
	var $_isDBChecked;
	
	function __construct() {
		global $mainframe, $option;
		
		require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'spip2joomla.php');	
		
		parent::__construct();

   	$this->_params = &JComponentHelper::getParams( 'com_spip2joomla' );
		
		//get the pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'));
		$limitstart = $mainframe->getUserStateFromRequest($option.'limitstart','limitstart', 0);
		// set the state pagination variables
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

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

	
	function _buildQuery() {
		$spipVersion=$this->_params->get( 'spip_version' );
   	if(empty($this->_id))
   	{
   		$query= ' SELECT a.id_article AS id_article,'
   				.' a.chapo AS chapo,'
   				.' a.surtitre AS surtitre,'
   				.' a.titre AS titre,'
   				.' a.soustitre AS soustitre,'
   				.' a.id_secteur AS id_secteur,'
   				.' a.id_rubrique AS id_rubrique,'
   				.' a.descriptif AS descriptif,'
   				.' a.date AS date,'
   				.' a.texte AS texte,'
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
	      		.' a.chapo AS chapo,'
   				.' a.surtitre AS surtitre,'
   				.' a.titre AS titre,'
   				.' a.soustitre AS soustitre,'
   				.' a.id_secteur AS id_secteur,'
   				.' a.id_rubrique AS id_rubrique,'
   				.' a.descriptif AS descriptif,'
   				.' a.date AS date,'
   				.' a.texte AS texte,'
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
    
	function getData() {
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
   
   function getArticle() {
		$db = $this->_dbconnect();  
		if (!$db) return false; 	

		$query = $this->_buildQuery();
		
		$db->setQuery($query);
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
  	 if (empty($this->_pagination))
  	 {
  	 	
  	 	//prepare the pagination values
  	 	$total=$this->getTotal();
  	 	$limitstart=$this->getState('limitstart');
  	 	$limit=$this->getState('limit');

  	 	if (!$total) return new JPagination(0,$limitstart,$limit);;
  	 	
  	 	//create the pagination object
  	 	$this->_pagination= new JPagination($total,$limitstart,$limit);
  	 }
  	 
  	 return $this->_pagination;
   }

	function getJoomlaId($rubrique_id,$container)
   {
   	$query= 'SELECT joomla_id '
   			. ' FROM #__spip2joomla_rubriques '
   			. " WHERE rubrique_id=". $rubrique_id ." AND joomla_container='" . $container . "'";
   	$joomla_id= $this->_getList( $query );
   	
   	return $joomla_id['0']->joomla_id;
   }
   
   function getJoomlaAuteurId($auteur_id)
   {
   	$query= 'SELECT joomla_id '
   			. ' FROM #__spip2joomla_auteurs '
   			. ' WHERE auteur_id='. $auteur_id;
   	$joomla_auteur_id= $this->_getList( $query );

   	return $joomla_auteur_id['0']->joomla_id;
   }
   
   function getIdAuteurSpip($article_id)
   {
   	$db = $this->_dbconnect();
   
   	$query= 'SELECT a.id_auteur '
   			. ' FROM #__auteurs_articles as a '
   			. ' WHERE a.id_article='. $article_id;
   	$db->setQuery($query);		
   	$spip_auteur_id= $db->loadResult( $query );

		return $spip_auteur_id;
   }
   
   function getArticleLogo($article_id)
   {
   	$params = &JComponentHelper::getParams( 'com_spip2joomla' );
  		
  		// We get the spip path from Joomla!	
 		$spipDirectory= $params->get('spip_directory');
 		
 		// We get the Joomla Root and we add the SPIP images directory
 		$spipDirectory= '..'. DS . $spipDirectory . DS . 'IMG';
 		
 		//we get the logo fullpath
 		$filter='arton' . $article_id . '\..';
 		$recurse=false;
 		$fullpath=true;
 		$logoPath= JFolder::files($spipDirectory,$filter,$recurse,$fullpath);
 		
 		//we get the logo filename
 		$logoName= JFile::getName($logoPath[0]);
 		
 		//We create the new file path
		$joomlaLogoPath= 'images' . DS . 'stories' . DS .'SPIP'. DS .$logoName;	

		//We check wether the SPIP directory exists inside the images folder		
		if( (JFolder::exists(JPATH_SITE . DS .'images' . DS . 'stories' . DS .'SPIP')) == false)
			JFolder::create(JPATH_SITE . DS .'images' . DS . 'stories' . DS .'SPIP');
			

		//$joomlaLogoPath= 'images' . DS . 'stories' . DS .'SPIP'. DS .$logoName;

		//We copy the logo
		JFile::copy($logoPath[0], JPATH_SITE . DS .$joomlaLogoPath);	
			
      return $joomlaLogoPath;
   }
   
	
	function exportArticle2Joomla($spipArticle)
   { 
   	$params = &JComponentHelper::getParams( 'com_spip2joomla' );
   	  		
		$article['title']=$spipArticle->titre;
		$article['introtext'] = "<h3>". $spipArticle->surtitre ."</h3>";
		$article['introtext'] .= "<h3>". $spipArticle->soustitre ."</h3>";

		//We get the article Logo
		$logoFilter= 'arton' . $article_id;
		$logoPath=spip2joomlasHelper::getSPIPLogo($logoFilter);
		$logoInsertion='<img src="'. $logoPath . '" width="150" align="left" hspace="10" vspace="5">';

		$article['introtext'] .= $logoInsertion;
		$article['introtext'] .= spip2joomlasHelper::translateSPIPformat($spipArticle->chapo);
		// We convert to UTF8 from the 1.9.x versions
		if($spipVersion == 1.9)
			$texto=iconv('LATIN1', 'UTF-8', $spipArticle->texte);
		else
			$texto=iconv('UTF-8', 'UTF-8', $spipArticle->texte);
		$article['fulltext']= spip2joomlasHelper::translateSPIPformat($texto);
		$ps="<p>". spip2joomlasHelper::translateSPIPformat($spipArticle->ps) ."</p>";
		$article['fulltext'].=$ps;
		$article['created']=$spipArticle->date;
		$article['sectionid']= $this->getJoomlaId($spipArticle->id_secteur,'section');
		$article['catid']= $this->getJoomlaId($spipArticle->id_rubrique,'category');
		$article['alias']=$spipArticle->url_propre;
		
		$article['metadesc']= $spipArticle->descriptif;
		
		$spipArticle->id_auteur=$this->getIdAuteurSpip($spipArticle->id_article);		
		$article['created_by']=$this->getJoomlaAuteurId($spipArticle->id_auteur);

		//We look for documents
		$svrgallery= $params->get('images_way');
		$spipDocuments= spip2joomlasHelper::getDocumentsID($spipArticle->id_article);
		if($spipDocuments)
			$article['fulltext'].="<hr/>";
		foreach ($spipDocuments as $spipDocument) 
		{
			$Document= spip2joomlasHelper::getDocument($spipDocument);
			$Document[0]->filename= JFile::getName($Document[0]->path);
			$spipDirectory= $params->get('spip_directory');
			$spipIMGDirectory= '..'. DS . $spipDirectory . DS . 'IMG' . DS . $Document[0]->path;
			//We create the new file path
			$joomlaDocumentFolder= 'images' . DS . 'stories' . DS .'SPIP'. DS . 'article' . $spipArticle->id_article;
			$joomlaDocumentPath= $joomlaDocumentFolder . DS . $Document[0]->filename;
			
			//We check wether the SPIP directory exists inside the images folder		
			if( (JFolder::exists(JPATH_SITE . DS . $joomlaDocumentFolder)) == false)
				JFolder::create(JPATH_SITE . DS . $joomlaDocumentFolder);
				
			//We copy the document
				JFile::copy($spipIMGDirectory, JPATH_SITE . DS .$joomlaDocumentPath);
			
			//We insert the document into the text
			if($Document[0]->type=='document')
				$article['fulltext'].='<div><a href="'. $joomlaDocumentPath .'">' . $Document[0]->title . '</a></div>';
			else if(!$svrgallery)
				$article['fulltext'].='<img src="'. $joomlaDocumentPath . '" width="400" align="left">';
							
		}
			//if the user prefers the SIG way
			if($spipDocuments && $svrgallery)
				$article['fulltext'].='{gallery}' . $joomlaDocumentFolder . '{/gallery}';
			
		// Create and load the content table row
		$joomlaArticle = & JTable::getInstance('content');

		if (!$joomlaArticle->bind($article)) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}

		if (!$joomlaArticle->store()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		
   }
   function spipArticles2Joomla()
	{		
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (count( $cids ))		{
			foreach($cids as $cid) {
				$this->_id = $cid;
				//We get the SPIP article
				$spipArticle= & $this->getArticle();
				if (!$spipArticle) return "";
				$result=$this->exportArticle2Joomla($spipArticle[0]);
			}						
		}		
				
		return $result;
	}
}