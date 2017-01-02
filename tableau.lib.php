<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

function getNombreColonnes ($arrayTR) {
	
	$premierTR = getPremierTR($arrayTR) ;
	
	$arrayTD = array ();
	preg_match_all  ("|<td[^>]*>(.*)<\/td>|U", $premierTR, $arrayTD);
	//preg_match_all  ("|<td[^>]+>(.*)<\/td>|U", $premierTR, $arrayTD); avant
	$nombre = sizeof($arrayTD[0]);
	for ($i=1; $i<sizeof($arrayTD[0]);$i++){
		
		$ligneCOLSPAN = strchr($arrayTD[0][$i], "colspan=");
		$ligneCOLSPAN = str_replace("colspan=", "", $ligneCOLSPAN);
		if ($ligneCOLSPAN != "") {
			
			$guillemet = substr($ligneCOLSPAN, 0, 1);
			if ($guillemet == "\"") {
				$ligneCOLSPAN = substr($ligneCOLSPAN, 1, strlen($ligneCOLSPAN));
				$toDel = strchr($ligneCOLSPAN, "\"");
				$colspan = str_replace($toDel, "", $ligneCOLSPAN);
				$colspan = $colspan-1;
			}
			else {
				$toDel = strchr($ligneCOLSPAN, " ");
				$colspan = str_replace($toDel, "", $ligneCOLSPAN);
				$colspan = $colspan-1;
			}
		}
		else {
			$colspan = 0;
		}
		$nombre = $nombre + $colspan;
	}
	return $nombre;
}

function getNombreLignes ($arrayTR) {
	return sizeof($arrayTR);
}

function getArrayByTR ($sHTML) {
	preg_match_all  ("|<tr>(.*)<\/tr>|U", $sHTML, $arrayTR);
	return ($arrayTR[0]);
}

function getPremierTR ($arrayTR) {
	return ($arrayTR[0]);
}

function getColorTD ($idLigne) {
	if ($idLigne == 1)
		//$colorTD="class=\"tetierefondtxt\"";
		$colorTD="class=\"technical_table_td1_1\"";
	else if ($idLigne == 2)
		//$colorTD="class=\"tetierefondtxt\"";
		$colorTD="class=\"technical_table_td1_1bis\"";
	else 
		$colorTD="class=\"technical_table_td2\"";
	/*if ($idLigne%2 != 0)
		//$colorTD="bgcolor=\"#FFFFFF\" class=\"txtviolet\"";
	else 
		//$colorTD="bgcolor=\"#eee6f1\" class=\"txtvioletfondclair\"";*/
		
	return ($colorTD);
}

function getHTMLActionsDroite ($idLigne, $idColonne, $colorTD, $id, $nombreLignes) {
	$HTMLActionsDroite = "";
	//$HTMLActionsDroite.= "<td ".$colorTD."><input type=\"text\" id=\"".$idLigne."/".$idColonne."\" name=\"".$idLigne."/".$idColonne."\" value=\"\" class=\"arbo\" size=\"10\" ></td>";
	$HTMLActionsDroite.= "<td width=\"200px\"><input type=\"text\" id=\"".$idLigne."/".$idColonne."\" name=\"".$idLigne."/".$idColonne."\" value=\"\" class=\"arbo\" size=\"10\" ></td>";
	$HTMLActionsDroite.= "<td width=\"500\"><a href=\"http://".$_SERVER["HTTP_HOST"]."/backoffice/cms/cms_tableau/edit_cms_tableau.php?id=".$id."&ligne=".$idLigne."\"><img src=\"/backoffice/cms/img/2013/icone/modifier.png\"></a>";
	if ($idLigne !=1) $HTMLActionsDroite.= "<a href=\"#\" onClick=\"javascript:delLigne(".$idLigne.");\"><img src=\"/backoffice/cms/img/2013/icone/supprimer.png\"></a>";
	if ($idLigne != $nombreLignes) $HTMLActionsDroite.= "<a href=\"#\" onClick=\"javascript:moveLigne(".$idLigne.", 'down');\"><img src=\"/backoffice/cms/img/bas.gif\"></a>";
	else $HTMLActionsDroite.= "<img src=\"/backoffice/cms/img/vide.gif\" width=\"12px\" height=\"9px\">";
	if ($idLigne != 1) $HTMLActionsDroite.= "<a href=\"#\" onClick=\"javascript:moveLigne(".$idLigne.", 'up');\"><img src=\"/backoffice/cms/img/haut.gif\"></a>";
	else $HTMLActionsDroite.= "<img src=\"/backoffice/cms/img/vide.gif\" width=\"12px\" height=\"9px\">";
	$HTMLActionsDroite.= "</td>";
	
	return $HTMLActionsDroite;
}

