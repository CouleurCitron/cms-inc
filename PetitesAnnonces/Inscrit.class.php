<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*
	$Id: Inscrit.class.php,v 1.1 2013-09-30 09:30:48 raphael Exp $
	$Author: raphael $

	$Log: Inscrit.class.php,v $
	Revision 1.1  2013-09-30 09:30:48  raphael
	*** empty log message ***

	Revision 1.3  2013-03-22 13:41:50  raphael
	*** empty log message ***

	Revision 1.2  2013-03-01 10:33:59  pierre
	*** empty log message ***

	Revision 1.1  2009-02-16 15:01:39  pierre
	*** empty log message ***

	Revision 1.2  2009-02-16 14:59:58  pierre
	*** empty log message ***

	Revision 1.6  2008/03/04 14:55:54  pierre
	*** empty log message ***
	
	Revision 1.5  2007/06/21 08:51:48  pierre
	*** empty log message ***
	
	Revision 1.4  2007/06/06 14:54:39  pierre
	*** empty log message ***
	
	Revision 1.3  2006/12/19 13:56:04  pierre
	*** empty log message ***
	
	Revision 1.2  2006/12/04 15:39:58  arnaud
	*** empty log message ***
	
	Revision 1.1  2006/04/12 07:26:22  sylvie
	*** empty log message ***
	
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

include_once($_SERVER['DOCUMENT_ROOT']."/include/cms-inc/mail_lib.php");

class Inscript {

	var $id = null;

	var $mail = '';
	var $mdpCrypte = '';
	var $mdpNonCrypte = '';
	var $telephone ='';
	var $prenom = '';
	var $nom = '';
	var $adresse = '';
	var $codePostal = '';
	var $ville = '';
	var $valide = 0;
	var $dateInscription = '';
	var $conndt = ''; // dernière date de connection
	var $rappeldt = ''; // date du rappel

	var $id_site = '';

	var $dbConn = null;


// constructeur
function Inscript($id=null) 
{
	global $db;
	$this->dbConn = &$db;

	if($id!=null) {
		$temp = dbGetObjectFromPK("Inscript", $id);
		foreach ($temp as $key => $val){
			$this->$key = $val;		
		}
		
	} else {

		$this->id = '';

		$this->mail = '';
		$this->mdpCrypte = '';
		$this->telephone = '';
		$this->prenom = '';
		$this->nom = '';
		$this->adresse = '';
		$this->codePostal = '';
		$this->ville = '';
		$this->valide = '';
		$this->dateInscription = '';
		$this->conndt = '';
		$this->rappeldt = '';

		$this->id_site = '';						
	}
}



// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp

	$laListeChamps[]=new dbChamp("inscrit_id", "entier", "getId", "setId");
	$laListeChamps[]=new dbChamp("inscrit_mail", "text", "getMail", "setMail");
	$laListeChamps[]=new dbChamp("inscrit_passwd", "text", "getPasswd", "setInscrit_Passwd");
	$laListeChamps[]=new dbChamp("inscrit_tel", "text", "getTel", "setTel");
	$laListeChamps[]=new dbChamp("inscrit_prenom", "text", "getPrenom", "setPrenom");
	$laListeChamps[]=new dbChamp("inscrit_nom", "text", "getNom", "setNom");
	$laListeChamps[]=new dbChamp("inscrit_adresse", "text", "getAdresse", "setAdresse");
	$laListeChamps[]=new dbChamp("inscrit_cp", "text", "getCp", "setCp");
	$laListeChamps[]=new dbChamp("inscrit_ville", "text", "getVille", "setVille");
	$laListeChamps[]=new dbChamp("inscrit_valid", "entier", "getValid", "setValid");
	$laListeChamps[]=new dbChamp("inscrit_dt", "date_formatee_timestamp_with_zone", "getDt", "setDt");
	$laListeChamps[]=new dbChamp("id_site", "entier", "getId_site", "setId_site");
	$laListeChamps[]=new dbChamp("inscrit_conndt", "date_formatee", "getConndt", "setConndt");
	$laListeChamps[]=new dbChamp("inscrit_rappeldt", "date_formatee", "getRappeldt", "setRappeldt");

	return($laListeChamps);
}

function getId() { return($this->id); }
function getMail() { return($this->mail); }
function getPasswd() { return($this->mdpCrypte); }
function getTel() { return($this->telephone); }
function getPrenom() { return($this->prenom); }
function getNom() { return($this->nom); }
function getAdresse() { return($this->adresse); }
function getCp() { return($this->codePostal); }
function getVille() { return($this->ville); }
function getValid() { return($this->valide); }
function getDt() { return($this->dateInscription); }
function getId_site() { return($this->id_site); }
function getConndt() { return($this->conndt); }
function getRappeldt() { return($this->rappeldt); }


