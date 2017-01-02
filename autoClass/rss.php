<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}
//------------------------------------------------------------------------------------------------------
//header("Content-type: text/plain");
header("Content-type: application/rss+xml");
//header("Content-Encoding: ISO-8859-1");
header("Content-Encoding: utf-8");
//header("Content-Disposition: attachment; filename=\"".$classeName.".xml\"");

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

if (!isset($translator)){
	$translator =& TslManager::getInstance(); 
}

$bDebug = false;
$sMessage="";

// objet 
eval("$"."oRes = new ".$classeName."();");

$sXML = $oRes->XML;

xmlClassParse($sXML);

$classeName = $stack[0]["attrs"]["NAME"];
$classeLibelle = "";
if (isset($stack[0]["attrs"]["LIBELLE"])) {
	$classeLibelle =  $stack[0]["attrs"]["LIBELLE"];
}
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];

if(isset($stack[0]['attrs']['RSS_LINK'])) $link_base = $stack[0]['attrs']['RSS_LINK'];


//===============================
// TRIS
//===============================

// si on change de page, on reset
if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF']) === false) {
	unset($aGetterOrderBy);
	unset($_SESSION['champTri_res']);
	unset($aGetterSensOrderBy);
	unset($_SESSION['sensTri_res']);
	$_SESSION['adodb_curr_page'] = "1";
}

$champTri = $_POST['champTri'];
if ($champTri == "") $champTri = $_GET['champTri'];

$sensTri = $_POST['sensTri'];
if ($sensTri == "") $sensTri = $_GET['sensTri'];

/////////////////////////
// SESSION //////////////
if ($_POST['champTri'] != "") $_SESSION['champTri_res'] = $_POST['champTri'];
if ($_POST['sensTri'] != "") $_SESSION['sensTri_res'] = $_POST['sensTri'];
/////////////////////////


//////////////////////////
// TRIS

// le tri utilisateur est fait en premier
// les autres tris sont faits même si c non visible dans l'interface
// l'odre des tris est défini ici

// le premier tri est ôté de la liste pour être placé en premier par la suite
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM"){
		if ($aNodeToSort[$i]["attrs"]["NAME"] == "ordre"){
			 $aListeTri[] = new dbTri($classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], "ASC");		
		}	
		if (isset($aNodeToSort[$i]["attrs"]["RSS"])){
			if ($aNodeToSort[$i]["attrs"]["RSS"] == "pubDate"){
				 $aListeTri[] = new dbTri($classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], "DESC");		
			}
		}
	/*
		if ($aNodeToSort[$i]["attrs"]["ORDER"] == "true"){
			if ($aNodeToSort[$i]["attrs"]["TYPE"] == "date"){
				$sAscDesc = "DESC";
			}
			else{
				$sAscDesc = "ASC";
			}
			if ($_SESSION['champTri_res'] != $classePrefixe."_ref") $aListeTri[] = new dbTri($classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"], $sAscDesc);	
			
		}
		*/
	}
}

// tri numéro 1 => celui demandé dans l'interface
if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF']) !== false) {

	//if ($_SESSION['champTri_res'] != "") $aGetterOrderBy[] = $_SESSION['champTri_res'];
	//if ($_SESSION['sensTri_res']  != "") $aGetterSensOrderBy[] = $_SESSION['sensTri_res'];
}
else{
	// on récupere rien
}

// autres tris
for ($i=0; $i < sizeof($aListeTri); $i++){

	$oTri = $aListeTri[$i];

	$aGetterOrderBy[] = $oTri->getNom();
	$aGetterSensOrderBy[] = $oTri->getSens();
}

// check des doublons dans les tris
for ($iOrder = 0;$iOrder < count($aGetterOrderBy);$iOrder++){
	//pre_dump(array_slice($aGetterOrderBy,$iOrder+1));
	$key = array_search($aGetterOrderBy[$iOrder], array_slice($aGetterOrderBy,$iOrder+1));	
	if ($key !== false){
		//echo "search : ".$aGetterOrderBy[$iOrder]."<br>found : ";
		//pre_dump($key);
		//echo "on splice";	
		array_splice ($aGetterOrderBy, $key+1, 1);
		array_splice ($aGetterSensOrderBy, $key+1, 1);
	}
}
//////////////////////////

