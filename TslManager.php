<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

/**
 * Internationalisation of text through DB records
 *
 * PHP versions 4 > 5
 *
 * @category	library
 * @author	Luc Thibault <luc@suhali.net>
 *
 * usage :
 *
 * $translator =& TslManager::getInstance();
 * $translator->getText("my_text_chain");
 * $translator->getByID(translation_id);
 *
 * !!! Ne plus modifier DEF_APP_LANGUE une fois le développement démarré !!!
 *
 */

//include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_langue.class.php');
//include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_chaine_reference.class.php');

class TslManager {

	var $reference;
	var $translated;

	// constructor
	/**
	 * Not supposed to be called directly
	 *
	 * @return	Void
	 */
	function TslManager() {
		// Initialisation
		$this->reference = new cms_chaine_reference();
		$this->translated = new cms_chaine_traduite();

		//Verify language in session
		if (($_SESSION['BO']['id_langue'] > 0)&&(preg_match('/[0-9]+/si', $_SESSION['BO']['id_langue'])==1)	&& 	preg_match('/backoffice/', $_SERVER['PHP_SELF'])) {
			// session OK
			$_SESSION['tsl_langue']=$_SESSION['BO']['id_langue'];
			if (!defined('DEF_APP_LANGUE')){
				define('DEF_APP_LANGUE', 1);
			}	
		}
		elseif (($_SESSION['id_langue'] > 0)&&(preg_match('/[0-9]+/si', $_SESSION['id_langue'])==1)) {
			// session OK
			$_SESSION['tsl_langue']=$_SESSION['id_langue'];
			if (!defined('DEF_APP_LANGUE')){
				define('DEF_APP_LANGUE', 1);
			}	
		} 
		else{
			echo "<!-- No language stored in session - Translation Manager will fail -->";
		}		
	}

	// load
	/**
	 * Build the required instance if none exists already
	 *
	 * @return	unique instance of its own class
	 */
	function &getInstance() {
		static $singleton;

		if (!$singleton)
			$singleton = new TslManager();

		return $singleton;
	}

	// getLanguages
	/**
	 * Get an array of languages DB records values (with options)
	 *
	 * @param	Bool		$active		Get only active languages
	 * @param	Bool		$localized	Wether language name should be localized or not
	 * @return	Array		An array of all activated languages
	 */
	function getLanguages ($active=false, $localized=false) {
		$res=false;
	
		//1 test langue asso au site
		if (class_exists('cms_assolanguesite')&&isTable('cms_assolanguesite')&&isset($_SESSION['idSite'])){			
			$sql = "	SELECT	l.*
			FROM	`cms_langue` as l, cms_assolanguesite as x 
			WHERE x.xls_site = ".$_SESSION['idSite']." AND x.xls_langue = l.lan_id
			".($active ? " AND l.lan_statut=4" : '')."
			ORDER BY l.lan_id;";
			
			$res = dbGetObjectsFromRequete('cms_langue', $sql);
			
		}
		
		//2 test all langues
		if ($res==false){
		$sql = "	SELECT	*
			FROM	`cms_langue`
			".($active ? " WHERE lan_statut=4" : '')."
			ORDER BY lan_id;";
			$res = dbGetObjectsFromRequete('cms_langue', $sql);
		}

		$pile = Array();
		foreach ($res as $language) {
			if ($localized)
				$pile[$language->get_id()]['libelle'] = $this->getText($language->get_libelle());
			else	$pile[$language->get_id()]['libelle'] = $language->get_libelle();
			$pile[$language->get_id()]['libellecourt'] = $language->get_libellecourt();
		}
		return (Array) $pile;
	}
	
	
	
	function getLangCodeById($id){
		/*["cms_langues"]=>
	  array(2) {
		["fr"]=>
		string(1) "1"
		["en"]=>
		string(1) "2"
	  }*/
  		if (is_array($_SESSION['cms_langues'])){
			foreach($_SESSION['cms_langues'] as $code => $k){
				if ($k == $id){
					return $code;
				}
			
			}
		}
		return NULL;
	
	
	}


	// getActiveLangIds
	/**
	 * Get an ID list of all activated languages
	 *
	 * @return	Array		An array of all activated languages
 	*/
	function getActiveLangIds () {
		$lang = $this->getLanguages(true);
		//$pile = Array();
		//foreach ($lang as $id => $row)
		//	$pile[] = $id;
		//return (Array) $pile;
		return (Array) array_keys($lang);
	}


	/**
	 * Store language pile in session
	 *
	 * @return	Array		An array of all activated languages
 	*/
	function makeSessionLangPile () {
		$_SESSION['lang_pile'] = $this->getLanguages(true);
	}


