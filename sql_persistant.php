<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

// sponthus 31/05/2005
// fonctions sql persistantes

// sponthus 02/07/2005
// ajout de fonctions d'objets persistantes
// plus besoin d'écrire des insert, update, delete et select generiques

// a_voir sponthus a terme mettre ce fichier avec dbChamp dans /include/db/

/*

function getObjetListeChamps($oObjet){
function dbMakeObjet($sObjet, $row)
function dbGetObjectFromPK($sObjet, $id)
function dbGetObjectsFromFieldValue3($sObjet, $aGetterWhere, $aOperands, $aValeurChamp, $aGetterOrderBy, $aGetterSensOrderBy) {
function dbGetObjectsFromFieldValue($sObjet, $aGetterWhere, $aValeurChamp, $sGetterOrderBy)
function dbGetObjectsFromFieldValue2($sObjet, $aGetterWhere, $aValeurChamp, $aGetterOrderBy, $aGetterSensOrderBy)
function dbGetSQLFromFieldValue2($sObjet, $aGetterWhere, $aValeurChamp, $sGetterOrderBy)
function dbGetSQLFromFieldValue($sObjet, $aGetterWhere, $aValeurChamp, $sGetterOrderBy)
function dbGetSQLFromFieldValue3($sObjet, $aGetterWhere, $aOperands, $aValeurChamp, $aGetterOrderBy, $aGetterSensOrderBy) {
function dbGetObjects($oObjet)
function dbMakeInsertReq($oObjet)
function dbInsert($oObjet)
function dbInsertWithAutoKey($oObjet)
function dbUpdate($oObjet)
function dbUpdateStatut($oObjet)
function dbArchiveOrphans($oObjet, $bDelete=false, $bRecurse=false) {
function dbDelete($oObjet, $bDeleteOrphans=false, $bRecurse=false)
function dbDeleteAll($sObjet)
function dbDeleteId($sTable, $sFieldId, $eValueId)
function dbDeleteWhere($sTable, $aValues)
function getNextVal($sTable, $sField)

function getObjectById($sOBjet, $id)
function isObjectById$sOBjet, $id)

function dbSauve($oObjet)
function dbSauveAsNew($oObjet)

function getCount($sTable, $sFieldCount, $sFieldWhere, $eValueWhere, $sTypeWhere="NUMBER")
function getCount2($oObjet, $sFieldWhere, $eValueWhere, $sTypeWhere="NUMBER")
function getCount_where($sObjet, $aFieldWhere, $aValueWhere, $aTypeWhere)

function getSearchField($sTable, $sFields, $sFieldWhere, $eValueWhere, $sTypeWhere="NUMBER")
function getSearchFields($sTable, $aFields, $aFieldWhere, $aValueWhere, $aTypeWhere )

function to_dbstring($schaine)
function to_dbquote($schaine) { 

function dedoublonne($aTab)
function dbConcat($str1, $str2){
function istable($tablename, $doNotTrigger=false) {

function dbExecuteQueryQuiet($sql)

*/


//------------------------------------------------
// Prend une ligne recuperee d'un resultset 
// et construit et renvoie l'Objet correspondant
//------------------------------------------------


function getObjetListeChamps($oObjet){

	if (	(isset($oObjet->XML)||isset($oObjet->XML_inherited))	&&	!isset($oObjet->oldstyleclass)){
		$aListeChamps = getListeChampsForObject($oObjet);
	}
	else{	
		$aListeChamps = $oObjet->getListeChamps();
	}
	return $aListeChamps;
}

function dbMakeObjet($sObjet, $row) {
	//echo "<br />objet cree= ".$sObjet;
	$oObjet = new $sObjet;

	$aListeChamps=getObjetListeChamps($oObjet);
	
	/*if (	(isset($oObjet->XML)||isset($oObjet->XML_inherited))	&&	!isset($oObjet->oldstyleclass)){
		$aListeChamps = getListeChampsForObject($oObjet);
	}
	else{		
		$aListeChamps = $oObjet->getListeChamps();
	}*/
	
	if (isset($oObjet->XML_inherited) && !is_null($oObjet->XML_inherited)) {
		foreach ($oObjet->inherited_list as $cls) {
			$oCls = new $cls();
			$tabs = $oCls->getListeChamps();
			foreach ($tabs as $key=>$val)
				array_push($aListeChamps, $val);				
		}
	}
	
	// la liste des champs
	for ($i=0; $i<sizeof($aListeChamps); $i++) {	
		$sSetter = $aListeChamps[$i]->Setter;
		if (method_exists($oObjet, $sSetter)){
			$sType = $aListeChamps[$i]->TypeBD;
			
			if ($sType == "date_formatee"){
				$oObjet->$sSetter(from_dbdate($row->fields[n($aListeChamps[$i]->getNomBD())]));
			}
			elseif ($sType == "decimal"){
				$oObjet->$sSetter(str_replace(',', '.', $row->fields[n($aListeChamps[$i]->getNomBD())]));
			}
			elseif ($sType == "date_formatee_timestamp"){
				$oObjet->$sSetter(from_dbdate_TIMESTAMP($row->fields[n($aListeChamps[$i]->getNomBD())]));
			}
			elseif ($sType == "date_formatee_timestamp_with_zone"){
				$oObjet->$sSetter(from_dbdate_TIMESTAMP_WITH_TIME_ZONE($row->fields[n($aListeChamps[$i]->getNomBD())]));
			}
			elseif (isset($row->fields[n($aListeChamps[$i]->getNomBD())])){
				$oObjet->$sSetter($row->fields[n($aListeChamps[$i]->getNomBD())]);
			}
		}
	}
				
	//pre_dump($oObjet);
	return($oObjet);
}

//------------------------------------------------
// récupère tous les éléments de mon Objet
// une clé primaire est un entier
//------------------------------------------------

function dbGetObjectFromPK($sObjet, $id, $quiet=true) {
	global $db;

	$oObjet = new $sObjet;
	$sTable = $oObjet->getTable();
	$sPK = $oObjet->getFieldPK();
	
	$sql = "SELECT * FROM $sTable where $sPK=".$id; 
	//print("<br>$sql");
	$rs = $db->Execute($sql);

	if ($rs && !$rs->EOF) {
		$oObjet = dbMakeObjet($sObjet, $rs);
	}
	else {
		if(DEF_MODE_DEBUG==true) {
			echo "dbGetObjectFromPK(".$sObjet.", .".$id.")";
			echo "<br /><strong>$sql</strong>";
		}
		if($quiet==false) {
			echo "<br />Erreur interne de programme";
		}
		
			error_log("----- DEBUT ERREUR ------------------------");
			error_log("Erreur dans ".__FILE__." Ligne ".__LINE__." Fonction ".__FUNCTION__);
			error_log('erreur lors de l\'execution de la requete');
			error_log($sql);
			error_log($db->ErrorMsg());
			error_log("----- FIN ERREUR ------------------------");

		return false;
	}
	$rs->Close();
	return($oObjet);
}