//===============================
// REQUETTE
//===============================

// obtention de la requete
$sql = "SELECT ".$classeName.".* ";
//$sql.= dbMakeRequeteWithCriteres2($classeName, $aRecherche, $aGetterOrderBy, $aGetterSensOrderBy);

if ($bDebug) print("<br>$sql");

$sTexte = $_SESSION['S_BO_sTexte_ref'];
$eStatut = $_SESSION['S_BO_select3_ref'];
$eType = $_SESSION['S_BO_select2_ref'];
$eHomepage = $_SESSION['S_BO_select_ref'];
$aRecherche = array();


$oRech = new dbRecherche();

//////////////////////////
// recherche par mot clé
//////////////////////////
if($sTexte==""){
$sTexte=trim($_POST['sTexte']);
$_SESSION['sTexte']=$sTexte;
}
if($sTexte==""){
$sTexte=trim($_SESSION['sTexte']);
}
if ($sTexte != "") {
$_SESSION['sTexte']=$sTexte;
	$oRech = new dbRecherche();
	
	$oRech->setValeurRecherche("declencher_recherche");
	$oRech->setTableBD($classeName);
	
	$cptvarchar=0;
	
	//on compte le nombre de varchar dans la classe
	for ($i=0;$i<count($aNodeToSort);$i++){
		if(($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){
		$cptvarchar++;
		}
	}

	//construction de la requete dynamique
	for ($i=0;$i<count($aNodeToSort);$i++){
		if (($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){
			$cpt++;
			
			if($cptvarchar!=$cpt){				
				if($cpt==1){$sRechercheTexte="(";}
				$sRechercheTexte .= ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]." like'%".$sTexte."%' OR ";
			}
			else{	
				if($cpt==1){$sRechercheTexte="(";}
				$sRechercheTexte .= ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]." like'%".$sTexte."%' )";
				//$sRechercheTexte .= ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]." like'%".$sTexte."%'";
			}
		}//fin if ($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")
	}// fin for ($i=0;$i<count($aNodeToSort);$i++)	

	
	$oRech->setJointureBD($sRechercheTexte);
	$oRech->setPureJointure(1);
	$aRecherche[] = $oRech;
	
}//fin if ($sTexte != "")

//////////////////////////
// recherche par statut // only EN LIGNE
//////////////////////////
if ($oRes->getGetterStatut() != "none"){ // si l'objet a un champs statut
	$oRech2 = new dbRecherche();
	$oRech2->setValeurRecherche("declencher_recherche");
	$oRech2->setTableBD($classeName);
	$oRech2->setJointureBD(" ".ucfirst($classePrefixe)."_statut=".DEF_ID_STATUT_LIGNE." ");
	$oRech2->setPureJointure(1);
	
	$aRecherche[] = $oRech2;				
}
else{ // sinon, on s'en fout
	//
}

$sql.= dbMakeRequeteWithCriteres2($classeName, $aRecherche, $aGetterOrderBy, $aGetterSensOrderBy);
 
unset($aRecherche);
unset($aGetterOrderBy);
unset($aGetterSensOrderBy);

// execution de la requette avec pagination

$sParam = "";
$pager = new Pagination($db, $sql, $sParam);
$pager->Render($rows_per_page=20000000);

//////// DEBUGAGE ////////
//error_reporting(E_ALL);
if ($bDebug) { 
print("<br>///////////////////////<br>");
print("<br>".$sql);
print("<br>///////////////////////<br>");
print("<br>".var_dump($pager->aResult));
print("<br>///////////////////////<br>");
}
//////// DEBUGAGE ////////

// tableau d'id renvoyé par la fonction de pagination
$aId = $pager->aResult;

