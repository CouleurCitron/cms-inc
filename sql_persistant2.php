<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// sponthus 03/08/2005
// fonctions sql persistantes 2

// fonctions de recherche

// a_voir sponthus a terme mettre ce fichier avec dbChamp dans /include/db/

/*

function dbExecuteQuery($sql)
function dbGetUniqueValueFromRequete($sql)
function dbGetUniqueValue2FieldsFromRequete($sql)
function dbGetArrayOneFieldFromRequete($sql)
function dbGetObjectsFromRequete($sObjet, $sRequete)
function dbGetObjectsFromRequeteID($sObjet, $sql)
function dbGetIDFromRequeteID($sObjet, $sql)
function dbGetObjectsFromRequeteCache($sObjet, $sql, $ttl=100){
function dbGetIdListRech($sObjet, $aRecherche, $sOrderBy="", $eLimit)
function dbGetCount($sObjet)
function dbGetListRech($sObjet, $aRecherche, $sOrderBy="", $eLimit)
function dbGetCountIdListRech($sObjet, $aRecherche)
function dbMakeRequeteWithCriteres($sObjet, $aRecherche, $sOrderBy="")
function dbMakeRequeteWithCriteres2($sObjet, $aRecherche, $aOrderBy,  $aSensOrderBy)
function dbMakeRequeteWithCriteres2_OR($sObjet, $aRecherche, $aOrderBy,  $aSensOrderBy)
*/

//------------------------------------------------
// execute une requete
//------------------------------------------------

function dbExecuteQuery($sql)
{
	global $db;

	$rs = $db->Execute($sql);
	
	if($rs) {
		$result = true;
		$rs->Close();
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />dbExecuteQuery($sql)";
			echo "<br /><strong>$sql</strong>";
		}
		echo "<br />Erreur interne de programme";
		error_log("----- DEBUT ERREUR ------------------------");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("----- FIN ERREUR ------------------------");
		$result = false;
	}
	return $result;
}

//------------------------------------------------
// recupere une enregistrement unique d'une sRequete specifique
//------------------------------------------------

function dbGetUniqueValueFromRequete($sql)
{
	global $db;

	$rs = $db->Execute($sql);
	
	if($rs) {
		$result = $rs->fields[0];
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />dbGetUniqueValueFromRequete($sObjet, $sql)";
			echo "<br /><strong>$sql</strong>";
		}
		echo "<br />Erreur interne de programme";
		error_log("----- DEBUT ERREUR ------------------------");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("----- FIN ERREUR ------------------------");
		return false;
	}
	$rs->Close();
	return $result;
}


//------------------------------------------------
// recupere une enregistrement unique de deux champs d'une sRequete specifique
//------------------------------------------------

function dbGetUniqueValue2FieldsFromRequete($sql)
{
	global $db;

	$rs = $db->Execute($sql);
	
	$result = array();

	if($rs) {
		$result[0] = $rs->fields[0];
		$result[1] = $rs->fields[1];
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />dbGetUniqueValueFromRequete($sObjet, $sql)";
			echo "<br /><strong>$sql</strong>";
		}
		echo "<br />Erreur interne de programme";
		error_log("----- DEBUT ERREUR ------------------------");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("----- FIN ERREUR ------------------------");
		return false;
	}
	$rs->Close();
	return $result;
}


//------------------------------------------------
// recupere une liste d'un champ specifiee par une sRequete specifique
//------------------------------------------------

function dbGetArrayOneFieldFromRequete($sql)
{
	if(trim($sql)==''){
		return false;
	}

	global $db;
	$aResultat = array();

	$rs = $db->Execute($sql);
	
	if($rs) {
		while(!$rs->EOF) {

			array_push($aResultat, $rs->fields[0]);

			$rs->MoveNext();
		}
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />dbGetArrayOneFieldFromRequete($sql)";
			echo "<br /><strong>$sql</strong>";
		}
		echo "<br />Erreur interne de programme";
		error_log("----- DEBUT ERREUR ------------------------");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("----- FIN ERREUR ------------------------");
		return false;
	}
	$rs->Close();
	return $aResultat;
}




//------------------------------------------------
// recupere une liste de mon Objet specifiee par une sRequete specifique
//------------------------------------------------

