<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_assobo_userscms_statut_content :: class cms_assobo_userscms_statut_content

SQL mySQL:

DROP TABLE IF EXISTS cms_assobo_userscms_statut_content;
CREATE TABLE cms_assobo_userscms_statut_content
(
	xus_id			int (11) PRIMARY KEY not null,
	xus_bo_users			int (11),
	xus_cms_statut_content			int (11)
)

SQL Oracle:

DROP TABLE cms_assobo_userscms_statut_content
CREATE TABLE cms_assobo_userscms_statut_content
(
	xus_id			number (11) constraint xus_pk PRIMARY KEY not null,
	xus_bo_users			number (11),
	xus_cms_statut_content			number (11)
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_assobo_userscms_statut_content" is_asso="true" prefix="xus" display="bo_users" abstract="cms_statut_content">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false" option="asso">
<option name="bo_users" asso="bo_users" type="asso"/>
<option name="cms_statut_content" asso="cms_statut_content" type="asso"/>
</item>
<item name="bo_users" libelle="Utilisateurs" type="int" length="11" default="-1" order="true"  fkey="bo_users"  list="true"/>
<item name="cms_statut_content" libelle="Statut d'un contenu" type="int" length="11" default="-1" order="true"  fkey="cms_statut_content"  list="true"/>
</class>


==========================================*/

class cms_assobo_userscms_statut_content
{
var $id;
var $bo_users;
var $cms_statut_content;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_assobo_userscms_statut_content\" is_asso=\"true\" prefix=\"xus\" display=\"bo_users\" abstract=\"cms_statut_content\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\" option=\"asso\">
<option name=\"bo_users\" asso=\"bo_users\" type=\"asso\"/>
<option name=\"cms_statut_content\" asso=\"cms_statut_content\" type=\"asso\"/>
</item>
<item name=\"bo_users\" libelle=\"Utilisateurs\" type=\"int\" length=\"11\" default=\"-1\" order=\"true\"  fkey=\"bo_users\"  list=\"true\"/>
<item name=\"cms_statut_content\" libelle=\"Statut d\'un contenu\" type=\"int\" length=\"11\" default=\"-1\" order=\"true\"  fkey=\"cms_statut_content\"  list=\"true\"/>
</class>";

var $sMySql = "CREATE TABLE cms_assobo_userscms_statut_content
(
	xus_id			int (11) PRIMARY KEY not null,
	xus_bo_users			int (11),
	xus_cms_statut_content			int (11)
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_assobo_userscms_statut_content") == false){
		dbExecuteQuery($this->sMySql);
	}

	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;
	} else {
		$this->id = -1;
		$this->bo_users = -1;
		$this->cms_statut_content = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Xus_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xus_bo_users", "entier", "get_bo_users", "set_bo_users");
	$laListeChamps[]=new dbChamp("Xus_cms_statut_content", "entier", "get_cms_statut_content", "set_cms_statut_content");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_bo_users() { return($this->bo_users); }
function get_cms_statut_content() { return($this->cms_statut_content); }


// setters
function set_id($c_xus_id) { return($this->id=$c_xus_id); }
function set_bo_users($c_xus_bo_users) { return($this->bo_users=$c_xus_bo_users); }
function set_cms_statut_content($c_xus_cms_statut_content) { return($this->cms_statut_content=$c_xus_cms_statut_content); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xus_id"); }
// statut
function getGetterStatut() {return("none"); }

function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assobo_userscms_statut_content"); }
function getClasse() { return("cms_assobo_userscms_statut_content"); }
function getDisplay() { return("bo_users"); }
function getAbstract() { return("cms_statut_content"); }


} //class


function sendAlerteToAdmin($oAssoAdminAlerte, $idUser, $idContent, $idSite) {
 
 	// utilisateur connecté
 	$oUserLogged =  getObjectById("bo_users", $idUser);
	$userLogged =  $oUserLogged->getUser_prenom()." ".$oUserLogged->getUser_nom()." ( login : ".$oUserLogged->getUser_login()." )";
	
	// admin
	$admin =  getObjectById("bo_users", $oAssoAdminAlerte->get_bo_users());  
	
	// statut
	$oStatutContent =  getObjectById("cms_statut_content", $oAssoAdminAlerte->get_cms_statut_content());
	$idStatutContent =  $oStatutContent->get_value();
	
	// minisite
	$oSite =  getObjectById("cms_site", $idSite);
	$sSite =  $oSite->get_name();
	
	switch ($idStatutContent) {
	 
	case 1:
		$statutContent=DEF_LIB_STATUT_ATTEN;
		break;
	case 2:
		$statutContent=DEF_LIB_STATUT_REDACT;
		break;
	case 3:
		$statutContent=DEF_LIB_STATUT_GEST;
		break;
	case 4:
		$statutContent=DEF_LIB_STATUT_LIGNE;
		break;
	case 5:
		$statutContent=DEF_LIB_STATUT_ARCHI;
		break; 
	}
	
	// contenu 
	$oContentUpdated = getObjectById("cms_content", $idContent);
	$contentUpdated = $oContentUpdated->getName_content();
	
	// node
	$aNode =  getObjectById("cms_arbo_pages", $oContentUpdated->getNodeid_content());  
	$node = $aNode->get_absolute_path_name();
	
	
	// page
	$aPage =   dbGetObjectsFromFieldValue("cms_struct_page", array("getId_content"), array($idContent), "") ; 
	$oPage = getObjectById("cms_page", $aPage[0]->getId_page());  
	$sPage = $oPage->getName_page();
	 
	 
	
	$expediteur = DEF_CONTACT_FROM_EMAIL;
	$destinataire = $admin->get_mail();
	//$destinataire = "thao@couleur-citron.com";
	
	//--- la structure du mail ----//  
 
	$smailSujet = "Mise à jour d'un contenu du site ".$sSite;
	$smailHTML = "<table style='font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 11px; width: 80%; border: 0px solid #000000; padding: 5px;' align='left' cellspacing='0'><TR style='background-color: #ffffff;'><TD width='40%'>&nbsp;</TD><TD width='60%'>&nbsp;</TD></TR>";
	$smailHTML .= "<TR><TD colspan='2'>".htmlentities("Bonjour,")."<BR/><br/>";
	$smailHTML .= "<TR><TD colspan='2'>".htmlentities("La mise à jour d'un contenu vient d'être effectuée.")."<BR/><br/><br/>";
	$smailHTML .=htmlentities("Utilisateur :")." ".$userLogged."<br/>";
	$smailHTML .=htmlentities("Statut du contenu :")." ".$statutContent."<br/>";
	$smailHTML .=htmlentities("Nom du contenu  :")." ".$contentUpdated."<br/>";
	$smailHTML .=htmlentities("Emplacement du contenu :")." ".$node."".$sPage.".php&nbsp;<br/><BR/>";  
	$smailHTML .=htmlentities("Vous pouvez accéder à la liste des contenus depuis le backoffice :")."<BR/>";
	$smailHTML .= "<a href='http://".$_SERVER['HTTP_HOST']."/backoffice/cms/listeContenus.php'>http://".$_SERVER['HTTP_HOST']."/backoffice/cms/listeContenus.php</a><BR/><br><br>"; 
	$smailHTML .="</TD></TR>"; 
	$smailHTML .="</table>";
	
	$smailTEXT  = "Bonjour,\r\nLa mise à jour d'un contenu vient d'être effectuée.\r\n";
	$smailTEXT .="Utilisateur : ".$userLogged."\r\n";
	$smailTEXT .="Statut du contenu : ".$statutContent."\r\n";
	$smailTEXT .="Nom du contenu : ".$contentUpdated."\r\n";
	$smailTEXT .="Emplacement du contenu : ".$node."".$sPage.".php \r\n"; 
	$smailTEXT .="Vous pouvez accéder à la liste des contenus depuis le backoffice :\r\n";
	$smailTEXT .="http://".$_SERVER['HTTP_HOST']."/backoffice/cms/listeContenus.php\r\n"; 
		 
	 
	$bRetour = multiPartMail( $destinataire, $smailSujet , $smailHTML , $smailTEXT, $expediteur, "", "", DEF_MAIL_HOST);
 
	return $bRetour;
}



function sendAlerteModuleToAdmin($oAssoAdminAlerte, $idUser, $idObject, $sClassename, $idSite) {
 
 	
 	// utilisateur connecté
 	$oUserLogged =  getObjectById("bo_users", $idUser);
	$userLogged =  $oUserLogged->getUser_prenom()." ".$oUserLogged->getUser_nom()." ( login : ".$oUserLogged->getUser_login()." )";
	
	// admin
	$admin =  getObjectById("bo_users", $oAssoAdminAlerte->get_bo_users());  
	
	// minisite
	$oSite =  getObjectById("cms_site", $idSite);
	$sSite =  $oSite->get_name();
	
	$expediteur = DEF_CONTACT_FROM_EMAIL;
	$destinataire = $admin->get_mail(); 
	
	//--- la structure du mail ----//  
 
	$smailSujet = "Mise à jour d'un module du site ".$sSite;
	$smailHTML = "<table style='font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 11px; width: 80%; border: 0px solid #000000; padding: 5px;' align='left' cellspacing='0'><TR style='background-color: #ffffff;'><TD width='40%'>&nbsp;</TD><TD width='60%'>&nbsp;</TD></TR>";
	$smailHTML .= "<TR><TD colspan='2'>".htmlentities("Bonjour,")."<BR/><br/>";
	$smailHTML .= "<TR><TD colspan='2'>".htmlentities("La mise à jour d'un module vient d'être effectuée.")."<BR/><br/><br/>";
	$smailHTML .=htmlentities("Utilisateur :")." ".$userLogged."<br/>";
	$smailHTML .=htmlentities("Module :")." ".$sClassename."<br/><br/>";
	$smailHTML .=htmlentities("Vous pouvez accéder à ce module depuis le backoffice :")."<BR/>";
	if (preg_match ("/cms/msi", $sClassename)) { 
		$smailHTML .= "<a href='http://".$_SERVER['HTTP_HOST']."/backoffice/cms/".$sClassename."/show_".$sClassename.".php?id=".$idObject."'>http://".$_SERVER['HTTP_HOST']."/backoffice/cms/".$sClassename."/show_".$sClassename.".php?id=".$idObject."</a><BR/><br><br>"; 
	}
	else {
		$smailHTML .= "<a href='http://".$_SERVER['HTTP_HOST']."/backoffice/".$sClassename."/show_".$sClassename.".php?id=".$idObject."'>http://".$_SERVER['HTTP_HOST']."/backoffice/".$sClassename."/show_".$sClassename.".php?id=".$idObject."</a><BR/><br><br>"; 
	}
	$smailHTML .="</TD></TR>"; 
	$smailHTML .="</table>";
	
	$smailTEXT  = "Bonjour,\r\nLa mise à jour d'un contenu vient d'être effectuée.\r\n";
	$smailTEXT .="Utilisateur : ".$userLogged."\r\n";
	$smailTEXT .="Module : ".$sClassename."\r\n"; 
	$smailTEXT .="Vous pouvez accéder à ce module depuis le backoffice :\r\n";
	if (preg_match ("/cms/msi", $sClassename)) { 
		$smailTEXT .="http://".$_SERVER['HTTP_HOST']."/backoffice/cms/".$sClassename."/show_".$sClassename.".php?id=".$idObject."\r\n"; 
	}
	else {
		$smailTEXT .="http://".$_SERVER['HTTP_HOST']."/backoffice/".$sClassename."/show_".$sClassename.".php?id=".$idObject."\r\n"; 
	}
		 
	
	$bRetour = multiPartMail( $destinataire, $smailSujet , $smailHTML , $smailTEXT, $expediteur, "", "", DEF_MAIL_HOST);
 
	return $bRetour;
}
 ?>