<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}
//------------------------------------------------------------------------------------------------------
if (!function_exists('sanitize')){
	function sanitize($str){
		$search = array();
		//$search = getExtendedAsciiTable(128, 255);
		$search[] = 'apos;';
		$search[] = 'quot;';
		$search[] = '&';
		$search[] = chr(10);
		$search[] = '<';
		$destroy = array();
		//$destroy = getExtendedAsciiTableUTF8(128, 255);
		$destroy[] = "'";
		$destroy[] = '"';
		$destroy[] = '&amp;';
		$destroy[] = "&#10;";
		$destroy[] = "&lt;";	
		
		return str_replace($search, $destroy, $str);
	}
}

//------------------------------------------------------------------------------------------------------

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

ini_set ('max_execution_time', 0); // Aucune limite d'execution
ini_set("memory_limit","1024M");

unset($_SESSION['BO']['CACHE']);

if (DEF_APP_USE_TRANSLATIONS)
	$translator =& TslManager::getInstance();

/*
$neededRAM = 512; // Mo

$localRAM = (int)intval(str_replace('M', '', ini_get('memory_limit')));
if ($localRAM < $neededRAM){
	@ini_set('memory_limit', $neededRAM.'M'); 
}
*/
$bDebug = false;
$sMessage='';

// objet 
eval('$'.'oRes = new '.$classeName.'();');

if(!is_null($oRes->XML_inherited))
	$sXML = $oRes->XML_inherited;
else
	$sXML = $oRes->XML;
//$sXML = $oRes->XML;

xmlClassParse($sXML);

//$sPathSurcharge = $_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/'.$stack[0]['attrs']['NAME'].'.class.xml';
//		
//if(is_file($sPathSurcharge)){ 
//	$stack = array();		
//	// le parse
//	xmlFileParse($sPathSurcharge);
//}

$classeName = $stack[0]['attrs']['NAME'];
$classePrefixe = $stack[0]['attrs']['PREFIX'];
$aListeChamps = $oRes->getListeChamps(); 


$aNodeToSort = $stack[0]['children'];
$statusGetter = $oRes->getGetterStatut();

//////////////////////////
// recherche par statut
//////////////////////////
if (isset($_POST['eStatut'])){
	$eStatut=$_POST['eStatut'];
	$_SESSION['eStatut']=$eStatut;
}
if($eStatut==''){
	$eStatut=$_SESSION['eStatut'];
}


///////////////////////////
// Recherche par critère du backoffice
//////////////////////////
$filters = array();
foreach($_SESSION as $key => $data){
    if(preg_match('/filter/', $key) && $data != "-1" && !preg_match('/filterAsso/', $key) ){
        $filters[$key] = $data;
    }
}
 

if (($eStatut != -1) && ($eStatut != '') && ($statusGetter != 'none')) {
    
	$sql= 'SELECT * FROM '.$classeName.' WHERE '.$classePrefixe.'_statut = '.$eStatut.';';
        
} else if(count($filters) != 0){
    
    $sql = "SELECT * FROM $classeName WHERE ";
    $iswhere = false ;
    $i = 0;
    foreach($filters as $key => $data){ 
	 
       if ( doesFieldExist($aListeChamps, str_replace("filter", "", $key))) {
			$aWhere = array();
			$iswhere = true ;
			if($i == count($filters)-1 ){
				//si c'ets le dernier élément
				if (preg_match('/,/', $data))
					$aWhere[]= strtolower(str_replace("filter", "", $key))." IN (".$data.");";
				else
					$aWhere[]= strtolower(str_replace("filter", "", $key))." = '".$data."';";
			} else {
				if (preg_match('/,/', $data))
					$aWhere[]= strtolower(str_replace("filter", "", $key))." IN (".$data.") ";
				else
					$aWhere[]= strtolower(str_replace("filter", "", $key))." = '".$data."' ";
			}
		}
        
        
    }
	if ($iswhere == false) $sql= 'SELECT * FROM '.$classeName.';';
	else $sql = "SELECT * FROM $classeName WHERE ".implode (" AND ", $aWhere) ;
    
}
else{
	$sql= 'SELECT * FROM '.$classeName.';';
} 
//pre_dump($filters);
//pre_dump($_SESSION);



// Cas over mega pas typique du tout
// Cloisonnement sur administrateur loggué
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM" && $aNodeToSort[$i]["attrs"]["FKEY"] == 'bo_users' && $aNodeToSort[$i]["attrs"]["RESTRICT"] == 'true' && $_SESSION["rank"] != 'ADMIN') {
		 
		$jointure = " ".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]." = ".$_SESSION["userid"];
		if (preg_match('/where/', $sql))
			$sql = str_replace('where', 'where '.$jointure.' and ', $sql);
		else	$sql = str_replace($classeName, $classeName.' where '.$jointure, $sql);
	}
}