function setId($c_id) { return($this->id=$c_id); }
function setMail($c_mail) { return($this->mail=$c_mail); }
function setInscrit_Passwd($c_passwd) { return($this->mdpCrypte=$c_passwd); }
function setTel($c_tel) { return($this->telephone=$c_tel); }
function setPrenom($c_prenom) { return($this->prenom=$c_prenom); }
function setNom($c_nom) { return($this->nom=$c_nom); }
function setAdresse($c_adresse) { return($this->adresse=$c_adresse); }
function setCp($c_cp) { return($this->codePostal=$c_cp); }
function setVille($c_ville) { return($this->ville=$c_ville); }
function setValid($c_valid) { return($this->valide=$c_valid); }
function setDt($c_dateInscription) { return($this->dateInscription=$c_dateInscription); }
function setId_site($c_id_site) { return($this->id_site=$c_id_site); }
function setConndt($c_conndt) { return($this->conndt=$c_conndt); }
function setRappeldt($c_rappeldt) { return($this->rappeldt=$c_rappeldt); }


// autres getters
function getGetterPK() { return("getId"); }
function getSetterPK() { return("setId"); }
function getFieldPK() { return("inscrit_id"); }
function getTable() { return("pa_inscrits"); }
function getClasse() { return("Inscript"); }






	function authentificate($mail, $passwd) {

		$sql = " SELECT inscrit_id, inscrit_mail, inscrit_passwd, inscrit_tel, inscrit_prenom, inscrit_nom, ";
		$sql.= " inscrit_adresse, inscrit_cp, inscrit_ville, inscrit_valid, inscrit_dt ";
		$sql.= " FROM pa_inscrits ";
		$sql.= " WHERE inscrit_valid=1 ";
		$sql.= " AND inscrit_mail = ".$this->dbConn->qstr($mail);
		$sql.= " AND inscrit_passwd = ".$this->dbConn->qstr(md5($passwd));

//print("<br>$sql");

		$rs = $this->dbConn->Execute($sql);

		if($rs && !$rs->EOF) {
			$this->nom = $rs->fields[5];
			$this->prenom = stripslashes($rs->fields[4]);
			$this->mail = $rs->fields[1];
			$this->mdpCrypte = trim($rs->fields[2]);
			$this->telephone = trim($rs->fields[3]);
			$this->adresse = stripslashes($rs->fields[6]);
			$this->codePostal = $rs->fields[7];
			$this->ville = stripslashes($rs->fields[8]);
			$this->dateInscription = $rs->fields[10];
			$this->id = $rs->fields[0];
			$this->valide = $rs->fields[9];
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
		}
	}

	function initValues($id) {

		$sql = " SELECT inscrit_id, inscrit_mail, inscrit_passwd, inscrit_tel, inscrit_prenom, ";
		$sql.= " inscrit_nom, inscrit_adresse, inscrit_cp, inscrit_ville, inscrit_valid, inscrit_dt ";
		$sql.= " FROM pa_inscrits ";
		$sql.= " WHERE inscrit_id = $id";

		$rs = $this->dbConn->Execute($sql);

		if($rs && !$rs->EOF) {
			$this->nom = stripslashes($rs->fields[5]);
			$this->prenom = stripslashes($rs->fields[4]);
			$this->mail = $rs->fields[1];
			$this->mdpCrypte = trim($rs->fields[2]);
			$this->telephone = trim($rs->fields[3]);
			$this->adresse = stripslashes($rs->fields[6]);
			$this->codePostal = $rs->fields[7];
			$this->ville = stripslashes($rs->fields[8]);
			$this->dateInscription = $rs->fields[10];
			$this->id = $rs->fields[0];
			$this->valide = $rs->fields[9];
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
		}
	}


	function listInscrits() {
		$result = array();
		$sql = " SELECT inscrit_id, inscrit_dt FROM pa_inscrits ORDER BY inscrit_dt desc";
		$rs = $this->dbConn->Execute($sql);
		if($rs) {
			while(!$rs->EOF) {
				$unInscrit = new Inscript($rs->fields[0]);
				array_push($result,$unInscrit);
				$rs->MoveNext();
			}
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
		}
		return $result;
	}

	function pwgen() {
		$passwd = '';
		for($i=0;$i<5;$i++) {
			$c = 0;
			while(!(  (($c>48) && ($c<57)) || (($c>65) && ($c<90)) || (($c>97) && ($c<122)) ))
				$c = rand(48,122);
			$passwd .= chr($c).rand(0,9);
		}
		$this->mdpNonCrypte = $passwd;
		$this->mdpCrypte=md5($this->mdpNonCrypte);
	}

	function setPasswd() {
		$return = true;
		$this->pwgen();
		return $return;
	}

	function notify($action) {
		
		$msg = '';
		$to = $this->mail;

		if($action == 'validate') 
		{
			$from = DEF_VALIDATE_PA_FROM;
			$subject = DEF_VALIDATE_PA_SUJET;
			$subject = str_replace("[SITE]", strtoupper($_SESSION['rep_travail']), $subject);

			$msg = DEF_VALIDATE_PA_MSG;
			$msg = str_replace("[PRENOM]", $this->prenom, $msg);
			$msg = str_replace("[NOM]", $this->nom, $msg);
			$msg = str_replace("[MAIL]", $this->mail, $msg);
			$msg = str_replace("[PWD]", $this->mdpNonCrypte, $msg);

			$this->mdpNonCrypte = '';

		} elseif($action=='unvalidate') {

			$from = DEF_UNVALIDATE_PA_FROM;
			$subject = DEF_UNVALIDATE_PA_SUJET;
			$subject = str_replace("[SITE]", strtoupper($_SESSION['rep_travail']), $subject);

			$msg = DEF_UNVALIDATE_PA_MSG;
			$msg = str_replace("[PRENOM]", $this->prenom, $msg);
			$msg = str_replace("[NOM]", $this->nom, $msg);

		} elseif($action=='delete') {

			$from = DEF_SUPPINSCRIT_PA_FROM;
			$subject = DEF_SUPPINSCRIT_PA_SUJET;
			$subject = str_replace("[SITE]", strtoupper($_SESSION['rep_travail']), $subject);

			$msg = DEF_SUPPINSCRIT_PA_MSG;
			$msg = str_replace("[PRENOM]", $this->prenom, $msg);
			$msg = str_replace("[NOM]", $this->nom, $msg);
		}

		$retour = send_mail($this->id, $to , $subject , $msg , $text, $from);
		
		//print("<br>MAIL=>".$retour);
	}

	function unvalidate() {
		$this->valide = 0;
		$this->notify('unvalidate');
		return $this->update_valide();
	}

	function validate() {
		$this->valide = 1;
		$this->setPasswd();
		$this->notify('validate');
		return $this->update_valide();
	}

	function listeAnnonces($isGarde="") {
		$result = array();

		$sql = " SELECT annonce_id ";
		$sql.= " FROM pa_annonces ";
		$sql.= " WHERE annonce_depot_inscrit_id = ".$this->id;

		if ($isGarde != "") $sql.= " AND annonce_is_garde=".$isGarde;

		$sql.= " ORDER BY annonce_valid, annonce_is_garde, annonce_dt_debut desc, annonce_dt_perim";

		$rs = $this->dbConn->Execute($sql);

		if($rs) {
			while(!$rs->EOF) {
				$uneAnnonce = new Annonce($rs->fields[0]);
				array_push($result, $uneAnnonce);	
				$rs->MoveNext();
			}
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			$result = false;
		}
		return $result;
	}

	function update_rappeldt() {
		if(! (($this->id !=null) && ($this->id>0)) )
			return false;

		$sql = " UPDATE pa_inscrits ";
		if (DEF_BDD != "ORACLE") {
		$sql.= " SET inscrit_rappeldt = str_to_date('".$this->rappeldt."', 'yyyy-mm-dd HH24:MI:SS') ";
		}else{
		$sql.= " SET inscrit_rappeldt = to_date('".$this->rappeldt."', 'yyyy-mm-dd HH24:MI:SS') ";
		}
		$sql.= " WHERE inscrit_id = ".$this->id;

//print("<br>$sql");

		$rs = $this->dbConn->Execute($sql);

		if($rs) {
			return true;
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			return false;
		}
		return false; // sert à rien mais au cas où...
	}

	function update_conndt() {
		if(! (($this->id !=null) && ($this->id>0)) )
			return false;

		$sql = " UPDATE pa_inscrits ";
		if (DEF_BDD != "ORACLE") {
		$sql.= " SET inscrit_conndt = str_to_date('".$this->conndt."', 'yyyy-mm-dd HH24:MI:SS') ";
		}else{
		$sql.= " SET inscrit_conndt = to_date('".$this->conndt."', 'yyyy-mm-dd HH24:MI:SS') ";
		}
		$sql.= " WHERE inscrit_id = ".$this->id;

//print("<br>$sql");

		$rs = $this->dbConn->Execute($sql);

		if($rs) {
			return true;
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			return false;
		}
		return false; // sert à rien mais au cas où...
	}

	function update_pwd() {
		if(! (($this->id !=null) && ($this->id>0)) )
			return false;

		$sql = " UPDATE pa_inscrits ";
		$sql.= " SET inscrit_passwd = '".md5($this->mdpNonCrypte)."' ";
		$sql.= " WHERE inscrit_id = ".$this->id;

//print("<br>$sql");

		$rs = $this->dbConn->Execute($sql);

		if($rs) {
			return true;
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			return false;
		}
		return false; // sert à rien mais au cas où...
	}

	function update_valide() {
		if(! (($this->id !=null) && ($this->id>0)) )
			return false;

		$sql = " UPDATE pa_inscrits ";
		$sql.= " SET inscrit_valid = ".$this->valide.",  ";
		$sql.= " inscrit_passwd = '".$this->mdpCrypte."' ";		
		$sql.= " WHERE inscrit_id = ".$this->id;

//print("<br>$sql");

		$rs = $this->dbConn->Execute($sql);

		if($rs) {
			return true;
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			return false;
		}
		return false; // sert à rien mais au cas où...
	}

	function update() {
		if(! (($this->id !=null) && ($this->id>0)) )
			return false;

		$sql = " UPDATE pa_inscrits SET inscrit_mail = ".$this->dbConn->qstr($this->mail).", ";
		$sql.= " inscrit_passwd = ".$this->dbConn->qstr($this->mdpCrypte).", ";
		$sql.= " inscrit_tel = ".$this->dbConn->qstr($this->telephone).", ";
		$sql.= " inscrit_prenom = ".$this->dbConn->qstr($this->prenom).", ";
		$sql.= " inscrit_nom = ".$this->dbConn->qstr($this->nom).", ";
		$sql.= " inscrit_adresse = ".$this->dbConn->qstr($this->adresse).", ";
		$sql.= " inscrit_cp = ".$this->dbConn->qstr($this->codePostal).", ";
		$sql.= " inscrit_ville = ".$this->dbConn->qstr($this->ville).", ";
		$sql.= " inscrit_valid = ".$this->valide.", ";
		$sql.= " inscrit_dt = ".$this->dbConn->qstr($this->dateInscription)." ";
		$sql.= " WHERE inscrit_id = ".$this->id;

//print("<br>$sql");

		$rs = $this->dbConn->Execute($sql);

		if($rs) {
			return true;
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			return false;
		}
		return false; // sert à rien mais au cas où...
	}

	function delete() {
		if(! (($this->id !=null) && ($this->id>0)) )
			return false;
		$sql = "delete from pa_inscrits where inscrit_id = ".$this->id;
		$rs = $this->dbConn->Execute($sql);
		if($rs) {
			return true;
			$this->notify('delete');
		} else {
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($this->dbConn->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);
			return false;
		}
		return false; // sert à rien mais au cas où...
	}
}

	// envoi d'un message de rappel pour les inscrits des petites annonces
	function send_rappel($oInscrit) {

		$from = DEF_EXPIRE_FROM;
		$to = $oInscrit->getMail();
		$subject = DEF_EXPIRE_SUJET;
		$msg = DEF_EXPIRE_MSG;

		$html = str_replace("[ID]", $oInscrit->getId(), $html);
		$html = str_replace("[MAIL]", $oInscrit->getMail(), $html);
		$html = str_replace("[NOM]", $oInscrit->getNom(), $html);
		$html = str_replace("[PRENOM]", $oInscrit->getPrenom(), $html);

//print("<br>".$msg);
		// enregistrer l'information mail envoyé
		// pour ne supprimer que ceux qui n'ont pas répondu alors qu'ils ont reçu le mail
		// date rappel
		$oInscrit->setRappeldt(date('Y-m-d H:i:s'));
		$oInscrit->update_rappeldt();

		// envoie de mail
		send_mail($oInscrit->getId(), $to , $subject , $html , $text, $from);
	}


