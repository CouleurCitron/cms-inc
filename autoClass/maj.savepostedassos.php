<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


// maj.php

//pre_dump($_POST);
// post des checkboxes d'asso
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){
		if ($aNodeToSort[$i]["attrs"]["ASSO"]){ // cas d'asso

			$aTempClasse = array();
			$aTempClasse = explode(',', $aNodeToSort[$i]["attrs"]["ASSO"]);		

			for ($m=0; $m<sizeof($aTempClasse);$m++) {

				//$sAssoClasse = $aNodeToSort[$i]["attrs"]["ASSO"];
				$sAssoClasse = $aTempClasse[$m];

				eval("$"."oAsso = new ".$sAssoClasse."();");
				
				//echo $sAssoClasse."<br />";
				$aForeign = dbGetObjects($sAssoClasse);

				// cas des deroulant d'id, pointage vers foreign
				if(!is_null($oAsso->XML_inherited))
					$sXML = $oAsso->XML_inherited;
				else
					$sXML = $oAsso->XML;
				//$sXML = $oAsso->XML;
				unset($stack);
				$stack = array();
				xmlClassParse($sXML);

				$foreignNodeToSort = $stack[0]["children"];

				$tempAsso = $stack[0]["attrs"]["NAME"];
				$tempAssoPrefixe = $stack[0]["attrs"]["PREFIX"];
				$tempAssoFull = $stack[0]["attrs"]["IS_ASSO"] == 'true' ? true : false;
				$tempAsymetric = ($tempAssoFull && $stack[0]["attrs"]["IS_ASYMETRIC"] == 'true') ? true : false;
				$tempAssoIn = "";
				$tempAssoOut = "";
				if (is_array($foreignNodeToSort)) {
					// associate different record from the SAME table
					// Added by Luc - 9 nov. 2009
					$track_key = 0;
					
					
					foreach ($foreignNodeToSort as $nodeId => $nodeValue) { 
						if (isset($nodeValue["attrs"]["FKEY"]) && $nodeValue["attrs"]["FKEY"] == $classeName)
							$track_key++;  
						
					}
					if ($track_key > 1) {
						$cnt = 0;
						foreach ($foreignNodeToSort as $nodeId => $nodeValue) {
							if (isset($nodeValue["attrs"]["FKEY"]) && $nodeValue["attrs"]["FKEY"] == $classeName) {
								$tempAssoIn = $tempAssoOut = $nodeValue["attrs"]["FKEY"];
								if ($cnt == 0)
									$tempAssoInName = $nodeValue["attrs"]["NAME"];
								else	$tempAssoOutName = $nodeValue["attrs"]["NAME"];
								$cnt++;
							}
						}
					// end associate different record from the SAME table
					} else {
						$bStatutOut = false; 
						foreach ($foreignNodeToSort as $nodeId => $nodeValue) {		
							if (($nodeValue["attrs"]["FKEY"] != "") && isset($nodeValue["attrs"]["FKEY"])){
								if ($nodeValue["attrs"]["FKEY"] == $classeName){	
								// if ($nodeValue["attrs"]["FKEY"] == $classeName && ($tempForeignDisplay!=$tempForeignAbstract)){	
									$tempAssoIn = $nodeValue["attrs"]["FKEY"]; // obvious
									$tempAssoInName = $nodeValue["attrs"]["NAME"];
								} else {
									$tempAssoOut = $nodeValue["attrs"]["FKEY"]; // 
									$tempAssoOutName = $nodeValue["attrs"]["NAME"];
								}
							} 
							if (strtolower(stripslashes($nodeValue["attrs"]["NAME"])) == "statut") 
								$bStatutOut = true;
								
							
						}
						// assymetric mode is only for table asso-linking to itself
						// so in case it was unwantedly set in asso table XML :
						$tempAsymetric = false;
					}
				}
				//echo "tempAssoIn : ".$tempAssoIn."<br/>";
				//echo "tempAssoOut : ".$tempAssoOut."<br/>";

				if ($tempAssoFull && $tempAssoOut != ""){
						// -- DEBUT traiment asso par table d'asso -------------------

					// on connait $tempAssoOut -- on recommence la recherche de foreign vers $tempAssoOut

					$sTempClasse = $tempAssoOut;
					
					 
					eval("$"."oAssoOut = new ".$tempAssoOut."();");

					if(!is_null($oAssoOut->XML_inherited))
						$sXML = $oAssoOut->XML_inherited;
					else
						$sXML = $oAssoOut->XML;
					//$sXML = $oAssoOut->XML;
					unset($stack);
					$stack = array();
					xmlClassParse($sXML);
										
					$foreignName = $stack[0]["attrs"]["NAME"];
					$foreignXMLChildren = $stack[0]["children"];
					$foreignPrefixe = $stack[0]["attrs"]["PREFIX"];
					
					
					$bCms_site = false;
					$champ_id = '';

					$track_translate = 0;
					$track_key = 0;
					foreach ($foreignXMLChildren as $nodeId => $nodeValue) {
						if (isset($nodeValue["attrs"]["FKEY"]) && $nodeValue["attrs"]["FKEY"] == $oRes->getTable())
							$track_key++;
						if (isset($nodeValue["attrs"]["IS_TRANSLATE"]) && $nodeValue["attrs"]["IS_TRANSLATE"])
							$track_translate++;
					}
					
					if (is_array($foreignXMLChildren)) {
						foreach ($foreignXMLChildren as $nodeId => $nodeValue) {	
							//echo $nodeValue["attrs"]["NAME"]."<br />";  
							if (preg_match ('/id_/', strtolower(stripslashes($nodeValue["attrs"]["NAME"]))) && $nodeId == 0 && preg_match ('/cms/', strtolower($foreignName))) {
							 
								$champ_id = strtolower(stripslashes($nodeValue["attrs"]["NAME"]));
							}

							if (isset($nodeValue["attrs"]["FKEY"]) && strtolower(stripslashes($nodeValue["attrs"]["FKEY"])) == "cms_site" && $track_translate == 0 && $sTempClasse != "cms_site") { 
					
								$bCms_site = true; 
								if ($sTempClasse == "cms_page") { 
									$champCms_site = strtolower(stripslashes($nodeValue["attrs"]["NAME"])); 	 
								}
								else  { 
									$champCms_site = $foreignPrefixe."_".strtolower(stripslashes($nodeValue["attrs"]["NAME"])); 
									
								}
							}
							 
						}
					} 
					
					if ($champ_id == '') { 
						$champ_id = $foreignPrefixe."_id"; 
						$champ_display = $foreignPrefixe."_".$valueDisplay;
						$champ_abstract = $foreignPrefixe."_".$valueAbstract;
						//$champ_temp_abstract = $foreignPrefixe."_".strval($oTemp->getAbstract());
					}
					else {
						$champ_display = $valueDisplay;
						$champ_abstract = $valueAbstract;
						//$champ_temp_abstract = strval($oTemp->getAbstract());
					}
					

					// Build optimized query
					// LEFT JOIN with MYSQL4+, ORACLE9+, PGSQL8+
					$sql = "	SELECT		ref.".$champ_id." AS ref_id ";
					if ($oAssoOut->getGetterStatut() != "none")
						$sql .= ", ref.".$foreignPrefixe."_statut AS ref_statut";
					$sql .= ",
								asso.".$tempAssoPrefixe."_".$tempAssoInName." AS fkey_1,
								asso.".$tempAssoPrefixe."_".$tempAssoOutName." AS fkey_2
						FROM		";
					$sql .= $foreignName." ref
						LEFT JOIN 	".$tempAsso." asso					
						";
					/*
					
					// mis à jour 09.11.12 car les asso entre 2 même classe bug
					if ($track_key > 1)
						$sql .= "ON		(ref.".$champ_id." = asso.".$tempAssoPrefixe."_".$tempAssoInName." OR ref.".$champ_id." = asso.".$tempAssoPrefixe."_".$tempAssoOutName.")
						";
					else	*/$sql .= "ON		ref.".$champ_id." = asso.".$tempAssoPrefixe."_".$tempAssoOutName."
						";

					// connditions sur site courant
					$where=array();

					if ($bCms_site && ($tempAssoOutName!='classe')) {
						$swhere_cms_site  = " ref.".$champCms_site." =".$_SESSION['idSite_travail'];
						if ($tempAssoOutName == 'cms_arbo_pages') $swhere_cms_site .= " OR ref.node_id = 0 "; // ajout de la racine (associée uniquement à l'idsite 1) pour la classe cms_arbo_pages
						$where[] = $swhere_cms_site;
					}
					
					if ( $tempAssoOutName =='cms_page' ) {
						$where[] = "ref.valid_page = 1 and ref.isgabarit_page = 0 ";
					}								
					
					if(count($where)>0){
						$sql .= ' WHERE '.implode(' AND ', $where);
					}

					//echo $sql."<br/>";
					
					$res = $db->Execute($sql);					 
					
					if ($res) {						 
						// use buffer table to avoid record ubiquity
						$asso_pile = Array();
						while(!$res->EOF) { 
							
							$row = $res->fields; 
							//viewArray($row, 'row '.$res->fields['fkey1']);
							if ($oAssoOut->getGetterStatut() != "none"){
								$tempStatus = $row['ref_statut'];
							} else	$tempStatus = DEF_ID_STATUT_LIGNE;
							
							$tempId = $row['ref_id']; 
							// Do not handle current record in case of association between elements of the SAME table
							if ($track_key > 1 && $tempId == $id) {
								$res->MoveNext();
								continue;
							}
							if ($tempStatus == DEF_ID_STATUT_LIGNE) {
								$asso_fld = "fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAssoOut)."_".$tempId;
								if (isset($_POST[$asso_fld])) {
									if (!$asso_pile[$asso_fld]['checked']) {
										if ($_POST[$asso_fld] == $tempId) {
											if (($row['fkey_1'] == $id && $row['fkey_2'] == $row['ref_id']) || (!$tempAsymetric && $row['fkey_1'] == $row['ref_id'] && $row['fkey_2'] == $id)) {
												// already checked
												$asso_pile[$asso_fld]['checked'] = true;
												$asso_pile[$asso_fld]['create'] = false;
												$asso_pile[$asso_fld]['id'] = $tempId;
											} else {
												$asso_pile[$asso_fld]['id'] = $tempId;
												$asso_pile[$asso_fld]['create'] = true;
											}
										}
									}
								} else {
									if (($row['fkey_1'] == $id && $row['fkey_2'] == $row['ref_id']) || (!$tempAsymetric && $row['fkey_1'] == $row['ref_id'] && $row['fkey_2'] == $id)) {
										// was checked
										$asso_pile[$asso_fld]['id'] = $tempId;
										// tester si on n'est pas sur un objet appartenant à un autre mini site...
										$asso_pile[$asso_fld]['delete'] = true;
									}
								}
							}
							$res->MoveNext();
						}
					 
						//viewArray($asso_pile, 'asso_pile '.$tempAsso);
						
						foreach ($asso_pile as $fld => $association) {
							  
							if ($association['create']) {
								
								eval("$"."oNewAsso = new ".$tempAsso."();");
								eval("$"."oNewAsso->set_".$tempAssoInName."(".$id.");");
								eval("$"."oNewAsso->set_".$tempAssoOutName."(".$association['id'].");");
								
								// order 
								//echo $tempAssoPrefixe." - ".$tempAssoIn." - ".$tempAssoOut." - ".$association['id'];
								if (assoOrderFieldMatches($tempAssoPrefixe, $tempAssoIn, $tempAssoOut, $association['id'])){
									//echo "ici";
									$valueOrdre = assoOrderFieldValue($tempAssoPrefixe, $tempAssoIn, $tempAssoOut, $association['id']);
									eval("$"."oNewAsso->set_"."ordre"."(".$valueOrdre.");"); 
								}								
								//pre_dump($oNewAsso);
								$bAssoRetour = dbInsertWithAutoKey($oNewAsso);
								//echo ' insert asso '.$bAssoRetour.'<br>';
							}
							elseif ($association['delete']) {
								$resTrashAsso = getSearchFields($tempAsso, array($tempAssoPrefixe."_id", $tempAssoPrefixe."_".$tempAssoInName, $tempAssoPrefixe."_".$tempAssoOutName), array($tempAssoPrefixe."_".$tempAssoInName, $tempAssoPrefixe."_".$tempAssoOutName), array($id,$association['id']), array("NUMBER", "NUMBER"));
								//echo " - id to del : ".$resTrashAsso[0][0];
								eval("$"."oNewAsso = new ".$tempAsso."(".$resTrashAsso[0][0].");");
								$bAssoRetour = dbDelete($oNewAsso);
							}
							else{ // checked	
								 				
								$resUpdateAsso = getSearchFields($tempAsso, array($tempAssoPrefixe."_id", $tempAssoPrefixe."_".$tempAssoInName, $tempAssoPrefixe."_".$tempAssoOutName), array($tempAssoPrefixe."_".$tempAssoInName, $tempAssoPrefixe."_".$tempAssoOutName), array($id,$association['id']), array("NUMBER", "NUMBER"));
								//echo " - id to update : ".$resUpdateAsso[0][0]."<br />";								
								eval("$"."oNewAsso = new ".$tempAsso."(".$resUpdateAsso[0][0].");");
								eval("$"."oNewAsso->set_".$tempAssoInName."(".$id.");");
								eval("$"."oNewAsso->set_".$tempAssoOutName."(".$association['id'].");");								
								
								// order 
								
								//echo "tempAssoIn : ".$tempAssoIn."<br/>";
								//echo "tempAssoOut : ".$tempAssoOut."<br/>";
								
								//echo " ".$tempAssoPrefixe." -- ".$tempAssoInName." -- ". $tempAssoOutName." -- ".$association['id']."<br />";
								if (assoOrderFieldMatches($tempAssoPrefixe, $tempAssoIn, $tempAssoOut, $association['id'])){ 
									 
									$valueOrdre = assoOrderFieldValue($tempAssoPrefixe, $tempAssoIn, $tempAssoOut, $association['id']);
									eval("$"."oNewAsso->set_"."ordre"."(".$valueOrdre.");");									
								}
								$bAssoRetour = dbUpdate($oNewAsso); 	
								//echo ' dbUpdate asso '.$bAssoRetour.'<br>';						
							}
						}
					}
					// fin traitement par table asso ----------
				} else {
					// -- DEBUT traiment asso sans table d'asso -------------------	
					if ($tempAsso != "") { // check les records pointant vers la table sont plus que ZERO
	
						$sTempClasse = $tempAsso;
	
						eval("$"."oAssoOut = new ".$tempAsso."();");
						$aForeign = dbGetObjects($tempAsso);
						
						for ($iForeign=0;$iForeign<count($aForeign);$iForeign++){
							$oForeign = $aForeign[$iForeign];
							if ($oAssoOut->getGetterStatut() != "none")
								eval ("$"."tempStatus = $"."oForeign->".strval($oAssoOut->getGetterStatut())."();");					
							else	$tempStatus = DEF_ID_STATUT_LIGNE;
							eval ("$"."tempId = $"."oForeign->get_id();");
							
							if ($tempStatus == DEF_ID_STATUT_LIGNE){
								//pre_dump($oForeign);
								// check sur post de fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAssoOut) et sa valeur (le assoOut)
								if (isset($_POST["fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAsso)."_".$tempId])){
									if ($_POST["fAsso".ucfirst($tempAssoIn)."_".ucfirst($tempAsso)."_".$tempId] == $tempId){
										//echo "fAsso".ucfirst($tempAssoIn)."_".$tempId." = checked";
										if (getCount_where($tempAsso, array($tempAssoPrefixe."_".$tempAssoInName, $tempAssoPrefixe."_id"), array($id,$tempId), array("NUMBER","NUMBER")) ==  1){
											//echo " deja checked";
											//echo " - on ne fait rien";
										} else {
											//echo " pas deja checked";
											//echo " - on set l'asso ".$tempAsso." ".$tempId." a : ".$id;
											eval("$"."oNewAsso = new ".$tempAsso."(".$tempId.");");
											eval("$"."oNewAsso->set_".$tempAssoInName."(".$id.");");
											//eval("$"."oNewAsso->set_".$tempAssoOut."(".$tempId.");");
											//pre_dump($oNewAsso);
											$bAssoRetour = dbUpdate($oNewAsso);
										}
									}
									//echo "<br />";
								} else {
									//echo "fAsso".ucfirst($tempAssoIn)."_".$tempId." = not checked";
		
									if (getCount_where($tempAsso, array($tempAssoPrefixe."_".$tempAssoInName, $tempAssoPrefixe."_id"), array($id,$tempId), array("NUMBER","NUMBER")) ==  1){
										//echo ", was checked";
										//echo " - on set l'asso ".$tempAsso." ".$tempId." a : -1";
										eval("$"."oNewAsso = new ".$tempAsso."(".$tempId.");");
										eval("$"."oNewAsso->set_".$tempAssoInName."(-1);");
										//eval("$"."oNewAsso->set_".$tempAssoOut."(".$tempId.");");
										//pre_dump($oNewAsso);
										$bAssoRetour = dbUpdate($oNewAsso);
									} else {
										//echo ", deja pas checked";
										//echo " - on ne fait rien";
									}
									//echo "<br />";
								}
							}						
						}
					} // fin if ($tempAsso != ""){ // check les records pointant vers la table sont plus que ZERO
					// -- FIN traiment asso sans table d'asso -------------------	
				}
				
				unset ($_SESSION["AWS_".$sAssoClasse."_idObject"]); // suppression de la session contenant les nouvelles associations (via bouton ajouter un item)
				
			}
		}
	}
}


// fin ------- post des checkboxes d'asso

?>