//paramètre
$k=0;
while (isset($_GET['champ'.$k])&& $_GET['champ'.$k]!=""){ 
	if (isset($_GET['operateur'.$k]) && $_GET['operateur'.$k]!="" && isset($_GET['valeur'.$k]) && $_GET['valeur'.$k]!="") 
		$jointure = " ".$_GET['champ'.$k]." ".urldecode($_GET['operateur'.$k])." '".$_GET['valeur'.$k]."' ";
	if (preg_match('/where/', $sql))
		$sql = str_replace('where', 'where '.$jointure.' and ', $sql);
	else	$sql = str_replace($classeName, $classeName.' where '.$jointure, $sql);
	
	$k++;
}

if (!is_null($oRes->XML_inherited)){
	$aListe_res = dbGetObjects($classeName);
}
else{
	$aListe_res = dbGetObjectsFromRequeteCache($classeName, $sql,100);
}
//echo $sql;

//die($sql);
 
// --- PHPExcel	 ----------------------------------------------

/** Error reporting */
//error_reporting(E_ALL);

date_default_timezone_set('Europe/Paris');

/** PHPExcel */
require_once 'include/cms-inc/lib/PHPExcel/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("Adequat Website")
			->setLastModifiedBy("Adequat Website")
			->setTitle('Export '.$classeName)
			->setSubject('Export '.$classeName)
			->setDescription('Export '.$classeName)
			->setKeywords("office 2007 ".$classeName)
			->setCategory("Export");

// Add some data
//$objPHPExcel->setActiveSheetIndex(0);
         //   ->setCellValue('A1', 'Hello')
        //    ->setCellValue('B2', 'world!')
         //   ->setCellValue('C1', 'Hello')
         //   ->setCellValue('D2', 'world!');
		 
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="export_'.$classeName.'.xlsx"');
header('Cache-Control: max-age=0');

		 
		 
$j = 0; 
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]['name'] == 'ITEM' &&	(!isset($aNodeToSort[$i]['attrs']['NOEXPORT']) || $aNodeToSort[$i]['attrs']['NOEXPORT'] != 'true')){	
		if (!isset($aNodeToSort[$i]["attrs"]["SKIP"]) || $aNodeToSort[$i]["attrs"]["SKIP"] != "true" || ($aNodeToSort[$i]["attrs"]["SKIP"] == "true" && $_GET["Type"] == 'template')) {
			
			
			//if ( ( $_GET["Type"] == 'template' /*&& $aNodeToSort[$i]["attrs"]["NAME"]  != "id"*/ ) || $_GET["Type"] != 'template' ) {
			 	$letter = NumToLetter($j +1);
				$case = $letter.'1';
				if (( $aNodeToSort[$i]["attrs"]["NAME"]  != "id" && $_GET["Type"]  == 'template') || $_GET["Type"]  == '' ) {
					if (isset($aNodeToSort[$i]["attrs"]["LIBELLE"]) && $aNodeToSort[$i]["attrs"]["LIBELLE"] != '')	
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($case, utf8_encode(stripslashes($aNodeToSort[$i]['attrs']['LIBELLE'])));
					else	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($case, utf8_encode(stripslashes($aNodeToSort[$i]['attrs']['NAME'])));
					$j++; 
				} 
				if ($aNodeToSort[$i]["attrs"]["ASSO"] || $aNodeToSort[$i]["attrs"]["ASSO_VIEW"] || $aNodeToSort[$i]["attrs"]["ASSO_EDIT"]) { // cas d'asso  
					$aTempClasse = array();
					if ($aNodeToSort[$i]["attrs"]["ASSO"])
						$aTempClasse = split(',', $aNodeToSort[$i]["attrs"]["ASSO"]);		
					elseif ($aNodeToSort[$i]["attrs"]["ASSO_VIEW"])
						$aTempClasse = split(',', $aNodeToSort[$i]["attrs"]["ASSO_VIEW"]);		
					elseif ($aNodeToSort[$i]["attrs"]["ASSO_EDIT"])
						$aTempClasse = split(',', $aNodeToSort[$i]["attrs"]["ASSO_EDIT"]);		
				}	
				
				
			//}
		}	
		 
	}
}

// libelle asso
 