// date limite autorisée de non réponse au rappel au module des petites annonces
function getDateLimiteRappel_Ymd()
{
	// la date limite de délai de connection autorisée est fonction de DEF_EXPIRE_INSCRIPTION
	// DEF_EXPIRE_INSCRIPTION est une valeur en nombre de jours saisie dans le fichier de config
	$dDate = date( "Y/m/d", mktime(0, 0, 0, date("m"), date("d") - DEF_RAPPEL_INSCRIPTION, date("Y")) ) ;
	return($dDate);
}

// date limite autorisée de non utilisation au module des petites annonces
function getDateLimiteInscription_Ymd()
{
	// la date limite de délai de connection autorisée est fonction de DEF_EXPIRE_INSCRIPTION
	// DEF_EXPIRE_INSCRIPTION est une valeur en nombre de jours saisie dans le fichier de config
	$dDate = date( "Y/m/d", mktime(0, 0, 0, date("m"), date("d") - DEF_EXPIRE_INSCRIPTION, date("Y")) ) ;
	return($dDate);
}

// date limite autorisée de non utilisation au module des petites annonces
function getDateLimiteInscription_dmY()
{
	// la date limite de délai de connection autorisée est fonction de DEF_EXPIRE_INSCRIPTION
	// DEF_EXPIRE_INSCRIPTION est une valeur en nombre de jours saisie dans le fichier de config
	$dDate = date( "d/m/Y", mktime(0, 0, 0, date("m"), date("d") - DEF_EXPIRE_INSCRIPTION, date("Y")) ) ;
	return($dDate);
}