//------------------------------------------------
// recupere une liste sous forme d'un tableau de mon Objet de tous mes Objets dont la valeur des 
// champs aGetterWhere est egale a aValeurChamp, tries par sGetterOrderBy
//------------------------------------------------

function dbGetObjectsFromFieldValue($sObjet, $aGetterWhere, $aValeurChamp, $sGetterOrderBy=NULL) {

	if (trim($sGetterOrderBy) == '')
		$sGetterOrderBy = NULL;
		
		
	//sGetterOrderBy doit $etre un tableau
	if(!is_null($sGetterOrderBy) && !is_array($sGetterOrderBy)){
		$sGetterOrderBy = array($sGetterOrderBy);
	}else{
		$sGetterOrderBy = array();
	}
	
	$sRequete = dbGetSQLFromFieldValue3($sObjet, $aGetterWhere, null, $aValeurChamp, $sGetterOrderBy, null);
	//print("<br/>".$sRequete);

	return dbGetObjectsFromRequete($sObjet, $sRequete);
}


//------------------------------------------------
// recupere une liste sous forme d'un tableau de mon Objet de tous mes Objets dont la valeur des 
// champs aGetterWhere est egale a aValeurChamp, tries par sGetterOrderBy
// a terme cette fonction doit remplacer la premiere
// passage des order by par tableau et non plus par chaine de caractère
//------------------------------------------------

function dbGetObjectsFromFieldValue2($sObjet, $aGetterWhere, $aValeurChamp, $aGetterOrderBy, $aGetterSensOrderBy) {

	$sRequete = dbGetSQLFromFieldValue3($sObjet, $aGetterWhere, null, $aValeurChamp, $aGetterOrderBy, $aGetterSensOrderBy);
	//print("<br/>".$sRequete);

	return dbGetObjectsFromRequete($sObjet, $sRequete);
}


//------------------------------------------------
// recupere une liste sous forme d'un tableau de mon Objet de tous mes Objets dont la valeur des 
// champs aGetterWhere est egale a aValeurChamp, tries par sGetterOrderBy
// a terme cette fonction doit remplacer la premiere
// passage des order by par tableau et non plus par chaine de caractère
//------------------------------------------------

function dbGetObjectsFromFieldValue3($sObjet, $aGetterWhere, $aOperands, $aValeurChamp, $aGetterOrderBy, $aGetterSensOrderBy) {

	$sRequete = dbGetSQLFromFieldValue3($sObjet, $aGetterWhere, $aOperands, $aValeurChamp, $aGetterOrderBy, $aGetterSensOrderBy);
	//print("<br/>".$sRequete);

	return dbGetObjectsFromRequete($sObjet, $sRequete);
}


//------------------------------------------------
// recupere une requete SQL de tous mes Objets dont la valeur des 
// champs aGetterWhere est egale a aValeurChamp, tries par sGetterOrderBy
//------------------------------------------------

function dbGetSQLFromFieldValue($sObjet, $aGetterWhere, $aValeurChamp, $sGetterOrderBy) {
	if (trim($sGetterOrderBy) == '')
		$sGetterOrderBy = NULL;

	//sGetterOrderBy doit $etre un tableau
	if(!is_array($sGetterOrderBy)){
		$sGetterOrderBy = array($sGetterOrderBy);
	}
	return dbGetSQLFromFieldValue3($sObjet, $aGetterWhere, null, $aValeurChamp, $sGetterOrderBy, null);
}


//------------------------------------------------
// recupere une requete SQL de tous mes Objets dont la valeur des 
// champs aGetterWhere est egale a aValeurChamp, tries par sGetterOrderBy
// a terme cette fonction doit remplacer la premiere
// passage des order by par tableau et non plus par chaine de caractère
//------------------------------------------------

function dbGetSQLFromFieldValue2($sObjet, $aGetterWhere, $aValeurChamp, $aGetterOrderBy, $aGetterSensOrderBy) {

	return dbGetSQLFromFieldValue3($sObjet, $aGetterWhere, null, $aValeurChamp, $aGetterOrderBy, $aGetterSensOrderBy);
}


//------------------------------------------------
// recupere une requete SQL de tous mes Objets dont la valeur des 
// champs aGetterWhere sont testés au moyen des opérandes $aOperands face aux valeurs aValeurChamp, 
// tries par sGetterOrderBy selon le sens $aGetterSensOrderBy
// > operandes : equals (=), differ (!=), lower, (<), lower_equals (<=), higher (>), higher_equals (>=), in et not_in.
// a terme cette fonction doit remplacer la seconde (qui devait déjà remplacer la premiere)
// passage des order by par tableau et non plus par chaine de caractère
//------------------------------------------------