function dbGetObjectsFromRequete($sObjet, $sql)
{
	if ($sql!=''	&&	$sql != NULL){
	global $db;
	$aResultat = array();
	//var_dump($sql);
	$rs = $db->Execute($sql);
	if($rs) {
		while(!$rs->EOF) {
			array_push($aResultat, dbMakeObjet($sObjet, $rs));

			$rs->MoveNext();
		}
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />dbGetObjectsFromRequete($sObjet, $sql)";
			echo "<br /><strong>$sql</strong>";
		}
		echo "<br />Erreur interne de programme";
		error_log("----- DEBUT ERREUR ------------------------");
			error_log(__FUNCTION__."(".$sObjet.", ".$sql.")");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("----- FIN ERREUR ------------------------");
		return false;
	}
	$rs->Close();
	return $aResultat;
	}
	else{
		error_log("----- DEBUT ERREUR ------------------------");
		error_log(__FUNCTION__."(".$sObjet.", ".$sql.")");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('no sql query');
		error_log("----- FIN ERREUR ------------------------");
		return false;
	}
}


//------------------------------------------------
// recupere une liste de mon Objet specifiee par une sRequete specifique
// ATTENTION cette requete ne renvie q'un seul champ : l'ID
//------------------------------------------------

function dbGetObjectsFromRequeteID($sObjet, $sql)
{
	global $db;
	$aResultat = array();

	$rs = $db->Execute($sql);
	
	if($rs) {
		while(!$rs->EOF) {

			array_push($aResultat, dbGetObjectFromPK($sObjet, $rs->fields[0]));

			$rs->MoveNext();
		}
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />dbGetObjectsFromRequete($sObjet, $sql)";
			echo "<br /><strong>$sql</strong>";
		}
		echo "<br />Erreur interne de programme";
		error_log("----- DEBUT ERREUR ------------------------");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("----- FIN ERREUR ------------------------");
		return false;
	}
	$rs->Close();
	return $aResultat;
}

//------------------------------------------------
// recupere une liste de mon Objet specifiee par le cache
//------------------------------------------------

function dbGetObjectsFromRequeteCache($sObjet, $sql, $ttl=100){
	//error_reporting(E_ALL);
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/lib/phpfastcache/php_fast_cache.php'); 
        phpFastCache::setup("storage","auto");
	//phpFastCache::$storage = "auto";
	$cache = phpFastCache();
        
	$aObjet = $cache->get(md5($sql));

	if ($aObjet!=NULL){
		//error_log('found in cache '.$sql);
		return $aObjet;
	}
	else{
		$aObjet = dbGetObjectsFromRequete($sObjet, $sql);	
		$cache->set(md5($sql),$aObjet,$ttl);
		return $aObjet;
	}		
	
}


//------------------------------------------------
// recupere une liste d'ID specifiee par une sRequete specifique
// ATTENTION cette requete ne renvie q'un seul champ : l'ID
//------------------------------------------------

function dbGetIDFromRequeteID($sObjet, $sql)
{
	global $db;
	$aResultat = array();

	$rs = $db->Execute($sql);

////////////////////////
// A VOIR
////////////////////////
// renvoi un array de 2 ID en fait
////////////////////////

	
	if($rs) {
		while(!$rs->EOF) {

			array_push($aResultat, array($rs->fields[0], $rs->fields[1]));

			$rs->MoveNext();
		}
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />dbGetIDFromRequeteID($sObjet, $sql)";
			echo "<br /><strong>$sql</strong>";
		}
		echo "<br />Erreur interne de programme";
		error_log("----- DEBUT ERREUR ------------------------");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("----- FIN ERREUR ------------------------");
		return false;
	}
	$rs->Close();
	return $aResultat;
}


//------------------------------------------------
// recupere liste d'id dont la valeur est filtrée par des critères de recherche
//------------------------------------------------

