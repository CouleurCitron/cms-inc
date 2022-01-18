<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/utils/htmlentities4flash.lib.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mail_lib.php');
//======================================================
// sponthus 01/06/05
// enregistrement des r�sultats du formulaire dans un fichier csv
//======================================================


 










$idForm = $_POST["formulaireid"]; 
$oForm = new Cms_form($idForm); 
$sNomFormulaire = $oForm->getName_form(); 


 $DIR_FILE = $_SERVER['DOCUMENT_ROOT']."/".DEF_CSV;  
if (!is_dir($DIR_FILE)) mkdir($DIR_FILE); 
$sNomFile = $DIR_FILE."data_".$sNomFormulaire.".csv";
// a_voir sponthus
// liste des fichiers dispo � downloader ou ouvrir

// tableau des ent�tes de colonne
$aEntete = array(); 

// tableau d'entetes
foreach ($_POST as $k => $v) { 
	if (!preg_match ('/destEmails|brTypeForm|formulaire|formulaireid|cb_/', $k) ) { 
		$aEntete[]=$k;
	}
}



$body = '';

// le fichier n'existe pas encore  : on cr�e la ligne des ent�tes
 
if (!is_file($sNomFile)) { 
	// ensemble des ent�tes
	for ($p=0; $p<newSizeOf($aEntete); $p++) { 
		$body .= $aEntete[$p].";"; // �criture ligne d'ent�te
	} 
	$body .="\n"; 
} 
else { 
	$body = "";
} 


$sFile = fopen($sNomFile, "a+");


foreach ($_POST as $k => $v) { 
	if (!preg_match ('/destEmails|brTypeForm|formulaire|formulaireid|cb_/', $k) ) {  
		if(strlen($v)==0 ) $_POST[$k]='----';
		if (!is_array($_POST[$k])){
			$_POST[$k] = stripslashes($_POST[$k]);  
		}
	}
}

//--------------------------------
// traitement des valeurs post�es
//--------------------------------
 

$eCol=0; // indice de colonne
// contiendra tous les emails pour envoi d'un AR
$aFromperso = array ();