function getNbColonnesActionsDroite () {

	return 2;
}

function getHTMLActionsHaut ($arrayTR, $colorTD) {
	$nombreColonnes = getNombreColonnes ($arrayTR);
	$HTMLActionsHaut = "";
	$HTMLActionsHaut = "<tr height=\"20\"><td>&nbsp;</td>";
	for ($i=0;$i<$nombreColonnes;$i++) {
		$idColonne = $i+1;
		//$HTMLActionsHaut.= "<td ".$colorTD."><a href=\"#\" onClick=\"javascript:delColonne(".$idColonne.");\">del</a></td>";
		$HTMLActionsHaut.= "<td >";
		if ($idColonne > 2) $HTMLActionsHaut.= "<a href=\"#\" onClick=\"javascript:moveLigne(".$idColonne.", 'left');\"><img src=\"/backoffice/cms/img/gauche.gif\"></a>";
		$HTMLActionsHaut.= "<a href=\"#\" onClick=\"javascript:delColonne(".$idColonne.");\"><img src=\"/backoffice/cms/img/2013/icone/supprimer.png\"></a>";
		$HTMLActionsHaut.= "</td>";
	} 
	//$HTMLActionsHaut.= "<td colspan=\"2\" ".$colorTD."></td>";
	$HTMLActionsHaut.= "<td colspan=\"2\"></td>";
	$HTMLActionsHaut.= "</tr>";
	
	return $HTMLActionsHaut;
}


// ajouter une ligne
function getHTMLAjout ($nombreLignes, $nombreColonnes) {
	/*$nombreLignes = getNombreLignes ($arrayTR);
	$nombreColonnes = getNombreColonnes ($arrayTR);*/
	$HTMLAjout="";
	$HTMLAjout.="<tr ><td>&nbsp;</td><td colspan=\"".$nombreColonnes."\">&nbsp;</td><td ".$colorTD."><input type=\"text\" id=\"colonnetoadd\" name=\"colonnetoadd\" value=\"".($nombreColonnes+1)."\" class=\"arbo\" size=\"1\" ><a href=\"#\" onClick=\"javascript:ajoutColonne();\"><img src=\"/backoffice/cms/img/2013/icone/add.png\"  border=\"0\"> une colonne</a></td><td>&nbsp;</td></tr>";
	$HTMLAjout.="<tr><td>&nbsp;</td>";
	$idLigne = $nombreLignes+1;
	$colorTD = getColorTD ($idLigne);
	$colorTD = "";
	for ($a=0;$a<$nombreColonnes;$a++) {
		$idColonne = $a+1;
		$HTMLAjout.= "<td ".$colorTD."><input type=\"text\" id=\"".$idLigne."/".$idColonne."\" name=\"".$idLigne."/".$idColonne."\" value=\"\" class=\"arbo\" size=\"10\" ></td>";
	} 
	$HTMLAjout.= "<td ".$colorTD.">&nbsp;</td>";
	$HTMLAjout.= "<td ".$colorTD."><input type=\"text\" id=\"lignetoadd\" name=\"lignetoadd\" value=\"".($nombreLignes+1)."\" class=\"arbo\" size=\"1\" >&nbsp;<a href=\"#\" onClick=\"javascript:ajoutLigne();\"><img src=\"/backoffice/cms/img/2013/icone/add.png\" border=\"0\"> une ligne</a>&nbsp;</td>";
	$HTMLAjout.= "</tr>";
	
	return $HTMLAjout;
}