// A VOIR sponthus
// la fonction de pagination devrait renvoyer un tableau d'objet
// pour l'instant je n'exploite qu'un tableau d'id
// ce ui m'oblige à re sélectionner mes objets
// à perfectionner

// liste des objets
$aListe_res = array();
for ($m=0; $m<sizeof($aId); $m++){
	//eval("$"."aListe_res[] = new ".$classeName."($"."aId[$"."m]);");
	$aListe_res[] = new $classeName($aId[$m]);
}

if ($classeLibelle == ""){
	$classeLibelle = $classeName;
}

if(!defined("DEF_TITLE_RSS")){
    if (!defined("DEF_CONTACT_FROM_NAME")){
            define("DEF_CONTACT_FROM_NAME", $classeLibelle);
            //$title = DEF_CONTACT_FROM_NAME;
            $title = DEF_CONTACT_FROM_NAME;
    }
} else {
    $title = DEF_TITLE_RSS;
}


if(!defined("DEF_CONTACT_RSS")){
    $contact = DEF_CONTACT_FROM_NAME;
} else {
    $contact = DEF_CONTACT_RSS;
}


if (!defined("DEF_RSS_GUID")){
	define("DEF_RSS_GUID", 'http://'.$_SERVER['HTTP_HOST'].'/frontoffice/'.$classeName.'/foshow_'.$classeName.'.php?id=');
}


// XML generation
echo "<?xml version='1.0' encoding='utf-8' ?".">\n";
//echo "<?xml-stylesheet type=\"text/xsl\" href=\"rss.xsl\" ?".">\n";
echo "	<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n"; 
 
echo "	<channel>\n";
echo "	<atom:link href=\"http://".$_SERVER['HTTP_HOST']."/backoffice/".$classeName."/rss_".$classeName.".php\" rel=\"self\" type=\"application/rss+xml\" />";
echo "		<title>".utf8_encode($title)."</title>\n"; 
echo "		<description>".apos(html_to_rss($classeLibelle." - en provenance de ".$_SERVER['HTTP_HOST']))."</description>\n";
echo "		<link>".DEF_RSS_GUID."</link>\n";
echo "		<language>fr-FR</language>\n";
echo "		<managingEditor>".makeEmailAddyXMLfriendly($contact)." (".utf8_encode($contact).") </managingEditor>\n";
echo "		<webMaster>".makeEmailAddyXMLfriendly($contact)." (".utf8_encode($contact).") </webMaster>\n";
echo "		<image>\n"; 
echo "		<title><![CDATA[".apos(utf8_encode($title))."]]></title>\n"; 
echo "		<url>".DEF_RSS_GUID."</url>\n"; 
echo "		<link>".DEF_RSS_GUID."</link>\n"; 
echo "		</image>\n"; 
$URL_MEDIA = "http://".$_SERVER['HTTP_HOST']."/custom/upload/".$classeName."/";


