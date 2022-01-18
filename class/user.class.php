<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*
	$Id: user.class.php,v 1.17 2013-06-04 15:11:30 pierre Exp $
	$Author: pierre $

	$Log: user.class.php,v $
	Revision 1.17  2013-06-04 15:11:30  pierre
	*** empty log message ***

	Revision 1.16  2013-03-01 10:34:04  pierre
	*** empty log message ***

	Revision 1.15  2012-07-31 14:24:47  pierre
	*** empty log message ***

	Revision 1.14  2010-01-08 11:30:22  pierre
	*** empty log message ***

	Revision 1.13  2009-09-16 10:16:43  pierre
	*** empty log message ***

	Revision 1.12  2008-10-21 09:20:47  pierre
	*** empty log message ***

	Revision 1.10  2008/05/05 14:21:33  pierre
	*** empty log message ***
	
	Revision 1.9  2008/04/15 14:25:37  pierre
	*** empty log message ***
	
	Revision 1.8  2007/11/29 17:03:57  pierre
	*** empty log message ***
	
	Revision 1.7  2007/09/17 09:18:55  pierre
	*** empty log message ***
	
	Revision 1.6  2007/08/30 10:03:49  thao
	*** empty log message ***
	
	Revision 1.5  2007/08/29 07:51:17  pierre
	*** empty log message ***
	
	Revision 1.4  2007/08/28 16:34:53  pierre
	*** empty log message ***
	
	Revision 1.3  2007/08/08 13:53:33  thao
	*** empty log message ***
	
	Revision 1.2  2007/08/08 13:42:54  thao
	*** empty log message ***
	
	Revision 1.1  2007/08/08 13:11:43  thao
	*** empty log message ***
	
	Revision 1.1.1.1  2006/01/25 15:14:27  pierre
	projet CCitron AWS 2006 Nouveau Website
	
	Revision 1.5  2005/12/19 13:16:36  sylvie
	*** empty log message ***
	
	Revision 1.4  2005/11/09 13:48:31  sylvie
	*** empty log message ***
	
	Revision 1.2  2005/10/27 13:35:38  sylvie
	*** empty log message ***
	
	Revision 1.1.1.1  2005/10/24 13:37:05  pierre
	re import fusion espace v2 et ADW v2
	
	Revision 1.2  2005/10/21 07:09:04  sylvie
	*** empty log message ***
	
	Revision 1.1.1.1  2005/10/20 13:10:54  pierre
	Espace V2
	
	Revision 1.1.1.1  2005/04/18 13:53:29  pierre
	again
	
	Revision 1.1.1.1  2005/04/18 09:04:21  pierre
	oremip new
	
	Revision 1.1.1.1  2004/11/03 13:49:54  ddinside
	lancement du projet - import de adequat
	
	Revision 1.1  2004/06/16 15:25:19  ddinside
	fin synchro
	
	Revision 1.3  2004/06/02 13:39:08  ddinside
	commit version finale garde partagee, reste petites annonces à faire
	
	Revision 1.2  2004/06/01 14:25:19  ddinside
	ajout petites annonce dont gardes finies
	newslettrer
	
	Revision 1.1  2004/05/18 15:20:31  ddinside
	classe gestino des inscrits
	
	Revision 1.1  2004/05/18 13:47:12  ddinside
	creation module petites annonces
	
*/

/*

	// sponthus 10/06/05
	// ajout du rang (ADMIN, GEST, REDACT)
	// ajout du site de l'utilisateur (un seul par utilisateur !, tous pour l'admin)

	// sponthus 20/06/2005
	// fonction de recherche d'un user avec même email

	// sponthus 20/06/2005
	// ajout du champ login

	// sponthus 29/06/2005
	// ajout des getters et setters

	// sponthus 26/08/2005
	// ajout de pref USER_PREFSITE

*/

/*
function User($id=null)
function authentificate($login, $passwd)
function initValues($id)
function getUser($email, $idSite) 
function listUsers($idSite="")
function pwgen()
function setPasswd()
function notify($action)
function unvalidate()
function validate()
function update()
function delete()
function add()

function listUsersWidthAdmin($idSite) {

*/

/*

CREATE TABLE bo_users (
  user_id int(11) PRIMARY KEY NOT NULL default '0',
  user_login varchar(50) NOT NULL default '',
  user_mail varchar(250) NOT NULL default '',
  user_passwd varchar(250) NOT NULL default '',
  user_tel varchar(14) NOT NULL default '',
  user_prenom varchar(50) NOT NULL default '',
  user_nom varchar(50) NOT NULL default '',
  user_valid int(1) NOT NULL default '0',
  user_dt date NOT NULL default '0000-00-00',
  user_rank varchar(10) NOT NULL default '',
  cms_id int(5) NOT NULL default '0',
  user_validauto int(1) NOT NULL default '0',
  user_prefsite int(1) NOT NULL default '0', 
  user_grp_id int(11) NOT NULL default '-1'
);

*/

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mail_lib.php');

