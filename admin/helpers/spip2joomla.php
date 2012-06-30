<?php
/**
 * Helpers for Spip 2 Joomla Component
 * 
 *
 * @author    Carlos M. Cámara Mora
 * @link http://www.gnumla.com
 * @license    GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die(); 
 
jimport( 'joomla.application.component.helper' );
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

class spip2joomlasHelper
{
	function __construct() {
		global $mainframe, $option;	
		
		parent::__construct();
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
	
	function getSPIPLogo($logoFilter)
   {
   	$params = &JComponentHelper::getParams( 'com_spip2joomla' );
  		
  		// We get the spip path from Joomla!	
 		$spipDirectory= $params->get('spip_directory');

 		// We get the Joomla Root and we add the SPIP images directory
 		$spipIMGDirectory= '..' . DS . $spipDirectory . DS . 'IMG';
 		
 		//we get the logo fullpath
 		$filter= $logoFilter . '\..';
 		$recurse=false;
 		$fullpath=true;
 		$logoPath= JFolder::files($spipIMGDirectory,$filter,$recurse,$fullpath);
 		
 		//we get the logo filename
 		$logoName= JFile::getName($logoPath[0]);

 		//We create the new file path
		$joomlaLogoPath= 'images' . DS . 'stories' . DS .$logoName;	

		//We copy the logo
		if($logoPath)
			JFile::copy($logoPath[0], JPATH_SITE . DS .$joomlaLogoPath);
		else
			$joomlaLogoPath= null;
			
      return $joomlaLogoPath;
   }
   
	function getDocumentsID($id_spipArticle)
   {
   	$db = $this->_dbconnect();  
		if (!$db) return false;
		
		$query=	 ' SELECT a.id_document AS id_document'
					.' FROM ' . $this->_params->get( 'db_prefix' ) .'documents_liens AS a '
   	         .' WHERE a.id_objet = ' . $id_spipArticle;
   	
   	$db->setQuery($query);
   	
   	//If we get the correct connection
		if ( !$db->loadResultArray() ) {
			JError::raiseWarning(500, 'SPIP Table connection Error: ' . $db->toString() . '<br />Check SPIP table Prefix in Spip2Joomla parameters !' );
			return false;
		}
		$this->_data = $db->loadResultArray();
		
		return $this->_data;
   }

	function getDocument($id_document)
	{
		$params = &JComponentHelper::getParams( 'com_spip2joomla' );
	
		$db = $this->_dbconnect();  
		if (!$db) return false;
		
		$query=	 ' SELECT a.fichier AS path,'
					.' a.titre AS title,'
					.' a.descriptif AS description,'
					.' a.extension AS extension,'
					.' a.mode AS type,'
					.' a.largeur AS width,'
					.' a.hauteur AS height'
					.' FROM ' . $this->_params->get( 'db_prefix' ) .'documents AS a '
   	         .' WHERE a.id_document = ' . $id_document;
   	
   	$db->setQuery($query);
   	
   	//If we get the correct connection
		if ( !$db->loadObjectList() ) {
			JError::raiseWarning(500, 'SPIP Table connection Error: ' . $db->toString() . '<br />Check SPIP table Prefix in Spip2Joomla parameters !' );
			return false;
		}
		$this->_data = $db->loadObjectList();

		return $this->_data;
	}   
   
   function translateSPIPformat($letexte)
   {
   $params = &JComponentHelper::getParams( 'com_spip2joomla' );
	
	$debut_intertitre = "\n<h3 class=\"spip\">\n";
	$fin_intertitre = "</h3>\n";

   	/* This code is from SPIP 1.9.2f*/
// Harmoniser les retours chariot
	$letexte = preg_replace(",\r\n?,S", "\n", $letexte);

	// Recuperer les para HTML
	$letexte = preg_replace(",<p[>[:space:]],iS", "\n\n\\0", $letexte);
	$letexte = preg_replace(",</p[>[:space:]],iS", "\\0\n\n", $letexte);