	// getText
	/**
	 * get the translation for the current Text
	 *
	 * @param	String		$text		The reference text string
	 * @param	Int		$lang		Force the translation rather than the reference
	 * @param	String		$ref_id		reference record ID in case of 'by ID' retrieval'
	 * @return	String		The translated or not string
	 */
	function getText ($text, $lang=NULL, $ref_id=null) {
	
		if( $lang == NULL){
			if (isset($_SESSION['tsl_langue'])){
				$lang=$_SESSION['tsl_langue'];
			}
			else{
				$lang=DEF_APP_LANGUE;
				$bDetectLang = true;
			}
		}
		else{
			$bDetectLang = false;
		}
	
		//echo "<br />".$text."*".$lang."*".$ref_id."<br />";
		if (!is_null($ref_id)) { 
			//echo "<br />this->reference->get_id ".$this->reference->get_id()."<br />";
			if ($this->reference->get_id() != $ref_id)
				$this->reference = new cms_chaine_reference($ref_id);	// peupler si différent de la chaîne précédente
			$text = $this->reference->get_chaine();
			
			// cas champ vide 
			if ($lang != DEF_APP_LANGUE) { 
				$this->translated = new cms_chaine_traduite();
				$res = dbGetObjectsFromFieldValue('cms_chaine_traduite', Array('get_id_reference', 'get_id_langue'), Array($ref_id, $lang), null);
				if (count($res) == 0)  $text = ''; 
			}
			// fin cas champ vide 
			
		} else	$ref_id = $this->addReference($text, true);
			//$ref_id = $this->getTextID($text, true);
			//$this->checkTextID($text, true);
		if ($lang == DEF_APP_LANGUE && $_SESSION['tsl_langue'] == DEF_APP_LANGUE) {
			// dealing with reference
			$text = $this->checkNoInput($text);
			return (String) $text;
		} else {
			// dealing with translation if it exists otherwise return reference	
			
			/* 
			if ($lang != DEF_APP_LANGUE){
				$tsl_lang = $lang;
			}
			else{
				$tsl_lang = $_SESSION['tsl_langue'];
			}*/
			// pour le bo quand aucne langue n'est donnée ?
			if ($bDetectLang){
				$tsl_lang = $_SESSION['tsl_langue'];
			}
			else{
				$tsl_lang = $lang;
			}
			
			$tsl_lang = $lang;
			
			//if (preg_match ("/backoffice/", $_SERVER['PHP_SELF']) ){
				//if (preg_match ("/backoffice/", $_SERVER['PHP_SELF']) ) $tsl_lang = $lang; 
				//$this->translated = new cms_chaine_traduite();
				$res = dbGetObjectsFromFieldValue('cms_chaine_traduite', Array('get_id_reference', 'get_id_langue'), Array($ref_id, $tsl_lang), null);
				if (count($res) > 1) {
						echo 'TslManager::getText() - Error - multiple Db records for text ('.$text.')';
						return null;
				}
				elseif (count($res) == 1) {
					$this->translated = $res[0];
					$test = $this->translated->get_chaine();
				}
				elseif(count($res) == 0) {
					//echo 'no trad avail';
				}
			//}

			$text = $this->checkNoInput($text);

			return (String) (!is_null($test) ? $test : $text);
		}				
		
	}

	// getByID
	/**
	 * get the translation for the given ID
	 *
	 * @param	Int		$ref_id		The text string ID
	 * @param	Int		$lang		Force the translation rather than the reference
	 * @return	String		The translated or not string
	 */
	function getByID ($ref_id, $lang=NULL) {
		if( $lang == NULL){
			if (isset($_SESSION['tsl_langue'])){
				$lang=$_SESSION['tsl_langue'];
			}
			else{
				$lang=DEF_APP_LANGUE;
				$bDetectLang = true;
			}
		}
		else{
			$bDetectLang = false;
		}
		
		//error_log( 'getByID('.$lang.')');
		if ($ref_id > 0) {
			$text = $this->getText('', $lang, $ref_id);	
			$text = $this->checkNoInput($text);
			return (String) $text;	
		} else	return (String) '';
	}


	// isText
	/**
	 * Check wether the given ID matches a reference record
	 *
	 * @param	String		$ref_id		reference record ID to check
	 * @return	Boolean		Record existence
	 */
	function isText ($ref_id) {
		if ($ref_id > 0) {
			$check = new cms_chaine_reference($ref_id);
			if ($check->get_chaine() != '')
				return (Boolean) true;
			return (Boolean) false;
		}
		return (Boolean) false;
	}


