<?php
header("Content-type: application/xml");
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

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/sync.out.inc.php');

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

//--------

$aClassStack=array(); // 


$rs = $db->Execute($sql);

if($rs) {
	echo '<adequat>';
	while(!$rs->EOF) {	
	
	
	
		syncOutObject($aNodeToSort, $rs->fields, $oRes);
		
		
		
		$rs->MoveNext();
	}
	echo '</adequat>';
}

?>