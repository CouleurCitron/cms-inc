<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


/*======================================

sponthus 29/08/05
objet de BDD PA_ANNONCES :: class Annonce

Transformation de cette classe en classe utilisatrice de sql_persistant

==========================================*/




include_once($_SERVER['DOCUMENT_ROOT']."/include/cms-inc/mail_lib.php");

class Annonce {

	// Propriétés
	var $dbConn = null;

	var $id = null;
	var $date_debut = '';
	var $date_perim = '';
	var $deposant = null;
	var $categorie = null;
	var $valide = 0;
	var $isGarde = 0;
	var $isOffre = 0;
	var $libelle = '';
	var $libellePlus = '';
	var $corps = '';

	var $id_site = '';


// constructeur
function Annonce($id=null) 
{
	global $db;
	$this->dbConn = &$db;

	if($id!=null) {
		//$this = dbGetObjectFromPK("Annonce", $id);
		$temp = dbGetObjectFromPK("Annonce", $id);
		foreach ($temp as $key => $val){
			$this->$key = $val;		
		}
	} else {
		$this->id_avis = -1;

		$this->date_debut = '';
		$this->date_perim = '';
		$this->deposant = '';
		$this->categorie = '';
		$this->valide = '';
		$this->isGarde = '';
		$this->isOffre = '';
		$this->libelle = '';
		$this->libellePlus = '';
		$this->corps = '';

		$this->id_site = '';						
	}
}

// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp

	$laListeChamps[]=new dbChamp("annonce_id", "entier", "getId", "setId");
	$laListeChamps[]=new dbChamp("annonce_dt_debut", "date_formatee_timestamp_with_zone", "getDt_debut", "setDt_debut");
	$laListeChamps[]=new dbChamp("annonce_dt_perim", "date_formatee_timestamp_with_zone", "getDt_perim", "setDt_perim");
	$laListeChamps[]=new dbChamp("annonce_depot_inscrit_id", "entier", "getDepot_inscrit_id", "setDepot_inscrit_id");
	$laListeChamps[]=new dbChamp("annonce_categorie_id", "entier", "getCategorie_id", "setCategorie_id");
	$laListeChamps[]=new dbChamp("annonce_valid", "entier", "getValid", "setValid");
	$laListeChamps[]=new dbChamp("annonce_is_garde", "entier", "getIs_garde", "setIs_garde");
	$laListeChamps[]=new dbChamp("annonce_is_offre", "entier", "getIs_offre", "setIs_offre");
	$laListeChamps[]=new dbChamp("annonce_libelle", "text", "getLibelle", "setLibelle");
	$laListeChamps[]=new dbChamp("annonce_libelleplus", "text", "getLibelleplus", "setLibelleplus");
	$laListeChamps[]=new dbChamp("annonce_corps", "text", "getCorps", "setCorps");
	$laListeChamps[]=new dbChamp("id_site", "entier", "getId_site", "setId_site");

	return($laListeChamps);
}

function getId() { return($this->id); }
function getDt_debut() { return($this->date_debut); }
function getDt_perim() { return($this->date_perim); }
function getDepot_inscrit_id() { return($this->deposant); }
function getCategorie_id() { return($this->categorie); }
function getValid() { return($this->valide); }
function getIs_garde() { return($this->isGarde); }
function getIs_offre() { return($this->isOffre); }
function getLibelle() { return($this->libelle); }
function getLibelleplus() { return($this->libellePlus); }
function getCorps() { return($this->corps); }
function getId_site() { return($this->id_site); }


function setId($c_id) { return($this->id=$c_id); }
function setDt_debut($c_dt_debut) { return($this->date_debut=$c_dt_debut); }
function setDt_perim($c_dt_perim) { return($this->date_perim=$c_dt_perim); }
function setDepot_inscrit_id($c_depot_inscrit_id) { return($this->deposant=$c_depot_inscrit_id); }
function setCategorie_id($c_categorie_id) { return($this->categorie=$c_categorie_id); }
function setValid($c_valid) { return($this->valide=$c_valid); }
function setIs_garde($c_is_garde) { return($this->isGarde=$c_is_garde); }
function setIs_offre($c_is_offre) { return($this->isOffre=$c_is_offre); }
function setLibelle($c_libelle) { return($this->libelle=$c_libelle); }
function setLibelleplus($c_libellePlus) { return($this->libellePlus=$c_libellePlus); }
function setCorps($c_corps) { return($this->corps=$c_corps); }
function setId_site($c_id_site) { return($this->id_site=$c_id_site); }



