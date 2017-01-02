<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// sponthus 31/05/05
// fichier de manipulation des define 
// (définis dans le config du cms)

/*
function doRightFieldName($sField)
function n($sField) 
function getLibelleDefineWithCode($eCode)
function getIdDefineWithCode($eCode)
function lib($eCode)
function showDefine($sLibDefine, $sValDefine)
*/

// vrai nom des champs en BDD (oracle sensible à la casse)
// pour boulogne, BDD oracle créée en Majuscules
function doRightFieldName($sField)
{
	if (DEF_NAME_FIELD == "UPPER") return(strtoupper($sField));
	else if (DEF_NAME_FIELD == "LOWER") return(strtolower($sField));	
	else return($sField);
}

// alias de la fonction précédente
function n($sField) {return doRightFieldName($sField); }


// fonction permettant de renvoyer un libelle define à partir d'un code define
function getLibelleDefineWithCode($eCode)
{
	// fonctionnalités
	if ($eCode == DEF_FONCT0) return DEF_LIBFONCT0;
	if ($eCode == DEF_FONCT1) return DEF_LIBFONCT1;
	if ($eCode == DEF_FONCT2) return DEF_LIBFONCT2;
	if ($eCode == DEF_FONCT3) return DEF_LIBFONCT3;	
	if ($eCode == DEF_FONCT4) return DEF_LIBFONCT4;	
	if ($eCode == DEF_FONCT5) return DEF_LIBFONCT5;	
	if ($eCode == DEF_FONCT6) return DEF_LIBFONCT6;	

	// rangs
	if ($eCode == DEF_ADMIN) return DEF_LIBADMIN;	
	if ($eCode == DEF_GEST) return DEF_LIBGEST;	
	if ($eCode == DEF_REDACT) return DEF_LIBREDACT;	

	// statuts
	if ($eCode == DEF_ID_STATUT_LIGNE) return DEF_LIB_STATUT_LIGNE;	
	if ($eCode == DEF_ID_STATUT_GEST) return DEF_LIB_STATUT_GEST;	
	if ($eCode == DEF_ID_STATUT_REDACT) return DEF_LIB_STATUT_REDACT;	
	if ($eCode == DEF_ID_STATUT_ATTEN) return DEF_LIB_STATUT_ATTEN;	
	if ($eCode == DEF_ID_STATUT_ARCHI) return DEF_LIB_STATUT_ARCHI;	

	// statuts actualité
	if ($eCode == DEF_ID_STATUT_ACTU_LIGNE) return DEF_LIB_STATUT_ACTU_LIGNE;	
	if ($eCode == DEF_ID_STATUT_ACTU_BROUILLON) return DEF_LIB_STATUT_ACTU_BROUILLON;	

	// types des références
	if ($eCode == DEF_ID_REF_01) return DEF_LIB_REF_01;	
	if ($eCode == DEF_ID_REF_02) return DEF_LIB_REF_02;	
	if ($eCode == DEF_ID_REF_03) return DEF_LIB_REF_03;	
	if ($eCode == DEF_ID_REF_04) return DEF_LIB_REF_04;	
	if ($eCode == DEF_ID_REF_05) return DEF_LIB_REF_05;	

	// statut des références
	if ($eCode == DEF_ID_STATUT_REF_BROUILLON) return DEF_LIB_STATUT_REF_BROUILLON;	
	if ($eCode == DEF_ID_STATUT_REF_LIGNE) return DEF_LIB_STATUT_REF_LIGNE;	

	// statuts des newsletter
	if ($eCode == DEF_ID_STATUT_NEWS_ATTEN) return DEF_LIB_STATUT_NEWS_ATTEN;	
	if ($eCode == DEF_ID_STATUT_NEWS_VALID) return DEF_LIB_STATUT_NEWS_VALID;	
	if ($eCode == DEF_ID_STATUT_NEWS_ENVOI) return DEF_LIB_STATUT_NEWS_ENVOI;	

	// newsletter
	if ($eCode == DEF_ID_INSCRIT_0) return DEF_LIB_INSCRIT_0;

	// civilité
	if ($eCode == DEF_ID_CIV_MME) return DEF_LIB_CIV_MME;
	if ($eCode == DEF_ID_CIV_M) return DEF_LIB_CIV_M;
	if ($eCode == DEF_ID_CIV_MLLE) return DEF_LIB_CIV_MLLE;	
}

// alias de la fonction précédente
function lib($eCode)
{
	return getLibelleDefineWithCode($eCode);
}

// affichage des valeurs des define
function showDefine($sLibDefine, $sValDefine)
{
	if ($sLibDefine == "/////////////") {
		$sDebut_Div = "<div style=\"font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 10px;color:#FF9900\">";
		$sFin_Div   = "</div>";
	}
	else {
		$sDebut_Div = "<div style=\"font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 10px;\">";
		$sFin_Div   = "</div>";
	}	
	
	if ($_GET['show'] == "y") print("<br>".$sDebut_Div.$sLibDefine." = ".$sValDefine.$sFin_Div);
}


?>