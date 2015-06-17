<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*
sponthus 09/06/2005

sponthus 08/06/2005 : renommage des tampons
il y a maintenant plusieurs variables tampon correspondant aux diff�rents remplissages de tampon

sponthus 07/06/2005
tampon � �crire syst�matiquement d�fini dans newpage_write.php et dans newgabarit_write.php

Mkl 07/06/2005
Correction Bug : Attention les classes (et id) des divs doivent commencer par une lettre
pour pouvoir appliquer un style � ces div (sinon bug de positionnement).


function getTamponContent_gabarit($divArray, $oInfos_page, $oPage)
function getTamponContent_page($divArray, $oInfos_page, $oPage)

*/

//=======================================================================
// 3 cas : 
//    1.0 cr�ation d'un gabarit (avec des briques)	
//    1.1 cr�ation du gabarit de d�part
//    2.0 cr�ation d'une page (avec des briques)
//=======================================================================


//=======================================================================
//=======================================================================
// cr�ation d'un gabarit (avec des briques) ou du gabarit de d�part
// 1.0 et 1.1
//=======================================================================
//=======================================================================

function getTamponContent_gabarit($divArray, $oInfos_page, $oPage){
	$oGab = new Cms_page($oPage->getGabarit_page());
	$gabarit = $sRep.$oGab->getName_page().'.php';
	$gabfilehandle = $oGab->getHtml_page();
	//========================================
	// cr�ation d'un gabarit : tampon_haut
	//========================================
	$tmpfilehandle="";
	// ce n'est pas le gabarit de d�part
	if (!($oPage->getName_page() == DEF_GABINIT)){
		// cr�ation d'un gabarit : tamponGabarit
		$tampon_hautgabarit = getTamponGabarit($divArray, $oPage);
		$tmpfilehandle.=$tampon_hautgabarit;
	}
	else {
		// cr�ation du gabarit de d�part : getTamponGabarit_init
		$tampon_hautgabarit = getTamponGabarit_init();
		$tmpfilehandle.=$tampon_hautgabarit;		
	}

	//========================================
	// cr�ation des div
	//========================================

	// ce n'est pas le gabarit de d�part => cr�ation des briques
	if ($oPage->getName_page() != DEF_GABINIT){
		// recherche des briques zone �ditable de ce gabarit

		// je comprends pas pkoi mais sans �crire une espace ou une image ici, rien ne s'affiche....
		//$tampon_brique = '<img src="/custom/img/espace/puces/spacer.gif" height="1" width="1" alt="." />';
		$tampon_brique = '<div id="space" class="space">';
		$tampon_brique.= '<div id="content" class="content">';		
		$tampon_brique.= '<div id="divhachures" style="display:none; z-index:200; position:absolute; width:100%; height:100%; background-image:url(\'/backoffice/cms/img/pix_griser.gif\');"></div>' ;		
		//print_r($divArray);
		
		foreach($divArray as $res){
			 $sortAux[] = $res['top'];
		}		
		array_multisort($sortAux, SORT_ASC, SORT_NUMERIC, $divArray);
		
		foreach($divArray as $k => $v) {
			if(is_array($v)){

				$oBriqueEdit = new Cms_content();
				$oBriqueEdit->initValues($v['id']);
				// sponthus 09/11/05
				// distinguer da,ns les gabarits les �l�ments du duvarray
				// les zone editables : ZE
				// les briques fixes : BF
				// les sytles s'appellent toujours div...

				if ($oBriqueEdit->getIszonedit_content()) {
					$sLib="ze";
					// on est sur la brique zone �ditable
					// les retour � la ligne de <!--DEBUTCMS est essentiel 
					// pour lire ID= dans pageLiteEditor3.php
					// Laisser le id="div et class="div pour �viter bug!
					$divString = '<div id="'.$sLib.$v['id'].'" class="div'.$v['id'].'">'."\n";
					$divString .= '<!--DEBUTCMS;ID='.$v['id'].';-->'."\n";
					$divString .= '<!--FINCMS;ID='.$v['id'].';-->'."\n";
					$divString .= '</div>'."\n";					
				}
				else {
					$sLib="bf";
					// on est sur la brique quelconque
					$divString = '<div id="'.$sLib.$v['id'].'" class="div'.$v['id'].'">'.utf8IfNeeded(stripslashes($v['content'])).'</div>';
				}
				$tampon_brique.="$divString\n";
			}
		}
		$tampon_brique.='</div>
		</div>';

		$tmpfilehandle.=$tampon_brique;
	}

	//-------------------------------------------------
	// inclusion de la FIN du gabarit de d�part
	// apr�s la balise <!--FINCMS-->, on recopie toute la fin du gabarit de d�part
	//-------------------------------------------------
	$fin_trouve=0;
	
	$tampon_gabarit=$gabfilehandle;
	$tmpfilehandle.=$tampon_gabarit;
	
	return $tmpfilehandle;
}


//=======================================================================
//=======================================================================
// corps d'une page ou d'un gabarit
// �criture des tamponspage, tampongabarits et des composants
//=======================================================================
//=======================================================================