function dbGetSQLFromFieldValue3($sObjet, $aGetterWhere, $aOperands, $aValeurChamp, $aGetterOrderBy, $aGetterSensOrderBy) {
	$oObjet = new $sObjet();
	$sTable = $oObjet->getTable();
	$aMasterFields = $oObjet->getListeChamps();
	
	// set master minimal properties
	$pile = Array(	$sTable	=> Array(	'type'	=> 'master',
						'props'	=> Array() ) );
	
	$error = false;
	// check for any inheritance
	if (isset($oObjet->inherited_list)&& (sizeof($oObjet->inherited_list) > 0)){
		$inheriting = $oObjet->inherited_list[0];
		$oInheriting = new $inheriting();
		$aInheritingFields = $oInheriting->getListeChamps();
		// set minimal inheriting properties
		$pile[$inheriting]['type'] = 'inheriting';
		$pile[$inheriting]['props'] = Array();
		
		// populate master and inheriting conditions according to each field owner
		for ($i=0; $i<sizeof($aGetterWhere); $i++) {
			$found_master = false;
			for ($j=0; $j<sizeof($aMasterFields); $j++) {
				if ($aMasterFields[$j]->getGetter() == $aGetterWhere[$i]) {
					// populate with this master field
					$found_master = true;
					$pile[$sTable]['props']['fields'][] = strtolower(substr($aMasterFields[$j]->getNomBD(),0,1)).substr($aMasterFields[$j]->getNomBD(),1);
					if (!is_null($aOperands[$i]) && !empty($aOperands[$i]))
						$pile[$sTable]['props']['operands'][] = $aOperands[$i];
					else	$pile[$sTable]['props']['operands'][] = 'equals';
					if ($aMasterFields[$j]->getTypeBD() == "text")
						$pile[$sTable]['props']['values'][] = to_dbstring($aValeurChamp[$i]);
					elseif ($aMasterFields[$j]->getTypeBD() == "date_formatee")
						$pile[$sTable]['props']['values'][] = to_dbdate($aValeurChamp[$i]);
					else	$pile[$sTable]['props']['values'][] = $aValeurChamp[$i];
					break;
				}
			}
			if (!$found_master) {
				$found_inheriting = false;
				for ($j=0; $j<sizeof($aInheritingFields); $j++) {
					if ($aInheritingFields[$j]->getGetter() == $aGetterWhere[$i]) {
						// populate with this inheriting field
						$found_inheriting = true;
						$pile[$inheriting]['props']['fields'][] = strtolower(substr($aInheritingFields[$j]->getNomBD(),0,1)).substr($aInheritingFields[$j]->getNomBD(),1);
						if (!is_null($aOperands[$i]) && !empty($aOperands[$i]))
							$pile[$inheriting]['props']['operands'][] = $aOperands[$i];
						else	$pile[$inheriting]['props']['operands'][] = 'equals';
						if ($aInheritingFields[$j]->getTypeBD() == "text")
							$pile[$inheriting]['props']['values'][] = to_dbstring($aValeurChamp[$i]);
						elseif ($aInheritingFields[$j]->getTypeBD() == "date_formatee")
							$pile[$inheriting]['props']['values'][] = to_dbdate($aValeurChamp[$i]);
						else	$pile[$inheriting]['props']['values'][] = $aValeurChamp[$i];
						break;
					}
				}
			}
			if (!$found_master && !$found_inheriting) {
				echo "<br/><br/>ERROR : could not find any corresponding field for getter <b>{$aGetterWhere[$i]}</b> neither in <b>{$sTable}</b> nor in inheriting <b>{$inheriting}</b><br/><br/>";
				$error = true;
			}
		}
		for ($i=0; $i<sizeof($aGetterOrderBy); $i++) {
			// populate master and inheriting ordering according to each field owner
			$found_master = false;
			for ($j=0; $j<sizeof($aMasterFields); $j++) {
				if ($aMasterFields[$j]->getGetter() == $aGetterOrderBy[$i]) {
					// populate with this master field
					$found_master = true;
					$pile[$sTable]['props']['orders'][] = strtolower(substr($aMasterFields[$j]->getNomBD(),0,1)).substr($aMasterFields[$j]->getNomBD(),1);
					$pile[$sTable]['props']['directions'][] = $aGetterSensOrderBy[$i];
					break;
				}
			}
			if (!$found_master) {
				$found_inheriting = false;
				for ($j=0; $j<sizeof($aInheritingFields); $j++) {
					if ($aInheritingFields[$j]->getGetter() == $aGetterOrderBy[$i]) {
						// populate with this inheriting field
						$found_inheriting = true;
						$pile[$inheriting]['props']['orders'][] = strtolower(substr($aInheritingFields[$j]->getNomBD(),0,1)).substr($aInheritingFields[$j]->getNomBD(),1);
						$pile[$inheriting]['props']['directions'][] = $aGetterSensOrderBy[$i];
						break;
					}
				}
			}
			if (!$found_master && !$found_inheriting) {
				echo "<br/><br/>ERROR : could not find any corresponding field for order by <b>{$aGetterWhere[$i]}</b> neither in <b>{$sTable}</b> nor in inheriting <b>{$inheriting}</b><br/><br/>";
				$error = true;
			}
		}
	} else {
		// no inheritance
		for ($i=0; $i<sizeof($aGetterWhere); $i++) {
			$found_master = false;
			for ($j=0; $j<sizeof($aMasterFields); $j++) {
				if ($aMasterFields[$j]->getGetter() == $aGetterWhere[$i]) {
					// populate with this master field
					$found_master = true;
					$pile[$sTable]['props']['fields'][] = strtolower(substr($aMasterFields[$j]->getNomBD(),0,1)).substr($aMasterFields[$j]->getNomBD(),1);
					if (!is_null($aOperands[$i]) && !empty($aOperands[$i]))
						$pile[$sTable]['props']['operands'][] = $aOperands[$i];
					else	$pile[$sTable]['props']['operands'][] = 'equals';
					if ($aMasterFields[$j]->getTypeBD() == "text")
						$pile[$sTable]['props']['values'][] = to_dbstring($aValeurChamp[$i]);
					elseif ($aMasterFields[$j]->getTypeBD() == "date_formatee")
						$pile[$sTable]['props']['values'][] = to_dbdate($aValeurChamp[$i]);
					else	$pile[$sTable]['props']['values'][] = $aValeurChamp[$i];
					break;
				}
			}
			if (!$found_master) {
				echo "<br/><br/>ERROR : could not find any corresponding field for getter <b>{$aGetterWhere[$i]}</b> in <b>{$sTable}</b><br/><br/>";
				$error = true;
			}
		}
		for ($i=0; $i<sizeof($aGetterOrderBy); $i++) {
			$found_master = false;
			for ($j=0; $j<sizeof($aMasterFields); $j++) {
				if ($aMasterFields[$j]->getGetter() == $aGetterOrderBy[$i]) {
					// populate with this master field
					$found_master = true;
					$pile[$sTable]['props']['orders'][] = strtolower(substr($aMasterFields[$j]->getNomBD(),0,1)).substr($aMasterFields[$j]->getNomBD(),1);
					$pile[$sTable]['props']['directions'][] = $aGetterSensOrderBy[$i];
					break;
				}
			}
			if (!$found_master) {
				echo "<br/><br/>ERROR : could not find any corresponding field for order by <b>{$aGetterWhere[$i]}</b> in <b>{$sTable}</b><br/><br/>";
				$error = true;
			}
		}
	}
	// viewArray($pile);

	if (!$error) {
		$sRequete = "SELECT	*\n";
	
		foreach ($pile as $table => $usage) {
			// field filters
			if ($usage['type'] == 'master')
				$sRequete .= "FROM	{$table}";
			elseif ($usage['type'] == 'inheriting')
				$sRequete .= ",\n		{$table}";
		}
		$cnt = 0;
		
		
		
		foreach ($pile as $table => $usage) {
			if (sizeof($usage['props']['fields']) > 0) {
				if ($cnt > 0)
					$sRequete .= "\nAND	";
				else
					$sRequete .= "\nWHERE	";
				$cnt++;
			}
			// tous les champs clause WHERE
			for ($a=0; $a<sizeof($usage['props']['fields']); $a++) {
			
			
				// print("<br/>field=>{$sTable}.".$usage['props']['fields'][$a]);
				// print("<br/>sOperand=>".$usage['props']['operands'][$a]);
				// print("<br/>sValeurChamp=>".$usage['props']['values'][$a]);
				
				
		
				// handle condition with operand
				if ($a > 0)
					$sRequete .= "\nAND	";
				if ($usage['type'] == 'master')
					$sRequete .= "{$sTable}.".$usage['props']['fields'][$a];
				elseif ($usage['type'] == 'inheriting')
					$sRequete .= "{$inheriting}.".$usage['props']['fields'][$a];
					
				switch ($usage['props']['operands'][$a]) {
					case 'equals' : 		$sRequete .= " = ";
								break;
					case 'differ' : 		$sRequete .= " != ";
								break;
					case 'lower' : 		$sRequete .= " < ";
								break;
					case 'lower_equals' : 	$sRequete .= " <= ";
								break;
					case 'higher' : 		$sRequete .= " > ";
								break;
					case 'higher_equals' : 	$sRequete .= " >= ";
								break;
					case 'in'	: 	$sRequete .= " IN ";
								break;
					case 'not_in'	: 	$sRequete .= " NOT IN ";
								break;
				}	
				
				if (isFieldTranslate($oObjet, $usage['props']['fields'][$a])){
					// echo 'cas translate';
					
					$translator =& TslManager::getInstance();
					$usage['props']['values'][$a] = $translator->getTextId($usage['props']['values'][$a], $_SESSION['id_langue'], NULL);
				
				}	
				
				if ($usage['props']['operands'][$a] == 'in' || $usage['props']['operands'][$a] == 'not_in')
					$sRequete .= "(".$usage['props']['values'][$a].")";
				else{
                                    
                                    if(preg_match('#\'(.*)\'#', $usage['props']['values'][$a])){
					$sRequete .= $usage['props']['values'][$a];
                                        } else {
                                            $sRequete .= "'".$usage['props']['values'][$a]."'";
                                        }
                                    
					//$sRequete .= $usage['props']['values'][$a];
				}
			}
			if ($usage['type'] == 'inheriting')
				// add inheriting relationship
				$sRequete .= "\nAND	{$sTable}.".$oObjet->getFieldPK()." = {$inheriting}.".$oInheriting->getFieldPK();
		}
		foreach ($pile as $table => $usage) {
			// order by
			if (isset($usage['props']['orders'])&& (sizeof($usage['props']['orders']) > 0)){
				$sRequete .= "\nORDER BY	";
				for ($m=0; $m<sizeof($usage['props']['orders']); $m++) {
					if ($m> 0)
						$sRequete .= ", ";
					if (!is_null($usage['props']['directions']) && !empty($usage['props']['directions'][$m]))
						$sRequete .= $usage['props']['orders'][$m]." ".$usage['props']['directions'][$m];
					else	$sRequete .= $usage['props']['orders'][$m];
				}
			}
		}
		$sRequete .= ";";
		// print("<br/>".$sRequete);

		return $sRequete;

	} else	return null;
}