//
	// Notes de bas de page
	//
	$ouvre_note='[';
	$ferme_note=']';
	$ouvre_ref='[';
	$ferme_ref=']';
	$mes_notes = '';
	$regexp = ', *\[\[(.*?)\]\],msS';
	if (preg_match_all($regexp, $letexte, $matches, PREG_SET_ORDER))
	foreach ($matches as $regs) {
		$note_source = $regs[0];
		$note_texte = $regs[1];
		$num_note = false;

		// note auto ou pas ?
		if (preg_match(",^ *<([^>]*)>,", $note_texte, $regs)){
			$num_note = $regs[1];
			$note_texte = str_replace($regs[0], "", $note_texte);
		} else {
			$compt_note++;
			$num_note = $compt_note;
		}

		// preparer la note
		if ($num_note) {
			if ($marqueur_notes) // quand il y a plusieurs series
								 // de notes sur une meme page
				$mn = $marqueur_notes.'-';
			$ancre = $mn.rawurlencode($num_note);

			// ne mettre qu'une ancre par appel de note (XHTML)
			if (!$notes_vues[$ancre]++)
				$name_id = " name=\"nh$ancre\" id=\"nh$ancre\"";
			else
				$name_id = "";

			$lien = "<a href=\"#nb$ancre\"$name_id class=\"spip_note\">";

			// creer le popup 'title' sur l'appel de note
			if ($title = supprimer_tags(propre($note_texte))) {
				$title = $ouvre_note.$num_note.$ferme_note.$title;
				$title = couper($title,80);
				$lien = inserer_attribut($lien, 'title', $title);
			}

			$insert = "$ouvre_ref$lien$num_note</a>$ferme_ref";
						
			$appel = "$ouvre_note<a href=\"#nh$ancre\" name=\"nb$ancre\" class=\"spip_note\" title=\"" . $note_texte . " $ancre\">$num_note</a>$ferme_note";
			
		} else {
			$insert = '';
			$appel = '';
		}

		// l'ajouter "tel quel" (echappe) dans les notes
		if ($note_texte) {
			if ($mes_notes)
				$mes_notes .= "\n\n";
			$mes_notes .= code_echappement($appel) . $note_texte;
		}

		// dans le texte, mettre l'appel de note a la place de la note
		$pos = strpos($letexte, $note_source);
		$letexte = substr($letexte, 0, $pos) . $insert
			. substr($letexte, $pos + strlen($note_source));
			
		// We write the footnote at the end of the text.
		
		$letexte .= '<p>' . $appel . ' ' . $note_texte . '</p>';
	}







	//
	// Raccourcis automatiques [?SPIP] vers un glossaire
	// (on traite ce raccourci en deux temps afin de ne pas appliquer
	//  la typo sur les URLs, voir raccourcis liens ci-dessous)
	//

	// We get the external glossary defined on the spip2joomla preferences
	$url_glossaire_externe=$params->get( 'spip_external_glossary' ).'/';
	
	if ($url_glossaire_externe) {
		$regexp = "|\[\?+([^][<>]+)\]|S";
		if (preg_match_all($regexp, $letexte, $matches, PREG_SET_ORDER))
		foreach ($matches as $regs) {
			$terme = trim($regs[1]);
			$terme_underscore = preg_replace(',\s+,', '_', $terme);
			// faire sauter l'eventuelle partie "|bulle d'aide" du lien
			// cf. http://fr.wikipedia.org/wiki/Wikip%C3%A9dia:Conventions_sur_les_titres
			$terme_underscore = preg_replace(',[|].*,', '', $terme_underscore);
			if (strstr($url_glossaire_externe,"%s"))
				$url = str_replace("%s", rawurlencode($terme_underscore),
					$url_glossaire_externe);
			else
				$url = $url_glossaire_externe.$terme_underscore;
			$url = str_replace("@lang@", $GLOBALS['spip_lang'], $url);
			$code = '['.$terme.'->?'.$url.']';

			// Eviter les cas particulier genre "[?!?]"
			if (preg_match(',[a-z],i', $terme))
				$letexte = str_replace($regs[0], $code, $letexte);
		}
	}
	
  	//
	// Raccourcis ancre [#ancre<-]
	//
	$regexp = "|\[#?([^][]*)<-\]|S";
	if (preg_match_all($regexp, $letexte, $matches, PREG_SET_ORDER))
	foreach ($matches as $regs)
		$letexte = str_replace($regs[0],
		'<a name="'.entites_html($regs[1]).'"></a>', $letexte);

	//
	// Enlaces a urls [xxx->url]
	// Note : complique car c'est ici qu'on applique typo(),
	// et en plus on veut pouvoir les passer en pipeline
	//

	$inserts = array();

	if (preg_match_all(_RACCOURCI_LIEN, $letexte, $matches, PREG_SET_ORDER)) {
		$i = 0;
		foreach ($matches as $regs) {		
			$inserts[++$i] = traiter_raccourci_lien($regs);
			$letexte = str_replace($regs[0], "@@SPIP_ECHAPPE_LIEN_$i@@",
				$letexte);
		}
	}

