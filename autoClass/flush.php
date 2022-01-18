<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}

//------------------------------------------------------------------------------------------------------

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

$bDebug = false;
$sMessage="";

// objet 
eval("$"."oRes = new ".$classeName."();");

$sXML = $oRes->XML;

xmlClassParse($sXML);

$classeName = $stack[0]["attrs"]["NAME"];
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];
$statusGetter = $oRes->getGetterStatut();

//////////////////////////
// recherche par statut
//////////////////////////
if (isset($_POST['eStatut'])){
	$eStatut=$_POST['eStatut'];
	$_SESSION['eStatut']=$eStatut;
}
if($eStatut==""){
	$eStatut=$_SESSION['eStatut'];
}

if (($eStatut != -1) && ($eStatut != "") && ($statusGetter != "none")) {
	$sql= "SELECT * FROM ".$classeName." WHERE ".$classePrefixe."_statut = ".$eStatut.";";
}
else{
	$sql= "SELECT * FROM ".$classeName.";";
}
$aListe_res = dbGetObjectsFromRequete($classeName, $sql);
 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"export_".$classeName.".csv\""); 
 
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){		 
			echo $aNodeToSort[$i]["attrs"]["NAME"].";"; 			
	}
}
echo "\n"; 

if(newSizeOf($aListe_res)>0) {
// liste
for($k=0; $k<newSizeOf($aListe_res); $k++) {
	$oRes = $aListe_res[$k];

   for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){			 
			//$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
			$eKeyValue = call_user_func(array($oRes, "get_".$aNodeToSort[$i]["attrs"]["NAME"]));
			echo $eKeyValue;
			echo ";";
		//}
	}
}
 
echo "\n";
 
}}
?>