// désinscription automatique au module des petites annonces
// si cet inscrit a déjà reçu un mail il y a 15 j -> le supprimer
// sinon -> lui envoyer un rappel
function desinscription_pa_auto()
{
	global $db;

	$dExpiration = getDateLimiteInscription_Ymd();
	$dRappel = getDateLimiteRappel_Ymd();

//------------------------------------------------------------------
// 1. SUPPRESSION DES INSCRITS DONT LA DATE DE RAPPEL EST PASSEE

	$sql = " SELECT inscrit_id FROM pa_inscrits ";
	if (DEF_BDD != "ORACLE") {
	$sql.= " WHERE (inscrit_conndt < str_to_date('".$dExpiration."', 'yyyy/mm/dd'))";
	$sql.= " AND (inscrit_rappeldt < str_to_date('".$dRappel."', 'yyyy/mm/dd'))";
	}else{
	$sql.= " WHERE (inscrit_conndt < to_date('".$dExpiration."', 'yyyy/mm/dd'))";
	$sql.= " AND (inscrit_rappeldt < to_date('".$dRappel."', 'yyyy/mm/dd'))";
	}
	$sql.= " AND id_site = ".$_SESSION['idSite_travail'];

//print("<br>$sql");

	$rs = $db->Execute($sql);

	$result = array();

	if($rs) {
		while(!$rs->EOF) {
			$result[] = $rs->fields[0];

			// suppression de l'inscrit
			delete_inscrit($rs->fields[0]);
			$rs->MoveNext();
		}
	} else {
		error_log($_SERVER['PHP_SELF']);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log($_SERVER['PHP_SELF']);
		$result = false;
	}
//------------------------------------------------------------------
//print("<br>".newSizeOf($result)." inscrits supprimés");

//------------------------------------------------------------------
// 2. ENVOIE DE RAPPEL AUX DES INSCRITS DONT LA DATE DE CONNEXION EST PASSEE

	$sql = " SELECT inscrit_id FROM pa_inscrits ";
	if (DEF_BDD != "ORACLE") {
	$sql.= " WHERE (inscrit_conndt < str_to_date('".$dExpiration."', 'yyyy/mm/dd'))";
	}else{
	$sql.= " WHERE (inscrit_conndt < to_date('".$dExpiration."', 'yyyy/mm/dd'))";
	}
	$sql.= " AND id_site = ".$_SESSION['idSite_travail'];

//print("<br>$sql");

	$rs = $db->Execute($sql);

	$result = array();

	if($rs) {
		while(!$rs->EOF) {
			$result[] = $rs->fields[0];

			$oInscrit = new Inscript($rs->fields[0]);
			send_rappel($oInscrit);

			$rs->MoveNext();
		}
	} else {
		error_log($_SERVER['PHP_SELF']);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log($_SERVER['PHP_SELF']);
		$result = false;
	}
//------------------------------------------------------------------
//print("<br>".newSizeOf($result)." inscrits rappelés");


	return $result;
}

// suprpession d'un inscrit et de ses annonces
function delete_inscrit($id)
{
	// suppression de l'inscrit
	$bRetour = dbDeleteId("pa_inscrits", "inscrit_id", $id);
	// suppression de ses annonces
	$sql = " DELETE FROM pa_annonces WHERE annonce_depot_inscrit_id=".$id;
	dbExecuteQuery($sql);

	return($bRetour);
}

?>