	// updateText
	/**
	 * update translation item data for the current language
	 *
	 * @param	Int		$ref_id		The reference translation ID
	 * @param	String		$text		The new text string
	 * @param	Int		$lang		Force the translation rather than the reference
	 * @return	Int		Updated translation id
	 */
	function updateText ($ref_id, $text, $lang=DEF_APP_LANGUE) {
		//$this->reference->id($ref_id);
		$this->reference = dbGetObjectFromPK('cms_chaine_reference', $ref_id);
		if ($lang == DEF_APP_LANGUE && $_SESSION['tsl_langue'] == DEF_APP_LANGUE) {
			$exists = $this->getTextID($text);
			if ($exists > 0)
				// in case the new string is already in the DB, just return its ID
				return (Int) $exists;
			else {
				// update reference version of the text string
				$this->reference = new cms_chaine_reference($ref_id);
				$this->reference->set_chaine($text);
				$this->reference->set_MD5(md5($text));
				$id = dbUpdate($this->reference);
			}
		} else {
			// update translation version of the text string
			//$this->translated = new cms_chaine_traduite();
			if ($lang != DEF_APP_LANGUE)
				$tsl_lang = $lang;
		    	else	$tsl_lang = $_SESSION['tsl_langue'];
			$res = dbGetObjectsFromFieldValue('cms_chaine_traduite', Array('get_id_reference', 'get_id_langue'), Array($ref_id, $tsl_lang), null);
		    	if (count($res) > 1) {
		    		echo 'TslManager::updateText() - Error - multiple Db records for translation text (<i>'.$this->reference->get_chaine().'</i>)';
		    		return null;
			} elseif (count($res) == 1) {
				// translation exists so update it
				$this->translated = $res[0];
				$this->translated->set_chaine($text);
				$id = dbUpdate($this->translated);
			} else {
				// translation does not exist so create it
				$this->translated->set_id_reference($ref_id);
				$this->translated->set_id_langue($_SESSION['tsl_langue']);
				$this->translated->set_chaine($text);
				$id = dbInsertWithAutoKey($this->translated);
			}
		}
		if ($id > 0)
			return (Int) $ref_id;
		else	return (Int) 0;
	}


	// getTextID
	/**
	 * Get the translation ID for the given string
	 * Optionnally force creation a new record
	 *
	 * @param	String		$text		Reference text string
	 * @param	Bool		$new		Force creation if record doesn't exist
	 * @return	Int		Text string ID
	 */
	function getTextID ($text, $new=false) {
		if ($text <> '') {
			$id = $this->getReferenceID(md5($text));
			//echo "test : ".$id."<br/>";
			if ($id > 0)
				return (Int) $id;
		    	elseif ($id == 0 && $new) {
		    		$id = $this->addReference($text, false);
		    		return (Int) $id;
		    	} else	return $id;
			//return (Int) $this->reference->checkTextID($text, $new);
		} else	return (Int) 0;
	}


	// getReferenceID
	/**
	 * Get the record ID for the current MD5 text
	 *
	 * @param	String	MD5		unique hash for the record
	 * @return	Int			record ID
	 */
	function getReferenceID ($MD5=null) {
		if (!is_null($MD5)) {
			$res = dbGetObjectsFromFieldValue('cms_chaine_reference', Array('get_md5'), Array($MD5), null);
			if (count($res) == 1)
				return (Int) $res[0]->get_id();
		    	elseif (count($res) > 1) {
		    		echo 'Error - MD5 hash for translation is not unique in DB records ('.$MD5.')';
		    		return -1;
			} else	return (Int) 0;
		} else	return (Int) 0;
	}


	// addReference
	/**
	 * Add a new reference DB record
	 *
	 * @param	String		$text		Reference text string
	 * @param	Boolean		$check		Checks wether Reference already exists
	 * @return	Int		Text string ID
	 * @todo		Why not try to use getTextID with the forced 'create' parameter ?
	 */
	function addReference ($text, $check=true) {
		if ($check) {
			$ref_id = $this->getTextID($text);
			if ($ref_id > 0){
				//echo 'check '.$ref_id;
				return (Int) $ref_id;
			}
		}
		$this->reference->set_chaine($text);
		$this->reference->set_MD5(md5($text));
		$ref_id = (Int) dbInsertWithAutoKey($this->reference);
		//echo 'inset '.$ref_id;
		return $ref_id;
	}


