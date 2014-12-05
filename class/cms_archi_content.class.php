<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

Mkl 08/07/05
objet de BDD cms_archi_content :: class Cms_archi_content
archivage des briques "en ligne" de cms_content
Plusieurs niveaux d'archivage existent
Utilise dbChamp.php pour monter la classe plus facilement

function initValues($id)
function updateHTMLarchiContent()
function cms_archi_content_insert()
function cms_archi_content_update()
function cms_archi_content_delete()
function cms_archi_content_save()
function updateStatutArchi()
function getNextVersion()
function getNextId()
function cms_getFromContent($oContent)
function cms_putToContent($oContent)

// fin class

function getArchiList($id_content=null) {
function getArchiListStatut($idStatut) {
function getListContentEdit_archi($aRecherche, $sOrderBy="", $eLimit)
function getCountListContentEdit_archi($aRecherche, $sOrderBy="")
function getArchiWithIdContent($id) 

==========================================*/

class Cms_archi_content
{

var $id_archi;
var $id_content_archi;
var $statut_archi;
var $version_archi;
var $date_archi;
var $html_archi;

// constructeur
function Cms_archi_content($id=null)
{
	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;
	} else {
		$this->id_archi=-1;
		$this->id_content_archi=-1;
		$this->statut_archi='';
		$this->version_archi='';
		$this->date_archi='';
		$this->html_archi='';
	}
}

// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp

	$laListeChamps[]=new dbChamp("id_archi", "entier", "getId_archi", "setId_archi");
	$laListeChamps[]=new dbChamp("id_content_archi", "entier", "getId_content_archi", "setId_content_archi");
	$laListeChamps[]=new dbChamp("statut_archi", "entier", "getStatut_archi", "setStatut_archi");
	$laListeChamps[]=new dbChamp("version_archi", "entier", "getVersion_archi", "setVersion_archi");
	$laListeChamps[]=new dbChamp("date_archi", "date", "getDate_archi", "setDate_archi");
	$laListeChamps[]=new dbChamp("html_archi", "text", "getHtml_archi", "setHtml_archi");

	return($laListeChamps);
}


// getters
function getId_archi() { return($this->id_archi); } 
function getId_content_archi() { return($this->id_content_archi); } 
function getStatut_archi() { return($this->statut_archi); } 
function getVersion_archi() { return($this->version_archi); } 
function getDate_archi() { return($this->date_archi ); }
function getHtml_archi() { return($this->html_archi); } 


// setters
function setId_archi($c_id_archi) { return($this->id_archi=$c_id_archi); } 
function setId_content_archi($c_id_content_archi) { return($this->id_content_archi=$c_id_content_archi); } 
function setStatut_archi($c_statut_archi) { return($this->statut_archi=$c_statut_archi); } 
function setVersion_archi($c_version_archi) { return($this->version_archi=$c_version_archi); } 
function setDate_archi($c_date_archi) { return( $this->date_archi= $c_date_archi ); } 
function setHtml_archi($c_html_archi) { return($this->html_archi=$c_html_archi); } 



// autres getters
function getGetterPK() { return("getId_archi"); }
function getSetterPK() { return("setId_archi"); }
function getFieldPK() { return("id_archi"); }
function getGetterStatut() {return("getStatut_archi"); }
function getFieldStatut() {return("statut_archi"); }
function getTable() { return("cms_archi_content"); }
function getClasse() { return("Cms_archi_content"); }

//-------------------------
// a_voir sponthus
// a réfléchir
// pour des problèmes d'optimisation de requete, 
// il faudrait constituer un objet d'affichage extrait de cet objet
// en effet ce qui est lourd est le fait de retourner tout l'objet dans les listes
// pour l'instant juste un getter mais je suppose qu'il faudrait faire des getListeChamps d'affichage
function getFieldAffichage() { return("id_content_archi"); }
//-------------------------

// initialisation, obtention d'un  objet Cms_archi_content
function initValues($id) 
{
	global $db;
	$result = true;
	
		$sql = " SELECT id_archi, id_content_archi, statut_archi, ";
		$sql.= " version_archi, ".from_dbdatetime("date_archi").", html_archi";
		$sql.= " FROM cms_archi_content";
		$sql.= " WHERE id_archi = $id";
		if (DEF_BDD != "ORACLE") $sql.= ";";

		$rs = $db->Execute($sql);
		if($rs && !$rs->EOF) {

			$this->id_archi = $rs->fields[n('id_archi')];
			$this->id_content_archi = $rs->fields[n('id_content_archi')];			
			$this->statut_archi = $rs->fields[n('statut_archi')];
			$this->version_archi = $rs->fields[n('version_archi')];
			$this->date_archi = $rs->fields[n('date_archi_format')];
			$this->html_archi = $rs->fields[n('html_archi')];
			
			if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
			
		} else {
			echo "<br />Erreur de fonctionnement interne";
			if(DEF_MODE_DEBUG==true) {
				echo "<br />cms_archi_content.class.php > initValues";
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


function updateHTMLarchiContent() {

	global $db;
	
	$result = true;
	
	if (DEF_BDD != "ORACLE") {
		$sql = " UPDATE cms_archi_content SET html_archi='".to_dbquote($this->html_archi)."' WHERE id_archi=".$this->id_archi;
		$rs = $db->Execute($sql);
	}
	else {
		// spécifique ORACLE
		// le champ CLOB est envoyé après
		// car s'il est trop gros > 4000, la requete plante en ORA-01704
		// a_voir sponthus : ajouter un test sur la longueur de la chaine, sinon passer en INSERT
		$stmt = OCIParse($db->_connectionID, "UPDATE cms_archi_content SET html_archi=:gros_champ_clob WHERE id_archi=".$this->id_archi);
		OCIBindByName($stmt, ":gros_champ_clob", $this->html_archi, -1);
		$rs = OCIExecute($stmt); 
	}
	
	if($rs != false) {	  
			if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
    } else {
			echo "<br />Erreur de fonctionnement interne";
			if(DEF_MODE_DEBUG==true) {
				echo "cms_archi_content.class.php > updateHTMLarchiContent";
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

// INSERT
function cms_archi_content_insert()
{
	global $db;
    $result = true;
	
    $sql = " INSERT INTO cms_archi_content (";
	$sql.= " id_archi, id_content_archi, statut_archi,";
	$sql.= " version_archi, date_archi)";
	$sql.= " VALUES(";
	$sql.= " ".$this->id_archi.", ".$this->id_content_archi.", ".$this->statut_archi.", ";
	$sql.= " ".$this->version_archi.", ".to_dbdatetime($this->date_archi).")";

	$rs = $db->Execute($sql);

    if($rs != false) {
      $result = $this->id_content;
	  
			if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
	  
    } else {
			echo "<br />Erreur de fonctionnement interne";
			if(DEF_MODE_DEBUG==true) {
				echo "cms_archi_content.class.php > cms_archi_content_insert";
				echo "<br /><strong>$sql</strong>";
			}
			error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($db->ErrorMsg());
      $result = false;
    }
	$rs->Close();
	
	if($this->updateHTMLarchiContent()) return $result;
	else return false;
	
  }


// UPDATE
function cms_archi_content_update() {

	global $db;

		if(! (($this->id_archi !=null) && ($this->id_archi>0)) )
			$result = false;
			
		$sql = " UPDATE cms_archi_content";
		$sql.= " SET id_content_archi = ".$this->id_content_archi.",";
		$sql.= " statut_archi = ".$this->statut_archi.",";
		$sql.= " version_archi = ".$this->version_archi.",";
		$sql.= " date_archi = ".to_dbdatetime($this->date_archi)."";		
		$sql.= " WHERE id_archi = ".$db->qstr($this->id_archi);

	$rs = $db->Execute($sql);
		
	if($rs) {
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		$result = $this->id_archi;
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "cms_archi_content.class.php > cms_archi_content_update";
			echo "<br /><strong>$sql</strong>";
		}
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());

		$result = false;
	}
	$rs->Close();

	if($this->updateHTMLarchiContent()) return $result;
	else return false;

}


// DELETE
function cms_archi_content_delete() {

	global $db;

		if(! (($this->id_archi !=null) && ($this->id_archi>0)) ) 
			return false;

		$sql = " DELETE FROM cms_archi_content";
		$sql.= " WHERE id_archi=".$this->id_archi;

	$rs = $db->Execute($sql);
		
	if($rs) {
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		$result = true;
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "cms_archi_content.class.php > cms_archi_content_delete";
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

// Enregistre dans la base l'objet Archi (gère update/insert)
// s'il n'est pas déjà présent?
function cms_archi_content_save() {

	if( ($this->id_archi == null) || ($this->id_archi == -1) ) {
		$this->setId_archi( $this->getNextId() );
		$this->cms_archi_content_insert();
	}
	else $this->cms_archi_content_update();
	
}

// UPDATE statut
function updateStatutArchi() {

	global $db;

	if(! (($this->id_archi !=null) && ($this->id_archi>0)) )
		return false;
		
	$sql = " UPDATE cms_archi_content";
	$sql.= " SET statut_archi = ".$this->statut_archi."";		
	$sql.= " WHERE id_archi = ".$this->id_archi;

	$rs = $db->Execute($sql);
		
	if($rs) {

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);

		$result = $this->id_content;
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "cms_archi_content.class.php > updateStatutArchi";
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


// Renvoi la dernière version de cette brique
function getNextVersion() {

	global $db;
	$result = true;
	
	$sql = " SELECT MAX(version_archi) as v_archi";
	$sql.= " FROM cms_archi_content";
	$sql.= " WHERE id_content_archi = ".$this->id_content_archi;
	if (DEF_BDD != "ORACLE") $sql.= ";";

	$rs = $db->Execute($sql);
	if($rs && !$rs->EOF) {

		$result = $rs->fields[n('v_archi')];
		if($result=="") $result = 0;
		$result++;

		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_archi_content.class.php > getNextVersion";
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

// Renvoi le dernier ID
function getNextId() {
	return getNextVal("cms_archi_content", "id_archi");
}

// Récupère le contenu de l'objet oContent => dans Archi
function cms_getFromContent($oContent) {

	$this->setId_content_archi( $oContent->getId_content() );
	$this->setStatut_archi( $oContent->getStatut_content() );
	$this->setVersion_archi( $this->getNextVersion() );
	$this->setDate_archi( getdatetime() );
	$this->setHtml_archi( $oContent->getHtml_content() );
	
}

// Récupère le contenu de archi => implémente l'objet oContent
function cms_putToContent($oContent) {
	$oContent->setHtml_content( $this->getHtml_archi() );
}

} //class 


// Récupère la liste de toutes les versions de la brique $id_content
// si pas d'id fourni => renvoi tout
function getArchiList($id_content=null) {

/* a_voir : PB de date avec les fonctions génériques de sql_persistant
	if($id_content!=null) {
		$aGetterWhere[] = "id_content_archi";
		$aValueWhere[] = $id_content;
	}
	$aCms_archi_content = dbGetObjectsFromFieldValue("Cms_archi_content", $aGetterWhere, $aValueWhere, "getVersion_archi");
*/

	global $db;
	$result = true;

	$sql = " SELECT id_archi, id_content_archi, statut_archi, ";
	$sql.= " version_archi, ".from_dbdatetime("date_archi").", html_archi";
	$sql.= " FROM cms_archi_content, cms_struct_page, cms_page";
	$sql.= " WHERE ";
	$sql.= " cms_archi_content.id_content_archi=cms_struct_page.id_content ";
	$sql.= " AND cms_struct_page.id_page=cms_page.id_page ";
	$sql.= " AND cms_page.valid_page=1";
	if($id_content!=null) $sql.= " AND id_content_archi = ".$id_content;

	$sql.= " ORDER BY id_content_archi ASC";
	if (DEF_BDD != "ORACLE") $sql.= ";";

	$rs = $db->Execute($sql);
	
	
	if($rs) {
		$aCms_archi_content = array();
		while(!$rs->EOF) {
			$oCms_archi_content = new Cms_archi_content();
			
			$oCms_archi_content->id_archi = $rs->fields[n('id_archi')];
			$oCms_archi_content->id_content_archi = $rs->fields[n('id_content_archi')];			
			$oCms_archi_content->statut_archi = $rs->fields[n('statut_archi')];
			$oCms_archi_content->version_archi = $rs->fields[n('version_archi')];
			$oCms_archi_content->date_archi = $rs->fields[n('date_archi')];
			$oCms_archi_content->html_archi = $rs->fields[n('html_archi')];
			
			array_push($aCms_archi_content,$oCms_archi_content);
			
			if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
			
			$rs->MoveNext();
		}
		
		$result= $aCms_archi_content;
		
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_archi_content.class.php > getArchiList";
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

// Récupère toutes les briques de CMS_ARCHI_CONTENT d'un statut
function getArchiListStatut($idStatut, $idUser=-1) {

	global $db;
	$result = true;

	$sql = " SELECT id_archi, id_content_archi, statut_archi, ";
	$sql.= " version_archi, ".from_dbdatetime("date_archi").", html_archi";
	$sql.= " FROM cms_archi_content, cms_struct_page, cms_page";
	if ($idUser != -1) $sql.= ", cms_droit";
	$sql.= " WHERE statut_archi = $idStatut";
	$sql.= " AND cms_archi_content.id_content_archi=cms_struct_page.id_content ";
	$sql.= " AND cms_struct_page.id_page=cms_page.id_page ";
	$sql.= " AND cms_page.valid_page=1 AND cms_page.id_site=".$_SESSION['idSite_travail'];
	if ($idUser != -1) $sql.= " AND cms_archi_content.id_content_archi=cms_droit.id_content AND cms_droit.user_id=".$idUser;
	$sql.= " ORDER BY id_content_archi ASC, version_archi ASC";
	if (DEF_BDD != "ORACLE") $sql.= ";";

//print("<br>$sql");

	$rs = $db->Execute($sql);
	
	
	if($rs) {
		$aCms_archi_content = array();
		while(!$rs->EOF) {
			$oCms_archi_content = new Cms_archi_content();
			
			$oCms_archi_content->id_archi = $rs->fields[n('id_archi')];
			$oCms_archi_content->id_content_archi = $rs->fields[n('id_content_archi')];			
			$oCms_archi_content->statut_archi = $rs->fields[n('statut_archi')];
			$oCms_archi_content->version_archi = $rs->fields[n('version_archi')];
			$oCms_archi_content->date_archi = $rs->fields[n('date_archi')];
			$oCms_archi_content->html_archi = $rs->fields[n('html_archi')];
			
			array_push($aCms_archi_content, $oCms_archi_content);
			
			if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
			
			$rs->MoveNext();
		}
		
		$result= $aCms_archi_content;
		
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_archi_content.class.php > getArchiList";
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

// fonction renvoyant une liste de content archivés
// avec des critères de recherche

function getListContentEdit_archi($aRecherche, $sOrderBy="", $eLimit)
{

	$aContent = dbGetIdListRech("Cms_archi_content", $aRecherche, $sOrderBy, $eLimit);

	return $aContent;

}

// fonction renvoyant le nombre de content d'une requete
// avec des critères de recherche

function getCountListContentEdit_archi($aRecherche, $sOrderBy="")
{

	$eContent = dbGetCountIdListRech("Cms_archi_content", $aRecherche, $sOrderBy);

	return $eContent;

}


// obtention d'un  objet Cms_archi_content EN LIGNE
// avec un id_content

function getArchiWithIdContent($id) 
{
	global $db;
	$result = true;
	
	$sql = " SELECT id_archi, id_content_archi, statut_archi, ";
	$sql.= " version_archi, ".from_dbdatetime("date_archi").", html_archi";
	$sql.= " FROM cms_archi_content";
	$sql.= " WHERE id_content_archi = $id AND statut_archi=".DEF_ID_STATUT_LIGNE;
	if (DEF_BDD != "ORACLE") $sql.= ";";

	$rs = $db->Execute($sql);
	if($rs) {

		$oArchi_content = new Cms_archi_content();
			
		$oArchi_content->id_archi = $rs->fields[n('id_archi')];
		$oArchi_content->id_content_archi = $rs->fields[n('id_content_archi')];			
		$oArchi_content->statut_archi = $rs->fields[n('statut_archi')];
		$oArchi_content->version_archi = $rs->fields[n('version_archi')];
		$oArchi_content->date_archi = $rs->fields[n('date_archi')];
		$oArchi_content->html_archi = $rs->fields[n('html_archi')];
		
		if(DEF_MODE_DEBUG==true) error_log("TRACE :: ".$sql);
		
		$result = $oArchi_content;
		
	} else {
		echo "<br />Erreur de fonctionnement interne";
		if(DEF_MODE_DEBUG==true) {
			echo "<br />cms_archi_content.class.php > getArchiWithIdContent($id) ";
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

?>