//	$letexte = typo($letexte, /* echap deja fait, accelerer */ false);

	foreach ($inserts as $i => $insert) {
		$letexte = str_replace("@@SPIP_ECHAPPE_LIEN_$i@@", $insert, $letexte);
	}


	//
	// Tableaux
	//

	// ne pas oublier les tableaux au debut ou a la fin du texte
	$letexte = preg_replace(",^\n?[|],S", "\n\n|", $letexte);
	$letexte = preg_replace(",\n\n+[|],S", "\n\n\n\n|", $letexte);
	$letexte = preg_replace(",[|](\n\n+|\n?$),S", "|\n\n\n\n", $letexte);

	// traiter chaque tableau
	if (preg_match_all(',[^|](\n[|].*[|]\n)[^|],UmsS', $letexte,
	$regs, PREG_SET_ORDER))
	foreach ($regs as $tab) {
		$letexte = str_replace($tab[1], traiter_tableau($tab[1]), $letexte);
	}

	//
	// Ensemble de remplacements implementant le systeme de mise
	// en forme (paragraphes, raccourcis...)
	//

	$letexte = "\n".trim($letexte);

	// les listes
	if (ereg("\n-[*#]", $letexte))
		$letexte = traiter_listes($letexte);

	// Puce
	if (strpos($letexte, "\n- ") !== false)
		$puce = definir_puce();
	else $puce = '';

	// Proteger les caracteres actifs a l'interieur des tags html
	$protege = "{}_-";
	$illegal = "\x1\x2\x3\x4";
	if (preg_match_all(",</?[a-z!][^<>]*[".preg_quote($protege)."][^<>]*>,imsS",
	$letexte, $regs, PREG_SET_ORDER)) {
		foreach ($regs as $reg) {
			$insert = $reg[0];
			// hack: on transforme les caracteres a proteger en les remplacant
			// par des caracteres "illegaux". (cf corriger_caracteres())
			$insert = strtr($insert, $protege, $illegal);
			$letexte = str_replace($reg[0], $insert, $letexte);
		}
	}

	// autres raccourcis
	$cherche1 = array(
		/* 0 */ 	"/\n(----+|____+)/S",
		/* 1 */ 	"/\n-- */S",
		/* 2 */ 	"/\n- */S",
		/* 3 */ 	"/\n_ +/S",
		/* 4 */   "/(^|[^{])[{][{][{]/S",
		/* 5 */   "/[}][}][}]($|[^}])/S",
		/* 6 */ 	"/(( *)\n){2,}(<br\s*\/?".">)?/S",
		/* 7 */ 	"/[{][{]/S",
		/* 8 */ 	"/[}][}]/S",
		/* 9 */ 	"/[{]/S",
		/* 10 */	"/[}]/S",
		/* 11 */	"/(?:<br\s*\/?".">){2,}/S",
		/* 12 */	"/<p>\n*(?:<br\s*\/?".">\n*)*/S",
		/* 13 */	"/<quote>/S",
		/* 14 */	"/<\/quote>/S",
		/* 15 */	"/<\/?intro>/S"
	);
	$remplace1 = array(
		/* 0 */ 	"\n\n$ligne_horizontale\n\n",
		/* 1 */ 	"\n<br />&mdash;&nbsp;",
		/* 2 */ 	"\n<br />$puce&nbsp;",
		/* 3 */ 	"\n<br />",
		/* 4 */ 	"\$1\n\n$debut_intertitre",
		/* 5 */ 	"$fin_intertitre\n\n\$1",
		/* 6 */ 	"<p>",
		/* 7 */ 	"<strong class=\"spip\">",
		/* 8 */ 	"</strong>",
		/* 9 */ 	"<i class=\"spip\">",
		/* 10 */	"</i>",
		/* 11 */	"<p>",
		/* 12 */	"<p>",
		/* 13 */	"<blockquote class=\"spip\"><p>",
		/* 14 */	"</blockquote><p>",
		/* 15 */	""
	);
	$letexte = preg_replace($cherche1, $remplace1, $letexte);
	$letexte = preg_replace("@^ <br />@S", "", $letexte);

	// Retablir les caracteres proteges
	$letexte = strtr($letexte, $illegal, $protege);
	return $letexte;
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
			

		$joomlaLogoPath= 'images' . DS . 'stories' . DS .'SPIP'. DS .$logoName;

		//We copy the logo
		JFile::copy($logoPath[0], JPATH_SITE . DS .$joomlaLogoPath);	
			
      return $joomlaLogoPath;
   }
}