// s'il y a des enregistrements à afficher
if(sizeof($aListe_res)>0) {
	// liste
	for($k=0; $k<sizeof($aListe_res); $k++) {
		$oRes = $aListe_res[$k];
	
		$RSS = array();
		$RSS['title'] = "";
		$RSS['pubDate'] = "";
		$RSS['pubendDate'] = "";
		$RSS['description'] = "";
		$RSS['link'] = "";
		$RSS['image'] = "";
		$RSS['frenchdate'] = "";
		$RSS['frenchenddate'] = "";
		$RSS['type'] = "";
		$RSS['texte'] = "";
		$RSS['site'] = "";
		$RSS['enclosure'] = "";
		
		$descritionHTML = false;
                if(isset($link_base)) $links_items = $link_base;
		
	   for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){
                    
                    //pre_dump($aNodeToSort[$i]['attrs']['NAME']);
                    /* gestion des champs � mettre dans l'url des feeds */
                    if(isset($links_items)){
                        $value_link = noAccent(getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]));
                        if(isset($aNodeToSort[$i]["attrs"]['TRANSLATE']) && $aNodeToSort[$i]["attrs"]['TRANSLATE'] == 'reference'){
                            $value_link = noAccent($translator->getByID(getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"])));
                        }

                        if($aNodeToSort[$i]['attrs']['OPTION'] == 'enum'){
                            foreach($aNodeToSort[$i]['children'] as $aOption){
                                if($value_link == $aOption['attrs']['VALUE']) $value_link = noAccent($aOption['attrs']['LIBELLE']);
                            }
                            
                        }
                        
                        if(preg_match('#[['.$aNodeToSort[$i]['attrs']['NAME'].']]#', $links_items)) $links_items = str_replace('[['.$aNodeToSort[$i]['attrs']['NAME'].']]', $value_link, $links_items);
                    
                        //pre_dump($links_items);
                    }
			//if ($aNodeToSort[$i]["attrs"]["LIST"] == "true"){
			if ((isset($aNodeToSort[$i]["attrs"]["RSS"])) && ($aNodeToSort[$i]["attrs"]["RSS"] != "")){				
				
				$eKeyValue = getItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"]);
                                
				if ((isset($aNodeToSort[$i]["attrs"]["FKEY"])) && ($aNodeToSort[$i]["attrs"]["FKEY"] != "")){// cas de foregin key
					$sTempClasse = $aNodeToSort[$i]["attrs"]["FKEY"];
					if ($eKeyValue > -1){
						$oTemp = cacheObject($sTempClasse, $eKeyValue);
						$RSS[$aNodeToSort[$i]["attrs"]["RSS"]] = getItemValue($oTemp, $oTemp->getDisplay());
					}
					else{
						$RSS[$aNodeToSort[$i]["attrs"]["RSS"]] = "n/a";
					}
				}
				elseif ((isset($aNodeToSort[$i]["attrs"]["OPTION"])) && ($aNodeToSort[$i]["attrs"]["OPTION"] == "enum")){// cas enum		
					if (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)){
						foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
							if($childNode["name"] == "OPTION"){ // on a un node d'option				
								if ($childNode["attrs"]["TYPE"] == "value"){
									if (intval($eKeyValue) == intval($childNode["attrs"]["VALUE"])){
										$RSS[$aNodeToSort[$i]["attrs"]["RSS"]] = $childNode["attrs"]["LIBELLE"];
										break;
									}
								} //fin type  == value				
							}
						}
					}		
				} // fin cas enum
				else{ // cas typique
					if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut"){ // cas statut
						$RSS[$aNodeToSort[$i]["attrs"]["RSS"]] = lib($eKeyValue);
					}
					else{
						if ($eKeyValue > -1){ // cas typique typique
							if ((isset($aNodeToSort[$i]["attrs"]["OPTION"])) && ($aNodeToSort[$i]["attrs"]["OPTION"] == "file")){// cas file
								//$RSS[$aNodeToSort[$i]["attrs"]["RSS"]] = $eKeyValue;
								$RSS[$aNodeToSort[$i]["attrs"]["RSS"]] = $URL_MEDIA.$eKeyValue;
							}
							elseif ((isset($aNodeToSort[$i]["attrs"]["TYPE"])) && ($aNodeToSort[$i]["attrs"]["TYPE"] == "timestamp")){// cas timestamp
								//$RSS[$aNodeToSort[$i]["attrs"]["RSS"]] = $eKeyValue;
								$eKeyValue = str_replace ("//", "", $eKeyValue);
								$eKeyValue = strtotime($eKeyValue); 
								$RSS[$aNodeToSort[$i]["attrs"]["RSS"]] = date("r", $eKeyValue);
							}
							else{// cas typique typique typique
								// on converti br en \n et on remove les tags 
								//$RSS[$aNodeToSort[$i]["attrs"]["RSS"]] = utf8_encode(html_entity_decode(ereg_replace("<[^<>]+>", "", eregi_replace("<br[^<>]*>", "\n", $eKeyValue))));
                                                                
                                                                /* ajout de la traduction */
                                                            //pre_dump($aNodeToSort[$i]["attrs"]);
                                                                if(isset($aNodeToSort[$i]["attrs"]['TRANSLATE']) && $aNodeToSort[$i]["attrs"]['TRANSLATE'] == 'reference'){ $eKeyValue = $translator->getByID($eKeyValue); }
                                                                
								$RSS[$aNodeToSort[$i]["attrs"]["RSS"]] = html_to_rss($eKeyValue);
								if ($aNodeToSort[$i]["attrs"]["RSS"] == "description" && isset ($aNodeToSort[$i]["attrs"]["RSSHTML"]) &&  $aNodeToSort[$i]["attrs"]["RSSHTML"] == true) {

									$descritionHTML = true;
									$descritionField = $aNodeToSort[$i]["attrs"]["NAME"];
								}
							}
						}
						else{
							$RSS[$aNodeToSort[$i]["attrs"]["RSS"]] = "n/a";
						}
					}
				}
				
			//}
			}
		}
	}	
	if(isset($links_items)){
            $RSS['link'] = $links_items;
        }
        
        
        
	// s'il existe une date de fin de publication, on n'affiche pas l'info
	$RSS['pubDate']=preg_replace("#([0-9]{2})/([0-9]{2})/([0-9]{4})#", "\\3/\\2/\\1", $RSS['pubDate']); 
	$RSS['pubendDate']=preg_replace("#([0-9]{2})/([0-9]{2})/([0-9]{4})#", "\\3/\\2/\\1", $RSS['pubendDate']); 
	if ($RSS['pubendDate'] == "0000/00/00" ) $RSS['pubendDate'] =""; 
	if ($RSS['pubDate'] == "0000/00/00" ) $RSS['pubDate'] =""; 

	if ($RSS['pubendDate'] !="" &&  $RSS['pubDate'] =="" && $RSS['pubendDate'] < date("Y/m/d")  ) {   
		echo '<!-- périmée -->';
	}
	//else if ($RSS['pubDate'] !="" && $RSS['pubendDate'] =="" && $RSS['pubDate'] > date("Y/m/d")) {   
	//	echo '<!-- cas 2 -->';
	//}
	else if ($RSS['pubDate'] !="" && $RSS['pubendDate'] !="" && ($RSS['pubDate'] > date("Y/m/d") || $RSS['pubendDate'] < date("Y/m/d")) ) {  
		echo '<!-- cas 3 -->'; 
	}
	else { 
		if ($RSS['title'] == ""){
			if ($RSS['description'] != ""){
				$RSS['title'] = html_to_rss($RSS['description']);
			}
			else{
				$RSS['title'] = "Info du ".date("d-m-Y");
			}
		}
		if ($RSS['pubDate'] == ""){
			$RSS['pubDate'] = date('r');
		}
		else{
			$RSS['pubDate'] = date("r", strtotime(preg_replace("#([0-9]{2})/([0-9]{2})/([0-9]{4})#", "\\3/\\2/\\1", $RSS['pubDate'])));
		}
		if ($RSS['description'] == ""){
			if ($RSS['title'] != ""){
				$RSS['description'] = $RSS['title'];
			}
			else{
				$RSS['description'] = "Info du ".date("d-m-Y");
			}
		}
		if ($RSS['link'] == ""){
			$id = getItemValue($oRes, "id");
			if(defined("DEF_RSS_GUID")){
				$RSS['link'] = DEF_RSS_GUID.$id;
			}
			else{
				$RSS['link'] = 'http://'.$_SERVER['HTTP_HOST'].'/frontoffice/'.$classeName.'/foshow_'.$classeName.'.php?id='.$id;
			}
		}
		if ($RSS['image'] == ""){
		}
		if ($RSS['frenchdate'] == ""){
			$RSS['frenchdate'] = date("d-m-Y");
		}
		if ($RSS['frenchenddate'] == ""){
			$RSS['frenchenddate'] = date("d-m-Y");
		}
		if ($RSS['type'] == ""){
		}
		if ($RSS['site'] == ""){
			$RSS['site'] = 1;
		}
		if ($RSS['texte'] == ""){
			
			if ($RSS['description'] != "" && $descritionHTML == true){
				
				$id = getItemValue($oRes, "id");
				
				$fileName = "../../frontoffice/".$classeName."/foshow_".$classeName.".rss.php";
				if (file_exists($fileName)) {
					$fileName = "http://".$_SERVER['HTTP_HOST']."/frontoffice/".$classeName."/foshow_".$classeName.".rss.php";
					$fp = fopen($fileName."?id=".$id."","r"); //lecture du fichier
					$htmlLDJ= "";
					while (!feof($fp)) { //on parcourt toutes les lignes
					  $htmlLDJ.= fgets($fp, 4096); // lecture du contenu de la ligne
					}
					//$RSS['description'] = utf8_encode(accent2Html($htmlLDJ));
					$RSS['texte'] = utf8_encode(htmlcodes2chars($htmlLDJ));
					$RSS['description'] = utf8_encode(htmlcodes2chars($htmlLDJ));
					if ($RSS['link'] == ''){
						$RSS['link'] = "http://".$_SERVER['HTTP_HOST']."/frontoffice/".$classeName."/foshow_".$classeName.".php?id=".$id."";
					}
					
				}
				else {
					$RSS['texte'] = $RSS['description'];
					
				}
				
			}	
			else if ($RSS['description'] != ""){
			
				$RSS['texte'] = $RSS['description'];
			}
		}
		
		$description = DEF_RSS_HTML_TOP.$RSS['texte'].DEF_RSS_HTML_TOP;
		
			echo "			<item>\n";
			echo "<guid isPermaLink=\"false\">".DEF_RSS_GUID."".$oRes->get_id()."</guid>\n"; 
			//echo "				<title>".rawurlencode(($RSS['title']))."</title>\n";
			echo "				<title><![CDATA[".preg_replace("#&([^q]{1})#", "&amp;\\1", $RSS['title'])."]]></title>\n";
			echo "				<pubDate>".$RSS['pubDate']."</pubDate>\n";
			//echo "				<description>".rawurlencode(($RSS['description']))."</description>\n";
			echo "				<description><![CDATA[".preg_replace("#&([^q]{1})#", "&amp;\\1", $description)."]]></description>\n";
		
			$RSS['enclosure'] = controlLinkValue($RSS['enclosure'], $oRes);
			if ($RSS['enclosure'] != ""){	
				$sEncFullPath = preg_replace("#http://[^/]+#", $_SERVER['DOCUMENT_ROOT'], $RSS['enclosure']);
				$aEncStats = stat($sEncFullPath);
		
				echo "				<enclosure url=\"".$RSS['enclosure']."\" length=\"".$aEncStats["size"]."\" type=\"".cc_mime_content_type(basename($sEncFullPath))."\"/>\n";	
			}
			echo "				<link>".preg_replace("#&([^q]{1})#", "&amp;\\1", $RSS['link'])."</link>\n";
			if ($RSS['image']!="") echo "				<image>".$RSS['image']."</image>\n";
			echo "				<frenchdate>".$RSS['frenchdate']."</frenchdate>\n";
			//echo "				<frenchenddate>".$RSS['frenchenddate']."</frenchenddate>\n";
			//if ($RSS['type']!="")echo "				<type>".rawurlencode(($RSS['type']))."</type>\n";
			//if ($RSS['site']!="")echo "				<site>".rawurlencode(($RSS['site']))."</site>\n";
			//echo "				<texte>".rawurlencode(($RSS['texte']))."</texte>\n";
			//echo "				<texte><![CDATA[".ereg_replace("&([^q]{1})", "&amp;\\1", $RSS['texte'])."]]></texte>\n";
			//echo "				<description><![CDATA[".ereg_replace("&([^q]{1})", "&amp;\\1", $RSS['description'])."]]></description>\n";
			echo "			</item>\n";	
		
		
		}
		
	} // fin 	if ($RSS['pubendDate'] >= date("Y-m-d")) 
}
echo " 	</channel>\n";
echo "</rss>";




?>