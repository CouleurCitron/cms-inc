<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
header('Content-type: text/html; charset=iso-8859-1'); 


include_once("cms-inc/include_cms.php");
include_once("cms-inc/include_class.php");
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

$classeMain = $_POST['classeMain'];
$sTempClasse = $_POST['classeName'];
$action = $_POST['action'];

eval("$"."oTemp = new ".$sTempClasse."();");
$sXML = $oTemp->XML;

unset($stack);
$stack = array();
xmlClassParse($sXML);

$foreignName = $stack[0]["attrs"]["NAME"];
$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
$foreignNodeToSort = $stack[0]["children"];

if ($action == "add") {
	echo "var s = parent.document.getElementById('".$_POST["select"]."');"; // create input node
	$sql = "SELECT MAX(".$foreignPrefixe."_id) FROM ".$sTempClasse.";";
	$navigateur="";
	
	
	$aForeign = dbGetObjectsFromRequeteID($sTempClasse, $sql);
	$oForeign = $aForeign[0];
	//var_dump($oForeign);
	eval ("$"."iForeign = $"."oForeign->get_id();");
	//echo 'var tonDiv = parent.document.createElement(\'div\');';
	//echo 'tonDiv.name="".$_POST["select"]."";';
	eval ("$"."tempId = $"."oForeign->get_id();");
	eval ("$"."tempStatus = $"."oForeign->get_statut();");
		
	$sel="";
	echo "var div_".$iForeign." = parent.document.createElement(\"div\");";
	echo "div_".$iForeign.".name = \"fAsso".ucfirst($classeMain)."_".ucfirst($foreignName)."_".$tempId."_div\";"; 
	echo "div_".$iForeign.".id = \"fAsso".ucfirst($classeMain)."_".ucfirst($foreignName)."_".$tempId."_div\";";
	
	if (preg_match('/msie/msi', $_SERVER['HTTP_USER_AGENT']) && !preg_match('/opera/msi', $_SERVER['HTTP_USER_AGENT'])) {     // Internet Explorer  
		$navigateur="IE"; 
	}
	if ($tempStatus == DEF_ID_STATUT_LIGNE){
		if ($navigateur == "IE") {
			echo "var cb_".$iForeign." = parent.document.createElement('<INPUT id=\"fAsso".ucfirst($classeMain)."_".ucfirst($foreignName)."_".$tempId."\" TYPE=\"checkbox\" NAME=\"fAsso".ucfirst($classeMain)."_".ucfirst($foreignName)."_".$tempId."\" onclick=\"javascript:appel(".$tempId.", \'".$foreignName."\');\">');";
	}
		else {
			echo "var cb_".$iForeign." = parent.document.createElement(\"input\");";
			echo "cb_".$iForeign.".type = \"checkbox\";"; 
			echo "cb_".$iForeign.".name = \"fAsso".ucfirst($classeMain)."_".ucfirst($foreignName)."_".$tempId."\";"; 
			echo "cb_".$iForeign.".id = \"fAsso".ucfirst($classeMain)."_".ucfirst($foreignName)."_".$tempId."\";"; 
			echo "cb_".$iForeign.".checked = false;";
			//echo "cb_".$iForeign.".onclick = function(){parent.document.appel(".$tempId.", '".$foreignName."');};";
			echo "cb_".$iForeign.".setAttribute(\"onClick\",\"javascript:appel(".$tempId.",'".$foreignName."')\" );";
			//onclick=\"javascript:appel(".$tempId.", \'".$foreignName."\');\">'
		}	
	/*
		
		
	
		//echo 'var appel = new Function();';
		//echo 'cb_".$iForeign.".onclick = function(){alert(this.id);};';
		//echo 'cb_".$iForeign.".onclick = function(){alert(parent.document.getElementById(\'arrayAddCheck\').value);};';
		//echo 'cb_".$iForeign.".onclick = function(){parent.document.getElementById(\'arrayAddCheck\').value=parent.document.getElementById(\'arrayAddCheck\').value+".$tempId.";};';
		//echo 'cb_".$iForeign.".onclick = function(){alert(".$tempId.");};';
		//echo 'cb_".$iForeign.".onclick = function(){appel();};';*/
		echo "var br = parent.document.createElement('br');";
		echo "div_".$iForeign.".appendChild(cb_".$iForeign.");";
		
		//echo 'tonDiv.appendChild(cb_".$iForeign.");';
		//echo 'parent.document.body.appendChild(tonDiv);';
		//echo 'parent.document.getElementById("".$_POST["select"]."")';
		$tempIsAbstractForeign = false;
		$tempIsDisplayForeign = false;
			if(is_array($foreignNodeToSort)){
				foreach ($foreignNodeToSort as $nodeId => $nodeValue) {				
					if ($nodeValue["attrs"]["NAME"] == $oTemp->getAbstract()){					
						if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
							$tempIsAbstractForeign = true;
							//break;
						}
					}
					if ($nodeValue["attrs"]["NAME"] == $oTemp->getDisplay()){					
						if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
							$tempIsDisplayForeign = true;
							//break;
						}
					}
				}
			}
	
		if ($tempIsDisplayForeign){
			eval("$"."oForeignDisplay = new ".$oTemp->getDisplay()."($"."oForeign->get_".strval($oTemp->getDisplay())."());");
			eval ("$"."sel.= $"."oForeignDisplay->get_".strval($oForeignDisplay->getDisplay())."();");
		}
		else{
			eval ("$"."sel.= substr($"."oForeign->get_".strval($oTemp->getDisplay())."(), 0, 100);");
		}
		
		if ($oTemp->getDisplay() != $oTemp->getAbstract()){
			$sel.= " - ";
			if ($tempIsAbstractForeign){
				eval("$"."oForeignAbstract = new ".$tempForeignAbstract."($"."oForeign->get_".strval($oTemp->getAbstract())."());");
				eval ("$"."sel.=  $"."oForeignAbstract->get_".strval($oForeignAbstract->getDisplay())."();");
			}
			else{
				eval ("$"."sel.= $"."oForeign->get_".strval($oTemp->getAbstract())."();");
			}
		}
		//$sel.="<a href=\"javascript:deleteId(".$tempId.", \'".$foreignName."\')\" title=\"Supprimer\">x</a>";
		echo "var txt_".$iForeign." = parent.document.createTextNode('".$sel."  ');";
		echo "var link_".$iForeign." = parent.document.createElement('a');";
		echo "link_".$iForeign.".setAttribute('title', 'Supprimer');";
		echo "link_".$iForeign.".setAttribute('href', 'javascript:deleteId(".$tempId.", \'".$foreignName."\')');";
		echo "var linkText_".$iForeign."=parent.document.createTextNode('x');";
 		echo "link_".$iForeign.".appendChild(linkText_".$iForeign.");";
		echo "div_".$iForeign.".appendChild(txt_".$iForeign.");";
		//echo "div_".$iForeign.".appendChild(link_".$iForeign.");";
		echo "div_".$iForeign.".appendChild(br);";
		echo "s.appendChild(div_".$iForeign.");";
		
		
	}	
}
else if ($action== "del") {
	$id = $_POST['id'];
	
	echo "var s = document.getElementById('".$_POST["select"]."');"; // create input node

	$classeName = $sTempClasse;
	$classePrefixe = $foreignPrefixe;
	
	if ($id != "") {
		
		// compte les objets avec cet id
		// pour voir si cet objet existe
		$eEmp = getCount($classeName, ucfirst($classePrefixe)."_id", ucfirst($classePrefixe)."_id", $id);
		//echo $eEmp." ".$classeName." ".$classePrefixe." ".$classeMain;
		if ($eEmp == 1) {

			
			// recherche des fichiers uploader dans le dossier custom et les supprime s'ils existent
			
			eval("$"."oRes = new ".$classeName."($"."id);");
			$sXML = $oRes->XML;
			xmlClassParse($sXML);
			
			$classeName = $stack[0]["attrs"]["NAME"];
			$classePrefixe = $stack[0]["attrs"]["PREFIX"];
			$aNodeToSort = $stack[0]["children"];
			for ($i=0;$i<count($aNodeToSort);$i++){
				if ($aNodeToSort[$i]["name"] == "ITEM"){
					//var_dump($aNodeToSort);
					if (isset($aNodeToSort[$i]["attrs"]["OPTION"])&& $aNodeToSort[$i]["attrs"]["OPTION"]!= "" && isset($aNodeToSort[$i]["attrs"]["DIR"]) && $aNodeToSort[$i]["attrs"]["DIR"]!= "" ) {
						//echo ($aNodeToSort[$i]["attrs"]["NAME"])." ".$aNodeToSort[$i]["attrs"]["DIR"]."<br><br>";
						$nameField = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
						$dirField = $aNodeToSort[$i]["attrs"]["DIR"];
						$dirField2 = $_SERVER['DOCUMENT_ROOT'].$aNodeToSort[$i]["attrs"]["DIR"];
						
						$eFile = getCount($classeName, ucfirst($classePrefixe)."_id", ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"], "'".$nameField."'");
						if($eFile == 1 && is_file($dirField2.$nameField)) {
							unlink($dirField2.$nameField);
						}
						
					}
				}
			}
		
			dbDelete($oRes);
			$sMessage = $classeName." ".$oRes->get_id()." supprimé ";
			
			//****************modif thao**********************
			// récup de toutes les asso à $classeName
			
			$urlClass= "../../include/bo/class";
			//table contenant les classes liés
			$aTempClas=ScanDirs($urlClass, $classeName);
			for ($j=0; $j<sizeof($aTempClas);$j++) {
				$sAssoClasse = $aTempClas[$j];
				eval("$"."oAsso = new ".$sAssoClasse."();");
				$aForeign = dbGetObjects($sAssoClasse);
				$sXML = $oAsso->XML;
				// on vide le tableau stack
				unset($stack);
				$stack = array();
				xmlClassParse($sXML);	
				$foreignName = $stack[0]["attrs"]["NAME"];
				$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
				$foreignNodeToSort = $stack[0]["children"];
				for ($i=0;$i<count($foreignNodeToSort);$i++){
					if ($foreignNodeToSort[$i]["name"] == "ITEM"){	
						if ($foreignNodeToSort[$i]["attrs"]["NAME"] == $classeName){
							$eEmp = getCount($foreignName, ucfirst($foreignPrefixe)."_id", ucfirst($foreignPrefixe)."_".ucfirst($classeName), $id);
							if ($eEmp > 0) {
								
								$sqlDisplay =  "select ".ucfirst($foreignPrefixe)."_id from ".$foreignName." where ".ucfirst($foreignPrefixe)."_".ucfirst($classeName)."=".$id; 
								$aResponseDisplay = dbGetObjectsFromRequeteID($foreignName, $sqlDisplay);
								for ($a=0; $a<sizeof($aResponseDisplay); $a++) {
									$oResponseDisplay = $aResponseDisplay[$a];
									$idResponseDisplay = $oResponseDisplay->get_id();
									eval("$"."oRes3 = new ".$foreignName."($".idResponseDisplay.");");
									if ($oRes3->getGetterStatut()!="none") {
										if ($foreignNodeToSort[$i]["attrs"]["DEFAULT"] != ""){
											$foreignDefault=$foreignNodeToSort[$i]["attrs"]["DEFAULT"];
										}
										else {
											$foreignDefault="";
										}
										eval("$"."oRes3->set_".$classeName."($".foreignDefault.");"); 
										
										for ($l=0;$l<count($foreignNodeToSort);$l++){
											if ($foreignNodeToSort[$l]["name"] == "ITEM"){	
											
												if ($foreignNodeToSort[$l]["attrs"]["NAME"] == "statut") {
													if (isset($foreignNodeToSort[$l]["children"]) && (count($foreignNodeToSort[$l]["children"]) > 0)){
														$eCodeStatut = 5; // libelle écartée, code à reprendre pour le libellé "ecarté" ou autre
													}
													else
													{
														$eCodeStatut = DEF_CODE_STATUT_DEFAUT;
													}
												} // if ($foreignNodeToSort[$i]["attrs"]["name"] == "STATUT") {
											}
										}
										$oRes3->set_statut($eCodeStatut);
										$bAssoRetour = dbUpdate($oRes3);
									}
									else {
										$bAssoRetour = dbDelete($oRes3);
									} //if ($oRes3->getGetterStatut()!="none") {
								} //for ($a=0; $a<sizeof($aResponseDisplay); $a++) {
							}// if ($eEmp > 0) {
							
						}// if ($foreignNodeToSort[$i]["attrs"]["NAME"] == $classeName){
					}// if ($foreignNodeToSort[$i]["name"] == "ITEM"){	
				} //if ($foreignNodeToSort[$i]["name"] == "ITEM"){	
			} //for ($i=0;$i<count($foreignNodeToSort);$i++){
			
			//*************************************************
		}
	}
	echo "s.removeChild(document.getElementById('fAsso".ucfirst($classeMain)."_".ucfirst($classeName)."_".$id."_div'));";
}

?>