function getColspan ($ligneTD, $HTML) {
	$ligneAllTR = "";
	$ligneCOLSPAN = stristr($HTML, "colspan=\"");
	$ligneCOLSPAN2 = stristr($HTML, "colspan=");
	if ($ligneCOLSPAN != "") {
		$ligneCOLSPAN = str_replace("colspan=\"", "", $ligneCOLSPAN);
		$toDel = strchr($ligneCOLSPAN, "\"");
		$colspan = str_replace($toDel, "", $ligneCOLSPAN);
		$ligneTEXT = stristr($ligneTD, "id=\"");
		$ligneTEXT2 = strchr($ligneTEXT, "\"");
		$ligneTEXT3 = split(" ",$ligneTEXT2);
		$ligneTEXT4 = split("/",$ligneTEXT3[0]);
		$ref=$ligneTEXT4[0]."/".$ligneTEXT4[1];
		$ligne = $ligneTEXT4[0];
		$col = $ligneTEXT4[1];
		$refold=$ligne."/".$col;
		if ($colspan>0) {
			for ($m = 0; $m<$colspan;$m++) {
			//echo "******jerentre<br>";
				
				$refnew=$ligne."/".($col+$m)."\"";
				$colnew = $col+$m;
				$ligneTD = str_replace ("colspan=\"".$colspan."\"", "", $ligneTD);
				$ligneTD = str_replace ("id=".$refold , "id=".$refnew, $ligneTD);
				$ligneTD = str_replace ("name=".$refold , "name=".$refnew, $ligneTD);
				$ligneAllTR.= $ligneTD;
				$refold=$ligne."/".($colnew)."\"";
				
			}
		}
		
	}
	else if ($ligneCOLSPAN2 != "") {
		$ligneCOLSPAN2 = str_replace("colspan=", "", $ligneCOLSPAN2);
		$toDel = strchr($ligneCOLSPAN2, " ");
		$colspan = str_replace($toDel, "", $ligneCOLSPAN2);
		$ligneTEXT = stristr($ligneTD, "id=\"");
		$ligneTEXT2 = strchr($ligneTEXT, "\"");
		$ligneTEXT3 = split(" ",$ligneTEXT2);
		$ligneTEXT4 = split("/",$ligneTEXT3[0]);
		$ref=$ligneTEXT4[0]."/".$ligneTEXT4[1];
		$ligne = $ligneTEXT4[0];
		$col = $ligneTEXT4[1];
		$refold=$ligne."/".$col;
		if ($colspan>0) {
			for ($m = 0; $m<$colspan;$m++) {
			//echo "******jerentre<br>";
				
				$refnew=$ligne."/".($col+$m)."\"";
				$colnew = $col+$m;
				$ligneTD = str_replace ("colspan=".$colspan, "", $ligneTD);
				$ligneTD = str_replace ("id=".$refold , "id=".$refnew, $ligneTD);
				$ligneTD = str_replace ("name=".$refold , "name=".$refnew, $ligneTD);
				$ligneAllTR.= $ligneTD;
				$refold=$ligne."/".($colnew)."\"";
				
			}
		}
	}
	else {
		$ligneAllTR = $ligneTD;
	}
	
	return $ligneAllTR;
}


function getRowspan ($ligneTD, $HTML) {
	$ligneAllTR = "";
	$lignerowspan = stristr($HTML, "rowspan=\"");
	$lignerowspan2 = stristr($HTML, "rowspan=");
	if ($lignerowspan != "") {
		$lignerowspan = str_replace("rowspan=\"", "", $lignerowspan);
		$toDel = strchr($lignerowspan, "\"");
		$rowspan = str_replace($toDel, "", $lignerowspan);
	}
	else if ($lignerowspan2 != "") {
		$lignerowspan2 = str_replace("rowspan=", "", $lignerowspan2);
		$toDel = strchr($lignerowspan2, " ");
		$rowspan = str_replace($toDel, "", $lignerowspan2);
	}
	else {
		$rowspan = 0;
	}
	
	return $rowspan;
}


function countColspan ($ligneTD, $HTML) {
	$ligneCOLSPAN = strchr($HTML, "colspan=");
	$ligneCOLSPAN = str_replace("colspan=", "", $ligneCOLSPAN);
	if ($ligneCOLSPAN != "") {
		$guillemet = substr($ligneCOLSPAN, 0, 1);
		if ($guillemet == "\"") {
			$ligneCOLSPAN = substr($ligneCOLSPAN, 1, strlen($ligneCOLSPAN));
			$toDel = strchr($ligneCOLSPAN, "\"");
			$colspan = str_replace($toDel, "", $ligneCOLSPAN);
		}
		else {
			$toDel = strchr($ligneCOLSPAN, " ");
			$colspan = str_replace($toDel, "", $ligneCOLSPAN);
		}
	}
	else {
		$colspan = 1;
	}
	
	return $colspan;
}



function checkCss ($html) {
	$html= str_replace("tetierefondtxt", "technical_table_td1_1", $html);
	$html= str_replace("txtvioletfondfonce", "technical_table_td1_1", $html);
	$html= str_replace('txtvioletfondclair', 'technical_table_td2', $html);
	$html= str_replace('bgcolor="#eee6f1"', '', $html);
	$html= str_replace('txtviolet', 'technical_table_td2', $html);
	$html= str_replace('bgcolor="#FFFFFF"', '', $html);
	return $html;
}
function getHTMLSpacer () {
	$htmlSpacer= "<tr>";
	$htmlSpacer.= "<td colspan=\"".$nombreColonnesAvecActions."\" class=\"technical_table_td5\"></td>";
	$htmlSpacer.= "</tr>";
	
	return $htmlSpacer;
}