function getTamponContent_page($divArray, $oInfos_page, $oPage){
	//pre_dump($divArray);
	// objet gabarit	
	$oGab = new Cms_page($oPage->getGabarit_page());
	$idGab = $oGab->getId_page();
	$gabGenerated = 1; // tous les gabarits sont maintenant g�n�r�s par adequation
	
	$gabarit = $dir_gabarits.''.$oGab->getName_page().'.php';
	$gabfilehandle = $oGab->getHtml_page();
	$gabfilehandle=str_replace('<div id="divhachures" style="display:none; z-index:200; position:absolute; width:100%; height:100%; background-image:url(\'/backoffice/cms/img/pix_griser.gif\');"></div>', '', $gabfilehandle);

	//$tmpfilehandle = "";
	//========================================
	// TRES IMPORTANT
	// REODRONNE LES ZONES EDITABLES DU GABARIT
	//
	// pour inclure les contenus dans les zones editables on cherche les instructions DEBUTCMS;ID= pour chaque zone
	// le gabarit est parcouru jusqu'� trouver ces tag
	// l'ordre de recherche de ces zones de contenu est important 
	// car sinon le gabarit est parcouru et les zones pass�es ne seraient plus trouv�es

	$tampon_gabarit = $gabfilehandle;	
	// on a trouv� une zone editable
	// on extrait son ID
	$aIDZonedit = array();
	$aZoneCMS = explode(";", $tampon_gabarit); 
	for ($a=0; $a<sizeof($aZoneCMS); $a++) {
		$ligneTampon = $aZoneCMS[$a];
		if (substr($ligneTampon, 0, 2) == "ID") 
		{
			$aTampon2=explode("=", $ligneTampon);
			$id_div = $aTampon2[1];
			if (!in_array ($id_div, $aIDZonedit)) {
				$aIDZonedit[] = $id_div;
			}
		}
	}
	//pre_dump($aIDZonedit);

	//========================================

	//========================================
	// cr�ation d'une page : tampon_haut
	//========================================

	// gabGenerated :: permet d'indiquer dans l'�criture du tampon haut de le page 
	// si la page a �t� cr��e avec un gabarit g�n�r�
	// si tel est le cas, les style space et content ne sont pas r��cris

	// cr�ation d'une page : tamponPage
	$tampon_hautpage = getTamponPage($divArray, $oInfos_page, $oPage, $gabGenerated);

	//$tmpfilehandle.= "\n";
	$tmpfilehandle= $tampon_hautpage."\n";
	$tmpfilehandle.= $gabfilehandle."\n";
	//fputs($tmpfilehandle, $tampon_hautpage);

	//========================================
	// corps d'une page avec un gabarit non g�n�r�
	//========================================
	
	//========================================
	// cr�ation des div
	//========================================*/
	$tampon_brique= '<div id="space" class="space">
	<div id="content" class="content">';
	foreach($divArray as $k => $v) {
		if(is_array($v)){
			$divString = '<div id="div'.$v['id'].'" class="div'.$v['id'].'">'.stripslashes($v['content']).'</div>';
			$tampon_brique.="$divString";
		}
	}
	$tampon_brique.='</div>
	</div>';
	
	// toutes les zones editables de ce gabarit
	$aZonedit = getContentFromPage($idGab, 1);
	//========================================
	// TRES IMPORTANT

	// on recherche les zones editables dans l'ordre de l'apparition des zones editables dans le gabarit
	// donc on parcours aIDZonedit et non pas aZonedit

	//========================================
	
	// pour chaque zone editable
	for ($m=0; $m<sizeof($aIDZonedit); $m++){
		$oZonedit = new Cms_content($aIDZonedit[$m]);
		
		// recherche de la brique � ins�rer dans cette zone �ditabl
		// recherche cette zone editable dans div_array
		$eBrique = -1;
		$sContent = "";
		for ($k=0; $k<sizeof($divArray); $k++) {
			$eZonedit = $divArray[$k]['zonedit'];
			if ($oZonedit->getId_content() == $eZonedit) {
				$eBrique = $divArray[$k]['id'];
				$sContent = $divArray[$k]['content'];
				// Rajout d'un cas particulier li� � l'�dition d'une page
				// si on a une brique �ditable dont il faut r�initialiser le contenu HTML
				// avec le contenu HTML de la zone �ditable
				if($divArray[$k]["HTMLdefaut"]!="") {
					$oContentHTMLDefault = new Cms_content($divArray[$k]["HTMLdefaut"]);
					$sContent = utf8IfNeeded($oContentHTMLDefault->getHtml_content());
				}
				$k = sizeof($divArray); // sortie de la boucle
				//========================================
				// cr�ation des div
				//========================================
	
				// pour le cas ou l'on n'a pas trouv� de brique editable en rapport avec cette zone editable
				// --> on affiche une zone vide dans cette zone editable
				// le div est alors nomm� divVIDE				
			}
			if ($eBrique != -1) $tampon_brique = '<div id="div'.$eBrique.'" class="div'.$eBrique.'">'.$sContent.'</div>';
				else {
					$tampon_brique = '<div id="divVIDE">'.$sContent.'</div>';
					error_log("--------------------------------------------");
					error_log("ERROR DIV VIDE => GABARIT :: ".$idGab.", ZONE :: ".$oZonedit->getId_content().', PAGE :: '.$oPage->get_id());
					error_log("--------------------------------------------");
				}
			}
			$tmpfilehandle = str_replace("<!--DEBUTCMS;ID=".$oZonedit->getId_content().";-->", $tampon_brique."\n", $tmpfilehandle);
			
		$eBrique = -1;
	}

	//========================================
	// cr�ation d'une page ou d'un gabarit : tampon_bas
	//========================================
		
	// footer
	$tampon_bas = getPageFooter();
	$tmpfilehandle.=$tampon_bas."\n";

	return $tmpfilehandle;
}
?>