function dbGetIdListRech($sObjet, $aRecherche, $sOrderBy="", $eLimit)
	{

	$oObjet = new $sObjet;
	$sTable = $oObjet->getTable();
	$sFieldPK = $oObjet->getFieldPK();	
	$sFieldAffichage = $oObjet->getFieldAffichage();

	$sRequete = "SELECT distinct $sTable.$sFieldPK, $sTable.$sFieldAffichage ";

	// construction de la requete (clause FROM et WHERE)
	$sRequete.= dbMakeRequeteWithCriteres($sObjet, $aRecherche, $sOrderBy);

	//--------------------	
	// limite le nombre d'enregistrements de la requete
	// spécifique BDD 
	if (DEF_BDD != "ORACLE") {
		// a_voir
		// à développer pour les différentes BDD	
	} else {
		// ORACLE
		// rownum pour être pertinent doit intervenir avant la requete
		if ($eLimit != "") {
	
			$sRequete = " SELECT * FROM (".$sRequete.") WHERE rownum <= ".$eLimit;
		}
	}
	//--------------------	

	//error_log($sRequete);
	$aResultat = dbGetIDFromRequeteID($sObjet, $sRequete);

	return($aResultat);
}


//------------------------------------------------
//------------------------------------------------

function dbGetListRech($sObjet, $aRecherche, $sOrderBy="", $eLimit)
	{

	$oObjet = new $sObjet;

	$sTable = $oObjet->getTable();
	$sFieldPK = $oObjet->getFieldPK();	
	$sFieldAffichage = $oObjet->getFieldAffichage();

	$sRequete = "SELECT distinct $sTable.$sFieldPK, $sTable.$sFieldAffichage ";

	// construction de la requete (clause FROM et WHERE)
	$sRequete.= dbMakeRequeteWithCriteres($sObjet, $aRecherche, $sOrderBy);
	
	//--------------------	
	// limite le nombre d'enregistrements de la requete
	// spécifique BDD 
	if (DEF_BDD != "ORACLE") {
		// a_voir
		// à développer pour les différentes BDD	
	} else {
		// ORACLE
		// rownum pour être pertinent doit intervenir avant la requete
		if ($eLimit != "") {
	
			$sRequete = " SELECT * FROM (".$sRequete.") WHERE rownum <= ".$eLimit;
		}
	}
	//--------------------
	

//print("<br><font color=green>$sRequete</font>");

	$aResultat = dbGetObjectsFromRequeteID($sObjet, $sRequete);

	return($aResultat);
}


//------------------------------------------------
// compte le nombre d'enr d'une liste d'id dont la valeur est filtrée par des critères de recherche
//------------------------------------------------

function dbGetCountIdListRech($sObjet, $aRecherche, $sOrderBy)
	{
	global $db;
	
	$oObjet = new $sObjet;

	$sTable = $oObjet->getTable();
	$sFieldPK = $oObjet->getFieldPK();	
	
	$sRequete = "SELECT count(distinct $sTable.$sFieldPK) ";
	
	// construction de la requete (clause FROM et WHERE)
	$sRequete.= dbMakeRequeteWithCriteres($sObjet, $aRecherche, $sOrderBy);
	if (!isset($aRecherche)){
		$sRequete = preg_replace("/(where.*)/msi", "", $sRequete); 
	}

	
	$sql = $sRequete;

	$rs = $db->Execute($sql);
	if($rs) {
		$result = $rs->fields[0];
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />getCount($sTable, $sFieldCount, $sFieldWhere, $eValueWhere, $sTypeWhere)";
			echo "<br /><strong>$sql</strong>";
		}
		echo "<br />Erreur interne de programme";
		error_log("----- DEBUT ERREUR ------------------------");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("----- FIN ERREUR ------------------------");
		return false;
	}
	$rs->Close();
	return($result);

}


//------------------------------------------------
// compte le nombre d'enr 
//------------------------------------------------

function dbGetCount($sObjet)
	{
	global $db;	
	$oObjet = new $sObjet;
	$sTable = $oObjet->getTable();	
	$sRequete = "SELECT count(*) FROM ".$sTable;	
	$sql = $sRequete;

	$rs = $db->Execute($sql);
	if($rs) {
		$result = $rs->fields[0];
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />getCount(".$sTable.")";
			echo "<br /><strong>".$sql."</strong>";
		}
		echo "<br />Erreur interne de programme";
		error_log("----- DEBUT ERREUR ------------------------");
		error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
		error_log('erreur lors de l\'execution de la requete');
		error_log($sql);
		error_log($db->ErrorMsg());
		error_log("----- FIN ERREUR ------------------------");
		return false;
	}
	$rs->Close();
	return(intval($result));
}