function getHTML ($oRes) {
	$sHTML = $oRes->get_htmltmp();
	
	$sHTML = str_replace ("<a href=\"javascript:AffpageFpTed('/electrondevices/fp/formulaire/redirect.jsp?redirect=all/pdf/","PDF-", $sHTML);
	$sHTML = str_replace (".pdf ')\">",".pdf')\">", $sHTML);
	$sHTML = str_replace (".pdf')\">","-PDF", $sHTML);
	$sHTML = str_replace ("</a>","", $sHTML);
	
	
	return $sHTML;
}

function getHTMLTemp ($oRes) {
	$sHTML = $oRes->get_html();
	
	$sHTML = str_replace ("<a href=\"javascript:AffpageFpTed('/electrondevices/fp/formulaire/redirect.jsp?redirect=all/pdf/","PDF-", $sHTML);
	$sHTML = str_replace (".pdf ')\">",".pdf')\">", $sHTML);
	$sHTML = str_replace (".pdf')\">","-PDF", $sHTML);
	$sHTML = str_replace ("</a>","", $sHTML);
	
	
	return $sHTML;
}
/*
function initArrayROWSPAN ($ligneAll, $colonneValue) {
	$nombreRowspan = getRowspan ($ligneAll, $ligneAll);
	$nbColspan = countColspan ($ligneAll, $ligneAll);
	$cptArrayTDtoInsertPrev = $cptArrayTDtoInsert;
	$cptArrayTDtoInsert = $cptArrayTDtoInsert+1*$nbColspan;
	$nbRowsToBuilt = $cptArrayTDtoInsert - $cptArrayTDtoInsertPrev;
	for ($p=0; $p<$nbRowsToBuilt; $p++) {
		$arrayROWSPAN[0][($cptArrayTDtoInsert-$nbRowsToBuilt+$p)] = $nombreRowspan;
		$arrayROWSPANValue[0][($cptArrayTDtoInsert-$nbRowsToBuilt+$p)]=$colonneValue;
	}
	return ($arrayROWSPAN);
}


function initArrayROWSPANValue ($ligneAll, $colonneValue) {
	$nombreRowspan = getRowspan ($ligneAll, $ligneAll);
	$nbColspan = countColspan ($ligneAll, $ligneAll);
	$cptArrayTDtoInsertPrev = $cptArrayTDtoInsert;
	$cptArrayTDtoInsert = $cptArrayTDtoInsert+1*$nbColspan;
	$nbRowsToBuilt = $cptArrayTDtoInsert - $cptArrayTDtoInsertPrev;
	for ($p=0; $p<$nbRowsToBuilt; $p++) {
		$arrayROWSPAN[0][($cptArrayTDtoInsert-$nbRowsToBuilt+$p)] = $nombreRowspan;
		$arrayROWSPANValue[0][($cptArrayTDtoInsert-$nbRowsToBuilt+$p)]=$aItems[1][$l];
	}
	return ($arrayROWSPANValue);
}
*/


