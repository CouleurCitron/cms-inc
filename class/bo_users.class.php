<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* [Begin patch] */
/* [End patch] */
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/bo_users.class.php')  && (strpos(__FILE__,'/include/bo/class/bo_users.class.php')===FALSE) ){
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/bo/class/bo_users.class.php');
}else{
/*======================================

objet de BDD bo_users :: class bo_users

SQL mySQL:

DROP TABLE IF EXISTS bo_users;
CREATE TABLE bo_users
(
	user_id			int (11) PRIMARY KEY not null,
	user_login			varchar (255),
	user_mail			varchar (255),
	user_passwd			varchar (255),
	user_tel			varchar (14),
	user_prenom			varchar (50),
	user_nom			varchar (50),
	user_valid			int (2),
	user_dt			date,
	user_rank			int (11) not null,
	user_cms_site			int (11) not null,
	user_validauto			int (2),
	user_prefsite			int (11) not null,
	user_bo_groupes			int (11) not null
)

SQL Oracle:

DROP TABLE bo_users
CREATE TABLE bo_users
(
	user_id			number (11) constraint user_pk PRIMARY KEY not null,
	user_login			varchar2 (255),
	user_mail			varchar2 (255),
	user_passwd			varchar2 (255),
	user_tel			varchar2 (14),
	user_prenom			varchar2 (50),
	user_nom			varchar2 (50),
	user_valid			number (2),
	user_dt			date,
	user_rank			number (11) not null,
	user_cms_site			number (11) not null,
	user_validauto			number (2),
	user_prefsite			number (11) not null,
	user_bo_groupes			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="bo_users" libelle="Users de back-office" prefix="user" display="login" abstract="nom">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false"/>
<item name="login" libelle="Login" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="mail" libelle="E-mail" type="varchar" length="255" list="true" order="true" nohtml="true" />
<item name="passwd" libelle="Mot de passe" type="varchar" length="255" nohtml="true" option="password"/>
<item name="tel" libelle="Téléphone" type="varchar" length="14" nohtml="true" />
<item name="prenom" libelle="Prénom" type="varchar" list="true" order="true" length="50" nohtml="true" />
<item name="nom" libelle="Nom" type="varchar" list="true" order="true" length="50" nohtml="true" />
<item name="valid" libelle="Validé" type="int" length="2" list="true" order="true" option="bool" />
<item name="dt" libelle="Date" type="date" list="true" order="true" />
<item name="rank" libelle="Rang" type="int" length="11" notnull="true"  nohtml="true" list="true" order="true" fkey="bo_rank" />
<item name="cms_site" libelle="Site" type="int" length="11" notnull="true"  list="true" fkey="cms_site" />
<item name="validauto" libelle="Auto-validation" type="int"  length="2" list="true" order="true" option="bool" />
<item name="prefsite" libelle="Langue préférée" type="int"  length="11" default="1" notnull="true"  list="true" fkey="cms_langue" />
<item name="bo_groupes" libelle="Groupe" type="int"  length="11" notnull="true"  list="true" fkey="bo_groupes" />
<langpack lang="fr">
<norecords>Pas d'user à afficher</norecords>
</langpack>
</class>


==========================================*/

class bo_users
{
var $inherited_list = array();
var $inherited = array();
var $id;
var $login;
var $mail;
var $passwd;
var $tel;
var $prenom;
var $nom;
var $valid;
var $dt;
var $rank;
var $cms_site;
var $validauto;
var $prefsite;
var $bo_groupes;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"bo_users\" libelle=\"Users de back-office\" prefix=\"user\" display=\"login\" abstract=\"nom\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\"/>
<item name=\"login\" libelle=\"Login\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"mail\" libelle=\"E-mail\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\" nohtml=\"true\" />
<item name=\"passwd\" libelle=\"Mot de passe\" type=\"varchar\" length=\"255\" nohtml=\"true\" option=\"password\"/>
<item name=\"tel\" libelle=\"Téléphone\" type=\"varchar\" length=\"14\" nohtml=\"true\" />
<item name=\"prenom\" libelle=\"Prénom\" type=\"varchar\" list=\"true\" order=\"true\" length=\"50\" nohtml=\"true\" />
<item name=\"nom\" libelle=\"Nom\" type=\"varchar\" list=\"true\" order=\"true\" length=\"50\" nohtml=\"true\" />
<item name=\"valid\" libelle=\"Validé\" type=\"int\" length=\"2\" list=\"true\" order=\"true\" option=\"bool\" />
<item name=\"dt\" libelle=\"Date\" type=\"date\" list=\"true\" order=\"true\" />
<item name=\"rank\" libelle=\"Rang\" type=\"int\" length=\"11\" notnull=\"true\"  nohtml=\"true\" list=\"true\" order=\"true\" fkey=\"bo_rank\" />
<item name=\"cms_site\" libelle=\"Site\" type=\"int\" length=\"11\" notnull=\"true\"  list=\"true\" fkey=\"cms_site\" />
<item name=\"validauto\" libelle=\"Auto-validation\" type=\"int\"  length=\"2\" list=\"true\" order=\"true\" option=\"bool\" />
<item name=\"prefsite\" libelle=\"Langue préférée\" type=\"int\"  length=\"11\" default=\"1\" notnull=\"true\"  list=\"true\" fkey=\"cms_langue\" />
<item name=\"bo_groupes\" libelle=\"Groupe\" type=\"int\"  length=\"11\" notnull=\"true\"  list=\"true\" fkey=\"bo_groupes\" />
<langpack lang=\"fr\">
<norecords>Pas d'user à afficher</norecords>
</langpack>
</class>";

var $XML_inherited = null;

var $sMySql = "CREATE TABLE bo_users
(
	user_id			int (11) PRIMARY KEY not null,
	user_login			varchar (255),
	user_mail			varchar (255),
	user_passwd			varchar (255),
	user_tel			varchar (14),
	user_prenom			varchar (50),
	user_nom			varchar (50),
	user_valid			int (2),
	user_dt			date,
	user_rank			int (11) not null,
	user_cms_site			int (11) not null,
	user_validauto			int (2),
	user_prefsite			int (11) not null,
	user_bo_groupes			int (11) not null
)

";

// constructeur
function bo_users($id=null)
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
		$this->login = "";
		$this->mail = "";
		$this->passwd = "";
		$this->tel = "";
		$this->prenom = "";
		$this->nom = "";
		$this->valid = 1;
		$this->dt = date("d/m/Y");
		$this->rank = -1;
		$this->cms_site = -1;
		$this->validauto = -1;
		$this->prefsite = 1;
		$this->bo_groupes = -1;
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
	$laListeChamps[]=new dbChamp("User_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("User_login", "text", "get_login", "set_login");
	$laListeChamps[]=new dbChamp("User_mail", "text", "get_mail", "set_mail");
	$laListeChamps[]=new dbChamp("User_passwd", "text", "get_passwd", "set_passwd");
	$laListeChamps[]=new dbChamp("User_tel", "text", "get_tel", "set_tel");
	$laListeChamps[]=new dbChamp("User_prenom", "text", "get_prenom", "set_prenom");
	$laListeChamps[]=new dbChamp("User_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("User_valid", "entier", "get_valid", "set_valid");
	$laListeChamps[]=new dbChamp("User_dt", "date_formatee", "get_dt", "set_dt");
	$laListeChamps[]=new dbChamp("User_rank", "entier", "get_rank", "set_rank");
	$laListeChamps[]=new dbChamp("User_cms_site", "entier", "get_cms_site", "set_cms_site");
	$laListeChamps[]=new dbChamp("User_validauto", "entier", "get_validauto", "set_validauto");
	$laListeChamps[]=new dbChamp("User_prefsite", "entier", "get_prefsite", "set_prefsite");
	$laListeChamps[]=new dbChamp("User_bo_groupes", "entier", "get_bo_groupes", "set_bo_groupes");
	return($laListeChamps);
}

//old getters
function getUser_id() { return($this->id); }
function getUser_rank() { return($this->rank); }
function getValidauto() { return($this->validauto); }
function getUser_login() { return($this->login); }
function getUser_mail() { return($this->mail); } 
function getUser_tel() { return($this->tel); } 
function getUser_prenom() { return($this->prenom); } 
function getUser_nom() { return($this->nom); } 
function getUser_valid() { return($this->valid); } 
function getUser_dt() { return($this->dt); } 
function getcms_id() { return($this->cms_site); } 
function getPrefsite() { return($this->prefsite); } 	
function getGroupe() { return($this->bo_groupes); } 	

//old setters
function setUser_id($c_id) { return($this->id=$c_id); } 
function setUser_login($c_login) { return($this->login=$c_login); } 
function setUser_mail($c_mail) { return($this->mail=$c_mail); } 
function setUser_tel($c_telephone) { return($this->tel=$c_telephone); } 
function setUser_prenom($c_prenom) { return($this->prenom=$c_prenom); } 
function setUser_nom($c_nom) { return($this->nom=$c_nom); } 
function setUser_valid($c_valid) { return($this->valid=$c_valid); } 
function setUser_dt($c_dt) { return($this->dt=$c_dt); } 
function setUser_rank($c_rank) { return($this->rank=$c_rank); } 
function setcms_id($c_cms_id) { return($this->cms_site=$c_cms_id); } 
function setValidauto($c_validauto) { return($this->validauto=$c_validauto); } 
function setPrefsite($c_prefsite) { return($this->prefsite=$c_prefsite); } 
function setGroupe($c_groupe) { return($this->bo_groupes=$c_groupe); } 


// getters
function get_id() { return($this->id); }
function get_login() { return($this->login); }
function get_mail() { return($this->mail); }
function get_passwd() { return($this->passwd); }
function get_tel() { return($this->tel); }
function get_prenom() { return($this->prenom); }
function get_nom() { return($this->nom); }
function get_valid() { return($this->valid); }
function get_dt() { return($this->dt); }
function get_rank() { return($this->rank); }
function get_cms_site() { return($this->cms_site); }
function get_validauto() { return($this->validauto); }
function get_prefsite() { return($this->prefsite); }
function get_bo_groupes() { return($this->bo_groupes); }


// setters
function set_id($c_user_id) { return($this->id=$c_user_id); }
function set_login($c_user_login) { return($this->login=$c_user_login); }
function set_mail($c_user_mail) { return($this->mail=$c_user_mail); }
function set_passwd($c_user_passwd) { return($this->passwd=$c_user_passwd); }
function set_tel($c_user_tel) { return($this->tel=$c_user_tel); }
function set_prenom($c_user_prenom) { return($this->prenom=$c_user_prenom); }
function set_nom($c_user_nom) { return($this->nom=$c_user_nom); }
function set_valid($c_user_valid) { return($this->valid=$c_user_valid); }
function set_dt($c_user_dt) { return($this->dt=$c_user_dt); }
function set_rank($c_user_rank) { return($this->rank=$c_user_rank); }
function set_cms_site($c_user_cms_site) { return($this->cms_site=$c_user_cms_site); }
function set_validauto($c_user_validauto) { return($this->validauto=$c_user_validauto); }
function set_prefsite($c_user_prefsite) { return($this->prefsite=$c_user_prefsite); }
function set_bo_groupes($c_user_bo_groupes) { return($this->bo_groupes=$c_user_bo_groupes); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("user_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("bo_users"); }
function getClasse() { return("bo_users"); }
function getPrefix() { return(""); }
function getDisplay() { return("login"); }
function getAbstract() { return("nom"); }

	function authentificate($sLogin, $sPasswd) {
		global $db;
		$this->dbConn = &$db;
	
		$sql = " SELECT * ";
		$sql.= " FROM bo_users";
		$sql.= " WHERE user_valid=1";
		$sql.= " AND user_login = ".$this->dbConn->qstr($sLogin);
		$sql.= " AND user_passwd = ".$this->dbConn->qstr(md5($sPasswd));
		if (DEF_BDD != "ORACLE") $sql.= ";";
//print("<br>$sql");

		$rs = $this->dbConn->Execute($sql);
		if($rs && !$rs->EOF) {
			$this->nom = $rs->fields[n('user_nom')];
			$this->prenom = $rs->fields[n('user_prenom')];
			$this->mail = $rs->fields[n('user_mail')];
			$this->mdpCrypte = trim($rs->fields[n('user_passwd')]);
			$this->telephone = trim($rs->fields[n('user_tel')]);
			$this->dateCreation = $rs->fields[n('user_dt')];
			$this->id = $rs->fields[n('user_id')];
			$this->valide = $rs->fields[n('user_valid')];
			$this->rank = $rs->fields[n('user_rank')];
			$this->cms_id = $rs->fields[n('user_cms_site')];
			$this->login = $rs->fields[n('user_login')];
			$this->validauto = $rs->fields[n('user_validauto')];			
			$this->prefsite = $rs->fields[n('user_prefsite')];
			$this->groupe = $rs->fields[n('user_bo_groupes')];			
		
		} else {
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log("user.class.php > authentificate");
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			error_log("----------------------");
		}
	}
	
} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_users")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_users");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_users/list_bo_users.php", "w");
	$listContent = "<"."?php

\$"."script = explode('/',\$"."_SERVER['PHP_SELF']);
\$"."script = \$"."script[sizeof(\$"."script)-1];

if (is_file(\$"."_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\$"."script))
	require_once('include/bo/cms/prepend.'.\$"."script);

	include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_users/maj_bo_users.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_users/show_bo_users.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_users/rss_bo_users.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_users/xml_bo_users.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/bo_users/xlsx_bo_users.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xlsx.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_users/export_bo_users.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/bo_users/import_bo_users.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
}
?>