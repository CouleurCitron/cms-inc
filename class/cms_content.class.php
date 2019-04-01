<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

sponthus 01/06/05
objet de BDD cms_content :: class Cms_content

function Cms_content($id=null) 
function getListeChamps()
getters, setters, ...
function initValues($id) 
function cms_content_insert()
function cms_content_update() {
function cms_content_update_contenu() {
function updateStatut() {
function updateNom() {
function updateNoeud($idNode) {
function updateHTML() {
function cms_content_delete() {
function realDelete() {
function cms_content_move() {

// fin class

function getContentPurge() 
function getContentFromPage($idPage, $ifZonedit=0) 
function getContentForUser($idUser, $sTri="") 
function getFolderContents($idSite, $nodeId) {
function updateNomBriqueEdit($idContent, $idPage) {
function getListContentEdit($aRecherche, $sOrderBy="", $eLimit)
function getCountListContentEdit($aRecherche, $sOrderBy="")
function crea_brique_defo($oZonedit) { 
function crea_tout_brique_defo($oZonedit, $oPage) { 
function updateDimensions($oContent) {
==========================================*/

class Cms_content
{

var $id_content;
var $name_content;
var $type_content;
var $width_content;
var $height_content;
var $dateadd_content;
var $dateupd_content;
var $datedlt_content;
var $valid_content;
var $actif_content;
var $html_content;
var $nodeid_content;
var $obj_table_content;
var $obj_id_content;
var $id_site;
var $iszonedit_content;
var $statut_content;
var $isbriquedit_content;

// constructeur
function __construct($id=null) 
{
	global $db;
	if($id!=null) {
		$this->initValues($id);
	} else {
		$this->id_content=-1;
		$this->name_content='';
		$this->type_content='';
		$this->width_content=0;
		$this->height_content=0;
		$this->dateadd_content=date('Y-m-d H:i:s');
		$this->dateupd_content=date('Y-m-d H:i:s');
		$this->datedlt_content='0000-00-00 00:00:00';
		$this->valid_content=-1;
		$this->actif_content=-1;
		$this->html_content='';
		$this->nodeid_content=-1;
		$this->obj_table_content="";
		$this->obj_id_content=-1;
		$this->id_site=-1;
		$this->iszonedit_content=0;
		$this->statut_content = DEF_CODE_STATUT_DEFAUT;
		$this->isbriquedit_content=0;
	}
}

// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp

	$laListeChamps[]=new dbChamp("id_content", "entier", "getId_content", "setId_content");
	$laListeChamps[]=new dbChamp("name_content", "text", "getName_content", "setName_content");
	$laListeChamps[]=new dbChamp("type_content", "text", "getType_content", "setType_content");
	$laListeChamps[]=new dbChamp("width_content", "entier", "getWidth_content", "setWidth_content");
	$laListeChamps[]=new dbChamp("height_content", "entier", "getHeight_content", "setHeight_content");
	$laListeChamps[]=new dbChamp("dateadd_content", "date", "getDateadd_content", "setDateadd_content");
	$laListeChamps[]=new dbChamp("dateupd_content", "date", "getDateupd_content", "setDateupd_content");
	$laListeChamps[]=new dbChamp("datedlt_content", "date", "getDatedlt_content", "setDatedlt_content");
	$laListeChamps[]=new dbChamp("valid_content", "entier", "getValid_content", "setValid_content");
	$laListeChamps[]=new dbChamp("actif_content", "entier", "getActif_content", "setActif_content");
	$laListeChamps[]=new dbChamp("html_content", "text", "getHtml_content", "setHtml_content");
	$laListeChamps[]=new dbChamp("nodeid_content", "entier", "getNodeid_content", "setNodeid_content");
	$laListeChamps[]=new dbChamp("obj_table_content", "text", "getObj_table_content", "setObj_table_content");
	$laListeChamps[]=new dbChamp("obj_id_content", "entier", "getObj_id_content", "setObj_id_content");
	$laListeChamps[]=new dbChamp("id_site", "entier", "getId_site", "setId_site");
	$laListeChamps[]=new dbChamp("iszonedit_content", "entier", "getIszonedit_content", "setIszonedit_content");
	$laListeChamps[]=new dbChamp("statut_content", "entier", "getStatut_content", "setStatut_content");
	$laListeChamps[]=new dbChamp("isbriquedit_content", "entier", "getIsbriquedit_content", "setIsbriquedit_content");

	return($laListeChamps);
}

// getters
function getId_content() { return($this->id_content); } 
function getName_content() { return($this->name_content); } 
function getType_content() { return($this->type_content); } 
function getWidth_content() { return($this->width_content); } 
function getHeight_content() { return($this->height_content); } 
function getDateadd_content() { return($this->dateadd_content); } 
function getDateupd_content() { return($this->dateupd_content); } 
function getDatedlt_content() { return($this->datedlt_content); } 
function getValid_content() { return($this->actif_content); } 
function getActif_content() { return($this->actif_content); } 
function getHtml_content() { return($this->html_content); } 
function getNodeid_content() { return($this->nodeid_content); } 
function getObj_table_content() { return($this->obj_table_content); } 
function getObj_id_content() { return($this->obj_id_content); } 
function getId_site() { return($this->id_site); }
function getIszonedit_content() { return($this->iszonedit_content); }
function getStatut_content() { return($this->statut_content); }
function getIsbriquedit_content() { return($this->isbriquedit_content); }
function get_isbriquedit_content() { return($this->isbriquedit_content); }

function get_id() { return($this->id_content); } 

// setters
function setId_content($c_id_content) { return($this->id_content=$c_id_content); } 
function setName_content($c_name_content) { return($this->name_content=$c_name_content); } 
function setType_content($c_type_content) { return($this->type_content=$c_type_content); } 
function setWidth_content($c_width_content) { return($this->width_content=$c_width_content); } 
function setHeight_content($c_height_content) { return($this->height_content=$c_height_content); } 
function setDateadd_content($c_dateadd_content) { return($this->dateadd_content=$c_dateadd_content); } 
function setDateupd_content($c_dateupd_content) { return($this->dateupd_content=$c_dateupd_content); } 
function setDatedlt_content($c_datedlt_content) { return($this->datedlt_content=$c_datedlt_content); } 
function setValid_content($c_valid_content) { return($this->valid_content=$c_valid_content); } 
function setActif_content($c_actif_content) { return($this->actif_content=$c_actif_content); } 
function setHtml_content($c_html_content) { return($this->html_content=$c_html_content); } 
function setNodeid_content($c_nodeid_content) { return($this->nodeid_content=$c_nodeid_content); }
function set_nodeid_content($c_nodeid_content) { return($this->nodeid_content=$c_nodeid_content); }
function setObj_table_content($c_obj_table_content) { return($this->obj_table_content=$c_obj_table_content); } 
function setObj_id_content($c_obj_id_content) { return($this->obj_id_content=$c_obj_id_content); } 
function setId_site($c_id_site) { return($this->id_site=$c_id_site); }
function setIszonedit_content($c_iszonedit_content) { return($this->iszonedit_content=$c_iszonedit_content); }
function setStatut_content($c_statut_content) { return($this->statut_content=$c_statut_content); }
function setIsbriquedit_content($c_isbriquedit_content) { return($this->isbriquedit_content=$c_isbriquedit_content); }

function set_id($c_id_content) { return($this->id_content=$c_id_content); } 


// autres getters
function getGetterPK() { return("getId_content"); }
function getSetterPK() { return("setId_content"); }
function getFieldPK() { return("id_content"); }
function getTable() { return("cms_content"); }
function getClasse() { return("Cms_content"); }

// statut
function getGetterStatut() {return("getStatut_content"); }
function getFieldStatut() {return("statut_content"); }
//

function getDisplay() { return("name_content"); }
function getAbstract() { return("type_content"); }


//-------------------------
// a_voir sponthus
// a réfléchir
// pour des problèmes d'optimisation de requete, 
// il faudrait constituer un objet d'affichage extrait de cet objet
// en effet ce qui est lourd est le fait de retourner tout l'objet dans les listes
// pour l'instant juste un getter mais je suppose qu'il faudrait faire des getListeChamps d'affichage
function getFieldAffichage() { return("name_content"); }
//-------------------------


//----------------------------------------
// initialisation
// obtention d'un  objet cms_content
//----------------------------------------

function initValues($id) 
{
		global $db;
	  $result = true;

		$sql = " SELECT id_content, name_content, type_content, ";
		$sql.= " width_content, height_content, dateadd_content, dateupd_content, datedlt_content, ";
		$sql.= " valid_content, actif_content, html_content, ";
		$sql.= " nodeid_content, obj_table_content, obj_id_content, id_site, ";
		$sql.= " iszonedit_content, statut_content, isbriquedit_content";
		$sql.= " FROM cms_content";
		$sql.= " WHERE id_content = $id";

		if (DEF_BDD != "ORACLE") $sql.= ";";

		$rs = $db->Execute($sql);

		if($rs && !$rs->EOF) {

			$this->id_content = $rs->fields[n('id_content')];
			$this->name_content = $rs->fields[n('name_content')];			
			$this->type_content = $rs->fields[n('type_content')];
			$this->width_content = $rs->fields[n('width_content')];
			$this->height_content = $rs->fields[n('height_content')];
			$this->dateadd_content = $rs->fields[n('dateadd_content')];
			$this->dateupd_content = $rs->fields[n('dateupd_content')];
			$this->datedlt_content = $rs->fields[n('datedlt_content')];
			$this->valid_content = $rs->fields[n('valid_content')];
			$this->actif_content = $rs->fields[n('actif_content')];
			$this->html_content = $rs->fields[n('html_content')];
			$this->nodeid_content = $rs->fields[n('nodeid_content')];
			$this->obj_table_content = $rs->fields[n('obj_table_content')];
			$this->obj_id_content = $rs->fields[n('obj_id_content')];
			$this->id_site = $rs->fields[n('id_site')];
			$this->iszonedit_content = $rs->fields[n('iszonedit_content')];
			$this->statut_content = $rs->fields[n('statut_content')];
			$this->isbriquedit_content = $rs->fields[n('isbriquedit_content')];
			
			if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
			
		} else {
			echo "<br />Erreur de fonctionnement interne";
			if(DEF_MODE_DEBUG==true) {
				echo "<br />cms_content.class.php > initValues";
				echo "<br /><strong>$sql</strong>";
			}
			error_log($_SERVER['PHP_SELF']);
			error_log('erreur ou résultat vide lors de l\'execution de la requete');
			error_log($sql);
			error_log($db->ErrorMsg());
			error_log($_SERVER['PHP_SELF']);

   		  $result = false;
		}
		$rs->Close();
	    return $result;
	}


//----------------------------------------
// INSERT
//----------------------------------------

function cms_content_insert()
{
	global $db;
    $result = null;
	
	// ATTENTION
	// name_content, type_content et html_content doivent être envoyés avec to_char (pas de cote)
	// date RE-mises SID
	
    $sql = " INSERT INTO cms_content (";
	$sql.= " id_content, name_content, type_content,";
	$sql.= " width_content, height_content,";
	$sql.= " dateadd_content, dateupd_content, datedlt_content,";
	$sql.= " valid_content, actif_content, nodeid_content,";
	$sql.= " obj_table_content, obj_id_content, id_site, ";
	$sql.= " iszonedit_content, statut_content, isbriquedit_content)";
	$sql.= " VALUES(";
	$sql.= " ".$this->id_content.", ".$db->qstr($this->name_content).", ".$db->qstr($this->type_content).",";
	$sql.= " ".$this->width_content.", ".$this->height_content.", ";
	$sql.= " '".$this->dateadd_content."', '".$this->dateupd_content."', '".$this->datedlt_content."',";
	$sql.= " ".$this->valid_content.", ".$this->actif_content.", ".$this->nodeid_content.",";
	$sql.= " ".$db->qstr($this->obj_table_content).", ".$this->obj_id_content.", ".$this->id_site.", ";
	$sql.= " ".$this->iszonedit_content.", ".$this->statut_content.", ".$this->isbriquedit_content.")";

	$rs = $db->Execute($sql);

    if($rs != false) {
      $result = $this->id_content;
	  
			if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
	  
    } else {
			echo "<br />Erreur de fonctionnement interne";
			if(DEF_MODE_DEBUG==true) {
				echo "cms_content.class.php > cms_content_insert";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($db->ErrorMsg());
      $result = false;
    }

	if (DEF_BDD == "ORACLE") {
		// spécifique ORACLE
		// le champ CLOB est envoyé après
		// car s'il est trop gros > 4000, la requete plante en ORA-01704
		// a_voir sponthus : ajouter un test sur la longueur de la chaine, sinon passer en INSERT
		$stmt = OCIParse($db->_connectionID, "UPDATE cms_content SET html_content=:gros_champ_clob WHERE id_content=".$this->id_content);
		OCIBindByName($stmt, ":gros_champ_clob", $this->html_content, -1);
		OCIExecute($stmt); 
	}
	elseif (DEF_BDD == "POSTGRES")  {
		$sql= "UPDATE cms_content SET html_content=".$db->qstr($this->html_content)." WHERE id_content=".$this->id_content;
		$rs = $db->Execute($sql);
	}
	elseif (DEF_BDD == "MYSQL")  {
		$sql= "UPDATE cms_content SET html_content=".$db->qstr($this->html_content)." WHERE id_content=".$this->id_content.";";
		$rs = $db->Execute($sql);
	}
	$rs->Close();
    return $result;
  }


//----------------------------------------
// UPDATE
//----------------------------------------

function cms_content_update() {

	global $db;

	if(! (($this->id_content !=null) && ($this->id_content>0)) )
		$result = false;
		
	$sql = " UPDATE cms_content";
	$sql.= " SET name_content = ".$db->qstr($this->name_content).",";
	$sql.= " type_content = ".$db->qstr($this->type_content).",";
	$sql.= " width_content = ".$this->width_content.",";
	$sql.= " height_content = ".$this->height_content.",";
	$sql.= " valid_content = ".$this->valid_content.",";
	$sql.= " actif_content = ".$this->actif_content.",";
	$sql.= " nodeid_content = ".$this->nodeid_content.",";
	
	$sql.= " obj_table_content = ".$db->qstr($this->obj_table_content).",";
	$sql.= " obj_id_content = ".$this->obj_id_content.",";
	
	$sql.= " dateupd_content = ".$db->qstr(date('Y-m-d H:i:s')).",";
	$sql.= " statut_content = ".$this->statut_content."";		
	$sql.= " WHERE id_content = ".$this->id_content;

	$rs = $db->Execute($sql);
		
	if($rs) {

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

		$result = $this->id_content;
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "cms_content.class.php > cms_content_update";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());

		$result = false;
	}

	if (DEF_BDD != "ORACLE") {

		$sql = " UPDATE cms_content ";
		$sql.= " SET html_content=".$db->qstr($this->html_content)." ";
		$sql.= " WHERE id_content=".$this->id_content;

		$rs = $db->Execute($sql);
	}
	else {
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: UPDATE GROS_CHAMP_CLOB");
		// spécifique ORACLE
		// le champ CLOB est envoyé après
		// car s'il est trop gros > 4000, la requete plante en ORA-01704
		// a_voir sponthus : ajouter un test sur la longueur de la chaine, sinon passer en INSERT
		$sql = "UPDATE cms_content SET html_content=:gros_champ_clob WHERE id_content=".$this->id_content;
		$stmt = OCIParse($db->_connectionID, $sql);
		//if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		OCIBindByName($stmt, ":gros_champ_clob", $this->html_content, -1);
		OCIExecute($stmt); 
	}
	$rs->Close();
	return $result;
}


//----------------------------------------
// UPDATE
// maj contenu + nom + largeur + hauteur
//----------------------------------------

function cms_content_update_contenu() {

	global $db;

	if(! (($this->id_content !=null) && ($this->id_content>0)) )
		return false;
		
	$sql = " UPDATE cms_content";
	$sql.= " SET name_content = ".$db->qstr($this->name_content).", ";
	$sql.= " width_content = ".$this->width_content.", ";
	$sql.= " height_content = ".$this->height_content.", ";
	$sql.= " dateupd_content = ".$this->dateupd_content;
	$sql.= " WHERE id_content = ".$this->id_content;

	$rs = $db->Execute($sql);
		
	if($rs) {

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

		$result = $this->id_content;
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "cms_content.class.php > cms_content_update_contenu";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());

		$result = false;
	}

	if (DEF_BDD != "ORACLE") {
		$sql = "UPDATE cms_content SET html_content=".$db->qstr($this->html_content)." WHERE id_content=".$this->id_content;
		$rs = $db->Execute($sql);
	}
	else {
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: UPDATE GROS_CHAMP_CLOB");
		// spécifique ORACLE
		// le champ CLOB est envoyé après
		// car s'il est trop gros > 4000, la requete plante en ORA-01704
		// a_voir sponthus : ajouter un test sur la longueur de la chaine, sinon passer en INSERT
		$sql = "UPDATE cms_content SET html_content=:gros_champ_clob WHERE id_content=".$this->id_content;
		$stmt = OCIParse($db->_connectionID, $sql);
		//if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		OCIBindByName($stmt, ":gros_champ_clob", $this->html_content, -1);
		OCIExecute($stmt); 
	}
	$rs->Close();
	return $result;
}


//----------------------------------------
// UPDATE statut
//----------------------------------------

function updateStatut() {

	global $db;

	if(! (($this->id_content !=null) && ($this->id_content>0)) ) return false;
			
	$sql = " UPDATE cms_content";
	$sql.= " SET statut_content = ".$this->statut_content."";		
	$sql.= " WHERE id_content = ".$this->id_content;

	$rs = $db->Execute($sql);
		
	if($rs) {

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

		$result = $this->id_content;
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "cms_content.class.php > updateStatut";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		$result = false;
	}
	$rs->Close();
	return $result;
}


//----------------------------------------
// UPDATE nom
//----------------------------------------

function updateNom() {

	global $db;

	if(! (($this->id_content !=null) && ($this->id_content>0)) ) return false;
		
	$sql = " UPDATE cms_content";
	$sql.= " SET name_content = '".to_dbquote($this->name_content)."'";
	$sql.= " WHERE id_content = ".$this->id_content;

	$rs = $db->Execute($sql);
	
	if($rs) {

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

		$result = $this->id_content;
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "cms_content.class.php > updateNom";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		$result = false;
	}
	$rs->Close();
	return $result;
}




//----------------------------------------
// maj du noeud d'une brique
// utilisé pour :
// 1- confirmer la création d'une zone éditable (noeud -2 => noeud -1)
// 2- confirmer la création brique éditable (noeud -2 => noeud page)
//----------------------------------------

function updateNoeud($idNode) {

	global $db;

	if(! (($this->id_content !=null) && ($this->id_content>0)) ) return false;
			
	$sql = " UPDATE cms_content";
	$sql.= " SET nodeid_content = ".$idNode;
	$sql.= " WHERE id_content = ".$this->id_content;

	$rs = $db->Execute($sql);
		
	if($rs) {

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

		$result = $this->id_content;
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "cms_content.class.php > updateNoeud";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());

		$result = false;
	}
	$rs->Close();
	return $result;
}


//----------------------------------------
// maj du contenu HTML d'une brique
//----------------------------------------

function updateHTML() {

	global $db;

	if(! (($this->id_content !=null) && ($this->id_content>0)) )
		return false;

	if (DEF_BDD != "ORACLE") {

		$sql = " UPDATE cms_content ";
		$sql.= " SET html_content = '".to_dbquote($this->html_content)."' ";
		$sql.= " WHERE id_content=".$this->id_content;

		$rs = $db->Execute($sql);
	}
	else {
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: UPDATE GROS_CHAMP_CLOB");
		// spécifique ORACLE
		// le champ CLOB est envoyé après
		// car s'il est trop gros > 4000, la requete plante en ORA-01704
		// a_voir sponthus : ajouter un test sur la longueur de la chaine, sinon passer en INSERT
		$sql = "UPDATE cms_content SET html_content=:gros_champ_clob WHERE id_content=".$this->id_content;
		$stmt = OCIParse($db->_connectionID, $sql);
		//if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		OCIBindByName($stmt, ":gros_champ_clob", $this->html_content, -1);
		$rs = OCIExecute($stmt); 
	}

	if($rs) {

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		$result = $this->id_content;

	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "cms_content.class.php > updateHTML";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());

		$result = false;
	}
	$rs->Close();
	return $result;
}


//----------------------------------------
// DELETE
//----------------------------------------

function cms_content_delete() {

	global $db;

	if(! (($this->id_content !=null) && ($this->id_content>0)) ) return false;

	$sql = " UPDATE cms_content";
	$sql.= " SET actif_content = 0";
	$sql.= " WHERE id_content=".$this->id_content;

//print("<br>$sql");

	$rs = $db->Execute($sql);
		
	if($rs) {

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

		$result = true;
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "cms_content.class.php > cms_content_delete";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());

		$result = false;
	}
	$rs->Close();
	return $result;
}


//----------------------------------------
// DELETE : suppression en BDD (et non update actif_content=0)
//----------------------------------------

function realDelete() {

	global $db;

	if(! (($this->id_content !=null) && ($this->id_content>0)) )  return false;

	$sql = " DELETE FROM cms_content";
	$sql.= " WHERE id_content=".$this->id_content;

//print("<br>$sql");

	$rs = $db->Execute($sql);
		
	if($rs) {

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

		$result = true;
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "cms_content.class.php > realDelete";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());

		$result = false;
	}
	$rs->Close();
	return $result;
}


//----------------------------------------
// MOVE
//----------------------------------------

function cms_content_move() {

	global $db;

	if(! (($this->id_content !=null) && ($this->id_content>0)) ) return false;

	$sql = " UPDATE cms_content";
	$sql.= " SET nodeid_content = ".$this->nodeid_content;
	$sql.= " WHERE id_content=".$this->id_content;

//print("<br>$sql");

	$rs = $db->Execute($sql);
		
	if($rs) {

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

		$result = true;
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "cms_content.class.php > cms_content_move";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());

		$result = false;
	}
	$rs->Close();
	return $result;
}

} //class 

// FONCTIONS HORS CLASSE /////////////////


//----------------------------------------
// sélection des objets cms_content à purger
// dateadd_content < date du jour ET nodeid_content=-2
//----------------------------------------

function getContentPurge() 
{
	global $db;
	$result = array();

	$sql = " SELECT id_content, name_content, type_content,";
	$sql.= " width_content, height_content,";
	$sql.= " dateadd_content, dateupd_content, datedlt_content,";
	$sql.= " valid_content, actif_content, html_content, nodeid_content,";
	$sql.= " obj_table_content, obj_id_content, id_site, ";
	$sql.= " iszonedit_content, statut_content, isbriquedit_content";
	$sql.= " FROM cms_content";
	$sql.= " WHERE nodeid_content = -2 ";

	if (DEF_BDD != "ORACLE") $sql.= " AND str_to_date('".getDateNow()."', '%d/%m/%Y') > dateadd_content";
	else $sql.= " AND to_char(sysdate, 'yyyymmdd') > to_char(dateadd_content, 'yyyymmdd')";

	if (DEF_BDD != "ORACLE") $sql.= ";";

//print("<br><font color=black>$sql</font>");

	$rs = $db->Execute($sql);

	if($rs) {
		while(!$rs->EOF) {

			$oContent = new Cms_content();
			$oContent->initValues($rs->fields[n('id_content')]);
			array_push($result, $oContent);
			
			$rs->MoveNext();
		}

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_content.class.php > getContentPurge";
			echo "<br /><strong>$sql</strong>";
		}
		error_log($_SERVER['PHP_SELF']);
		error_log('erreur ou résultat vide lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log($_SERVER['PHP_SELF']);
	}
	$rs->Close();
	return $result;
}

//----------------------------------------
// sélection des briques d'une page
// en option, sélection des zones éditables
//----------------------------------------

function getContentFromPage($idPage, $ifZonedit=0) 
{
	global $db;
	$result = array();

	$sql = " SELECT c.id_content, c.name_content, c.type_content,";
	$sql.= " c.width_content, c.height_content,";
	$sql.= " c.dateadd_content, c.dateupd_content, c.datedlt_content,";
	$sql.= " c.valid_content, c.actif_content, c.html_content, c.nodeid_content,";
	$sql.= " c.obj_table_content, c.obj_id_content, c.id_site, ";
	$sql.= " c.iszonedit_content, c.statut_content, c.isbriquedit_content";
	$sql.= " FROM cms_content c, cms_struct_page s";
	$sql.= " WHERE c.id_content = s.id_content ";
	$sql.= " AND s.id_page = ".$idPage;
	if ($ifZonedit) $sql.= " AND c.iszonedit_content=1";
	
	if (DEF_BDD != "ORACLE") $sql.= ";";
			
	$rs = $db->Execute($sql);
	if($rs) {
		while(!$rs->EOF) {

			$oContent = new Cms_content($rs->fields[n('id_content')]);

			array_push($result, $oContent);
			
			$rs->MoveNext();
		}
		
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_content.class.php > getContentFromPage";
			echo "<br /><strong>$sql</strong>";
		}
		error_log($_SERVER['PHP_SELF']);
		error_log('erreur ou résultat vide lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log($_SERVER['PHP_SELF']);

	  $result = false;
	}
	$rs->Close();
	return $result;
}


//----------------------------------------
// sélection des briques (contenus) modifiables par un user
//----------------------------------------

function getContentForUser($idUser, $sTri="") 
{
	global $db;
	$result = array();

	$sql = " SELECT cms_content.id_content, cms_content.name_content, cms_content.type_content,";
	$sql.= " cms_content.width_content, cms_content.height_content, ";
	$sql.= " cms_content.dateadd_content, cms_content.dateupd_content, cms_content.datedlt_content,";
	$sql.= " cms_content.valid_content, cms_content.actif_content, cms_content.html_content, cms_content.nodeid_content,";
	$sql.= " cms_content.obj_table_content, cms_content.obj_id_content, cms_content.id_site, ";
	$sql.= " cms_content.iszonedit_content, cms_content.statut_content, cms_content.isbriquedit_content";
	$sql.= " FROM cms_content, cms_droit";
	$sql.= " WHERE cms_content.id_content = cms_droit.id_content";
	$sql.= " AND cms_droit.user_id = ".$idUser;

	if ($sTri != "") $sql.= " ORDER BY ".$sTri;

	if (DEF_BDD != "ORACLE") $sql.= ";";
			
	$rs = $db->Execute($sql);
	if($rs) {
		while(!$rs->EOF) {

			$oContent = new Cms_content($rs->fields[n('id_content')]);

			array_push($result, $oContent);
			
			$rs->MoveNext();
		}

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_content.class.php > getContentForUser";
			echo "<br /><strong>$sql</strong>";
		}
		error_log($_SERVER['PHP_SELF']);
		error_log('erreur ou résultat vide lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log($_SERVER['PHP_SELF']);

	  $result = false;
	}
	$rs->Close();
	return $result;
}


//----------------------------------------
// on ne renvoi pas les zones éditables et les briques éditables
//----------------------------------------

function getFolderContents($idSite, $nodeId) {

	if(strlen($nodeId)>0)
			$nodeId=array_pop(explode(',',$nodeId));
	else
			return false;
	global $db;
	$return = array();

	$sql = " SELECT id_content ";
	$sql.= " FROM cms_content ";
	$sql.= " WHERE nodeid_content=$nodeId ";
	$sql.= " AND valid_content=1 ";
	$sql.= " AND actif_content=1 ";
	$sql.= " AND id_site=$idSite ";
	$sql.= " AND isbriquedit_content <> 1 ";
	$sql.= " AND iszonedit_content <> 1 ";
	$sql.= " ORDER BY type_content, name_content";

	if (DEF_BDD != "ORACLE") $sql.= ";";

	$rs = $db->Execute($sql);
	if($rs) {
			if(!$rs->EOF) {
					while (!$rs->EOF) {

						$oContent = new Cms_content($rs->fields[n('id_content')]);
		
						array_push($return, $oContent);

						$rs->MoveNext();
					}
			} else {
					$return=false;
			}
	} else {
			echo "<br />Erreur de fonctionnement interne";
			if(DEF_MODE_DEBUG==true) {
				echo "<br />cms_content.class.php > getFolderContent";
				print("<br><strong>$sql</strong>");
			}			
			error_log("Plantage lors de l'execution de la requete\n $sql");
			error_log($db->ErrorMsg());
			$return = false;
	}
	return $return;
	$rs->Close();
}

// on ne renvoi que les zones éditables et les briques éditables
function getFolderEditContents($idSite, $nodeId) {

	if(strlen($nodeId)>0)
			$nodeId=array_pop(explode(',',$nodeId));
	else
			return false;
	global $db;
	$return = array();

	$sql = "SELECT id_content
	FROM cms_content
	WHERE nodeid_content=$nodeId
	AND valid_content=1
	AND actif_content=1
	AND id_site=$idSite
	AND isbriquedit_content <> 0
	
	ORDER BY type_content, name_content";
	
	//AND iszonedit_content <> 0

	if (DEF_BDD != "ORACLE") $sql.= ";";
	
	//echo $sql;

	$rs = $db->Execute($sql);
	if($rs) {
			if(!$rs->EOF) {
					while (!$rs->EOF) {

						$oContent = new Cms_content($rs->fields[n('id_content')]);
		
						array_push($return, $oContent);

						$rs->MoveNext();
					}
			} else {
					$return=false;
			}
	} else {
			echo "<br />Erreur de fonctionnement interne";
			if(DEF_MODE_DEBUG==true) {
				echo "<br />cms_content.class.php > getFolderContent";
				print("<br><strong>$sql</strong>");
			}			
			error_log("Plantage lors de l'execution de la requete\n $sql");
			error_log($db->ErrorMsg());
			$return = false;
	}
	$rs->Close();
	return $return;
}

//----------------------------------------
// maj le nom d'une brique editable
// son nom est de la forme : "nomzone" + "nompage"
//----------------------------------------

function updateNomBriqueEdit($idContent, $idPage) {
	
	global $db;
 
	// structure pour cette brique et cette page
	$aStruct = getObjetWithContentBEditPage($idContent, $idPage) ;

	// pour une brique et une page -> une seule structure
	$oStruct = $aStruct[0];

	// objet zone editable
	$oZonedit = new Cms_content($oStruct->getId_zonedit_content());
	// objet page
	$oPage = new Cms_page($oStruct->getId_page());

	// nouveau nom de la brique editable
	$sNouveauNom = $oZonedit->getName_content()."_".$oPage->getName_page();

		
	$sql = " UPDATE cms_content";
	$sql.= " SET name_content = '".$sNouveauNom."'";
	$sql.= " WHERE id_content = ".$idContent;
 
	$rs = $db->Execute($sql);

//print("<br>$sql");

	if($rs) {

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

		$result = $idContent;
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "cms_content.class.php > updateNomBriqueEdit";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());

		$result = false;
	}
	$rs->Close();
	return $result;
}


//----------------------------------------
// fonction renvoyant une liste de content
// avec des critères de recherche
//----------------------------------------

function getListContentEdit($aRecherche, $sOrderBy="", $eLimit)
{
	$aContent = dbGetIdListRech("Cms_content", $aRecherche, $sOrderBy, $eLimit);

	return $aContent;

}


//----------------------------------------
// fonction renvoyant le nombre de content d'une requete
// avec des critères de recherche
//----------------------------------------

function getCountListContentEdit($aRecherche, $sOrderBy="")
{

	$eContent = dbGetCountIdListRech("Cms_content", $aRecherche, $sOrderBy);

	return $eContent;

}


//---------------------------------------------
// Insère une brique par défaut
//---------------------------------------------

function crea_brique_defo($oZonedit) { 
	// création de la brique en BDD
	// la nouvelle brique est créée comme la zone éditable
	$oBriqueEdit = new Cms_content($oZonedit->getId_content());

	// Remplissage de la brique
	$oBriqueEdit->setHtml_content($oZonedit->getHtml_content());
	
	// numéro de la brique que l'on créé
	$eNoBrique = $a+1;

	$oBriqueEdit->setName_content(DEF_BRIQUEEDIT.$eNoBrique);
	$oBriqueEdit->setIszonedit_content(0);
	$oBriqueEdit->setIsbriquedit_content(1);
	
	// brique créée à un noeud non valid 
	// -> update au noeud de la page à la validation de la page
	// instaurer une purge des briques -2
	// attention ne pas puger les briques du jour (contexte multi utilisateur)
	$oBriqueEdit->setNodeid_content(-2);
	
	// nouvelle valeur de clé
	$oBriqueEdit->setId_content(getNextVal("cms_content", "id_content"));

	// INSERT
	$result = $oBriqueEdit->cms_content_insert();
	
	return $oBriqueEdit->getId_content();
}


//---------------------------------------------
// créé brique éditable par défaut + renomme noeud + maj nom
//---------------------------------------------

function crea_tout_brique_defo($oZonedit, $oPage) { 

	// brique par défaut
	$id = crea_brique_defo($oZonedit);

	// on se positionne sur la brique nouvellement créée
	$oContent = new Cms_content($id);

	// si brique editable -> noeud de la page
	$oContent->updateNoeud($oPage->getNodeid_page());

	// sauvegarde structure

	// objet cms_struct_page
	$oStruct_page = new Cms_struct_page();
				
	// alimentation objet
	$oStruct_page->setId_page($oPage->getId_page());
	$oStruct_page->setId_content($oContent->getId_content());
	$oStruct_page->setWidth_content($oContent->getWidth_content());
	$oStruct_page->setHeight_content($oContent->getHeight_content());
	$oStruct_page->setTop_content($topContent);
	$oStruct_page->setLeft_content($leftContent);
	$oStruct_page->setOpacity_content($opacity);
	$oStruct_page->setZindex_content($zindex);
	$oStruct_page->setId_zonedit_content($oZonedit->getId_content());

	$return = proc_storestruct($oStruct_page);


	// maj du nom de la brique editable
	// renommage
	updateNomBriqueEdit($oContent->getId_content(), $oPage->getId_page());
}

//----------------------------------------
// UPDATE dimensions
//----------------------------------------

function updateDimensions($oContent) {

	if(! (($oContent->getId_content() != null) && ($oContent->getId_content() > 0)) ) return false;
	if(! (($oContent->getWidth_content() != null) && ($oContent->getWidth_content() > 0)) ) return false;
	if(! (($oContent->getHeight_content() != null) && ($oContent->getHeight_content() > 0)) ) return false;

	$sql = " UPDATE cms_content";
	$sql.= " SET ";
	$sql.= " width_content = ".$oContent->getWidth_content().", ";
	$sql.= " height_content = ".$oContent->getHeight_content()." ";
	$sql.= " WHERE id_content = ".$oContent->getId_content();

print("<br>$sql");

	$result = dbExecuteQuery($sql);

	return $result;
}

?>