	// addTranslation
	/**
	 * Add a new translation DB record
	 *
	 * @param	String		$ref_text	Reference text string
	 * @param	Array		$units		An array of key(lang_id) => value (translated text)
	 * @return	Int		Reference text id
	 */
	function addTranslation ($ref_text, $units=null) {
		$ref_id = $this->getTextID($ref_text);
		//echo "<br />addTranslation<br />".$ref_id." ".$ref_text."<br />";		
		
		if ($ref_id == 0)
			$ref_id = $this->getTextID($ref_text, true);

		if (!empty($units)) { 
			foreach ($units as $lang => $trad_text) {
	    		if ($lang != DEF_APP_LANGUE)
					$tsl_lang = $lang;
				else	$tsl_lang = $_SESSION['tsl_langue'];					
				
				$res = dbGetObjectsFromFieldValue('cms_chaine_traduite', Array('get_id_reference', 'get_id_langue'), Array($ref_id, $tsl_lang), null);

				if (count($res) > 1) {
				    	echo 'TslManager::addTranslation() - Error - multiple DB records for text (<i>'.$ref_text.'</i>)';
				    	return null;
				} elseif ($res !== false && count($res) == 1) {
					// translation exists so update it
					$this->translated = $res[0];
					$this->translated->set_chaine($trad_text);
					//error_log('update langue = '.$tsl_lang.' id='.$ref_id.' trans = '.$trad_text);
					$id = dbUpdate($this->translated);
				} else {
					// translation does not exist so create it
					$this->translated = new cms_chaine_traduite();
					$this->translated->set_id_reference($ref_id);
					$this->translated->set_id_langue($tsl_lang);
					$this->translated->set_chaine($trad_text);
					//error_log('insert langue = '.$tsl_lang.' id='.$ref_id.' trans = '.$trad_text);
					$id = dbInsertWithAutoKey($this->translated);
				}
			}
		}
		
		return (Int) $ref_id;
	}


	// delTranslation
	/**
	 * Delete obsolete translation DB records
	 *
	 * @param	String		$ref_id	Reference text id
	 * @return	Void
	 */
	function delTranslation ($ref_id) {
		if ($ref_id > 0)
			dbDeleteId('cms_chaine_traduite', 'set_id_reference', $ref_id);
	}


	// switchLangInUrl
	/**
	 * Get current URI and parameters to switch current language
	 *
	 * @param	String		$lang		The desired language id
	 * @return	String		The rebuilt URI
	 */
	function switchLangInURI ($lang) {
		$test    = explode("?", $_SERVER['REQUEST_URI']);
		$tstdoc  = explode("/", $test[0]);
		$doc     = array_pop($tstdoc);
		$tstparam = explode("&", $test[1]);
		$params  = array();
		foreach ($tstparam as $val) {
			$tst = explode ("=", $val);
			$params[$tst[0]] = $tst[1];
		}
		$params['switchlang'] = $lang;

		$cnt = 0;
		$tmp = $doc."?";
		foreach ($params as $key => $val) {
			if ($cnt == 0) $tmp .= $key."=".$val;
			else $tmp .= "&".$key."=".$val;
			$cnt++;
		}
		return (String) $tmp;
	}


	// flush
	/**
	 * delete all translation item data added after Sirocco setup
	 *
	 * @return	Bool		Success
	 */
	function flush () {
		// check sécurity on administration privileges
		$this->sql = "	DELETE
				FROM	`cms_chaine_reference`,
					`cms_chaine_traduite`
				WHERE	cms_ctd_id_reference = cms_crf_id
				AND	cms_crf_id > 499;";

		return (Bool) mysql_query($this->sql);

	}

	function getTransByCode($code){	
		if (isset($_SESSION['BO']['cms_texte'][$code])){
			return $_SESSION['BO']['cms_texte'][$code];
		}
		else{
			$aChain = dbGetObjectsFromFieldValue("cms_texte", array("get_code"),  array($code), NULL);
					
			if ((count($aChain) > 0)&&($aChain!=false)){
				$oChain = $aChain[0];
				$_SESSION['BO']['cms_texte'][$code] = $this->getByID($oChain->get_chaine(), $_SESSION['tsl_langue']);
				return $_SESSION['BO']['cms_texte'][$code];
			}	
		}
	}
	
	function addTransByCode($code, $chaine){	
		 
		$oChain = new Cms_texte();
		$oChain->set_code($code);
		$chaine = $this->getTextID($chaine, true);
		$oChain->set_chaine($chaine);
		$r = dbInsertWithAutoKey($oChain);
		if ($r) {
			$_SESSION['BO']['cms_texte'][$code] = $this->getByID($oChain->get_chaine(), $_SESSION['tsl_langue']);
			return $_SESSION['BO']['cms_texte'][$code];
		}   
	}
	
