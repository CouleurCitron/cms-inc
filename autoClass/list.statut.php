<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');		
$other_statut = '';
if (isset ($stack[0]["attrs"]["STATUT"]) ) {
	$other_statut = $stack[0]["attrs"]["STATUT"];
}		
 
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){
		if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut" && $other_statut == ''){
			$aStatutNode = $aNodeToSort[$i];
			break;
		}
		if ($aNodeToSort[$i]["attrs"]["NAME"] == $other_statut && $other_statut != ''){
			$aStatutNode = $aNodeToSort[$i];
			break;
		}
	}
}

if (($aStatutNode != NULL)	&&	(isAllowed ($rankUser, "ADMIN;GEST") || ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/e/', $customActionControl[$_SESSION['rank']])))){	
?>
	<div class="arbo" style="float:left">	
	<?php		
	if (isset($aStatutNode["children"]) && (count($aStatutNode["children"]) > 0) ){			
		foreach ($aStatutNode["children"] as $childKey => $childNode){
			if($childNode["name"] == "OPTION"){ // on a un node d'option			
				if ($childNode["attrs"]["TYPE"] == "value"){
					?>
					<input type="button" name="btATTEN" id="btATTEN" value="<?php echo $translator->getText($childNode["attrs"]["LIBELLE"]); ?>" class="arbo" style="width:auto; padding: 0px 2px;" onclick="changeStatut(<?php echo $childNode["attrs"]["VALUE"]; ?>)" />&nbsp;
					<?php
				} //fin type  == value				
			}
		}
	} // if nodes children
	elseif ($other_statut == '') {
		?>
		<input type="button" name="btATTEN" id="btATTEN" value="<?php $translator->echoTransByCode('En_attente') ?>" class="arbo" style="width:auto; padding: 0px 2px;" onclick="changeStatut(<?php echo DEF_ID_STATUT_ATTEN; ?>)" />&nbsp;
		<input type="button" name="btLIGNE" id="btLIGNE" value="<?php $translator->echoTransByCode('En_ligne') ?>" class="arbo" style="width:auto; padding: 0px 2px;" onclick="changeStatut(<?php echo DEF_ID_STATUT_LIGNE; ?>)" />&nbsp;
		<input type="button" name="btARCHI" id="btARCHI" value="<?php $translator->echoTransByCode('Archive') ?>" class="arbo" style="width:auto; padding: 0px 2px;" onclick="changeStatut(<?php echo DEF_ID_STATUT_ARCHI; ?>)" />&nbsp;
		<?php
	}
	elseif (isset($aStatutNode["attrs"]["FKEY"]) && $aStatutNode["attrs"]["FKEY"] != '' ) {// cas fkey
		eval ("$"."oFK = new ".$aStatutNode["attrs"]["FKEY"]."();");						
		// cas des deroulant d'id, pointage vers foreign
		if(!is_null($oFK->XML_inherited))
			$sXML = $oFK->XML_inherited;
		else
			$sXML = $oFK->XML;
		unset($stack);
		$stack = array();
		xmlClassParse($sXML);

		$fkName = $stack[0]["attrs"]["NAME"];
		$fkPrefixe = $stack[0]["attrs"]["PREFIX"];
		$fkNodeToSort = $stack[0]["children"];
		
		$tempIsAbstractFK = false;
		$tempFKAbstract = "";
		$tempIsDisplayFK = false;
		$tempFKDisplay = "";		
		 
		if(is_array($fkNodeToSort)){
			foreach ($fkNodeToSort as $nodeId => $nodeValue) {				
					
				if ($nodeValue["attrs"]["NAME"] == $oFK->getAbstract()){					
					if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
						$tempIsAbstractFK = true;
						$tempFKAbstract = $nodeValue["attrs"]["FKEY"]; 
						//break;
					}
					$tempFKAbstractTranslate = $nodeValue["attrs"]["TRANSLATE"];
					$tempFKAbstractType = $nodeValue["attrs"]["TYPE"];
				}
				if ($nodeValue["attrs"]["NAME"] == $oFK->getDisplay()){				
					if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){ 
						$tempIsDisplayFK = true;
						$tempFKDisplay = $nodeValue["attrs"]["FKEY"]; 
						//break;
					}
					$tempFKDisplayTranslate = $nodeValue["attrs"]["TRANSLATE"];
					$tempFKDisplayType = $nodeValue["attrs"]["TYPE"];
				}
			}
		}							
		
		$aFKstatut = dbGetObjects($aStatutNode["attrs"]["FKEY"]);							
		
		foreach ($aFKstatut as $oStatut) { 							 
			if ($oFK->getGetterStatut() != "none"){
				eval ("$"."fkStatus = $"."oStatut->".strval($oFK->getGetterStatut())."();");					
			}
			else{
				$fkStatus = DEF_ID_STATUT_LIGNE;
			}							
			eval ("$"."fkId = $"."oStatut->get_id();");							
			 
			if ($fkStatus == DEF_ID_STATUT_LIGNE){								 	 
				if ($fkIsDisplayForeign){
					eval('$eForeignId=$oStatut->get_'.strval($oFK->getDisplay()).'();');
					$oStatutDisplay = cacheObject($fkForeignDisplay, $eForeignId);
					eval ("echo $"."oStatutDisplay->get_".strval($oStatutDisplay->getDisplay())."();");
				}
				else{// trad 									
					eval ("$"."itemValue = $"."oStatut->get_".strval($oFK->getDisplay())."();");
					 
					if (DEF_APP_USE_TRANSLATIONS && $tempFKDisplayTranslate) {
						if ($tempFKDisplayType == "int") {
							if ($tempFKDisplayTranslate == 'reference')
								$itemValue = $translator->getByID($itemValue);
						} elseif ($tempFKDisplayType == "enum") {
							if ($tempFKDisplayTranslate == "value")
								$itemValue = $translator->getText($itemValue);
						} else	echo "Error - Translation engine can not be applied to <b><i>".$tempFKDisplayType."</i></b> type fields !!";
					} 									
					echo '<input type="button" name="bt_statut" id="bt_statut" class="arbo" value="'.substr($itemValue, 0, 100).'" onclick="changeStatut('.$fkId.')" />&nbsp;';
				}								 		
			}	 
		} 
	}			
	elseif ( $aStatutNode["attrs"]["TYPE"] == 'enum' ) {		// enum value dans length				  
		if (isset($aStatutNode["attrs"]["LENGTH"]) && $aStatutNode["attrs"]["LENGTH"] != '' ) {
			$aValues = split (",", $aStatutNode["attrs"]["LENGTH"]);
			foreach ($aValues as $value) {
				$value  = str_replace ("'", "", $value);
				echo "TEST : ".$value."<br/>";
				echo '<input type="button" name="bt_statut" id="bt_statut" class="arbo" value="'.$value.'" onclick="changeStatut(\''.$value.'\')" />&nbsp;';
			}
		}
	}
?>
	</div>
<?php	
}
?>