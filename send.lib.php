<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*
envoi de mail

*/
//include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
//include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

include_once($_SERVER['DOCUMENT_ROOT']."/include/cms-inc/mail_lib.php");



function send_mail($id_inscrit, $to , $sujet , $html , $text, $from) {

$bDebug=true;

if ($bDebug) print("<br>SENDMAIL");
if ($bDebug) print("<br>1=>'$id_inscrit', <br>2=>'$to' , <br>3=>'$sujet' , <br>4=>'$html' , <br>5=>'$text', <br>6=>'$from'");

	if (($to != "") && ($sujet != "") && ($html != "" || $text != "") && ($from != "")) {

if ($bDebug) print("<br>OK");

		// objet inscrit
		$oInscrit = new Inscrit($id_inscrit);

		$aInteret = getInteretForInscrit($id_inscrit);
		$sInteret = "";
		for ($i=0; $i<sizeof($aInteret); $i++) {
			$oInteret = $aInteret[$i];
			$sInteret.= $oInteret->getInt_libelle();
			if ($i != sizeof($aInteret) -1) $sInteret.=" - ";
		}

		$html = str_replace("[MAIL]", $to, $html);
		$html = str_replace("[ID_INSCRIT]", $id_inscrit, $html);
		$html = str_replace("[NOM]", $oInscrit->getIns_nom(), $html);
		$html = str_replace("[PRENOM]", $oInscrit->getIns_prenom(), $html);
		$html = str_replace("[INTERET]", $sInteret, $html);

		$text = str_replace("[MAIL]", $to, $text);
		$text = str_replace("[ID_INSCRIT]", $id_inscrit, $text);
		$text = str_replace("[NOM]", $oInscrit->getIns_nom(), $html);
		$text = str_replace("[PRENOM]", $oInscrit->getIns_prenom(), $html);
		$text = str_replace("[INTERET]", $sInteret, $html);



if ($bDebug) print("<br>1=>'$id_inscrit', <br>2=>'$to' , <br>3=>'$sujet' , <br>4=>'$html' , <br>5=>'$text', <br>6=>'$from'");

		$retourMail = multiPartMail($to , $sujet , $html , $text, $from);

if ($bDebug) print("<br>retourMail=>$retourMail");

		$result = true;

	} else {
		$result = false;
	}

	return($result);
}
?>