//------------------------------------------------
// construit une requete avec des critères
//------------------------------------------------

function dbMakeRequeteWithCriteres($sObjet, $aRecherche, $sOrderBy="")
{
	$oObjet = new $sObjet;

	$sTable = $oObjet->getTable();

	$aFrom = array();
	$aWhere = array();
	$aFromTemp = array();
	$aWhereTemp = array();
	$sRequete = '';

	//--------------------
	// analyse des critères de recherche
	 
	$sClauseWhere = "";
	for ($a=0; $a<newSizeOf($aRecherche); $a++) {

		// élément de recherche
		$oRech = $aRecherche[$a];

		//var_dump($oRech);
		// si une valeur est recherchée
		if (($oRech->getValeurRecherche() != "") && ($oRech->getValeurRecherche() != "-1")) {

			// composition de la clause FROM (tables de jointure)
			$aFromThis = explode(";", $oRech->getTableBD());
			
			for($m=0; $m<newSizeOf($aFromThis); $m++) {
				if ($aFromThis[$m] != "") array_push($aFromTemp, $aFromThis[$m]);
			}
			// composition de la clause WHERE (jointures)
			if (strlen($oRech->getJointureBD()) != 0){
				$aJointure = explode(";", $oRech->getJointureBD());
				for($m=0; $m<newSizeOf($aJointure); $m++) {
					if ($aJointure[$m] != "") array_push($aWhereTemp, $aJointure[$m]);
				}
			}			
			
			// valeur du critère de recherche
			// dans le cas des pures jointures pas de valeur recherchée
			if (!$oRech->getPureJointure()) {
				$sWhere = " ".$oRech->getNomBD()." = ";
			
				if ($oRech->getTypeBD() == "text") $sWhere.= "'".$oRech->getValeurRecherche()."'";
				else $sWhere.= $oRech->getValeurRecherche();
					
				// clause Where
				$aWhereTemp[] = $sWhere;
			}
		}
	}
	//--------------------

	// ajout de la table de l'objet résultat dans la clause FROM
	
	$aFromTemp[] = $sTable;

	// dédoublonnage
	// petits soucis avec array_unique....
	$aFrom = dedoublonne($aFromTemp);
	$aWhere = dedoublonne($aWhereTemp);

	//--------------------
	// clause FROM
	$sRequete.= " FROM ";
	for ($p=0; $p<newSizeOf($aFrom); $p++) {

		$sRequete.= " ".$aFrom[$p];
		if ($p != newSizeOf($aFrom)-1) $sRequete.= ", ";
	}
	//--------------------

	//--------------------
	// clause WHERE
	$sRequete.= " WHERE ";
	// valeurs de critères
	for ($p=0; $p<newSizeOf($aWhere); $p++) {

		$sRequete.= " ".$aWhere[$p];
		if ($p != newSizeOf($aWhere)-1) $sRequete.= " AND ";
	}
	//--------------------
	
	//--------------------
	// clause ORDER BY
	if ($sOrderBy!= "") {
		$sRequete.=" ORDER BY ".$sOrderBy;
	}
	//--------------------
	return($sRequete);
}

//------------------------------------------------
// construit une requete avec des critères
//------------------------------------------------

