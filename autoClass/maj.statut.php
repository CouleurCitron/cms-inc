<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if ($oRes->getGetterStatut() != "none" ) {


	$other_statut = '';
	 
	if (!is_null($oRes->XML_inherited))
	$sXML = $oRes->XML_inherited;
	else	$sXML = $oRes->XML;
	xmlClassParse($sXML);

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
		
	
?>
 <tr>
  <td width="141" align="right" bgcolor="#E6E6E6" class="arbo">&nbsp;<u><b><?php $translator->echoTransByCode('statutdepublication'); ?></b></u>&nbsp;*</td>
 <td width="535" align="left" bgcolor="#EEEEEE" class="arbo">
  <?php
  	//$statusNodeName = str_replace('get_', '', $oRes->getGetterStatut());
 	//$statusNode = getItemByName($aNodeToSort, $statusNodeName);

	if (isAllowed ($rankUser, "ADMIN;GEST")) {	
		
		if (isset($aStatutNode["children"]) && (count($aStatutNode["children"]) > 0) ){		  
			$eKeyValue = $oRes->get_statut();
		 	for ($iSta=0; $iSta<count($aStatutNode["children"]);$iSta++){
				
				if ($aStatutNode["children"][$iSta]["attrs"]["TYPE"] == "value"){  
					if($eKeyValue==intval($aStatutNode["children"][$iSta]["attrs"]["VALUE"])) {
						$checked = "checked";
					}
					else{
						$checked = "";
					}					
					echo "<input type=\"radio\" name=\"f".ucfirst($classePrefixe)."_statut\" id=\"f".ucfirst($classePrefixe)."_statut\" value=\"".$aStatutNode["children"][$iSta]["attrs"]["VALUE"]."\" ".$checked." />&nbsp;".$translator->getText($aStatutNode["children"][$iSta]["attrs"]["LIBELLE"], $_SESSION['id_langue'])."&nbsp;";					
				}
			}	 
	  }
	  else{ // cas statut custom
	  //<option type="value" value="0" libelle="en attente" />
	   
			if ($other_statut == '') {
				$eKeyValue = $oRes->get_statut();
				
				if ($oRes->get_statut() == DEF_ID_STATUT_ATTEN) $checked_ATTEN = "checked"; else $checked_ATTEN = "";
				if ($oRes->get_statut() == DEF_ID_STATUT_LIGNE) $checked_LIGNE = "checked"; else $checked_LIGNE = "";
				if ($oRes->get_statut() == DEF_ID_STATUT_ARCHI) $checked_ARCHI = "checked"; else $checked_ARCHI = "";
				?>
				<input type="radio" name="f<?php echo ucfirst($classePrefixe)?>_statut" id="f<?php echo ucfirst($classePrefixe)?>_statut" value="<?php echo DEF_ID_STATUT_ATTEN?>" <?php echo $checked_ATTEN?>  />&nbsp;<?php $translator->echoTransByCode('statut'.DEF_ID_STATUT_ATTEN); ?>&nbsp;
				<input type="radio" name="f<?php echo ucfirst($classePrefixe)?>_statut" id="f<?php echo ucfirst($classePrefixe)?>_statut" value="<?php echo DEF_ID_STATUT_LIGNE?>" <?php echo $checked_LIGNE?> />&nbsp;<?php $translator->echoTransByCode('statut'.DEF_ID_STATUT_LIGNE); ?>&nbsp;
				<input type="radio" name="f<?php echo ucfirst($classePrefixe)?>_statut" id="f<?php echo ucfirst($classePrefixe)?>_statut" value="<?php echo DEF_ID_STATUT_ARCHI?>" <?php echo $checked_ARCHI?> />&nbsp;<?php $translator->echoTransByCode('statut'.DEF_ID_STATUT_ARCHI); ?>&nbsp;
				
				 
				<?php
			}
			else {
			
			
				eval("$"."eKeyValue = "."$"."oRes->get_".$other_statut."();");
				
				// cas fkey 
				if (isset($aStatutNode["attrs"]["FKEY"]) && $aStatutNode["attrs"]["FKEY"] != '' ) {
					//echo $aStatutNode["attrs"]["FKEY"];
					eval ("$"."oFK = new ".$aStatutNode["attrs"]["FKEY"]."();");
					
					
					// cas des deroulant d'id, pointage vers foreign
					//$sXML = $aForeign[0]->XML;
					if(!is_null($oFK->XML_inherited))
						$sXML = $oFK->XML_inherited;
					else
						$sXML = $oFK->XML;
					//$sXML = $oFK->XML;
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
					
					//echo " ".$tempIsDisplayFK." ".$tempFKDisplay." ".$tempFKDisplayTranslate."<br />";
						
					
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
							else{
								// trad 
								
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
								
								//echo substr($itemValue, 0, 100);
								
								//echo '<input type="button" name="bt_statut" id="bt_statut" class="arbo" value="'.substr($itemValue, 0, 100).'" onclick="changeStatut('.$fkId.')" />&nbsp;';
								 
								if($eKeyValue==intval($fkId)) {
									$checked = "checked";
								}
								else{
									$checked = "";
								}		
								echo "<input type=\"radio\" name=\"f".ucfirst($classePrefixe)."_".strtolower($other_statut)."\" id=\"f".ucfirst($classePrefixe)."_".strtolower($other_statut)."\" value=\"".$fkId."\" ".$checked." />&nbsp;".$itemValue."&nbsp;<br />";		
								
							}
							 
									
						}	 
					} 
				}
				// enum value dans length
				else if ( $aStatutNode["attrs"]["TYPE"] == 'enum' ) {
					  
					if (isset($aStatutNode["attrs"]["LENGTH"]) && $aStatutNode["attrs"]["LENGTH"] != '' ) {
						$aValues = split (",", $aStatutNode["attrs"]["LENGTH"]);
						foreach ($aValues as $value) {
							$value  = str_replace ("'", "", $value);
							 
							echo '<input type="button" name="bt_statut" id="bt_statut" class="arbo" value="'.$value.'" onclick="changeStatut(\''.$value.'\')" />&nbsp;';
						}
					}
				}
			
			
				 
			}
		}  		
	}
	else {
		if (!isset($statusNode["children"])){ // cas typique	
			if ($oRes->get_statut() == DEF_ID_STATUT_ATTEN) {
				$translator->echoTransByCode('statut'.DEF_ID_STATUT_ATTEN);
			}
			if ($oRes->get_statut() == DEF_ID_STATUT_LIGNE) {
				$translator->echoTransByCode('statut'.DEF_ID_STATUT_LIGNE);
			}
			if ($oRes->get_statut() == DEF_ID_STATUT_ARCHI) {
				$translator->echoTransByCode('statut'.DEF_ID_STATUT_ARCHI);
			}			
		}
		else{ // cas statut custom
			for ($iSta=0; $iSta<count($statusNode["children"]);$iSta++){
				if ($statusNode["children"][$iSta]["attrs"]["TYPE"] == "value"){
					if($oRes->get_statut()==intval($statusNode["children"][$iSta]["attrs"]["VALUE"])) {
						echo $statusNode["children"][$iSta]["attrs"]["LIBELLE"];	
						break;
					}
				}
			}
		}
		echo "<input type=\"hidden\" name=\"f".ucfirst($classePrefixe)."_statut\" id=\"f".ucfirst($classePrefixe)."_statut\" value=\"".$oRes->get_statut()."\" />";
	}
	?>
	</td>
 </tr>
<?php
}
?>