function initRowspanColspan($html) {
	$arrayTR = getArrayByTR ($html);
	$nombreColonnes = getNombreColonnes($arrayTR);
	$nombreLignes = getNombreLignes($arrayTR);
	
	$htmlToSave = "";
	$ligneAllAll = "";
	$arrayROWSPAN = array ();
	$arrayROWSPANValue = array ();
	$arrayROWSPANTemp = array ();
	$arrayROWSPANTempValue = array ();
	$nombreColonnesAvecActions =  getNbColonnesActionsDroite() + $nombreColonnes;
	$ligneTDprecedent = "";
	
	for ($i=0; $i<$nombreLignes;$i++){
		if ($i == 0) 
			$lignePrecedente = "";
		else {
			$lignePrecedente = $arrayTR[$i-1];
			$aItemsPrecedent = array();
			preg_match_all  ("|<td[^>]*>(.*)<\/td>|U", $lignePrecedente, $aItemsPrecedent);
			$arrayTDPrecedent = $aItemsPrecedent[1];
			$arrayTDBalisePrecedent = $aItemsPrecedent[0];
			
			$lignePrecedentePrecedente = $arrayTR[$i-2];
			$aItemsPrecedentePrecedente = array();
			preg_match_all  ("|<td[^>]*>(.*)<\/td>|U", $lignePrecedentePrecedente, $aItemsPrecedentePrecedente);
			$arrayTDPrecedentePrecedente = $aItemsPrecedentePrecedente[1];
			$arrayTDBalisePrecedentePrecedente = $aItemsPrecedentePrecedente[0];
			
		}
		$ligneTR = $arrayTR[$i];
		$ligneAll ="";
		$ligneAllTR ="";
		$aItems = array();
		
		preg_match_all  ("|<td[^>]*>(.*)<\/td>|U", $ligneTR, $aItems);
		$arrayTD = $aItems[1];
		$arrayTDBalise = $aItems[0];
		$idLigne = $i+1;
		$cptArrayTD = 0;
		$cptArrayTDtoInsert = -1;
		$valuePDF = "";
		if ($i>1) { 
			$valuePDF2 = $_POST[($i+1)."/PDF"];
			if ($valuePDF2 != "") {
				$valuePDF = "PDF-".$valuePDF2."-PDF";
				
			} 
		}
		//if ($i==1) pre_dump($arrayTD);
	
		//if ($i == 3) {
		
		$ligneTD ="";
		for ($l=0;$l<$nombreColonnes;$l++) {
			
			//echo "\n-------------------------------------------\n";
			if ($l == 0) {
				$contenuPrecedentLigne = "";
				//echo $i." ".$l." A\n";
			}
			else {
				$contenuPrecedentLigne = $arrayTD[($l-1)];
				//echo $i." ".$l." B\n";
			}	
			if ($i == 0) {
				//echo $i." ".$l." C\n";
				$contenuPrecedentColonne = "";
			}
			else {
				//echo $i." ".$l." D\n";
				$contenuPrecedentColonne = $arrayTDPrecedent[$l];
			}
				
			$contenuCourant = $arrayTD[$l];
			/*echo "ligne ".($i)."\n";
			echo "**--". $contenuCourant."\n";
			echo "--**".$contenuPrecedentColonne."\n";*/
			$contenuCourant=str_replace (' ', '&nbsp;', $contenuCourant);
			$contenuPrecedentLigne=str_replace (' ', '&nbsp;', $contenuPrecedentLigne);
			$contenuPrecedentColonne=str_replace (' ', '&nbsp;', $contenuPrecedentColonne);
			$arrayTDPrecedent[$l-1]=str_replace (' ', '&nbsp;', $arrayTDPrecedent[$l-1]);
			$arrayTDPrecedent[$l+1]=str_replace (' ', '&nbsp;', $arrayTDPrecedent[$l+1]);
			$contenuDessusX2 =str_replace (' ', '&nbsp;', $arrayTDPrecedentePrecedente[$l]);
			$contenuDessusX2Droite=str_replace (' ', '&nbsp;', $arrayTDPrecedentePrecedente[$l+1]);
			$contenuDessusX2Gauche=str_replace (' ', '&nbsp;', $arrayTDPrecedentePrecedente[$l-1]);
			//echo "****************".$arrayTDPrecedent[$l-1]."---------".$contenuCourant."************\n";
			
			//echo "contenuCourant : ".$contenuCourant."\n";
			// à gauche
			//echo "contenuPrecedentLigne : ".$contenuPrecedentLigne."\n";
			//  audessu
		//	echo "contenuPrecedentColonne : ".$contenuPrecedentColonne."\n";
			// audessu à gauche
			//echo "arrayTDPrecedent[$l-1] : ".$arrayTDPrecedent[$l-1]."\n";
			// audessu à droite
			//echo "arrayTDPrecedent[$l+1] : ".$arrayTDPrecedent[$l+1]."\n";
			
			//echo "contenuDessusX2 : ".$contenuDessusX2."\n";
			// audessu à droite
			//echo "contenuDessusX2Droite : ".$contenuDessusX2Droite."\n";
			//echo "contenuDessusX2Gauche : ".$contenuDessusX2Gauche."\n";
			if ((($contenuCourant == $contenuPrecedentLigne && $l>0) || ($contenuCourant == $contenuPrecedentColonne && $i >0)) ) {
				
				// 2ème ligne traitement différent)
				
				if ($i==2) {
					if ($contenuCourant == $contenuPrecedentColonne) {
						 $ligneTD.= "";	
					}
					
				}
				else { 
				 
					if ($arrayTDPrecedent[$l-1]==$contenuCourant && $contenuPrecedentLigne==$contenuCourant &&($l>2 && $i>2)) {
					//if ($arrayTDPrecedent[$l-1]==$contenuCourant && $contenuPrecedentLigne==$contenuCourant &&($l>2 && $i!=2 && $i!=1)) {
					//if ($arrayTDPrecedent[$l-1]==$contenuCourant && ($l>2 && $i!=2 && $i!=1)) {
					 $ligneTD.= "<td ".getColorTD ($idLigne)."></td>";	
						//echo "cas1";
					}
					else if ($arrayTDPrecedent[$l-1]!=$contenuCourant && ($contenuCourant!=$contenuPrecedentLigne)&& $i==2 && $l!=($nombreColonnes-1)) {
					 $ligneTD.= "<td ".getColorTD ($idLigne)."></td>";	
						//echo "cas3";
					}
					else if ($contenuCourant==$contenuPrecedentColonne && ($contenuCourant!=$contenuPrecedentLigne)&& $l==($nombreColonnes-1)) {
					 $ligneTD.= "";	
						//echo "cas9";
					}
					else if ( $contenuCourant!=$contenuPrecedentLigne && $contenuCourant==$contenuPrecedentColonne && $contenuCourant != $contenuDessusX2 && ($contenuCourant == $arrayTDPrecedent[$l-1] || $contenuCourant == $arrayTDPrecedent[$l+1])) {
					   $ligneTD.= "<td ".getColorTD ($idLigne)."></td>";	
						//echo "cas12";
					}
					else if ( $contenuCourant!=$contenuPrecedentLigne && $contenuCourant==$contenuPrecedentColonne && $contenuCourant == $contenuDessusX2 && ($contenuDessusX2 != $contenuDessusX2Droite || $contenuDessusX2 != $contenuDessusX2Droite)) {
					   $ligneTD.= "";	
						//echo "cas13";
					}
					else if ( $contenuCourant==$contenuPrecedentColonne && $contenuCourant!=$contenuPrecedentLigne &&  $contenuCourant!=$contenuDessusX2 && $contenuCourant==$contenuDessusX2Droite && $contenuCourant != $arrayTDPrecedent[$l+1] && $contenuCourant != $arrayTDPrecedent[$l-1] && $l>0) {
					  $ligneTD.= "<td ".getColorTD ($idLigne)."></td>";	
						//echo "cas14";
					}
					else if ( $contenuCourant==$contenuPrecedentColonne && $contenuCourant != $contenuPrecedentLigne) {
					  $ligneTD.= "";	
						//echo "cas100";
					}
					else if ( $contenuCourant==$contenuPrecedentLigne && $contenuCourant==$contenuPrecedentColonne && ($contenuCourant == $arrayTDPrecedent[$l-1] || $contenuCourant == $arrayTDPrecedent[$l+1])) {
					  $ligneTD.= "";	
						//echo "cas11";
					}
					
					else if ( $contenuCourant==$contenuPrecedentColonne && $contenuCourant == $arrayTDPrecedent[$l+1] && $i>0) {
					  $ligneTD.= "<td ".getColorTD ($idLigne)."></td>";	
						//echo "cas5";
					} 
					else if ( $contenuCourant==$contenuPrecedentColonne) {
					 $ligneTD.= "";	
						//echo "cas6";
					}
					else if ( $contenuCourant==$contenuPrecedentLigne && $i==1 && $arrayTDPrecedent[$l-1]==$contenuCourant) {
					 $ligneTD.= "<td ".getColorTD ($idLigne)."></td>";	
						//echo "cas8";
					}
					else {
						 $ligneTD.= "";
						 //echo "cas7";
					}
				}
			
				
			}
			else {
				// je gère le colspan
				//echo "là";
				$nbColspan = 1;
				for ($k=($l+1); $k<$nombreColonnes;$k++) {
					$contenuCourant=str_replace (' ', '&nbsp;', $contenuCourant);
					$arrayTD[$k-1]=str_replace (' ', '&nbsp;', $arrayTD[$k-1]);
					$arrayTD[$k]=str_replace (' ', '&nbsp;', $arrayTD[$k]);
					if ($arrayTD[$k-1] == $arrayTD[$k] && $arrayTD[$k] == $contenuCourant) {
						////echo "----------".$contenuCourant."   col ".($k-1)." "."col ".$k." ".$arrayTD[$k-1]." ".$arrayTD[$k]."\n";
						$nbColspan = $nbColspan+1;
					}
					else {
						break;
					}
					$contenuCourant=str_replace ('&nbsp;', ' ', $contenuCourant);
				}
				// je gère le rowspan
				/*echo "ligne ".($i)."\n";
				echo "--". $contenuCourant."\n";
				echo "**".$contenuPrecedentColonne."\n";*/
				//if ($contenuCourant == $contenuPrecedentColonne ) {	
				$nbRowspan = 1;
				for ($m=($i+1); $m<$nombreLignes;$m++) {
					$lignePrecedente_ = $arrayTR[$m-1];
					$aItemsPrecedent_ = array();
					preg_match_all  ("|<td[^>]*>(.*)<\/td>|U", $lignePrecedente_, $aItemsPrecedent_);
					$arrayTDPrecedent_ = $aItemsPrecedent_[1];
					$arrayTDBalisePrecedent_ = $aItemsPrecedent_[0]; 
					$ligneSuivante_ = $arrayTR[$m];
					$aItemsSuivant_ = array();
					preg_match_all  ("|<td[^>]*>(.*)<\/td>|U", $ligneSuivante_, $aItemsSuivant_);
					$arrayTDSuivant_ = $aItemsSuivant_[1];
					$arrayTDBaliseSuivant_ = $aItemsSuivant_[0];
					$contenuCourant=str_replace (' ', '&nbsp;', $contenuCourant);
					$arrayTDPrecedent_[$l]=str_replace (' ', '&nbsp;', $arrayTDPrecedent_[$l]);
					$arrayTDSuivant_[$l]=str_replace (' ', '&nbsp;', $arrayTDSuivant_[$l]);
					if ($contenuCourant == $arrayTDSuivant_[$l] &&  $arrayTDPrecedent_[$l] == $arrayTDSuivant_[$l] ) {
						/*echo "\n\n".$m."\n";
						echo "---".$contenuCourant."\n";
						echo "---".$arrayTDSuivant_[$l]."\n";
						echo "---".$arrayTDPrecedent_[$l]."\n";*/
						if ($arrayTDPrecedent_[$l] == $arrayTDSuivant_[$l]) echo "oui";
						$nbRowspan = $nbRowspan+1;
					}
					else {
						break;
					}
					$contenuCourant=str_replace ('&nbsp;', ' ', $contenuCourant);
				}
				//}
				$contenuCourant=str_replace ('&nbsp;', ' ', $contenuCourant);
				if (($nbColspan <= 1 && ($nbRowspan <=1|| $i==1)) ) {
					//echo "a";
					$ligneTD.= $arrayTDBalise[$l];
				}
				else {
					//echo "b";
					if ($nbColspan == 1) $sColspan = "";
					else $sColspan = "colspan=\"".$nbColspan."\"";
					if ($nbRowspan == 1) $sRowspan = "";
					else {
						//echo "c";
						if ($i == 0 && $nbRowspan > 2) $nbRowspan =2;
						else if ($i == 1) $nbRowspan =0;
						$sRowspan = "rowspan=\"".$nbRowspan."\"";
						//echo "colspan=".$nbColspan."\n";
						if ($nbColspan >1) {
							$nbRowspan = 0;
							//echo "oui";
						}
						if ($nbRowspan ==0) $sRowspan = "";
						
					}
					$ligneTD.= "<td ".getColorTD($idLigne)." ".$sColspan." ".$sRowspan." >".$contenuCourant."</td>";
					//echo "\ncolrow ".$nbRowspan." ".$nbColspan."\n";
				}
				 
				if ($i>1 && $l==0) {  
					if ($valuePDF!="") {
					//echo $contenuCourant."<br>\n";
					$contenuCourantPDF = str_replace ("<b>", "", $contenuCourant);
					$contenuCourantPDF = str_replace ("</b>", "", $contenuCourantPDF);
					$contenuCourantPDF = str_replace ("<B>", "", $contenuCourantPDF);
					$contenuCourantPDF = str_replace ("</B>", "", $contenuCourantPDF);
					//echo $contenuCourantPDF."<br>\n";
					if (ereg("-PDF", $contenuCourantPDF)) {
						$contenuCourantPDF = strchr($contenuCourantPDF, "-PDF");
						$contenuCourantPDF = str_replace ("-PDF", "", $contenuCourantPDF);
					}
					//echo $contenuCourantPDF."<br>\n";
					$contenuCourantPDF = ltrim(rtrim($valuePDF))."<b>".ltrim(rtrim($contenuCourantPDF))."</b>";
					//echo $contenuCourantPDF."<br>\n";
					$ligneTD = str_replace ($contenuCourant, $contenuCourantPDF, $ligneTD);
					
					}
				}
			}
			

		} 

		
		//echo "\nlignette".$i."\n-------------------\n".$ligneTD."\n-------------------\n";
		if ($ligneTD == "" ) {
			if ($i>1) 
				$htmlToSave.= "<tr><td ".getColorTD ($idLigne)." colspan=\"".$nombreColonnes."\"></td></tr>";
			else 
				$htmlToSave.= "<tr></tr>";
			
		}
		else {
			$htmlToSave.= "<tr>".$ligneTD."</tr>";
		}
		
	}
	
	return $htmlToSave;
}