function dbMakeRequeteWithCriteres2($sObjet, $aRecherche, $aOrderBy,  $aSensOrderBy)
{
	$oObjet = new $sObjet;

	$sTable = $oObjet->getTable();
	
	$sRequete = "";
	
	$aFrom = array();
	$aWhere = array();
	$aFromTemp = array($sTable);
	$aWhereTemp = array();

	//--------------------
	// analyse des critères de recherche
	
	$sClauseWhere = "";
	for ($a=0; $a<newSizeOf($aRecherche); $a++) {

		// élément de recherche
		$oRech = $aRecherche[$a];
		// si une valeur est recherchée
		if ($oRech->getValeurRecherche() != "" && $oRech->getValeurRecherche() != "-1") {
			// composition de la clause FROM (tables de jointure)
			$aFromThis = explode(";", $oRech->getTableBD());
			for($m=0; $m<newSizeOf($aFromThis); $m++) {
				if ($aFromThis[$m] != "")
					array_push($aFromTemp, $aFromThis[$m]);
			}
			
			// composition de la clause WHERE (jointures)
			if (newSizeOf($oRech->getJointureBD() != 0))
				$aJointure = explode(";", $oRech->getJointureBD());
                                //pre_dump($aJointure);
			for ($m=0; $m<newSizeOf($aJointure); $m++) {
				if ($aJointure[$m] != "")
					array_push($aWhereTemp, $aJointure[$m]);
			}
			
			// valeur du critère de recherche
			// dans le cas des pures jointures pas de valeur recherchée
			if (!$oRech->getPureJointure()) {
				$sWhere = " ".$oRech->getNomBD()." = ";

				if ($oRech->getTypeBD() == "text")
					$sWhere.= "'".$oRech->getValeurRecherche()."'";
				else	$sWhere.= $oRech->getValeurRecherche();
	
				// clause Where
				$aWhereTemp[] = $sWhere;
			}
		}
	}
        
        
	//viewArray($aFromTemp, 'tables');
	//viewArray($aWhereTemp, 'wheres');
	
	//--------------------
	// ajout de la table de l'objet résultat dans la clause FROM
	 
	//pre_dump($aFromTemp);
	 
	//viewArray($aFromTemp, $sTable);
	$aCounts = Array();
	foreach ($aFromTemp as $fromtemp) {
		if (empty($aCounts[$fromtemp]))
			$aCounts[$fromtemp] = 1;
		else	$aCounts[$fromtemp]++;
	}
	
	//viewArray($aCounts, 'COUNTS');
	$aFromTemp = array_keys($aCounts);
	foreach ($aFromTemp as $k => $fromtemp) {
		$len = strlen($sTable);
		if (substr($fromtemp, 0, $len) == $sTable && (substr($fromtemp, $len, 5) == ' LEFT' || substr($fromtemp, $len, 6) == ' RIGHT')) {
			// replace table name with complex JOIN clauses
			$replaceList[$sTable] = $fromtemp;
			unset($aFromTemp[$k]);
		}
	}
	if (!empty($replaceList)) {
		foreach ($replaceList as $table => $replace) {
			$key = array_search($table, $aFromTemp);
			$aFromTemp[$key] = $replace;
		}
	}	

	if (newSizeOf($aFromTemp) == 0 )
		$aFromTemp[] = $sTable;
	if (count($oObjet->inherited_list) > 0){
		foreach($oObjet->inherited_list as $cls)
			$aFromTemp[] = $cls;
	} 
	// dédoublonnage
	// petits soucis avec array_unique....
	//pre_dump($aFromTemp);
	//$aFrom = dedoublonne($aFromTemp);
	$aFrom =$aFromTemp;
	 
	//$aWhere = dedoublonne($aWhereTemp);
	$aWhere = $aWhereTemp;
	
	//viewArray($aFrom, 'FROM '.$sTable);
	//viewArray($aWhere, 'WHERE '.$sTable);
	if(count($oObjet->inherited_list) > 0){
		foreach($oObjet->inherited_list as $cls){
			$tempOClass = new $cls();
			$aWhere[] = $oObjet->getClasse().'.'.$oObjet->getFieldPK()." = ".$tempOClass->getClasse().'.'.$tempOClass->getFieldPK();
		}
	}
	
	//--------------------
	// clause FROM
	$sRequete.= " FROM ".implode(', ', $aFrom);

	//--------------------
	// clause WHERE
	if (newSizeOf($aWhere)>0)
		$sRequete.= " WHERE ".implode(' AND ', $aWhere);
	
	//--------------------
	// clause ORDER BY
	if (newSizeOf($aOrderBy) > 0)  $sRequete.= " ORDER BY ";
	for ($p=0; $p<newSizeOf($aOrderBy); $p++) {

		$sRequete.=$aOrderBy[$p]." ".$aSensOrderBy[$p];
		if ($p != newSizeOf($aOrderBy)-1) $sRequete.=", ";
	}
	//--------------------
	
	return($sRequete);
}




//------------------------------------------------
// construit une requete avec des critères
//------------------------------------------------

