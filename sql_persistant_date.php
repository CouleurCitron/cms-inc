<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// sponthus 03/11/2005
// fonctions sql persistantes de manipulation dates

/*
function dateToYmd($dateStr){
function to_dbdate($sDate) {
function from_dbdate($Date) {
function from_dbdate_TIMESTAMP($Date) {
function to_dbdate_TIMESTAMP($Date) {	
function from_dbdate_TIMESTAMP_WITH_TIME_ZONE($Date) {
function make_date_jjmmaaaa($Date) {
function to_dbdatetime($sDate) {
function from_dbdatetime($sChampDate) {
function getdatetime() {
function getDateNow() {
function isDdateVide($sChampDate) {
function afficheDate($dDate)
function isDdateNull($sChampDate) {
function getDateNowGB()
function afficheMn($s)
function formatInverse($sDate) {
*/

function dateToYmd($dateStr){
	$dateStr = str_replace("/", "-", $dateStr);
	if (preg_match("/[0-9]{2}-[0-9]{2}-[0-9]{4}/si", $dateStr)){
		return preg_replace("/([0-9]{2})-([0-9]{2})-([0-9]{4})/si", "$3-$2-$1", $dateStr);
	}
	elseif (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{1}/", $dateStr)){
		return $dateStr;
	}
	else{
		return false;
	}
}

//------------------------------------------------
// Fonction spécifique pour convertir la date chaine jj/mm/aaaa en date de la base et l'inverse
//------------------------------------------------

function to_dbdate($sDate) {
	if (DEF_BDD == "POSTGRES" || DEF_BDD == "ORACLE"){
		return "to_date('$sDate', 'dd/mm/yyyy')"; // version oracle et postgres
	}
	if (DEF_BDD == "MYSQL"){
		if (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}.*/msi", $sDate)==1){
			$sDate = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2}).*/msi", "$3/$2/$1", $sDate);
		}
		return "str_to_date('$sDate', '%d/%m/%Y')"; // version oracle et postgres
	}
}



//------------------------------------------------
// renvoie une date de type jj/mm/aaaa à partir d'une date aaaa-mm-jj
// Oracle renvoi aaaa-mm-jj comme date
//------------------------------------------------

function from_dbdate($Date) {
	if ($Date!="") {
		$parse = explode(' ', $Date);
		$Date = str_replace("/", "-", $parse[0]);
		list($an,$mois,$jour) = explode("-",$Date);

		/////////////
		// POSTGRES
		/////////////
		// la date postgres de format "timestamp without time zone" est renvoie ceci : 2005-08-22 00:00:00
		// donc le jour contient aussi les secondes
		if (DEF_BDD == "POSTGRES") $jour = substr($jour, 0, 2);

		return $jour."/".$mois."/".$an.(!empty($parse[1]) ? ' '.$parse[1] : ''); // version oracle (et autre?)
	} else {
		return "";
	}
}

//------------------------------------------------
// renvoie une date de type jj/mm/aaaa HH:mm:ss à partir d'une date aaaa/mm/dd HH:mm:ss
//------------------------------------------------
function from_dbdate_TIMESTAMP($Date) {	
	if (($Date != '') && ($Date != '0000-00-00 00:00:00')) {
		$timestp =  strtotime($Date);
		return date("d/m/Y H:i:s", $timestp);
	}
	else {
		return "";
	}
}

//------------------------------------------------
// renvoie une date de type aaaa/mm/dd HH:mm:ss à partir d'une date jj/mm/aaaa HH:mm:ss
//------------------------------------------------
function to_dbdate_TIMESTAMP($Date) {	
	if (preg_match("/[0-9]{2}[-\/]{1}[0-9]{2}[-\/]{1}[0-9]{4}.*/msi", $Date)==1){ // FR 2 US
		$Date = preg_replace("/([0-9]{2})[-\/]{1}([0-9]{2})[-\/]{1}([0-9]{4})(.*)/msi", "$3-$2-$1$4", $Date);
	}
	if($Date!="") {
		$timestp =  strtotime($Date);
		return "'".date("Y-m-d H:i:s", $timestp)."'";
	}
	else {
		return "'NULL'";
	}
}

//------------------------------------------------
// renvoie une date de type jj/mm/aaaa à partir d'une date 09/05/05 17:24:32,301077 +02:00
// Oracle renvoi 09/05/05 17:24:32,301077 +02:00 comme date
//------------------------------------------------