if (sizeof($aTempClasse) > 0) {
	for ($m=0; $m<sizeof($aTempClasse);$m++) {  
		$sTempClasse = $aTempClasse[$m]; 
		$letter = NumToLetter($j+1+$m);
		$case = $letter.'1'; 
		
		eval("$"."oTemp = new ".$sTempClasse."();");
				 
		if (!is_null($oTemp->XML_inherited))
			$sXML = $oTemp->XML_inherited;
		else
			$sXML = $oTemp->XML;
		//$sXML = $oTemp->XML;

		unset($stack);
		$stack = array();
		xmlClassParse($sXML);
 
		if ($stack[0]["attrs"]["LIBELLE"]!='') $assoLibelle = $stack[0]["attrs"]["LIBELLE"];
		else  $assoLibelle = $stack[0]["attrs"]["NAME"];
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($case, utf8_encode(stripslashes($assoLibelle)));
		
	}	
}

if ($_GET["Type"]  == '') { 
	if(sizeof($aListe_res)>0) {
		// liste
		for($k=0; $k<sizeof($aListe_res); $k++) {
		//for($k=0; $k<5; $k++) {
			$oRes = $aListe_res[$k];
			$l = 0;
			for ($i=0;$i<count($aNodeToSort);$i++){		
				if ($aNodeToSort[$i]['name'] == 'ITEM' && (!isset($aNodeToSort[$i]['attrs']['NOEXPORT']) || $aNodeToSort[$i]['attrs']['NOEXPORT'] != 'true')){
					if (!isset($aNodeToSort[$i]["attrs"]["SKIP"]) || $aNodeToSort[$i]["attrs"]["SKIP"] != "true" ) {
						$eKeyValue = trim(call_user_func(array($oRes, 'get_'.$aNodeToSort[$i]['attrs']['NAME'])));
						
						if (isset($aNodeToSort[$i]['attrs']['FKEY'])){ // cas de foregin key
							if ($eKeyValue == '')  $eKeyValue = -1 ; 
							$sTempClasse = $aNodeToSort[$i]['attrs']['FKEY'];
							if ($eKeyValue > -1){ 
								$oTemp = cacheObject($sTempClasse, $eKeyValue); 
								//if (isObjectById($sTempClasse, $eKeyValue)) {
								if ($oTemp!=false){
									$eKeyValue = trim(call_user_func(array($oTemp, 'get_'.$oTemp->getDisplay())));
									//if (  $oTemp->getDisplay() <>  $oTemp->getAbstract() ) $eKeyValue.= " - ".trim(call_user_func(array($oTemp, 'get_'.$oTemp->getAbstract())));
									
									// traduction ????
									if(!is_null($oTemp->XML_inherited))
										$sXML = $oTemp->XML_inherited;
									else
										$sXML = $oTemp->XML;
									//$sXML = $oTemp->XML;
									 
									unset($stack);
									$stack = array();
									xmlClassParse($sXML);
			
									$foreignNodeToSort = $stack[0]["children"];
									$tempIsAbstractForeign = false;
									$tempForeignAbstract = "";
									$tempIsDisplayForeign = false;
									$tempForeignDisplay = "";
			
									if(is_array($foreignNodeToSort)){ 
										foreach ($foreignNodeToSort as $nodeId => $nodeValue) {		
											if ($nodeValue["attrs"]["NAME"] == $oTemp->getDisplay()){	
												$valueDisplay = $nodeValue["attrs"]["NAME"];
												$typeDisplay = $nodeValue["attrs"]["TYPE"]; 
												$translateDisplay = $nodeValue["attrs"]["TRANSLATE"]; 	
												$fkeyDisplay = $nodeValue["attrs"]["FKEY"];
											}									 
										}
									} 
									if (DEF_APP_USE_TRANSLATIONS && $translateDisplay!='') {	 		
										if ($typeDisplay == "int") {
											if ($translateDisplay == 'reference')
												$eKeyValue = $translator->getByID($eKeyValue);
										} elseif ($typeDisplay == "enum") {
											if ($translateDisplay == "value")
												$eKeyValue =  $translator->getText($eKeyValue);
										} else	echo "Error - Translation engine can not be applied to <b><i>".$typeDisplay."</i></b> type fields !!";
									} 
								}
								else {
									$eKeyValue= "n/a";
								}
							}
							else{
								$eKeyValue= "n/a";
							}
						}
						elseif (isset($aNodeToSort[$i]['attrs']['NAME'])&&($aNodeToSort[$i]['attrs']['NAME'] == 'statut')){ // cas statut
							$eKeyValue=  lib($eKeyValue);
						}
						elseif (isset($aNodeToSort[$i]['attrs']['OPTION'])&&($aNodeToSort[$i]['attrs']['OPTION'] == 'bool')){ // cas bool
							if ($eKeyValue == 1) $eKeyValue = "oui";
							else $eKeyValue = "non";
						}
						elseif (isset($aNodeToSort[$i]['attrs']['OPTION'])&&($aNodeToSort[$i]['attrs']['OPTION'] == 'enum')){ // cas enum
							if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
								foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
									if($childNode["name"] == "OPTION"){ // on a un node d'option				
										if ($childNode["attrs"]["TYPE"] == "value"){
											if (strval($eKeyValue) == strval($childNode["attrs"]["VALUE"])){			
												$eKeyValue = $childNode["attrs"]["LIBELLE"];
												break;
											}
										} //fin type  == value				
									}
								}
							}
							// si on ne trouve rien $eKeyValue reste inchangé
						}			
						
						
						if (DEF_APP_USE_TRANSLATIONS && $aNodeToSort[$i]["attrs"]["TRANSLATE"]!='') {		
							$translateDisplay = 		$aNodeToSort[$i]["attrs"]["TRANSLATE"];
							$typeDisplay = 		$aNodeToSort[$i]["attrs"]["TYPE"];				
							if ($typeDisplay == "int") {
								if ($translateDisplay == 'reference')
									$eKeyValue = $translator->getByID($eKeyValue);
							} elseif ($typeDisplay == "enum") {
								if ($translateDisplay == "value")
									$eKeyValue =  $translator->getText($eKeyValue);
							}/* else	echo "Error - Translation engine can not be applied to <b><i>".$typeDisplay."</i></b> type fields !!";*/
						} 	
									
						
						if ($eKeyValue == -1 || $eKeyValue == '') $eKeyValue = "n/a";
						$letter = NumToLetter($l+1);
						$case = $letter.($k+2);
						$objPHPExcel->setActiveSheetIndex(0)		
									->setCellValue($case, utf8_encode(stripslashes($eKeyValue)));
									
						$l++;			
					
					} //fin if	 
								
				}// fin if
			}// fin for cols	
			 
			include("xlsx.association.php");  
			 
		} // fin for rows
	} // fin if
}
else {
	$l = 0;
	for ($i=0; $i<count($aNodeToSort); $i++) {
		if ($aNodeToSort[$i]["name"] == "ITEM") {	
			if ($aNodeToSort[$i]["attrs"]["NAME"] == "id") {
				/*$sHeader.=  "(colonne optionelle);" ;
				$letter = NumToLetter($l+1);
				$case = $letter.($k+2);
				$objPHPExcel->setActiveSheetIndex(0)		
							->setCellValue($case, utf8_encode(stripslashes(sanitize($eKeyValue))));
				$l++;*/
			}
			else if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut") { 
				$letter = NumToLetter($l+1);
				$case = $letter.(2);
				$objPHPExcel->setActiveSheetIndex(0)		
							->setCellValue($case, utf8_encode(stripslashes(lib(DEF_ID_STATUT_LIGNE))));
				$l++;
			}
			else if ($aNodeToSort[$i]["attrs"]["TYPE"] == "date") { 
				$letter = NumToLetter($l+1);
				$case = $letter.(2);
				$objPHPExcel->setActiveSheetIndex(0)		
							->setCellValue($case, utf8_encode(stripslashes(date("d/m/Y"))));
				$l++;
			}
			else if (isset($aNodeToSort[$i]["attrs"]["FKEY"])) {
				if (isset($aNodeToSort[$i]["attrs"]["DEFAULT"])) { 
					$chaine = $aNodeToSort[$i]["attrs"]["DEFAULT"];
				}
				else {
					$aFK = dbGetObjects($aNodeToSort[$i]["attrs"]["FKEY"]);
					$aValue = array() ; 
					if (sizeof($aFK) <=5) {
						foreach ($aFK as $oFK) {
							 
							array_push ($aValue, getItemValue($oFK, $oFK->getDisplay()));		
						}
						
						$chaine =  "".join ("/", $aValue)."" ;
					}
					else {
						$chaine =  "" ;
					}
				}
				$letter = NumToLetter($l+1);
				$case = $letter.(2);
				$objPHPExcel->setActiveSheetIndex(0)		
							->setCellValue($case, utf8_encode(stripslashes($chaine)));
				$l++;
				
			}
			else if (isset($aNodeToSort[$i]["attrs"]["DEFAULT"])) { 
				$letter = NumToLetter($l+1);
				$case = $letter.(2);
				$objPHPExcel->setActiveSheetIndex(0)		
							->setCellValue($case, utf8_encode($aNodeToSort[$i]["attrs"]["DEFAULT"]));
				$l++;
			}
			else { 
				$letter = NumToLetter($l+1);
				$case = $letter.(2);
				$objPHPExcel->setActiveSheetIndex(0)		
							->setCellValue($case, utf8_encode("(".$aNodeToSort[$i]["attrs"]["TYPE"].")"));
				$l++; 	
			} 
		} 
	}
	
	include("xlsx.association.php");  
}
 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle($classeName);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

// restore de la ram
@ini_set('memory_limit',$localRAM.'M');

exit;
?>