function dbMakeRequeteWithCriteres2_OR($sObjet, $aRecherche, $aOrderBy,  $aSensOrderBy)
{
	$oObjet = new $sObjet;

	$sTable = $oObjet->getTable();
	
	$sRequete = "";
	
	$aFrom = array();
	$aWhere = array();
	$aFromTemp = array();
	$aWhereTemp = array();

	//--------------------
	// analyse des critères de recherche
	
	$sClauseWhere = "";
	for ($a=0; $a<newSizeOf($aRecherche); $a++) {

		// élément de recherche
		$oRech = $aRecherche[$a];

		// si une valeur est recherchée
		if (($oRech->getValeurRecherche() != "") && ($oRech->getValeurRecherche() != "-1")) {

			// composition de la clause FROM (tables de jointure)
			$aFromThis = explode(";", $oRech->getTableBD());
			
			for($m=0; $m<newSizeOf($aFromThis); $m++) {
				if ($aFromThis[$m] != "") array_push($aFromTemp, $aFromThis[$m]);
			}
			
			// composition de la clause WHERE (jointures)
			if (newSizeOf($oRech->getJointureBD() != 0)) $aJointure = explode(";", $oRech->getJointureBD());
			for($m=0; $m<newSizeOf($aJointure); $m++) {
				if ($aJointure[$m] != "") array_push($aWhereTemp, $aJointure[$m]);
			}
			
			// valeur du critère de recherche
			// dans le cas des pures jointures pas de valeur recherchée
			if (!$oRech->getPureJointure()) {
				$sWhere = " ".$oRech->getNomBD()." = ";
			
				if ($oRech->getTypeBD() == "text") $sWhere.= "'".$oRech->getValeurRecherche()."'";
				else $sWhere.= $oRech->getValeurRecherche();
					
				// clause Where
				$aWhereTemp[] = $sWhere;
			}
			
			
		}
	}
	
	 
	//--------------------
	// ajout de la table de l'objet résultat dans la clause FROM
	 
	//pre_dump($aFromTemp);
	 
	$deja_present = false;
 
	foreach ($aFromTemp as $fromtemp) {
		if (preg_match ("/".$sTable."/i", $fromtemp) && preg_match ("/LEFT/i", $fromtemp)) 
			$deja_present = true; 
	}
	
	if ($deja_present) { 
		foreach ($aFromTemp as $k => $fromtemp) {
			if ( $fromtemp == $sTable ) 
				array_splice($aFromTemp , $k, 1);
		}
	}
	else {
		$aFromTemp[] = $sTable;
	} 
	 
	if (newSizeOf($aFromTemp) == 0 ) $aFromTemp[] = $sTable;
	if(count($oObjet->inherited_list) > 0){
		foreach($oObjet->inherited_list as $cls){
			$aFromTemp[] = $cls;
		}
	} 
	// dédoublonnage
	// petits soucis avec array_unique....
	//pre_dump($aFromTemp);
	$aFrom = dedoublonne($aFromTemp);
	 
	$aWhere = dedoublonne($aWhereTemp);
	
	if(count($oObjet->inherited_list) > 0){
		foreach($oObjet->inherited_list as $cls){
			$tempOClass = new $cls();
			$aWhere[] = $oObjet->getClasse().'.'.$oObjet->getFieldPK()." = ".$tempOClass->getClasse().'.'.$tempOClass->getFieldPK();
		}
	}
	
	//--------------------
	// clause FROM
	$sRequete.= " FROM ";
	for ($p=0; $p<newSizeOf($aFrom); $p++) {

		$sRequete.= " ".$aFrom[$p];
		if ($p != newSizeOf($aFrom)-1) $sRequete.= ", ";
	}
	//--------------------

	//--------------------
	// clause WHERE
	if (newSizeOf($aWhere)>0) $sRequete.= " WHERE ";
	// valeurs de critères
	for ($p=0; $p<newSizeOf($aWhere); $p++) {

		$sRequete.= " ".$aWhere[$p];
		if ($p != newSizeOf($aWhere)-1) $sRequete.= " OR ";
	}
	//--------------------
	
	//--------------------
	// clause ORDER BY
	if (newSizeOf($aOrderBy) > 0)  $sRequete.= " ORDER BY ";
	for ($p=0; $p<newSizeOf($aOrderBy); $p++) {

		$sRequete.=$aOrderBy[$p]." ".$aSensOrderBy[$p];
		if ($p != newSizeOf($aOrderBy)-1) $sRequete.=", ";
	}
	//--------------------
	
	return($sRequete);
}

