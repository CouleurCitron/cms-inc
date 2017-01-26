<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// maj.php
// post des champs normaux
for ($i=0;$i<count($aNodeToSort);$i++){
	if ($aNodeToSort[$i]["name"] == "ITEM") {
		$form_field = 'f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"];
		// DO NOT UPDATE VALUES NOT PASSED ALONG WITH THE FORM
		if (!isset($_POST[$form_field])) {
			if ($aNodeToSort[$i]["attrs"]["TYPE"] == "int") {
				// cas des int, ne pas inscrire de value vide dans la base
				if ($_POST[$form_field] == ""){
					setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], -1);
				}
			} elseif ($aNodeToSort[$i]["attrs"]["NAME"] == "cms_auteur"){
				setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], rewriteIfNeeded($_SESSION['userid']));
			}
				
		
		} else {
			// md5 field for translation references
			if ($classeName == 'cms_chaine_reference' && $aNodeToSort[$i]["attrs"]["NAME"] == 'md5') {
				setItemValue($oRes, 'md5', md5($_POST['fCms_crf_chaine']));
			} else {
				require($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/maj.saveposteditems.translations.php');
				
				if ($aNodeToSort[$i]["attrs"]["TYPE"] == "int") {
					// cas des int, ne pas inscrire de value vide dans la base
					if ($_POST[$form_field] == ""){
						$_POST[$form_field] = -1;
					}
					if($aNodeToSort[$i]["attrs"]["NAME"] == 'rank'){
						if (!isset($oUserLogged)){
							$oUserLogged = unserialize($_SESSION['BO']['LOGGED']);
						}
						$sRankId = $oUserLogged->get_rank();				
						if ($_POST[$form_field]=='-1'){
							if($oRes->get_id()==-1){// new record, rank non setté, on set au rank de l'user loggé
								setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], $sRankId);
							} else{
								// record existant
								$sGetter = 'get_'.$aNodeToSort[$i]["attrs"]["NAME"];
								if (method_exists($oRes, $sGetter)){
									$settedRank = $oRes->$sGetter();
									if ($settedRank==-1){ // ah non pas ça
										setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], $sRankId);
									}
								}
							}							
						}
						else{// rank setté dans le form
							if ((int)$_POST[$form_field] < (int)$sRankId){ // ah, ça on n'a pas le droit, on set au rank de l'user loggé
								setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], $sRankId);
							} else{
								setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], $_POST[$form_field]);
							}
						}
					} else{
                                            //pre_dump('trad_else_int_rank_else_'.$form_field);
						// Handle status change log
						if ($classeLogStatus && $oRes->getGetterStatut() == 'get_'.$aNodeToSort[$i]["attrs"]["NAME"]) {
							$tmp_getter = $oRes->getGetterStatut();
							if ($oRes->$tmp_getter() != $_POST[$form_field])
								$classeChangeStatus = true;
						}
//                                                pre_dump($form_field);
//                                                pre_dump($_POST[$form_field]);
//                                                pre_dump(rewriteIfNeeded($_POST[$form_field]));
                                                
						setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], rewriteIfNeeded($_POST[$form_field]));
					}
				} elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "password") {// cas password, on cryte 
					if (is_post($form_field)){
						//setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], md5($_POST[$form_field]));
						setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], password_hash($_POST[$form_field], PASSWORD_DEFAULT));
					}
				}
				elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "url") {// cas url, on ajoute le protocole http:// si manque
					if (isset($_POST[$form_field])) {
						$tempUrl = trim($_POST[$form_field]);
						if (!preg_match("/^http|ftp|https]:\/\/.*/msi", $tempUrl) && ($tempUrl != ""))
							$tempUrl = "http://".$tempUrl;		
							
						$tempUrl = rewriteIfNeeded($tempUrl);
						
						setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], $tempUrl);
					}
				}
				elseif ($aNodeToSort[$i]["attrs"]["OPTION"] == "file") {// file 
					//echo $form_field." ".$_POST[$form_field];
					// file récupéré avec le bouton parcourir le serveur
					 
					 
					preg_match_all("/{([^{}].*?)}/", $_POST[$form_field], $matches);
				 
					$aAll_images = array();
					
					$url_image = $_POST[$form_field];
					 
					
					if (sizeof($matches[1]) > 0) {
						$aAll_images = $matches[1]; 
					}
					else {
						$aAll_images[] = $_POST[$form_field];
					} 
					
					$aImageOutputsAll = array ();
					foreach ($aAll_images as $url_image) {
					
						
						if ( basename($url_image) !=  $url_image  ){ 
							 // copy
							 $subfile = basename($url_image);
							 
							 if ($_SERVER['DOCUMENT_ROOT'].$url_image != $_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$classeName.'/'.$subfile) {
								 if (!copy($_SERVER['DOCUMENT_ROOT'].$url_image, $_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$classeName.'/'.$subfile)) {
									//echo "failed to copy ".$_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$classeName.'/'.$subfile."...\n";
								} else {
									$url_image = 	$subfile;  
								} 
							}
							else {
								$url_image = 	$subfile; 
							}
							 
						}
						// file récupéré avec le bouton parcourir l'ordinateur local
						else {
							// echo "custom<br />";
						}
						
						$filePath = $_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$classeName.'/'.$url_image;  
						if (is_file($filePath ) && $url_image!=""){
							if (preg_match("/png|jpeg|jpg|gif|bmp/msi",  	$url_image)==1) {			
								if (is_array($aNodeToSort[$i]['children'])){
									$aImageOptions = array();
									$aImageOutputs = array(basename($filePath));
									foreach($aNodeToSort[$i]['children'] as  $kO => $nOption){
										if ($nOption['attrs']['TYPE']=='image'){
										$aImageOptions[]=$nOption['attrs'];
										}
									}
									// on effectue les opés de rigueur
									if (count($aImageOptions)>0){
										foreach($aImageOptions as $kO => $aOption){
											$aOption['src']=$filePath;
											//echo 'traiter l\'image '.$aOption['src'].' en X '.$aOption["MAXWIDTH"].' et Y '.$aOption["MAXHEIGHT"].'<br />';
											//echo 'traiter l\'image '.$aOption['src'].' en X '.$aOption["WIDTH"].' et Y '.$aOption["HEIGHT"].'<br />';
											
											$oIm = imagecreatefromAnyFile($aOption['src']);
											$bDoResize=false;
											if(isset($aOption['WIDTH'])&&($aOption['WIDTH']!='')&&isset($aOption['HEIGHT'])&&($aOption['HEIGHT']!='')){											
												if((imagesx($oIm)!=$aOption['WIDTH']) || (imagesy($oIm)!=$aOption['HEIGHT'])){
													$oIm = resizeImageObjectWidthHeightStrict($oIm, $aOption['WIDTH'], $aOption['HEIGHT']);
													$bDoResize=true;
												}
											}	
											elseif(isset($aOption['WIDTH'])&&($aOption['WIDTH']!='')){											
												if((imagesx($oIm)!=$aOption['WIDTH'])){
													$oIm = resizeImageObjectWidthWise($oIm, $aOption['WIDTH']);
													$bDoResize=true;
												}
											}	
											elseif(isset($aOption['HEIGHT'])&&($aOption['HEIGHT']!='')){											
												if((imagesy($oIm)!=$aOption['HEIGHT'])){
													$oIm = resizeImageObjectHeightWise($oIm, $aOption['HEIGHT']);
													$bDoResize=true;
												}
											}
											elseif(isset($aOption['MAXWIDTH'])&&($aOption['MAXWIDTH']!='')&&isset($aOption['MAXHEIGHT'])&&($aOption['MAXHEIGHT']!='')){
												if ((imagesx($oIm)>$aOption['MAXWIDTH'])||(imagesy($oIm)>$aOption['MAXHEIGHT'])){
													$oIm = resizeImageObjectWidthHeightWise($oIm, $aOption['MAXWIDTH'], $aOption['MAXHEIGHT']);
													$bDoResize=true;
												}
											}
											//if ($bDoResize==true){
												if (preg_match('/^.+\.bmp$/msi', $aOption['src'])==1){
													$aOption['resize']=preg_replace('/^(.+)\.bmp$/i', $aNodeToSort[$i]["attrs"]["NAME"].'_$1-size-'.($kO+1).'.jpg', basename($aOption['src']));
												}
												else{							
													$aOption['resize']=preg_replace('/^(.+)\.([png|jpeg|jpg|gif]+)$/i', $aNodeToSort[$i]["attrs"]["NAME"].'_$1-size-'.($kO+1).'.$2', basename($aOption['src']));
												}
												$aImageOutputs[]=$aOption['resize'];
												$newFilePath=$_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$classeName.'/'.$aOption['resize'];
												//echo $newFilePath.'<br />';												
												imageoutputtoAnyFile($oIm, $newFilePath);
											//}								
											
										}
									}
								}
								else { 
									$aImageOutputs = array($url_image);
								}	
							}
							else {  
								$aImageOutputs = array($url_image);
							} 
							if (count($aImageOutputs)>0){ // custom  
								//setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], implode(';', $aImageOutputs)); // par défaut
								//
								//pre_dump( $aImageOutputs);
								$aImageOutputsAll[]= implode(';', $aImageOutputs);
							}
						}
						else { 
							$url_image = basename($url_image); 
							// valeur vide, arrive quand on efface le fichier
							//setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], $url_image); // par défaut
							$aImageOutputsAll[]= $url_image;
						}
						
					} // end foreach ($aAll_images as $url_image) {
					
					
					
					if (sizeof($aImageOutputsAll) > 1) {
						setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], "{".implode ("}{", $aImageOutputsAll)."}" );
					}
					else if (preg_match ("/\[/", $aImageOutputsAll[0])) {
						setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], "{".implode ("", $aImageOutputsAll)."}" );
					}
					else {
						setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], implode ("", $aImageOutputsAll) );
					}
					
				}
				// decimal type
				elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "decimal"){
					setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], str_replace(',', '.', $_POST[$form_field]));
				}
				else{
					 //die("MAJ_SAVE");
					
					$value = compliesFCKhtmlForModuleContenu(rewriteIfNeeded($_POST[$form_field]));
					setItemValue($oRes, $aNodeToSort[$i]["attrs"]["NAME"], $value);
				}
			}
		}
	}
}
//die();
$oRes->set_id($id);
//pre_dump($oRes);
//die();
//pre_dump($oRes);

?>