function from_dbdate_TIMESTAMP_WITH_TIME_ZONE($Date) {
	if($Date!="") {
		$Date = str_replace('-', '/', $Date);
		list($jour, $mois, $an) = explode("/",$Date);

		/////////////
		// ORACLE et TIMESTAMP_WITH_TIME_ZONE
		/////////////
		// la date Oracle de format "timestamp without time zone" renvoie ceci : 09/05/05 17:24:32,301077 +02:00
		// donc l'annéecontient aussi les secondes
		if (DEF_BDD == "ORACLE") $an = substr($an, 0, 2);

		return $jour."/".$mois."/".$an; // version oracle (et autre?)
	}
	else {
		return "";
	}
}

//------------------------------------------------
// renvoie une date de type jj/mm/aaaa à partir d'une date 09/05/05 17:24:32,301077 +02:00
// Oracle renvoi 09/05/05 17:24:32,301077 +02:00 comme date
//------------------------------------------------

function make_date_jjmmaaaa($Date) {
	if($Date!="") {
		list($jour, $mois, $an) = explode("/",$Date);

		/////////////
		// TIMESTAMP_WITH_TIME_ZONE
		/////////////
		// la date de format "timestamp without time zone" renvoie ceci : 09/05/05 17:24:32,301077 +02:00
		// donc l'année contient aussi les secondes
		$an = substr($an, 0, 4);

		return $jour."/".$mois."/".$an; // version oracle (et autre?)
	}
	else {
		return "";
	}
}


//------------------------------------------------
// Fonction spécifique pour convertir la date/heure chaine jj/mm/aaaa en date/heure de la base et l'inverse
//------------------------------------------------

function to_dbdatetime($sDate) {
	if (DEF_BDD != "MYSQL")	return "to_date('$sDate', 'DD/MM/YYYY HH24:MI:SS')"; // version oracle et postgres
	else return "str_to_date('$sDate', '%d/%m/%Y %H:%i:%s')";  // version mysql
}


//------------------------------------------------
//------------------------------------------------

function from_dbdatetime($sChampDate) {
	if (DEF_BDD == "ORACLE" || DEF_BDD == "POSTGRES") return "to_char($sChampDate, 'DD/MM/YYYY HH24:MI:SS') AS \"".n($sChampDate)."\""; // version oracle et postgres
	else if (DEF_BDD == "MYSQL") return "date_format($sChampDate, '%d/%m/%Y %H:%i:%s') AS \"".n($sChampDate)."\""; // version mysql
}




//------------------------------------------------
// récupère la date à un format "universel" réutilisable
//------------------------------------------------

function getdatetime() {
	return date("d/m/Y H:i:s");
}


//------------------------------------------------
// récupère la date à un format "universel" réutilisable
//------------------------------------------------
function getDateNow() {
	return date("d/m/Y");
}

//------------------------------------------------
// teste si un champ date est vide
//------------------------------------------------
function isDdateVide($sChampDate) {
	if (DEF_BDD != "MYSQL")	{ if ($sChampDate == '') return true; else return false; } // version oracle et postgres
	else { if ($sChampDate == '00/00/0000') return true; else return false; } // version mysql
}

//------------------------------------------------
// affichage d'une date
//------------------------------------------------
function afficheDate($dDate)
{
	if (DEF_BDD != "MYSQL")	return $dDate; // version oracle et postgres
	else { if ($dDate == '00/00/0000') return ""; else return $dDate; } // version mysql
}

//------------------------------------------------
//------------------------------------------------
function isDdateNull($sChampDate) {
	if (DEF_BDD != "MYSQL")	return " IS NULL "; // version oracle et postgres
	else return " date_format(".$sChampDate.", '%d/%m/%Y') = '00/00/0000' "; // version mysql
}

function getDateNowGB()
{
	return(date("D").",&nbsp;".date("j")."&nbsp;".date("M")."&nbsp;".date("Y"));
}