// autres getters
function getGetterPK() { return("getAnnonce_id"); }
function getSetterPK() { return("setAnnonce_id"); }
function getFieldPK() { return("annonce_id"); }
function getTable() { return("pa_annonces"); }
function getClasse() { return("Annonce"); }




	function listAnnonces() {
		$result = array();
		$sql = "select annonce_id from pa_annonces order by annonce_dt_debut desc, annonce_dt_perim";
		$rs = $this->dbConn->Execute($sql);
		if($rs) {
			while(!$rs->EOF) {
				$uneAnnonce = new Annonce($rs->fields[0]);
				array_push($result,$uneAnnonce);
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
	
	function delete() {
		if(! (($this->id !=null) && ($this->id>0)) )
			return false;
		$sql = "delete from pa_annonces where annonce_id = ".$this->id;
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
		$sql = " UPDATE pa_annonces";
		$sql.= " SET annonce_dt_debut = ".$this->dbConn->qstr($this->date_debut).",";
		$sql.= " annonce_dt_perim = ".$this->dbConn->qstr($this->date_perim).",";
		$sql.= " annonce_depot_inscrit_id = ".$this->deposant->id.",";
		$sql.= " annonce_categorie_id = ".$this->categorie->id.",";
		$sql.= " annonce_valid = ".$this->valide.",";
		$sql.= " annonce_is_garde = ".$this->isGarde.",";
		$sql.= " annonce_is_offre = ".$this->isOffre.",";
		$sql.= " annonce_libelle = ".$this->dbConn->qstr($this->libelle).",";
		$sql.= " annonce_libelleplus = ".$this->dbConn->qstr($this->libellePlus).",";
		$sql.= " annonce_corps = ".$this->dbConn->qstr($this->corps);
		$sql.= " WHERE annonce_id = ".$this->id;
		if (DEF_BDD != "ORACLE") $sql.= ";";

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

	function validate() {
		if(! (($this->id !=null) && ($this->id>0)) )
			return false;
		$sql = " UPDATE pa_annonces";
		$sql.= " SET annonce_valid = ".$this->valide;
		$sql.= " WHERE annonce_id = ".$this->id;
		if (DEF_BDD != "ORACLE") $sql.= ";";

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

	function add() {

		$eNextVal = getNextVal("pa_annonces", "annonce_id");

		$sql = " INSERT INTO pa_annonces";
		$sql.= " (annonce_id, annonce_dt_debut, annonce_dt_perim, ";
		$sql.= " annonce_depot_inscrit_id, annonce_categorie_id, annonce_valid, annonce_is_garde, ";
		$sql.= " annonce_is_offre, annonce_libelle,";
		$sql.= " annonce_libelleplus, annonce_corps, id_site)";
		$sql.= " VALUES ($eNextVal, ";

//		$sql.= " ".$this->dbConn->qstr($this->date_debut.'.301077').", ";
//		$sql.= " ".$this->dbConn->qstr($this->date_perim.'.301077').", ";
		if (DEF_BDD != "ORACLE") {
		$sql.= " str_to_date('".$this->date_debut."', 'yyyy-mm-dd HH24:MI:SS'), ";
		$sql.= " str_to_date('".$this->date_perim."', 'yyyy-mm-dd HH24:MI:SS'), ";
		}else{
		$sql.= " to_date('".$this->date_debut."', 'yyyy-mm-dd HH24:MI:SS'), ";
		$sql.= " to_date('".$this->date_perim."', 'yyyy-mm-dd HH24:MI:SS'), ";
		}
		if (DEF_BDD != "MYSQL") {
		$sql.= " ".$this->deposant->id.", ".$this->categorie->id.", ".$this->valide.", ".$this->isGarde.",";
		$sql.= " ".$this->isOffre.", to_char(".$this->dbConn->qstr($this->libelle)."),";
		$sql.= " to_char(".$this->dbConn->qstr($this->libellePlus)."), to_clob(".$this->dbConn->qstr($this->corps)."), ".$_SESSION['idSite_pa'].")"; 
		}
		else{ // MYSQL
		$sql.= " ".$this->deposant->id.", ".$this->categorie->id.", ".$this->valide.", ".$this->isGarde.",";
		$sql.= " ".$this->isOffre.", (".$this->dbConn->qstr($this->libelle)."),";
		$sql.= " (".$this->dbConn->qstr($this->libellePlus)."), (".$this->dbConn->qstr($this->corps)."), ".$_SESSION['idSite_pa'].")"; 
		}
		if (DEF_BDD != "ORACLE") $sql.=";";			

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
	
	function notify($action) {
		$msg = '';
		$from = 'webmaster.internet@mairie-boulogne-billancourt.fr';
		$to = $this->deposant->mail;
		$subject = '[Mairie Boulogne Billancourt] Petites annonces et gardes partagées';
		if($action=='validate') {
			$msg = 'Bonjour '.$this->deposant->prenom.' '.$this->deposant->nom.',
Votre annonce vient d\'être validée, elle est désormais en ligne pour 3 mois. 
Nous vous demandons de la supprimer dès qu\'elle aura été satisfaite.

Annonce Ref ';
			$msg .= ($this->isGarde) ? "GAR" : "ANN";
			$msg .= ''.$this->id.'
 

cordialement,

Le webmaster.

http://www.boulognebillancourt.com
';

		}
		multiPartMail($to , $subject , '' , $msg, $from, null, 'text/plain', 'localhost');
	}
	
}


// renumérotation de toutes les petites annonces
// attention tous les id vont changer...
function renumerote()
{
	// sélection de toutes les petites annonces

	$sObjet = "Annonce";
	$aGetterWhere = array();
	$aValeurChamp = array();
	$aGetterOrderBy = array();
	$aGetterSensOrderBy = array();

	$aGetterOrderBy[] = "getId";
	$aGetterSensOrderBy[] = "ASC";

	$aPA = dbGetObjectsFromFieldValue2($sObjet, $aGetterWhere, $aValeurChamp, $aGetterOrderBy, $aGetterSensOrderBy);

	$sTrace = "<table class=arbo cellpadding='5' cellspacing='0'>";

	for ($i=0; $i<newSizeOf($aPA); $i++)
	{
		$oPA = $aPA[$i];

		$j = $i + 1;
		$sql = " UPDATE pa_annonces SET annonce_id=".$j." WHERE annonce_id=".$oPA->getId();

		if ($oPA->getIs_garde()) $sGarde = "Garde"; else $sGarde = "Classique";

		$sTrace.= "<tr><td>".$sGarde."</td><td>".$oPA->getId()."</td><td>=></td><td>".$j."</td></tr>";

//print("<br>$sql");

		dbExecuteQuery($sql);
	}

	$sTrace.= "</table>";

	return($sTrace);
}

?>