//------------------------------------------------
// recupere une liste sous forme d'un tableau de mon Objet de tous mes Objets
//------------------------------------------------

function dbGetObjects($sObjet) {
	$oObjet = new $sObjet;

	$sTable = $oObjet->getTable();
	$sPK = $oObjet->getFieldPK();	

	$tables = "";
	$where = "";
	if (is_array($oObjet->inherited)) {
		if (count($oObjet->inherited) > 0) {
			foreach ($oObjet->inherited as $key=>$class) {
				if (is_object($class)) {
					$tables = ", ".$class->getClasse();
					if($where == "") {
						$sSetPK2 = $class->getFieldPK();
						$where = $sPK."=".$sSetPK2;
					}
				}
			}
		}
	}	
	if($where != "")
		$where = "WHERE ".$where;
	$sRequete = "SELECT * FROM $sTable".$tables." ".$where." ORDER BY $sPK"; 
  	//print("<!-- ".$sRequete." -->");
	
	return(dbGetObjectsFromRequete($sObjet, $sRequete));
}


//------------------------------------------------
// make de la requete d'insert d'un objet
//------------------------------------------------

function dbMakeInsertReq($oObjet) {
	global $db;
	$result = null;

	$sTable = $oObjet->getTable();
	$aListeChamps=getObjetListeChamps($oObjet);
	/*
	if (	(isset($oObjet->XML)||isset($oObjet->XML_inherited))	&&	!isset($oObjet->oldstyleclass)){
		$aListeChamps = getListeChampsForObject($oObjet);
	}
	else{		
		$aListeChamps = $oObjet->getListeChamps();
	}*/

        //pre_dump($aListeChamps); die();
        
        
	$sRequete = "INSERT INTO $sTable (";
        
        foreach($aListeChamps as $k => $oChamps){
            //pre_dump($oChamps->NomBD);
            
            if($k != 0 ) $sRequete .=  ", ";
            $sRequete .=  $oChamps->NomBD;
        }
        
        $sRequete .= ") VALUES (";

	for ($i=0; $i<sizeof($aListeChamps); $i++) {
		$sType=$aListeChamps[$i]->getTypeBD();
		$sChamp=$aListeChamps[$i]->getGetter();
                //die($sChamp);
		if ($sType == "entier")
			$sRequete.= "'".$oObjet->$sChamp()."', ";
		elseif ($sType == "decimal")
			$sRequete.= str_replace(',', '.', "'".$oObjet->$sChamp())."', ";
		elseif (($sType == "text") || ($sType == "texte"))
			$sRequete.=to_dbstring($oObjet->$sChamp()).", ";
		elseif ($sType == "date") {
			// a_voir attention ici date oracle
			if (DEF_BDD == "ORACLE")
				$sRequete.="to_date('".$oObjet->$sChamp()."', 'dd/mm/yyyy'"."), ";
			elseif (DEF_BDD == "POSTGRES")
				$sRequete.=$oObjet->$sChamp()."', ";
			else	$sRequete.=to_dbdate($oObjet->$sChamp()).", ";
		} elseif ($sType == "date_formatee" || $sType == "date_formatee_timestamp_with_zone") {
			// nouveau type de date : doit rempacer le premier
			$sRequete.=to_dbdate($oObjet->$sChamp()).", ";
		} elseif ($sType == "date_formatee_timestamp"){
			// timestamp
			if ($oObjet->$sChamp() != 'NULL')
				$sRequete .= to_dbdate_TIMESTAMP($oObjet->$sChamp()).", ";
			else	$sRequete .= "NULL, ";
		}
		// todo gérer un type ENUM autonome injectant la valeur par défaut si celle fournie n'est pas valide
		if ($i == (sizeof($aListeChamps)-1))
			$sRequete=substr($sRequete, 0, -2);
	}
	
	$sRequete.= ")";

	$sql = $sRequete;

	//print("FIELD LIST <br/>$sql");	
	//error_log("FIELD LIST <br/>$sql");

	return $sql;

}

