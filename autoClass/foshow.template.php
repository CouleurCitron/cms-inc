<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}


//------------------------------------------------------------------------------------------------------

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

////////////////////////////////////////////////////////

// Fiche visu

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');


if ($id == "") {
	$id=$_GET['id'];
	if(!isset($id)) $id=$_POST['id'];
}

// objet 
eval("$"."oRes = new ".$classeName."($"."id);");

$sXML = $oRes->XML;
xmlClassParse($sXML);

$classeName = $stack[0]["attrs"]["NAME"];
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];

if($oRes) {	
	$fh = fopen($template,'r');
	$sBodyHTML="";
		if ($fh){
		while(!feof($fh)) {
			$sBodyHTML.=fgets($fh);
		}
		fclose($fh);
		
		
		//scan les item affichés
		$aItems = array();
		preg_match_all  ("/<autoclass item=\"([^\"]+)\">/", $sBodyHTML, $aItems);
		$aItems = $aItems[1];
		for ($i=0;$i<count($aItems);$i++){
			$itemName = $aItems[$i];
			$sBodyHTML = str_replace("<autoclass item=\"".$itemName."\">", formatItem($oRes, $itemName, $aNodeToSort), $sBodyHTML);
		}
		echo $sBodyHTML;
	}
}

?>