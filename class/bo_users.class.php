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
function __construct($id=null)
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
		
		
		// fetch login matching user
		$sql = " SELECT * ";
		$sql.= " FROM bo_users";
		$sql.= " WHERE user_valid=1";
		$sql.= " AND user_login = ".$this->dbConn->qstr($sLogin);
		if (DEF_BDD != "ORACLE") $sql.= ";";
		
		$rs = $this->dbConn->Execute($sql);
		if($rs && !$rs->EOF) {
			// 1st attempt, test hashed crypted pwd			
			if (password_verify ($sPasswd,trim($rs->fields[n('user_passwd')]))){
				error_log("- AUTH OK - BCRYPT ---------------------");
				$bAuth = true;
			}
			// 2nd attempt, test md5 pwd
			elseif (preg_match('/^[a-f0-9]{32}$/i', trim($rs->fields[n('user_passwd')]))	&&  (md5($sPasswd)==trim($rs->fields[n('user_passwd')]))){
				error_log("- AUTH OK - MD5 ---------------------");
				$bAuth=true;
				$this->upgradePasswordEncryption($sLogin, $sPasswd);				
			}
			// 3rd attempt, test encrypted md5
			elseif (password_verify (md5($sPasswd),trim($rs->fields[n('user_passwd')]))){
				error_log("- AUTH OK - BCRYPT OVER MD5 --------------------");
				$bAuth = true;
				$this->upgradePasswordEncryption($sLogin, $sPasswd);
			}
			else{
				$bAuth = false;
			}
			
			
			// if successful auth
			if ($bAuth){			
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
			}
		}
		else {
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log("bo_users.class.php > authentificate");
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			error_log("----------------------");
		}
	}
	
	function upgradePasswordEncryption($sLogin, $sPasswd){
		global $db;
		$this->dbConn = &$db;
		
		// update and crypt pwd if needed
		$sPasswd = password_hash($sPasswd, PASSWORD_DEFAULT);
		//error_log("- HASHED PWD - ".$sPasswd. " --------------------");	
		
		// update DB					
		$sql = 'UPDATE bo_users SET user_passwd = "'.$sPasswd.'" WHERE user_login = '.$this->dbConn->qstr($sLogin);
		if (DEF_BDD != "ORACLE") $sql.= ";";
		$rsUpdt = $this->dbConn->Execute($sql);
		if($rsUpdt && !$rsUpdt->EOF) {
			//error_log("- UPDATE OK---------------------");	
			return true;
		}
		else{
			//error_log("- UPDATE FAILED ---------------------");	
			return false;
		}
		
		
	}
	
	function initValues($id) {
		$sql = " SELECT * ";
		$sql.= " FROM bo_users";
		$sql.= " WHERE user_id = $id";
		if (DEF_BDD != "ORACLE") $sql.= ";";
				
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
			error_log("bo_users.class.php > initValues");
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			error_log("----------------------");
		}
	}

	function getUser($sLogin, $idSite) {
		$sql = " SELECT * ";
		$sql.= " FROM bo_users";
		$sql.= " WHERE user_login = '$sLogin' AND user_cms_site=$idSite";
		if (DEF_BDD != "ORACLE") $sql.= ";";

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
			error_log("bo_users.class.php > getUser($sLogin, $idSite)");
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			error_log("----------------------");
		}
	}

	//cherche tous les users d'un site

	function listUsers($idSite="") {

		$result = array();
		
		$sql = " SELECT user_id, user_dt";
		$sql.= " FROM bo_users";
		if ($idSite != "" && $idSite != -1) $sql.= " WHERE bo_users.user_cms_site=".$idSite;
		$sql.= " ORDER BY user_dt DESC";

		if (DEF_BDD != "ORACLE") $sql.= ";";

//print("<br>$sql");
		
		$rs = $this->dbConn->Execute($sql);
		if($rs) {
			while(!$rs->EOF) {
				$unInscrit = new bo_users($rs->fields[n('user_id')]);
				array_push($result, $unInscrit);
				$rs->MoveNext();
			}
		} else {
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log("bo_users.class.php > listUsers($idSite))");
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			error_log("----------------------");
		}
		return $result;
	}

	//cherche tous les users d'un site + les administrateurs quelque soit le site

	function listUsersPlusAdmin($idSite) {

		$result = array();
		
		$sql = " SELECT user_id, user_dt";
		$sql.= " FROM bo_users";
		$sql.= " WHERE bo_users.user_cms_site=".$idSite." OR user_rank='".DEF_ADMIN."'";
		$sql.= " ORDER BY user_rank, user_dt DESC";

		if (DEF_BDD != "ORACLE") $sql.= ";";

//print("<br>$sql");
		
		$rs = $this->dbConn->Execute($sql);
		if($rs) {
			while(!$rs->EOF) {
				$unInscrit = new bo_users($rs->fields[n('user_id')]);
				array_push($result, $unInscrit);
				$rs->MoveNext();
			}
		} else {
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log("bo_users.class.php > listUsers($idSite))");
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			error_log("----------------------");
		}
		return $result;
	}

	function pwgen() {
		$passwd = '';
		for($i=0;$i<8;$i++) {
			$c = 0;
			while(!(  (($c>48) && ($c<57)) || (($c>65) && ($c<90)) || (($c>97) && ($c<122)) ))
				$c = rand(48,122);
			$passwd .= chr($c);
		}
		$this->mdpNonCrypte = $passwd;
	}

	function setPasswd() {
		$return = false;
		if(!(strlen($this->mdpCrypte)>0)) {
			$this->mdpCrypte = password_hash($this->mdpNonCrypte, PASSWORD_DEFAULT);
			$return = true;
		}
		return $return;
	}

	function notify($action) {
		$msg = '';
		$from = 'contact@couleur-citron.com';
		$to = $this->mail;
		$subject = '[Couleur Citron] Adequat : validation compte administrateur';
		if($action=='validate') {
			$msg = 'Bonjour '.$this->prenom.' '.$this->nom.',
Nous avons bien reçu votre demande d\'inscription et l\'avons validée.

';
			if(strlen($this->mdpNonCrypte)>0){
				$msg .= 'Vos codes d\'accès : 

identifiant : '.$this->mail.'
mot de passe : '.$this->mdpNonCrypte;
				$this->mdpNonCrypte = '';
			}
			$msg.= '

Cordialement,

le webmaster.';
		} elseif($action=='unvalidate') {
			$msg = 'Bonjour '.$this->prenom.' '.$this->nom.',

Nous avons le regret de vous informer que votre compte a été désactivé par nos services.

Cordialement,

le webmaster';
		} elseif($action=='delete') {
			$msg = 'Bonjour '.$this->prenom.' '.$this->nom.',
Nous avons le regret de vous informer que votre compte a été supprimé de nos serveurs. Cette suppression est définitive.

Cordialement,

le webmaster';
		}
		multiPartMail($to , $subject , '' , $msg, $from, null, 'text/plain', 'localhost');
	}

	function unvalidate() {
		$this->valide = 0;
		return $this->update();
	}

	function validate() {
		$this->valide = 1;
		$this->setPasswd();
		return $this->update();
	}


	function update($bPasswd=0) {
		if(! (($this->id !=null) && ($this->id>0)) )
			return false;

		$sql = " UPDATE bo_users";
		$sql.= " SET user_login = ".$this->dbConn->qstr($this->login).",";
		$sql.= " user_mail = ".$this->dbConn->qstr($this->mail).",";

		// par défaut pas de modification du mot de passe
		if ($bPasswd) $sql.="user_passwd = ".$this->dbConn->qstr($this->mdpCrypte).",";

		$sql.= " user_tel = ".$this->dbConn->qstr($this->telephone).",";
		$sql.= " user_prenom = ".$this->dbConn->qstr($this->prenom).",";
		$sql.= " user_nom = ".$this->dbConn->qstr($this->nom).",";
		$sql.= " user_valid = ".$this->valide.",";
		$sql.= " user_rank = ".$this->dbConn->qstr($this->rank).",";
		$sql.= " user_cms_site = ".$this->cms_id.", ";
		$sql.= " user_bo_groupes = ".$this->groupe." ";
		$sql.= " WHERE user_id = ".$this->id;

		if (DEF_BDD != "ORACLE") $sql.= ";";

//				user_dt = ".$this->dbConn->qstr($this->dateCreation).",

		$rs = $this->dbConn->Execute($sql);
		if($rs) {
			return true;
		} else {
			echo('Erreur interne de programme');
			if(DEF_MODE_DEBUG==true) {
				echo "<br />bo_users.class.php > update($bPasswd)";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log("bo_users.class.php > update($bPasswd))");
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			error_log("----------------------");

			return false;
		}
		return false; // sert à rien mais au cas où...
	}


	function updateValidation() {
		if(! (($this->id !=null) && ($this->id>0)) )
			return false;

		$sql = " UPDATE bo_users";
		$sql.= " SET user_validauto = ".$this->validauto." ";
		$sql.= " WHERE user_id = ".$this->id;

		if (DEF_BDD != "ORACLE") $sql.= ";";

		$rs = $this->dbConn->Execute($sql);
		if($rs) {
			return true;
		} else {
			echo('Erreur interne de programme');
			if(DEF_MODE_DEBUG==true) {
				echo "<br />bo_users.class.php > updateValidation";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log("bo_users.class.php > updateValidation)");
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			error_log("----------------------");

			return false;
		}
		return false; // sert à rien mais au cas où...
	}

	function updatePrefsite() {
		if(! (($this->id !=null) && ($this->id>0)) )
			return false;

		global $db;

		$sql = " UPDATE bo_users";
		$sql.= " SET user_prefsite = ".$this->prefsite." ";
		$sql.= " WHERE user_id = ".$this->id;

		if (DEF_BDD != "ORACLE") $sql.= ";";

		$rs = $db->Execute($sql);
		if($rs) {
			return true;
		} else {
			echo('Erreur interne de programme');
			if(DEF_MODE_DEBUG==true) {
				echo "<br />bo_users.class.php > updatePrefsite";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log("bo_users.class.php > updatePrefsite)");
			error_log($sql);
			error_log($db->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			error_log("----------------------");

			return false;
		}
		$rs->Close();
		return false; // sert à rien mais au cas où...
	}


	function delete() {
		if(! (($this->id !=null) && ($this->id>0)) )
			return false;
		$sql = " DELETE FROM bo_users WHERE user_id = ".$this->id;
		if (DEF_BDD != "ORACLE") $sql.= ";";

		$rs = $this->dbConn->Execute($sql);
		if($rs) {
			return true;
			$this->notify('delete');
		} else {

			echo('Erreur interne de programme');
			if(DEF_MODE_DEBUG==true) {
				echo "<br />bo_users.class.php > delete";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log("bo_users.class.php > delete)");
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			error_log("----------------------");
		
			return false;
		}
		$rs->Close();
		return false; // sert à rien mais au cas où...
	}

	function add() {

		$eId = getNextVal("bo_users", "user_id");

// a_voir sponthus valid=0 par défaut

		$sql = " INSERT INTO bo_users (user_id, user_login, user_mail, ";
		$sql.= " user_passwd, user_tel, ";
		$sql.= " user_prenom, user_nom, user_valid, user_dt, user_rank, user_cms_site, user_validauto, user_bo_groupes, user_prefsite)";
		$sql.= " VALUES (".$eId.", ".$this->dbConn->qstr($this->login).", ".$this->dbConn->qstr($this->mail).",";
		$sql.= " ".$this->dbConn->qstr($this->mdpCrypte).", ".$this->dbConn->qstr($this->telephone).", ";
		$sql.= " ".$this->dbConn->qstr($this->prenom).", ".$this->dbConn->qstr($this->nom).", ".$this->valide.", ";

		if (DEF_BDD == "POSTGRES") $sql.= " cast(now() as timestamp), ";
		else if (DEF_BDD == "MYSQL") {

			$dNow = getDateNow();
			$sDate = to_dbdate($dNow);
			$sql.= $sDate." , ";
		}
		else if (DEF_BDD == "ORACLE") $sql.=" cast(sysdate as timestamp), ";

		$sql.= " ".$this->dbConn->qstr($this->rank).", ".$this->cms_id.", ".$this->validauto.", ".$this->groupe.", ".$this->prefsite.")";
		if (DEF_BDD != "ORACLE") $sql.= " ;";

//print("<br>$sql");

		$rs = $this->dbConn->Execute($sql);
		if($rs) {
			return true;
		} else {

			echo('Erreur interne de programme');
			if(DEF_MODE_DEBUG==true) {
				echo "<br />bo_users.class.php > add";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log("bo_users.class.php > add)");
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			error_log("----------------------");
		
			return false;
		}
		$rs->Close();
		return false; // sert à rien mais au cas où...
	
	}
}


	// cherche tous les users d'un site
	// plus les administrateurs

	function listUsersWidthAdmin($idSite) {

		global $db;
		$result = array();
		
		$sql = " SELECT user_id, user_dt";
		$sql.= " FROM bo_users";
		$sql.= " WHERE user_cms_site=".$idSite." OR user_rank='".DEF_ADMIN."'";
		$sql.= " ORDER BY user_rank";

		if (DEF_BDD != "ORACLE") $sql.= ";";
//print("<br>$sql");
		
		$rs = $db->Execute($sql);
		if($rs) {
			while(!$rs->EOF) {
				$unInscrit = new bo_users($rs->fields[n('user_id')]);
				array_push($result, $unInscrit);
				$rs->MoveNext();
			}
		} else {
		
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log("bo_users.class.php > listUsersWidthAdmin($idSite))");
			error_log($sql);
			error_log($db->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			error_log("----------------------");
		
		}
		$rs->Close();
		return $result;
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
?>