//------------------------------------------------
// insert d'un objet
//------------------------------------------------

function dbInsert($oObjet) {
	global $db;
   
	$sql = dbMakeInsertReq($oObjet);
	//print("<br>$sql");
	$rs = $db->Execute($sql);

	if ($rs != false) {
		$sTable = $oObjet->getTable();
		$sPK = $oObjet->getGetterPK();
		$result = $oObjet->$sPK();
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo '<br>dbInsert('.$oObjet->getTable().')';
			echo '<br><strong>'.$sql.'</strong>';
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

	if (is_array($oObjet->inherited)) {
		if (count($oObjet->inherited) > 0) {
			foreach ($oObjet->inherited as $key=>$class) {
				if (is_object($class)) {
					$sSetPK2 = $class->getSetterPK();
					$class->$sSetPK2($result);
					dbInsert($class);
				}
			}
		}
	}
	return $result;
}


//------------------------------------------------
// insert avec calcul automatique de la clé primaire
//------------------------------------------------

function dbInsertWithAutoKey($oObjet) {
	$sSetPK = $oObjet->getSetterPK();
	$sClasse = $oObjet->getClasse();
	$sTable = $oObjet->getTable();
	$sPK = $oObjet->getFieldPK();
	
 	$eClePlus1 = getNextVal($sTable, $sPK);
	
	$oObjet->$sSetPK($eClePlus1);	

        //pre_dump($oObjet);
        
        
	$result = dbInsert($oObjet);
	
	if ($result==false){ // seconde tentative
		$eClePlus1 = getNextVal($sTable, $sPK);	
		$oObjet->$sSetPK($eClePlus1);	
		$result = dbInsert($oObjet);
	}
	//pre_dump($result);
        //die();
	// retoune l'id de l'enregistrement créé
	return($result);
}

//------------------------------------------------
// update d'un enregistrement
//------------------------------------------------

function dbUpdate($oObjet) {
	global $db;
	$result = null;

	$sTable = $oObjet->getTable();
	
	$aListeChamps=getObjetListeChamps($oObjet);
	/*
	if (	(isset($oObjet->XML)||isset($oObjet->XML_inherited))	&&	!isset($oObjet->oldstyleclass)){
		$aListeChamps = getListeChampsForObject($oObjet);
	}
	else{		
		$aListeChamps = $oObjet->getListeChamps();
	}*/

	$sGetterPK = $oObjet->getGetterPK();
	
	$sRequete = "UPDATE ".$sTable." SET ";

	for($i=0; $i<sizeof($aListeChamps); $i++) {
		$sType=$aListeChamps[$i]->getTypeBD();
		$leNomBD=$aListeChamps[$i]->getNomBD();
		$sChamp=$aListeChamps[$i]->getGetter();

		if (strtolower($leNomBD) <> strtolower($oObjet->getFieldPK())) {
			//echo $sType." ".$oObjet->$sChamp()."<br>";
			
			if ($sType == "entier")
				$sRequete.="$leNomBD='".$oObjet->$sChamp()."', ";
			elseif ($sType == "decimal")
				$sRequete.="$leNomBD='".str_replace(',', '.', $oObjet->$sChamp())."', ";
			elseif ($sType == "text" || $sType == "texte") 
				$sRequete.="$leNomBD=".to_dbstring($oObjet->$sChamp()).", ";
			elseif ($sType == "date") {
				if (DEF_BDD == "ORACLE")
					$sRequete.="$leNomBD=to_date('".$oObjet->$sChamp()."', 'dd/mm/yyyy'"."), ";
				elseif (DEF_BDD == "POSTGRES")
					$sRequete.="$leNomBD='".$oObjet->$sChamp()."', ";
				else	$sRequete.="$leNomBD=".to_dbdate($oObjet->$sChamp()).", ";
			} elseif ($sType == "date_formatee")
				// a_voir attention ici date oracle
				$sRequete.="$leNomBD=".to_dbdate($oObjet->$sChamp()).", ";
			elseif ($sType == "date_formatee_timestamp")
				// timestamp
				$sRequete.="$leNomBD=".to_dbdate_TIMESTAMP($oObjet->$sChamp()).", ";
			elseif ($sType == "date_formatee_timestamp_with_zone")
				// a_voir attention ici date oracle
				$sRequete.="$leNomBD=".to_dbdatetime($oObjet->$sChamp()).", ";	
		}
		if ($i == (sizeof($aListeChamps)-1))
			$sRequete=substr($sRequete, 0, -2);
	}

	$sRequete .= " WHERE ".$oObjet->getFieldPK()."=".$oObjet->$sGetterPK();

	//print("<br>".$sRequete);
	//exit();
	$sql = $sRequete;
	$rs = $db->Execute($sql);

	if ($rs != false) {
		$result = $oObjet->$sGetterPK();
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />dbUpdate(".get_class($oObjet).")";
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
	
	if (isset($oObjet->inherited)&&is_array($oObjet->inherited)){
		if (count($oObjet->inherited) > 0) {
			foreach ($oObjet->inherited as $key=>$class) {
				if(is_object($class))
					dbUpdate($class);
			}
		}
	}	
	return $result;
}


//------------------------------------------------
// maj d'un statut
//------------------------------------------------

function dbUpdateStatut($oObjet) {
	if ($oObjet->getFieldStatut() == 'none'){
		return false;
	}
	
	//global $db;
	$result = null;

	//$sTable = $oObjet->getTable();
	//$sGetterPK = $oObjet->getGetterPK();
	//$sFieldStatut = $oObjet->getFieldStatut();
	//$sGetterStatut = $oObjet->getGetterStatut();	
	
	// maj date
	if (method_exists($oObjet, 'set_datem')){
		$oObjet->set_datem(from_dbdate_TIMESTAMP(date('Y-m-d H:i:s')));
	}
	elseif (method_exists($oObjet, 'set_mdate')){
		$oObjet->set_mdate(from_dbdate_TIMESTAMP(date('Y-m-d H:i:s')));
	}	
	
	$result = dbUpdate($oObjet);
	/*
	$sRequete = " UPDATE $sTable SET ";
	$sRequete.= " $sFieldStatut=".$oObjet->$sGetterStatut();
	$sRequete.= " WHERE ".$oObjet->getFieldPK()."=".$oObjet->$sGetterPK();

	//print("<br>".$sRequete);
	//exit();

	$sql = $sRequete;
	$rs = $db->Execute($sql);

	if ($rs != false) {
		$result = true;
	} else {
		if (DEF_MODE_DEBUG==true) {
			echo "<br />dbUpdateStatut($oObjet)";
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
	
	*/

	return $result;
}

//------------------------------------------------
// archive orphans
//------------------------------------------------

function dbArchiveOrphans($oObjet, $bDelete=false, $bRecurse=false) {
	global $stack;
	
	$res = true;
	$id = $oObjet->get_id();
	$classeName = $oObjet->getClasse();
	//echo "orphans from ".$classeName."<br />";
	$aClasse = dbGetObjectsFromFieldValue3('classe', array('get_statut'), array('equals'), array(DEF_ID_STATUT_LIGNE), NULL, NULL);
	$aAllClassesStack = array();	
	if ((count($aClasse) > 0)&&($aClasse!=false)){
		foreach($aClasse as $cKey => $oClasse){				
			$aAllClassesStack[] = $oClasse->get_nom();	
		}
	}
	foreach($_SESSION["cms_classes"] as $cKey => $sClasse){
		$aAllClassesStack[] = $sClasse;	
	}				
	foreach($_SESSION["classes"] as $cKey => $sClasse){
		$aAllClassesStack[] = $sClasse;	
	}	
	$aAllClassesStack = array_unique($aAllClassesStack);
	
	//pre_dump($aAllClassesStack);
	
	foreach($aAllClassesStack as $cKey => $sClass ){
		if (class_exists($sClass)){
			$oO =  new $sClass();
			if($oO	&&	isset($oO->XML)){
				
				$stack = xmlClassParse($oO->XML);	
				$foreignName = $stack[0]["attrs"]["NAME"];
				$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
				$foreignNodeToSort = $stack[0]["children"];
				unset($stack);
	
				for ($i=0;$i<count($foreignNodeToSort);$i++){
					if ($foreignNodeToSort[$i]["name"] == "ITEM"){	
						if ($foreignNodeToSort[$i]["attrs"]["FKEY"] == $classeName){
							//echo "match";
							$foreignField = $foreignPrefixe.'_'.$foreignNodeToSort[$i]["attrs"]["NAME"];
							if ($bRecurse) {
								$sql = "	SELECT	".$oO->getFieldPK()."
									FROM	".$foreignName."
									WHERE	".$foreignField.' = '.$id.';'; 
								$orphans = dbGetObjectsFromRequete($sClass, $sql);
							}
							if ($bDelete)
								$sql = 'DELETE FROM '.$foreignName.' WHERE '.$foreignField.' = '.$id.';';
							else	$sql = 'UPDATE '.$foreignName.' SET '.$foreignField.' = -1 WHERE '.$foreignField.' = '.$id.';';

							$upRes = dbExecuteQueryQuiet($sql);
							if ($upRes!=true){
								$res = false;
							} elseif ($bRecurse && !empty($orphans)) {
								foreach ($orphans as $orphan) {
									if (!dbArchiveOrphans($orphan, $bDelete, $bRecurse))
										$res = false;
								}
							}
						}					
					}//if ($foreignNodeToSort[$i]["name"] == "ITEM"){	
				}//for ($i=0;$i<count($foreignNodeToSort);$i++){	
			}
			else{
				//echo 'class no XML ';
			}
		}//if (class_exists($sClass)){
	}
	
	return $res;
}

//------------------------------------------------
// delete
//------------------------------------------------

function dbDelete($oObjet, $bDeleteOrphans=false, $bRecurse=false) {
	global $db;
	$result = null;
	
	$sTable = $oObjet->getTable();
	$sGetterPK = $oObjet->getGetterPK();
	$iId = $oObjet->$sGetterPK();
	
	$sql = "DELETE FROM $sTable WHERE ".$oObjet->getFieldPK()."=".$iId;

	$rs = $db->Execute($sql);

	if ($rs != false) {

		$result = true;
		// Heritage
		if (is_array($oObjet->inherited)) {
			if (count($oObjet->inherited) > 0) {
				foreach ($oObjet->inherited as $key=>$class) {
					if (is_object($class)) {
						$inherited = new $class($iId);
						if (!dbDelete($inherited))
							$result = false;
					}
				}
			}
		}
		// Related
		if ($result)
			$result = dbArchiveOrphans($oObjet, $bDeleteOrphans, $bRecurse);
	} else {
		if (DEF_MODE_DEBUG==true) {
			echo "<br />dbDelete($oObjet)";
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
// delete tous les enregistrements d'une table
//------------------------------------------------

function dbDeleteAll($sObjet) {
	global $db;
	$result = null;

	$oObjet = new $sObjet;
	$sTable = $oObjet->getTable();

	$sql = "DELETE FROM $sTable ";
	//print("<br>sql=".$sql);
	//exit();
	$rs = $db->Execute($sql);

	if ($rs != false) {
		$result = true;
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />dbDeleteAll($oObjet)";
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
	
	if (is_array($oObjet->inherited)) {
		if (count($oObjet->inherited) > 0) {
			foreach ($oObjet->inherited as $key=>$class) {
				if(is_object($class))
					dbDeleteAll($key);
			}
		}
	}		
	return $result;
}


//------------------------------------------------
// delete avec champ id (sans faire d'objet pour des traitements plus rapides)
//------------------------------------------------

function dbDeleteId($sTable, $sFieldId, $eValueId) {
	global $db;
	$result = null;

	$sql = "DELETE FROM ".$sTable." WHERE ".$sFieldId."=".$eValueId;
	//print("<br>sRequete=".$sRequete);
	//exit();
	$rs = $db->Execute($sql);

	if($rs != false) {
		$result = true;
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />dbDeleteId($sTable, $sFieldId, $eValueId)";
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
// delete avec clause where complexe (depuis un tableau associatif)
//------------------------------------------------

function dbDeleteWhere($sTable, $aValues) {
	global $db;
	$result = null;

	$wheres = "";
	foreach ($aValues as $key => $val)
		$wheres .= ($wheres != '' ? " AND " : " WHERE ").$key." = ".$val;
	$sql = "DELETE FROM ".$sTable.$wheres;
	//print("<br>sql=".$sql);
	//exit();
	$rs = $db->Execute($sql);

	if($rs != false) {
		$result = true;
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />dbDeleteWhere($sTable, $sFieldId, $eValueId)";
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
// prochaine valeur de champ pour une table
//------------------------------------------------

function getNextVal($sTable, $sField){
	global $db;

	$sql = " SELECT MAX(".$sField.") FROM $sTable";
//print("<br>$sql");

	$rs = $db->Execute($sql);
	if($rs) {
		$result = $rs->fields[0];
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />getNextVal($sTable, $sField)";
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
	// valeur suivante
	$result++;

	// Traductions
	// Added by Luc - 5 oct. 2009
	// if ($result == 1 && $sTable == 'cms_chaine_reference')
	//	$result = 500;
    	// end 
	$rs->Close();
	return($result);
}

//------------------------------------------------
// si un objet existe pour l'id, on le crée, sinon objet vide
//------------------------------------------------

function getObjectById($sOBjet, $id) {
	$sOBjet=trim($sOBjet);
	if (isObjectById($sOBjet, $id) == true)
		$oTemp = new $sOBjet($id);		
	else	$oTemp = new $sOBjet();

	return $oTemp;
}

//------------------------------------------------
// si un objet existe pour l'id, true
//------------------------------------------------

function isObjectById($sOBjet, $id) {	
	$sOBjet=trim($sOBjet);
	if (!isset($id) || ($id < 0)) {
		return false;
	} else {
                $sOBjet = str_replace(" ", "", $sOBjet);
                //pre_dump($sOBjet);
		$oTemp = new $sOBjet();	
		$eCount = getCount($oTemp->getTable(), $oTemp->getFieldPK(), $oTemp->getFieldPK(), $id, "NUMBER");
		
		if ($eCount == 0) {
			// objet vide
			return false;
		} else	return true;
	}
}

//------------------------------------------------
// si un objet existe pour l'id, on l'update, sinon insert
//------------------------------------------------
function dbSauve($oObjet) {
	$sOBjet = $oObjet->getClasse();
	$sOBjet=trim($sOBjet);
	$sGetterId = $oObjet->getGetterPK();
	$id = $oObjet->$sGetterId();

	if (isObjectById($sOBjet, $id) == true) {
		//echo "update";	
		$bReturn = dbUpdate($oObjet);
	} else {
		//echo "insert";
		if (!isset($id) || ($id < 0)) {
			//echo " with autokey";
			$bReturn = dbInsertWithAutoKey($oObjet);
		} else {
			//echo " simple";
			$bReturn = dbInsert($oObjet);
		}
	}
	return $bReturn;
}


//------------------------------------------------
//  insert forcé (id à -1)
//------------------------------------------------
function dbSauveAsNew($oObjet) {
	//pre_dump($oObjet);
	$oObjet->set_id(-1);	
	$bReturn = dbInsertWithAutoKey($oObjet);
	return $bReturn;
}

//------------------------------------------------
// nombre de $sFieldCount dans une table avec une dcondition where optionnelle
//------------------------------------------------

function getCount($sTable, $sFieldCount, $sFieldWhere, $eValueWhere, $sTypeWhere="NUMBER") {
	global $db;

	$sql = " SELECT COUNT(".$sFieldCount.") FROM $sTable";
	
	if ($sFieldWhere != "") {
		if ($sTypeWhere == "TEXT")
			$sql.= " WHERE $sFieldWhere = ".to_dbstring($eValueWhere);
		else	$sql.= " WHERE $sFieldWhere = $eValueWhere";	
	}
	//print("<br>$sql");
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
// nombre de $sFieldPK dans une table avec UNE condition where optionnelle
// a partir d'un objet
//------------------------------------------------

function getCount2($oObjet, $sFieldWhere, $eValueWhere, $sTypeWhere="NUMBER") {
	$sTable = $oObjet->getTable();
	$sFieldPK = $oObjet->getFieldPK();

	return getCount($sTable, $sFieldPK, $sFieldWhere, $eValueWhere, $sTypeWhere);
}

//------------------------------------------------
// nombre de $sFieldPK dans une table avec DES conditions where optionnelle
//------------------------------------------------

function getCount_where($sObjet, $aFieldWhere, $aValueWhere, $aTypeWhere) {
	global $db;

	$oObjet = new $sObjet;
	$sTable = $oObjet->getTable();
	$sFieldPK = $oObjet->getFieldPK();

	$sql = " SELECT COUNT(".$sFieldPK.") FROM $sTable";

	if (sizeof($aFieldWhere) > 0)
		$sql.=" WHERE ";
	for ($p=0; $p<sizeof($aFieldWhere); $p++)	{
		if ($p > 0)
			$sql.= " AND ";
		if ($aTypeWhere[$p] == "TEXT")
			$sql.= $aFieldWhere[$p]." = ".to_dbstring($aValueWhere[$p]);
		else	$sql.= $aFieldWhere[$p]." = ".$aValueWhere[$p];	
	}
	//print("<br>$sql");
	$rs = $db->Execute($sql);
	
	if($rs) {
		$result = $rs->fields[0];
	} else {
		if(DEF_MODE_DEBUG==true) {
			echo "<br />getCount_where($sObjet, $aFieldWhere, $aValueWhere, $aTypeWhere)";
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
// Fonction générique de récupération d'enregistrements en recherche d'un champs précis
// Si rien n'est trouvé, retourne null
// Recherche pour un seul champ
//------------------------------------------------

function getSearchField($sTable, $sFields, $sFieldWhere, $eValueWhere, $sTypeWhere="NUMBER") {
	global $db;
	$aResult = array();

	$sql = " SELECT $sFields FROM $sTable";
	
	if ($sFieldWhere != "")	{
		if ($sTypeWhere == "TEXT")
			$sql.= " WHERE $sFieldWhere = ".to_dbstring($eValueWhere)."";
		else if ($sTypeWhere == "GENERICTEXT")
			$sql.= " WHERE UPPER($sFieldWhere) LIKE '%".strtoupper(to_dbquote($eValueWhere))."%'";
		else	$sql.= " WHERE $sFieldWhere = $eValueWhere";	
	}
	if (DEF_BDD != "ORACLE")
		$sql.= ";";
	//echo $sql;
	$rs = $db->Execute($sql);
	if($rs) {
		$aFields = explode(",",$sFields);
		while(!$rs->EOF) {
			$aEnr = array();
			foreach($aFields as $k=>$sField) {
				array_push($aEnr, $rs->fields[n($sField)] );
			}
			array_push($aResult, $aEnr );
			$rs->MoveNext();
		}
	} else	return null;
	$rs->Close();

	return($aResult);
}

//------------------------------------------------
// Fonction générique de récupération d'enregistrements en recherche de plusieurs champs
// Si rien n'est trouvé, retourne null
// Recherche pour plusieurs champs
//------------------------------------------------

function getSearchFields($sTable, $aFields, $aFieldWhere, $aValueWhere, $aTypeWhere ) {
	global $db;
	$aResult = array();

	$sql = " SELECT ".implode(",", $aFields)." FROM $sTable";
	
	if ($aFieldWhere && sizeof($aFieldWhere)>0)	{
		$aCond = array();
		for($i=0;$i<sizeof($aFieldWhere);$i++) {
			if ($aTypeWhere && $aTypeWhere[$i] == "TEXT")
				array_push($aCond, $aFieldWhere[$i]." = ".to_dbstring($aValueWhere[$i]));
			else if ($aTypeWhere && $aTypeWhere[$i] == "GENERICTEXT")
				array_push($aCond, "UPPER(".$aFieldWhere[$i].") LIKE '%".strtoupper(to_dbquote($aValueWhere[$i]))."%'");
			else
				array_push($aCond, $aFieldWhere[$i]." = ".$aValueWhere[$i]);
		}
		$sql.= " WHERE ".implode(" AND ", $aCond);
	}

	if (DEF_BDD != "ORACLE") $sql.= ";";
	//echo $sql;
	$rs = $db->Execute($sql);
	if (DEF_BDD != "MSSQL"){	
		if($rs) {
			while(!$rs->EOF) {
				$aEnr = array();
				foreach($aFields as $k=>$sField)
					array_push($aEnr, $rs->fields[n($sField)] );
				array_push($aResult, $aEnr );
				$rs->MoveNext();
			}
		} else	return null;	
	} else {
		// MS SQLSERVER
		if($rs) {
			while(!$rs->EOF) {
				$aEnr = array();
				foreach($aFields as $k=>$sField)
					array_push($aEnr, $rs->fields[$k] );
				array_push($aResult, $aEnr );
				$rs->MoveNext();
			}
		} else	return null;
	}
	$rs->Close();

	return($aResult);
}


//------------------------------------------------
// ajout des quotes pour les chaines
//------------------------------------------------
function to_dbstring($schaine) {
	// déjà fait...
	return "'".to_dbquote($schaine)."'";
	// return "'".$schaine."'";
}	

//------------------------------------------------
// traite la chaîne de caractère pour éviter les problèmes à l'enregistrement dans la base
// comme les quotes par exemple
//------------------------------------------------
function to_dbquote($schaine) { 
	if (DEF_BDD == "POSTGRES") return str_replace("'","\'",$schaine);
	else if (DEF_BDD == "ORACLE") return str_replace("'","''",$schaine);
	else if (DEF_BDD == "MYSQL") return str_replace("'","''",stripslashes($schaine));
}

//------------------------------------------------
// fonction dédoublonnant un tableau
//------------------------------------------------
function dedoublonne($aTab) {
	$aResult = array();
	for ($i=0; $i<sizeof($aTab); $i++) {
		// lecture d'un élément de tableau
		$sChaine = $aTab[$i];
		// recherche de cette chaine dans le tableau final
		$bTrouve=0;
		for ($j=0; $j<sizeof($aResult); $j++) {
			if ($sChaine == $aResult[$j]) {
				$bTrouve=1;
				$j = sizeof($aResult);// sortie de la boucle
			}
		}
		if ($bTrouve == 0)
			array_push($aResult, $sChaine);
	}

	return $aResult;
}

function dbConcat($str1, $str2) {
	if (DEF_BDD == "MYSQL")
		$sqlConcat = "CONCAT(".$str1.", ".$str2.")";
	elseif (DEF_BDD == "POSTGRES")
		$sqlConcat = $str1." || ".$str2;

	return $sqlConcat;
}

function istable($tablename, $doNotTrigger=false) {
	global $db;
	
	if (!isset($_SESSION['classes']))
		$_SESSION['classes'] = array();
	elseif (in_array($tablename, $_SESSION['classes']))
		return true;
	
	
	
	if (DEF_BDD != "MYSQL")	{
		if ($doNotTrigger==false) {
			$_SESSION['classes'][]=$tablename; // les tests ne sont faits qu'une fois
		}
		return true; // on ne pas en charge autre chose que mysql	
	}	
	else {
		$sSQl = "DESCRIBE ".$tablename;
		$rs = $db->Execute($sSQl);

		if ($rs){
			if ($doNotTrigger==false) {
				$_SESSION['classes'][]=$tablename; // les tests ne sont faits qu'une fois
			}
			return true;
		}
		else{
			return false;
		}
	}
}

function ispatched($tablename) {
	global $db;
	
	if (!isset($_SESSION['patches']))
		$_SESSION['patches'] = array();
	elseif (in_array($tablename, $_SESSION['patches']))
		return true;

	if (istable($tablename, true))
		$_SESSION['patches'][]=$tablename; // les tests ne sont faits qu'une fois
	
	return false;
}


function getSql($oObjet) {
	global $db;
   
	$sql = dbMakeInsertReq($oObjet);
 
	return $sql;
}

function dbExecuteQueryQuiet($sql) {
	global $db;

	//print("<br>".$sql);
	$rs = $db->Execute($sql);

	return true;
}


?>