// pour chaque valeur post�e 
foreach ($_POST as $k => $v) { 
	// r�ception des donn�es
	$sValue=""; 
	// on ne traite pas les donn�es destEmails, brTypeForm, formulaire et de type hcb_ 
	if (!preg_match ('/destEmails|brTypeForm|formulaire|formulaireid|cb_/', $k) ) {  

		
			
//print("<br><strong>$k</strong>=>$v");

		// nom de colonne rencontr�
		if (substr($k, 0, 3) == "cb_") {

			// cas particulier des colonnes cases � cocher

			// les cases � cocher sont les seuls objets de ce formulaire pouvant avoir des r�ponses multiples
			// de plus, les cases � cocher non s�lectionn�es ne sont pas post�es
			// il faut donc remplir de vide les cc non s�lectionn�es
			// donc pour chaque entete de colonne (hcb_) -> on cherche si sa valeur a �t� post�e (cb_)
	
			// tant qu'on est sur des ent�tes de colonnes case � cocher
			while(substr($aEntete[$eCol], 0, 4) == "hcb_") {

				// la valeur de l'entete de colonne hcb_ : $_POST[$aEntete[$eColCC]
				// que l'on va comparer � la vraie valeur post�e cb_
				$sValCC_hcb = $_POST[$aEntete[$eCol]];

				// le post est un tableau de valeurs coch�es
				// recherche de sValCC_hcb dans ce tableau de valeurs CC post�es
				$trouve=0;
				for ($t=0; $t<newSizeOf($v); $t++) {
					// chaque valeur coch�e
					$valueCC = $v[$t];
					if ($valueCC == $sValCC_hcb) {
						$trouve = 1;
						$t=newSizeOf($v); // sortie de la boucle
					}
				}

				// si on est sur la bonne cc
				if ($trouve) {
					$sValue.= "$sValCC_hcb;"; 
				} else { 
					// on n'est pas sur la bonne colonne
					$sValue.= ";"; 
				}

				// colonne suivante
				$eCol++;
			}
			// pour la derni�re cc ne pas incr�menter le no de colonne
			$eCol--;


		} else {

			// affectation de la valeur � la colone
			$sValue.= "$v;";			
		}

//print("<br>$sValue");
		
		// �criture ligne de donn�e	
		$body .= $sValue;
	
		// colonne suivante dans le tableau des ent�tes
		$eCol++;
		/*if (substr($k, 0, 10) == "fromperso_") {  
			$fromperso = $v; 
		}*/
		//on teste s'il s'agit d'un champ email 
		$email = htmlspecialchars($v);
		if (preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
		{ 
			array_push ($aFromperso , $v ); 
		}
	} 
} 
// �criture de la ligne de donn�e
fwrite($sFile, $body."\n");
//echo $body;
 
// fermeture du fichier
fclose($sFile);

//---------------------------------------------------------------------------------
// partie e mail 
 

if ($oForm->getComm_form() != ""){
	
	
	

	$subject = "[".$_SERVER['HTTP_HOST']."] formulaire ".$sNomFormulaire;
	
	if ($fromperso!="")   $from_mail = $fromperso;
	else if (newSizeOf($aFromperso) >0 )  $from_mail = $aFromperso[0]; 
	else $from_mail = "technique@couleur-citron.com";

	$addies = str_replace("\"", " ", $oForm->getComm_form());
	$addies = str_replace("'", " ", $addies);
	$addies = str_replace(":", " ", $addies);
	$addies = str_replace(";", " ", $addies);
	$addies = str_replace("?", " ", $addies);
	$addies = str_replace("<", " ", $addies);
	$addies = str_replace(">", " ", $addies);
	$addies = str_replace("(", " ", $addies);
	$addies = str_replace(")", " ", $addies);
	$addies = str_replace("[", " ", $addies);
	$addies = str_replace("]", " ", $addies);
	$addies = preg_split("/[\s,]+/",  $addies);
	
	
	foreach ($_POST as $k => $v) {
		if(strlen($v)==0)
			$_POST[$k]='----';
		$_POST[$k] = stripslashes($_POST[$k]);
		$_SESSION[$k] = $_POST[$k];
	}
	
	//require_once('attachform.php');
	
	$body ="";
	$bodyHTML ="";
	
	
	
	 
 

	
	$body.= "Informations saisies : \n";
	foreach ($_POST as $k => $v) { 
		$body .= "$k : $v\n";
	}
	
	$bodyHTML.= "<b>Informations saisies : </b><br>";
	
	
	
	foreach ($_POST as $k => $v) {
		//if (($k != 'destEmails') and ($k != 'formulaire') and ($k != 'formulaireid')){		
		if(preg_match("/hcb_/", $k)) {
			// $bodyHTML.= "+++++$k : $v<br>";
			 $v_prec = $v;
		 }
		 elseif(preg_match("/cb_/", $k)) {
			$bodyHTML.= str_replace("cb_", "", $k)." : ".$v_prec."<br>";
		 }
		 else{
			 $bodyHTML.= "$k : $v<br>";
		 }
		//}
	}

	$bodyHTML.= "<br><br><a href='http://".$_SERVER['HTTP_HOST']."/modules/utils/telecharger.php?file=data_".$sNomFormulaire.".csv&chemin=".$DIR_FILE."&'>T�l�charger le fichier csv</a><br>"; 
	$bodyHTML.= "<a href='http://".$_SERVER['HTTP_HOST']."/modules/utils/telecharger.php?file=data_".$sNomFormulaire.".csv&chemin=".$DIR_FILE."&'>http://".$_SERVER['HTTP_HOST']."/modules/utils/telecharger.php?file=data_".	$sNomFormulaire.".csv&chemin=".$DIR_FILE."&</a><br>"; 
	 
	foreach ($addies as $key => $addy) {
		$addy = trim(strtolower($addy));
		if (preg_match('/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]+$/msi', $addy)){
		 	//echo "---".$addy;
			 $bResult = multiPartMail($addy, $subject, $bodyHTML, '', $from_mail, '','','localhost'); 
			 //if ($bResult) echo "ok $addy";
			 
			// echo $subject."<br /><br /><br /><br />";
			// echo $bodyHTML."<br /><br /><br /><br />";
			// echo "FROM : ".$from_mail."<br /><br /><br /><br />";
			//$bResult = multiPartMail("thao@couleur-citron.com", "envoi newsletter", "test", '', $from_mail, '','','localhost');
		 
		}
	} 
 
	
	// on envoi un AR au visiteur si on a son email
	if ($oForm->getAr_form()!="" && newSizeOf($aFromperso) >0 ) { 
		$body = $oForm->getAr_form();  
		foreach ($aFromperso as $fromperso) {
			$bResult = multiPartMail($fromperso, $subject, $body, $body, DEF_CONTACT_FROM, '','','localhost'); 
			 //if ($bResult) echo "ok $fromperso";
		}
		
	} 
	
	 
	
	
	
}

echo '<p class="validation">'.$oForm->getDesc_form().'</p>' ;
 
?>