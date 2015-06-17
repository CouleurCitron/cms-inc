<?php

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_inscrit.class.php')  && (strpos(__FILE__,'/include/bo/class/news_inscrit.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/news_inscrit.class.php');
}else{
/*======================================

objet de BDD news_inscrit :: class news_inscrit

SQL mySQL:

DROP TABLE IF EXISTS news_inscrit;
CREATE TABLE news_inscrit
(
	ins_id			int (11) PRIMARY KEY not null,
	ins_mail			varchar (255),
	ins_prenom			varchar (255),
	ins_nom			varchar (255),
	ins_recu			int (11),
	ins_inscrit			int (11) not null,
	ins_dt_recu			date,
	ins_societe			varchar (255) not null,
	ins_civilite			varchar (255) not null,
	ins_fonction			varchar (255) not null,
	ins_adresse			varchar (255),
	ins_cp			varchar (255),
	ins_ville			varchar (255),
	ins_tel			varchar (55),
	ins_profil			int (11) not null,
	ins_objet			int (11) not null,
	ins_objet_id			int (11) not null,
	ins_cms_site			int (11) not null,
	ins_dt_crea			date not null,
	ins_dt_modif			date not null
)

SQL Oracle:

DROP TABLE news_inscrit
CREATE TABLE news_inscrit
(
	ins_id			number (11) constraint ins_pk PRIMARY KEY not null,
	ins_mail			varchar2 (255),
	ins_prenom			varchar2 (255),
	ins_nom			varchar2 (255),
	ins_recu			number (11),
	ins_inscrit			number (11) not null,
	ins_dt_recu			date,
	ins_societe			varchar2 (255) not null,
	ins_civilite			varchar2 (255) not null,
	ins_fonction			varchar2 (255) not null,
	ins_adresse			varchar2 (255),
	ins_cp			varchar2 (255),
	ins_ville			varchar2 (255),
	ins_tel			varchar2 (55),
	ins_profil			number (11) not null,
	ins_objet			number (11) not null,
	ins_objet_id			number (11) not null,
	ins_cms_site			number (11) not null,
	ins_dt_crea			date not null,
	ins_dt_modif			date not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="news_inscrit" libelle="Inscrit" prefix="ins" display="mail" abstract="nom" >
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="true" order="true" asso="news_assoinscrittheme"/>
<item name="mail" libelle="Mail" type="varchar" length="255" list="true" order="true" option="email" nohtml="true"/>
<item name="prenom" libelle="Prénom" type="varchar" length="255" list="true" order="true" nohtml="true"/>
<item name="nom" libelle="Nom" type="varchar" length="255" list="true" order="true" nohtml="true"/>
<item name="recu" libelle="nb mails reçus" type="int"  length="11" list="false" order="false" default="0" nohtml="true"/>
<item name="inscrit" type="int" length="11" notnull="true" default="0" list="false"  order="false" />
<item name="dt_recu" type="date" list="false" order="false" />
<item name="societe" type="varchar" length="255" notnull="true" list="false" order="false" nohtml="true"/>
<item name="civilite" libelle="Civilité" type="varchar" length="255" notnull="true" default="" list="false"  order="false" nohtml="true"/>
<item name="fonction" type="varchar" length="255" notnull="true" default="" list="false"  order="false" nohtml="true"/>
<item name="adresse" libelle="Adresse" type="varchar" length="255" list="false" order="false" nohtml="true"/>
<item name="cp" libelle="Code Postal" type="varchar" length="255" list="false" order="false" nohtml="true"/>
<item name="ville" libelle="Ville" type="varchar" length="255" list="true" order="true" nohtml="true"/>
<item name="tel" libelle="Téléphone" type="varchar" length="55" list="false" order="false" nohtml="true"/>
<item name="profil" type="int" length="11" notnull="true" default="0" list="false" nohtml="true"/>
<item name="objet" type="int" length="11" notnull="true"  list="false" fkey="classe"/>
<item name="objet_id" type="int" length="11" notnull="true" default="-1" list="false"/>
<item name="cms_site" type="int" length="11" notnull="true" default="1" list="false" fkey="cms_site"/>
<item name="dt_crea" type="date" notnull="true" list="true" order="true" />
<item name="dt_modif" type="date" notnull="true" list="false" />
<langpack lang="fr">
<norecords>Pas d'inscrit à afficher</norecords>
</langpack>
</class>


==========================================*/

class news_inscrit
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $mail;
var $prenom;
var $nom;
var $recu;
var $inscrit;
var $dt_recu;
var $societe;
var $civilite;
var $fonction;
var $adresse;
var $cp;
var $ville;
var $tel;
var $profil;
var $objet;
var $objet_id;
var $cms_site;
var $dt_crea;
var $dt_modif;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"news_inscrit\" libelle=\"Inscrit\" prefix=\"ins\" display=\"mail\" abstract=\"nom\" >
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\" order=\"true\" asso=\"news_assoinscrittheme\"/>
<item name=\"mail\" libelle=\"Mail\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" option=\"email\" nohtml=\"true\"/>
<item name=\"prenom\" libelle=\"Prénom\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\"/>
<item name=\"nom\" libelle=\"Nom\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\"/>
<item name=\"recu\" libelle=\"nb mails reçus\" type=\"int\"  length=\"11\" list=\"false\" order=\"false\" default=\"0\" nohtml=\"true\"/>
<item name=\"inscrit\" type=\"int\" length=\"11\" notnull=\"true\" default=\"0\" list=\"false\"  order=\"false\" />
<item name=\"dt_recu\" type=\"date\" list=\"false\" order=\"false\" />
<item name=\"societe\" type=\"varchar\" length=\"255\" notnull=\"true\" list=\"false\" order=\"false\" nohtml=\"true\"/>
<item name=\"civilite\" libelle=\"Civilité\"  type=\"varchar\" length=\"255\" notnull=\"true\" default=\"\" list=\"false\"  order=\"false\" nohtml=\"true\"/>
<item name=\"fonction\" type=\"varchar\" length=\"255\" notnull=\"true\" default=\"\" list=\"false\"  order=\"false\" nohtml=\"true\"/>
<item name=\"adresse\" libelle=\"Adresse \" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\"/>
<item name=\"cp\" libelle=\"Code Postal\" type=\"varchar\" length=\"255\" list=\"false\" order=\"false\" nohtml=\"true\"/>
<item name=\"ville\" libelle=\"Ville\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\"/>
<item name=\"tel\" libelle=\"Téléphone\" type=\"varchar\" length=\"55\" list=\"false\" order=\"false\" nohtml=\"true\"/>
<item name=\"profil\" type=\"int\" length=\"11\" notnull=\"true\" default=\"0\" list=\"false\" nohtml=\"true\"/>
<item name=\"objet\" type=\"int\" length=\"11\" notnull=\"true\"  list=\"false\" fkey=\"classe\"/>
<item name=\"objet_id\" type=\"int\" length=\"11\" notnull=\"true\" default=\"-1\" list=\"false\"/>
<item name=\"cms_site\" type=\"int\" length=\"11\" notnull=\"true\" default=\"1\" list=\"false\" fkey=\"cms_site\"/>
<item name=\"dt_crea\" type=\"date\" notnull=\"true\" list=\"true\" order=\"true\" />
<item name=\"dt_modif\" type=\"date\" notnull=\"true\" list=\"false\" />
<langpack lang=\"fr\">
<norecords>Pas d'inscrit à afficher</norecords>
</langpack>
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE news_inscrit
(
	ins_id			int (11) PRIMARY KEY not null,
	ins_mail			varchar (255),
	ins_prenom			varchar (255),
	ins_nom			varchar (255),
	ins_recu			int (11),
	ins_inscrit			int (11) not null,
	ins_dt_recu			date,
	ins_societe			varchar (255) not null,
	ins_civilite			varchar (255) not null,
	ins_fonction			varchar (255) not null,
	ins_adresse			varchar (255),
	ins_cp			varchar (255),
	ins_ville			varchar (255),
	ins_tel			varchar (55),
	ins_profil			int (11) not null,
	ins_objet			int (11) not null,
	ins_objet_id			int (11) not null,
	ins_cms_site			int (11) not null,
	ins_dt_crea			date not null,
	ins_dt_modif			date not null
)

";

// constructeur
function news_inscrit($id=null)
{
	if (istable(get_class($this)) == false){
		dbExecuteQuery($this->sMySql);
	}

	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;
		if(array_key_exists('0',$this->inherited_list)){
			foreach($this->inherited_list as $class){
				if(!is_object($class))
					$this->inherited[$class] = dbGetObjectFromPK($class, $id);
			}
		}
	} else {
		$this->id = -1;
		$this->mail = "";
		$this->prenom = "";
		$this->nom = "";
		$this->recu = -1;
		$this->inscrit = -1;
		$this->dt_recu = date("d/m/Y");
		$this->societe = "";
		$this->civilite = "";
		$this->fonction = "";
		$this->adresse = "";
		$this->cp = "";
		$this->ville = "";
		$this->tel = "";
		$this->profil = -1;
		$this->objet = -1;
		$this->objet_id = -1;
		$this->cms_site = 1;
		$this->dt_crea = date("d/m/Y");
		$this->dt_modif = date("d/m/Y");
		if(array_key_exists('0',$this->inherited_list)){
			foreach($this->inherited_list as $class){
				if(!is_object($class))
					$this->inherited[$class] = new $class();
			}
		}
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Ins_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Ins_mail", "text", "get_mail", "set_mail");
	$laListeChamps[]=new dbChamp("Ins_prenom", "text", "get_prenom", "set_prenom");
	$laListeChamps[]=new dbChamp("Ins_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Ins_recu", "entier", "get_recu", "set_recu");
	$laListeChamps[]=new dbChamp("Ins_inscrit", "entier", "get_inscrit", "set_inscrit");
	$laListeChamps[]=new dbChamp("Ins_dt_recu", "date_formatee", "get_dt_recu", "set_dt_recu");
	$laListeChamps[]=new dbChamp("Ins_societe", "text", "get_societe", "set_societe");
	$laListeChamps[]=new dbChamp("Ins_civilite", "text", "get_civilite", "set_civilite");
	$laListeChamps[]=new dbChamp("Ins_fonction", "text", "get_fonction", "set_fonction");
	$laListeChamps[]=new dbChamp("Ins_adresse", "text", "get_adresse", "set_adresse");
	$laListeChamps[]=new dbChamp("Ins_cp", "text", "get_cp", "set_cp");
	$laListeChamps[]=new dbChamp("Ins_ville", "text", "get_ville", "set_ville");
	$laListeChamps[]=new dbChamp("Ins_tel", "text", "get_tel", "set_tel");
	$laListeChamps[]=new dbChamp("Ins_profil", "entier", "get_profil", "set_profil");
	$laListeChamps[]=new dbChamp("Ins_objet", "entier", "get_objet", "set_objet");
	$laListeChamps[]=new dbChamp("Ins_objet_id", "entier", "get_objet_id", "set_objet_id");
	$laListeChamps[]=new dbChamp("Ins_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("Ins_dt_crea", "date_formatee", "get_dt_crea", "set_dt_crea");
	$laListeChamps[]=new dbChamp("Ins_dt_modif", "date_formatee", "get_dt_modif", "set_dt_modif");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_mail() { return($this->mail); }
function get_prenom() { return($this->prenom); }
function get_nom() { return($this->nom); }
function get_recu() { return($this->recu); }
function get_inscrit() { return($this->inscrit); }
function get_dt_recu() { return($this->dt_recu); }
function get_societe() { return($this->societe); }
function get_civilite() { return($this->civilite); }
function get_fonction() { return($this->fonction); }
function get_adresse() { return($this->adresse); }
function get_cp() { return($this->cp); }
function get_ville() { return($this->ville); }
function get_tel() { return($this->tel); }
function get_profil() { return($this->profil); }
function get_objet() { return($this->objet); }
function get_objet_id() { return($this->objet_id); }
function get_cms_site() { return($this->cms_site); }
function get_dt_crea() { return($this->dt_crea); }
function get_dt_modif() { return($this->dt_modif); }


// setters
function set_id($c_ins_id) { return($this->id=$c_ins_id); }
function set_mail($c_ins_mail) { return($this->mail=$c_ins_mail); }
function set_prenom($c_ins_prenom) { return($this->prenom=$c_ins_prenom); }
function set_nom($c_ins_nom) { return($this->nom=$c_ins_nom); }
function set_recu($c_ins_recu) { return($this->recu=$c_ins_recu); }
function set_inscrit($c_ins_inscrit) { return($this->inscrit=$c_ins_inscrit); }
function set_dt_recu($c_ins_dt_recu) { return($this->dt_recu=$c_ins_dt_recu); }
function set_societe($c_ins_societe) { return($this->societe=$c_ins_societe); }
function set_civilite($c_ins_civilite) { return($this->civilite=$c_ins_civilite); }
function set_fonction($c_ins_fonction) { return($this->fonction=$c_ins_fonction); }
function set_adresse($c_ins_adresse) { return($this->adresse=$c_ins_adresse); }
function set_cp($c_ins_cp) { return($this->cp=$c_ins_cp); }
function set_ville($c_ins_ville) { return($this->ville=$c_ins_ville); }
function set_tel($c_ins_tel) { return($this->tel=$c_ins_tel); }
function set_profil($c_ins_profil) { return($this->profil=$c_ins_profil); }
function set_objet($c_ins_objet) { return($this->objet=$c_ins_objet); }
function set_objet_id($c_ins_objet_id) { return($this->objet_id=$c_ins_objet_id); }
function set_cms_site($c_ins_cms_site) { return($this->cms_site=$c_ins_cms_site); }
function set_dt_crea($c_ins_dt_crea) { return($this->dt_crea=$c_ins_dt_crea); }
function set_dt_modif($c_ins_dt_modif) { return($this->dt_modif=$c_ins_dt_modif); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("ins_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("news_inscrit"); }
function getClasse() { return("news_inscrit"); }
function getPrefix() { return(""); }
function getDisplay() { return("mail"); }
function getAbstract() { return("nom"); }



function isInscrit ( $type, $value) {

	switch ($type) {
		case "md5":
			$sql = "select COUNT(*) from news_inscrit where md5(ins_id) = '". $value."'"; 
			break; 
		case "mail":
			$sql = "select COUNT(*) from news_inscrit where ins_mail = '".$value."'"; 
			break;
		 
	}
	
	
	$eCount = dbGetUniqueValueFromRequete($sql);
	
	if ($eCount == 0) {
		// inscrit non trouvé  
		return false;   
	}
	else { 
		$sql = str_replace ("COUNT(*)", "*", $sql); 
		$aIns = dbGetObjectsFromRequete("News_inscrit", $sql);
		$oIns = $aIns[0];
		
		return $oIns;  
	}

}


function unsubscribeInscrit ( $mail  ) {
 
	
	$oIns = $this->isInscrit ( 'mail', $mail);
	
	if ($oIns === false) {

		return false; 
		
		
	} else {  
	 
		  
		$oR = $this->setStatut($oIns, DEF_ID_STATUT_ARCHI);
		return $oR; 
		
	} 
 
} 





function isAsso ($idIns) {

	// $sql = "select COUNT(*) from news_assoinscrittheme where	xit_news_inscrit  = ". $idIns." and xit_news_theme  = 1 "; 
	 $sql = "select COUNT(*) from news_assoinscrittheme where	xit_news_inscrit  = ". $idIns." "; 
	 //echo $sql;
	 $eCount = dbGetUniqueValueFromRequete($sql);
	
	if ($eCount == 0) {
		// inscrit non trouvé  
		return false;   
	}
	else { 
		$sql = str_replace ("COUNT(*)", "*", $sql); 
		$aX = dbGetObjectsFromRequete("news_assoinscrittheme", $sql);
		//$oX = $aX[0]; 
		return $aX;  
	}

}


function setStatut ($oIns, $statut) { 
	$aX = $this->isAsso ($oIns->get_id()); 
	if (sizeof($aX) > 0 && $aX != false) {
		foreach ($aX as $oX) {
			$oX->set_statut($statut);
			$oR = dbUpdate($oX);
		}
	}
	
	return true;
}



function addInscrit ( $aPOST  ) {
	if (isset( $_SESSION["idSite"]) ) {
		$idSite = $_SESSION["idSite"];
	}
	else {
		$idSite = 1;  
	}
 	
	 
	$oIns = $this->isInscrit ( 'mail',$aPOST["mail"]);
	
	if ($oIns === false) {

		$oIns = new News_inscrit (); 
		$oIns->set_nom($aPOST["nom"]);
		$oIns->set_prenom($aPOST["prenom"]); 
		$oIns->set_mail($aPOST["mail"]);  
		$oIns->set_cms_site($idSite); 
		
		$idIns = dbInsertWithAutoKey($oIns);
		
		if ($idIns) {
		
			$oX = new News_assoinscrittheme();
			$oX->set_news_inscrit($idIns);  
			$oX->set_news_theme(1); 
			$oX->set_statut(DEF_ID_STATUT_ATTEN); 
			$oR = dbInsertWithAutoKey($oX);
			
			return $oR; 
		}
		else {
			return false; 
		}
		
		
	} else { 
		
		$idIns = $oIns->get_id();
		
		$oX = $this->isAsso ($idIns);
		
		if ($oX === false) {
			$oX = new News_assoinscrittheme();
			$oX->set_news_inscrit($idIns);  
			$oX->set_news_theme(1); 
			$oX->set_statut(DEF_ID_STATUT_ATTEN); 
			$oR = dbInsertWithAutoKey($oX);
			return $idIns;
		}
		else {
			if ($oX->get_statut() == DEF_ID_STATUT_ATTEN) {
			 
				return $idIns;
				
			}
			else if ($oX->get_statut() == DEF_ID_STATUT_LIGNE) { 
				 
				return -1;
			}
			else if ($oX->get_statut() == DEF_ID_STATUT_ARCHI) { 
			
				$oX->set_statut(DEF_ID_STATUT_ATTEN);
				$oR = dbUpdate($oX);
				return $idIns;
				
			}
		} 
	}
	
 
	 
		 
 
} 


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_inscrit")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_inscrit");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/news_inscrit/list_news_inscrit.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

include('cms-inc/autoClass/list.php');
?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_inscrit/maj_news_inscrit.php", "w");
	$majContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_inscrit/show_news_inscrit.php", "w");
	$showContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_inscrit/rss_news_inscrit.php", "w");
	$rssContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_inscrit/xml_news_inscrit.php", "w");
	$xmlContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_inscrit/xlsx_news_inscrit.php", "w");
	$xmlxlsContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_inscrit/export_news_inscrit.php", "w");
	$exportContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/news_inscrit/import_news_inscrit.php", "w");
	$importContent = "<"."?php include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>