function deleteRowspanColspan ($oRes) {
	$htmlfusion = $oRes->get_html();
	$htmlSansFusion ="";
	//je traite
	$arrayTR = getArrayByTR ($htmlfusion);
	$nombreColonnes = getNombreColonnes($arrayTR);
	$nombreLignes = getNombreLignes($arrayTR);
	$htmlToSave = "";
	$ligneAllAll = "";
	$arrayROWSPAN = array ();
	$arrayROWSPANValue = array ();
	$nombreColonnesAvecActions =  getNbColonnesActionsDroite() + $nombreColonnes;
	for ($i=0; $i<$nombreLignes;$i++){
		$ligneTR = $arrayTR[$i];
		$ligneAll ="";
		$ligneAllTR ="";
		$aItems = array();
		
		preg_match_all  ("|<td[^>]*>(.*)<\/td>|U", $ligneTR, $aItems);
		$arrayTD = $aItems[1];
		$idLigne = $i+1;
		//if ($i == 1) pre_dump($aItems[0]);
		$cptArrayTD = 0;
		$cptArrayTDtoInsert = -1;
		
		
		
		for ($l=0;$l<$nombreColonnes;$l++) {
			
			if ($arrayROWSPAN[0][$l] != "" && $i==1) {
				$idColonne = $l+1;
				$ligneTD = "<td>".$arrayROWSPANValue[0][$l]."</td>";
				$boolRechercheClass = strpos($ligneTD, "class");
				if ($boolRechercheClass == "" ) {
					$colorTD = getColorTD ($idLigne);
					$ligneTD = str_replace ("<td", "<td ".$colorTD, $ligneTD);
				}
				$ligneAllTR.= $ligneTD; 
			}
			else {
				$ligneTD = $aItems[0][$cptArrayTD];
				$idColonne = $l+1;
				if ($valuePDF != "") {
					$valueSansPDF = str_replace ("PDF-".$valuePDF."-PDF", "", $arrayTD[$cptArrayTD]);
					
				}
				
				$nbColspan = countColspan ($ligneTD, $aItems[0][$cptArrayTD]);
				$ligneTD = getColspan ($ligneTD, $aItems[0][$cptArrayTD]);
				$nombreRowspan = getRowspan ($ligneTD, $aItems[0][$cptArrayTD]);
				$l=$l+$nbColspan-1;
				$cptArrayTDtoInsertPrev = $cptArrayTDtoInsert;
				$cptArrayTDtoInsert = $cptArrayTDtoInsert+1*$nbColspan;
				
				if ($i == 0) {
					$ligneTD = str_replace ("rowspan=\"".$nombreRowspan."\"", "", $ligneTD);
					$nbRowsToBuilt = $cptArrayTDtoInsert - $cptArrayTDtoInsertPrev;
					if ($i==0) {
						for ($p=0; $p<$nbRowsToBuilt; $p++) {
							$arrayROWSPAN[0][($cptArrayTDtoInsert-$nbRowsToBuilt+1+$p)] = $nombreRowspan;
							$arrayROWSPANValue[0][($cptArrayTDtoInsert-$nbRowsToBuilt+1+$p)]=$aItems[1][$cptArrayTD];
						}
					}
				}
	
				$ligneTDcontenu = $aItems[1][$cptArrayTD];
				if ($l==0) {
					if (!ereg ("<b>", $ligneTDcontenu) && ltrim($ligneTDcontenu)!="") {
					//echo "-------------------";
						$ligneTD = str_replace ($aItems[1][$cptArrayTD], "<b>".$aItems[1][$cptArrayTD]."</b>", $ligneTD);
					}
					if ($valuePDF!="") {
						$urlPDF="<a href=\"http://".$_SERVER['HTTP_HOST']."/backoffice/cms/utils/telecharger.php?chemin=/custom/upload/composant/tableaux/&file=/custom/upload/composant/tableaux/".$valuePDF.".pdf\" target=\"_blank\">";
						$ligneTD = str_replace ("PDF-".$valuePDF."-PDF", $urlPDF, $ligneTD);
						$ligneTD = $ligneTD."</a>";
					}
					//echo "--".$aItems[1][$cptArrayTD];
				}
				
				//recherche de classe
			
				$boolRechercheClass = strpos($ligneTD, "class");
				if ($boolRechercheClass == "" ) {
					$colorTD = getColorTD ($idLigne);
					$ligneTD = str_replace ("<td", "<td ".$colorTD, $ligneTD);
				}
				
				$ligneAllTR.= $ligneTD; 
				$cptArrayTD = $cptArrayTD + 1;
			}
			//if ($i == 1) echo "****************".$l." ".$ligneTD."<br>";
		}
	
		//if ($i == 1) {
		$colorTD = getColorTD ($idLigne);
		
		$idColonne = $nombreColonnes+1;
		
		$ligneAllAll.= "<tr>".$ligneAllTR;
		$ligneAllAll.= "</tr>";
		$htmlToSave.= $ligneAllTR;
	}
	
	
	$ligneAllAll= checkCss($ligneAllAll);
	$oRes->set_htmltmp($ligneAllAll);
	$bRetour = dbUpdate($oRes);
	return $ligneAllAll;
	
}
?>