// affichage des mn sous la forme 2am 10min
function afficheMn($s){

	$aMn = explode(":", $s);

	if ($aMn[0] == "01") $sMn = "1am";
	else if ($aMn[0] == "02") $sMn = "2am";
	else if ($aMn[0] == "03") $sMn = "3am";
	else if ($aMn[0] == "04") $sMn = "4am";
	else if ($aMn[0] == "05") $sMn = "5am";
	else if ($aMn[0] == "06") $sMn = "6am";
	else if ($aMn[0] == "07") $sMn = "7am";
	else if ($aMn[0] == "08") $sMn = "8am";
	else if ($aMn[0] == "09") $sMn = "9am";
	else if ($aMn[0] == "10") $sMn = "10am";
	else if ($aMn[0] == "11") $sMn = "11am";
	else if ($aMn[0] == "12") $sMn = "12am";
	else if ($aMn[0] == "13") $sMn = "1pm";
	else if ($aMn[0] == "14") $sMn = "2pm";
	else if ($aMn[0] == "15") $sMn = "3pm";
	else if ($aMn[0] == "16") $sMn = "4pm";
	else if ($aMn[0] == "17") $sMn = "5pm";
	else if ($aMn[0] == "18") $sMn = "6pm";
	else if ($aMn[0] == "19") $sMn = "7pm";
	else if ($aMn[0] == "20") $sMn = "8pm";
	else if ($aMn[0] == "21") $sMn = "9pm";
	else if ($aMn[0] == "22") $sMn = "10pm";
	else if ($aMn[0] == "23") $sMn = "11pm";
	else if ($aMn[0] == "24") $sMn = "12pm";

	if ($aMn[1] != "") $sMn.= " ".$aMn[1]."min";

	return($sMn);
}

// prend une date au format jj/mm/aaaa et la met au format aaaa/mm/jj
function formatInverse($sDate) {

	if ($sDate != "") {
		list($jour, $mois, $an) = explode("/", $sDate);
	}
	$sInserve = $an.$mois.$jour;
	return($sInserve);
}

function timestampFormat($sDate) {/*
	$sDate = str_replace ("//", "", $sDate);
	list($aDate, $aHoraire) = explode(" ", $sDate);
	list($annee, $mois, $jour) = explode("-", $aDate);
	list($heure, $minute, $seconde) = explode(":", $aHoraire);
	$sDateFormat = $jour."/".$mois."/".$annee." ".$heure.":".$minute.":".$seconde;
	*/
	if(($sDate != '') && ($sDate != '0000-00-00 00:00:00')) {
		//echo "ici".$sDate;
		if (preg_match("/[0-9]{2}[- ]{1}[0-9]{2}[-:]{1}[0-9]{2}[-:]{1}[0-9]{2}[-\/]{1}[0-9]{2}[-\/]{1}[0-9]{4}/si", $sDate)){ // FR 2 US 
			$sDate = preg_replace("/([0-9]{2})[- ]{1}([0-9]{2}[-:]{1}[0-9]{2}[-:]{1}[0-9]{2})[-\/]{1}([0-9]{2})[-\/]{1}([0-9]{4})/si", "$4-$3-$1 $2", $sDate);
		
		}
		else if (preg_match("/[0-9]{2}[-\/]{1}[0-9]{2}[-\/]{1}[0-9]{4}.*/si", $sDate)){ // FR 2 US
			$sDate = preg_replace("/([0-9]{2})[-\/]{1}([0-9]{2})[-\/]{1}([0-9]{4})(.*)/si", "$3-$2-$1$4", $sDate);
			
		}
		$timestp =  strtotime($sDate);
		return date("Y/m/d H:i:s", $timestp);
		//return($sDateFormat);
	}
	else{
		return "";
	}
}

function getDateFR ($sDate) {
	$jourFR = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
	$jourEN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
	$moisFR = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
	$moisEN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	
	for ($i = 0; $i<sizeof($jourFR); $i++) {
		$sDate = str_replace($jourEN[$i], $jourFR[$i], $sDate);
	}
	for ($j = 0; $j<sizeof($moisFR); $j++) {
		$sDate = str_replace($moisEN[$j], $moisFR[$j], $sDate);
	}
	return $sDate;
}

function getDateEN ($sDate) {
	for ($i = 0; $i<sizeof($jourFR); $i++) {
		$sDate = str_replace($jourEN[$i], $jourFR[$i], $sDate);
	}
	for ($j = 0; $j<sizeof($moisFR); $j++) {
		$sDate = str_replace($moisEN[$j], $moisFR[$j], $sDate);
	}
	/*list($jour, $mois, $an) = explode(" ", $sDate);
	$sDate = str_replace($mois, $jour, $an);*/
	
	return $sDate;
}
?>