	function checkNoInput($str){ // no input // EN = 
		if (preg_match('/^no input \/\/ [A-Z]{2}/msi', $str)){		
			return '';
		}
		else{
			return $str;
		}
	}
	
	function echoTransByCode($code){	
		echo $this->getTransByCode($code);
	}
	
	function echoLangPacks(){
		echo $this->getLangPacks();
	}
	
	function getLangPacks(){
		$return = '<langpack>'."\n";		
	
		$aChain = dbGetObjectsFromFieldValue("cms_texte", array("get_statut"),  array(DEF_ID_STATUT_LIGNE), NULL);
				
		if ((count($aChain) > 0)&&($aChain!=false)){
			foreach($aChain as $cKey => $oChain){
				$oFr = new cms_chaine_reference($oChain->get_chaine());
				$fr = $oFr->get_chaine();
				
				$aTrad = dbGetObjectsFromFieldValue("cms_chaine_traduite", array("get_id_reference"),  array($oChain->get_chaine()), NULL);
				if ((count($aTrad) > 0)&&($aTrad!=false)){
					$en = $aTrad[0]->get_chaine();
				}
				else{
					$en = '';
				}
				
				$return .= '<gui code="'.$oChain->get_code().'">';
				$return .= '<fr><![CDATA['.utf8_encode($fr).']]></fr>';
				$return .= '<en><![CDATA['.utf8_encode($en).']]></en>';
				$return .= '</gui>'."\n";
			}
		}
		
		$return .= '</langpack>';
		
		return $return;
	}
	
	function loadAllTransToSession($idLang=NULL){	
		if ($idLang==NULL){
			$idLang=$_SESSION['tsl_langue'];
		}
		else{
			$_SESSION['tsl_langue']=$idLang;
		}
		unset($_SESSION['BO']['cms_texte']);
		//error_log( 'loadAllTransToSession('.$idLang.')');
		$aChain = dbGetObjectsFromFieldValue("cms_texte", array("get_statut"),  array(DEF_ID_STATUT_LIGNE), NULL);
		if ((count($aChain) > 0)&&($aChain!=false)){
			foreach($aChain as $chK => $oChain){
				$_SESSION['BO']['cms_texte'][$oChain->get_code()]=$this->getByID($oChain->get_chaine(), $idLang);
			}
		}	
	}
	
	function downloadLangPacks($verbose=true){
		
		if ($verbose)
			echo 'downloadLangPacks';		
	
		if ($_SERVER['HTTP_HOST']=='aws.couleur-citron.com'){
			//dont sybc the repo on itself
			return true;	
		}
	
		global $stack ;
	
		$aCache = array(); // object caching
		$sObjet = 'cms_texte';
		$sRequete = 'SELECT * FROM '.$sObjet;
		$aCo = dbGetObjectsFromRequete($sObjet, $sRequete);
		foreach($aCo as $k => $oCo){	
			$aCache[trim($oCo->get_code())] = $oCo;
		}
		
		if ($verbose)
			echo '<pre>';		
		
		$stack = array();
		
		xmlUrlParse('http://aws.couleur-citron.com/backoffice/cms/cms_texte/xml_cms_texte.php');
		
		$aTexts = $stack[0]["children"];
		
		for ($i = 0; $i <= count($aTexts); $i++) {	
		
			$code = trim(removeForbiddenChars($aTexts[$i]["attrs"]["CODE"], false));		
			//echo $code.' loop num '.$i.' / '.count($aTexts).'<br />';
				
			if (($code!='')&&(!isset($aCache[$code]))){
			
				if ($verbose)
				echo $code.'<br />';
			
				if ($verbose)
				echo '<br />';
				
				foreach($aTexts[$i]["children"] as $k => $childNode){
					if(strtolower($childNode['name'])==($this->getLangCodeById(DEF_APP_LANGUE))){						
						$text = utf8_decode($childNode["cdata"]);						
					}
					else{				
						$trad = utf8_decode($childNode["cdata"]);		
					}
				}
				
				if ($verbose)
					echo '<br />text='.$text;
				
				if ($verbose)
					echo '<br />trad='.$trad;					
				
				$id = $this->addReference ($text);
				$this->addTranslation ($text, array(2 => $trad));
			
				$oText = new cms_texte();
		
				$oText->set_code($code);
				$oText->set_chaine($id);

				$oText->set_statut(DEF_ID_STATUT_LIGNE);
				dbSauve($oText);				
				
				if ($verbose)
				echo '<hr />';	
			}
			else{
				//echo 'dejà<hr />';
				//pre_dump($aCache[$code]);
			}	
		}	
	}
}
?>