class User {

	var $id = null;
	var $login = "";
	var $mail = "";
	var $mdpCrypte = '';
	var $mdpNonCrypte = '';
	var $telephone ='';
	var $prenom = '';	
	var $nom = "";
	var $valide = 0;
	var $dateCreation = '';
	var $rank='';
	var $cms_id;
	var $validauto=0;
	var $prefsite = 1; // par défaut le site principal => id = 1
	var $groupe = -1;
	
	var $dbConn = null;
	
	var $sMySql = "CREATE TABLE bo_users (
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

	
	function User($id=null) { // constructeur
		global $db;
		$this->dbConn = &$db;
		
		if (istable("bo_users") == false){
			dbExecuteQuery($this->sMySql);
		}
		
		if($id!=null) {
			$this->initValues($id);
		}
	}

	// getters
	function getUser_id() { return($this->id); } 
	function getUser_login() { return($this->login); }
	function getUser_mail() { return($this->mail); } 
	function getUser_tel() { return($this->telephone); } 
	function getUser_prenom() { return($this->prenom); } 
	function getUser_nom() { return($this->nom); } 
	function getUser_valid() { return($this->valid); } 
	function getUser_dt() { return($this->dt); } 
	function getUser_rank() { return($this->rank); } 
	function getcms_id() { return($this->cms_id); } 
	function getValidauto() { return($this->validauto); } 	
	function getPrefsite() { return($this->prefsite); } 	
	function getGroupe() { return($this->groupe); } 	
	function get_bo_groupes() { return($this->groupe); }

	// setters
	function setUser_id($c_id) { return($this->id=$c_id); } 
	function setUser_login($c_login) { return($this->login=$c_login); } 
	function setUser_mail($c_mail) { return($this->mail=$c_mail); } 
	function setUser_tel($c_telephone) { return($this->telephone=$c_telephone); } 
	function setUser_prenom($c_prenom) { return($this->prenom=$c_prenom); } 
	function setUser_nom($c_nom) { return($this->nom=$c_nom); } 
	function setUser_valid($c_valid) { return($this->valid=$c_valid); } 
	function setUser_dt($c_dt) { return($this->dt=$c_dt); } 
	function setUser_rank($c_rank) { return($this->rank=$c_rank); } 
	function setcms_id($c_cms_id) { return($this->cms_id=$c_cms_id); } 
	function setValidauto($c_validauto) { return($this->validauto=$c_validauto); } 
	function setPrefsite($c_prefsite) { return($this->prefsite=$c_prefsite); } 
	function setGroupe($c_groupe) { return($this->groupe=$c_groupe); } 

	// autres getters
	function getGetterPK() { return("getUser_id"); }
	function getSetterPK() { return("setUser_id"); }
	function getFieldPK() { return("user_id"); }
	function getTable() { return("bo_users"); }
	function getClasse() { return("User"); }


	function authentificate($sLogin, $sPasswd) {
		$sql = " SELECT * ";
		$sql.= " FROM bo_users";
		$sql.= " WHERE user_valid=1";
		$sql.= " AND user_login = ".$this->dbConn->qstr(trim($sLogin));
		$sql.= " AND user_passwd = ".$this->dbConn->qstr(md5(trim($sPasswd)));
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
			error_log("user.class.php > initValues");
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
			error_log("user.class.php > getUser($sLogin, $idSite)");
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
				$unInscrit = new User($rs->fields[n('user_id')]);
				array_push($result, $unInscrit);
				$rs->MoveNext();
			}
		} else {
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log("user.class.php > listUsers($idSite))");
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
				$unInscrit = new User($rs->fields[n('user_id')]);
				array_push($result, $unInscrit);
				$rs->MoveNext();
			}
		} else {
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log("user.class.php > listUsers($idSite))");
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
			//$this->pwgen();
			$this->mdpCrypte = md5($this->mdpNonCrypte);
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
				echo "<br />user.class.php > update($bPasswd)";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log("user.class.php > update($bPasswd))");
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
				echo "<br />user.class.php > updateValidation";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log("user.class.php > updateValidation)");
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
				echo "<br />user.class.php > updatePrefsite";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log("user.class.php > updatePrefsite)");
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
				echo "<br />user.class.php > delete";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log("user.class.php > delete)");
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
				echo "<br />user.class.php > add";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log("user.class.php > add)");
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
				$unInscrit = new User($rs->fields[n('user_id')]);
				array_push($result, $unInscrit);
				$rs->MoveNext();
			}
		} else {
		
			error_log("----------------------");
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log("user.class.php > listUsersWidthAdmin($idSite))");
			error_log($sql);
			error_log($db->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			error_log("----------------------");
		
		}
		$rs->Close();
		return $result;
	}


?>
