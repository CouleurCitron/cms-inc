<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
		// préparation de l'upload 
		// répertoires + noms

		function doUpload_part1($aFormUpload, $sOldFichier)
		{
			$bRetourImg = true;

			if($aFormUpload != null) { // Un fichier a été uploadé

				//$InfosPhoto = array_pop($aFormUpload);
				$InfosPhoto = $aFormUpload;

				// Suppression de l'ancien fichier
				if($sOldFichier != "" && file_exists(DEF_ROOT_UPLOADPHOTO."/".$sOldFichier))
					$bRetourImg = unlink(DEF_ROOT_UPLOADPHOTO."/".$sOldFichier);
	
				if($bRetourImg) {	// Vérif de la suppression de l'ancien fichier
					$old_file_name = $InfosPhoto['nom'];
					$new_file_name = preg_replace("/^TEMP/msi","", $old_file_name);
					$old_root_file_name = DEF_ROOT_UPLOADPHOTO."/".$old_file_name;
					$new_root_file_name = DEF_ROOT_UPLOADPHOTO."/".$new_file_name;
	
					// Vérif si un fichier ne porte pas déjà ce nom (sans TEMP au début)
					if(file_exists($new_root_file_name)) {
						// Si oui => Renommmage du fichier en fct (suppression du temp et [0-9]nomfichier)
	
						for($i=1;file_exists(DEF_ROOT_UPLOADPHOTO."/".$i.$new_file_name)&&$i<100;$i++) {
						}
						if($i==999) die("Erreur: Upload Massif");
						$new_file_name=$i.$new_file_name;
						$new_root_file_name = DEF_ROOT_UPLOADPHOTO."/".$new_file_name;
					}
	
					$upload_a_afaire = true;
				}
				else {
					$status .= 'Erreur : Impossible de supprimer l\'ancien fichier '. $sOldFichier;
				}
			}

			$aRetour = array();

			$aRetour[0]=$old_file_name;
			$aRetour[1]=$new_file_name;
			$aRetour[2]=$old_root_file_name;
			$aRetour[3]=$new_root_file_name;
			$aRetour[4]=$upload_a_afaire;

//print("<br>== old_file_name      ==[".$aRetour[1]."]");
//print("<br>== new_file_name      ==[".$aRetour[2]."]");
//print("<br>== old_root_file_name ==[".$aRetour[3]."]");
//print("<br>== new_root_file_name ==[".$aRetour[4]."]");
//print("<br>==$sMethodObjet HHHHHH upload_a_afaire    ==[".$aRetour[4]."]");
//print("<br>-----------------------------");
			return($aRetour);
		}


		// resize image

		function doUpload_img_part2($aRetour, $eWidth, $eHeight) {

			// Dans tous les cas => Renommmage du fichier (suppression du temp)
			if($aRetour[4]) {

				ResizeImg($aRetour[2], $eWidth, $eHeight, $aRetour[3]); // Resize image uploadée
				if(!unlink($aRetour[2])) {
					$status .= 'Erreur : Impossible de renommer le fichier temporaire '. $aRetour[0];
				}
			}
		}

		// upload fichiers joints

		function doUpload_file($Upload) {
/*pre_dump($Upload);
var_dump();
print("<br>================");
print("<br>".$Upload['chemin']);
print("<br>================");
print("<br>".$_SERVER["DOCUMENT_ROOT"].DEF_UPLOADPDF."/".$Upload['nom_originel']);*/

			if (!copy($Upload['chemin'], $_SERVER["DOCUMENT_ROOT"].DEF_UPLOADPDF."/".$Upload['nom_originel'])) {
			   //echo "failed to copy ".$Upload['chemin']."...\n";
			}
			else{
				//echo "copied ".$Upload['chemin']."...\n";
				unlink($Upload['chemin']);
			}
			return $Upload['nom_originel'];
		}
?>
