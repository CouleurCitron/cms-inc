<script type="text/javascript">
			
function isEmail(sEmail){	
	if ((sEmail.indexOf('@',0)==-1) || (sEmail.indexOf('.',0)==-1)) return false;
	else return true;
}

function validformul(){
			erreur=0;
			lib="";
			 
			if(document.commentaires.mail.value!="" && isEmail(document.commentaires.mail.value)==false)  {
				erreur++;
				lib+="Il y a une erreur de syntaxe dans ton email\n";
				
			}  
			if(erreur>0){
				alert(lib);
			}
			else{
				document.commentaires.operation.value="posteuncom";
				document.commentaires.submit();
			}
		}
</script>
<?php
if ((strpos($_SERVER['PHP_SELF'], "backoffice/") === false)&&(strpos($_SERVER['PHP_SELF'], "tmp/") === false)){ 
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php'); 
	 
	if (is_as_get("id")) $id = $_GET["id"];
	if (is_as_get("node")) $node = $_GET["node"];
	if (is_as_get("mois") &&  is_as_get("annee") && is_as_get("jour")) $sDate = $annee."-".$mois."-".$jour;
	
	
	if ($id!="") {
		$sql = " select * from cms_content  ";
		$sql.= " where  id_content  = ".$id;
	}
	else {
		$sql = " select * from cms_content, cms_page, cms_struct_page ";
		$sql.= " where isbriquedit_content  =  1 ";
		$sql.= " and statut_content =".DEF_ID_STATUT_LIGNE;
		$sql.= " and cms_page.valid_page =1 "; 
		$sql.= " and cms_page.existeligne_page =1 "; 
		$sql.= " and cms_struct_page.id_page = cms_page.id_page " ;
		$sql.= " and cms_struct_page.id_content = cms_content.id_content " ;
	}
	if ($node!="") $sql.= " and nodeid_content  = ".$node;
	if ($sDate!="") $sql.= " and dateupd_content ='".$sDate."' "; 
	$sql.= " order by dateupd_content DESC ";
	
	$aM = dbGetObjectsFromRequete("cms_content", $sql);
	
	if (sizeof($aM) > 0)  { 
		for ($i = 0; $i <sizeof ($aM); $i++) { 		
		
			$oM = $aM[$i]; 
			
			// titre
			$sql = " select * from cms_struct_page, cms_page, cms_infos_pages ";
			$sql.= " where id_zonedit_content  = 1 ";
			$sql.= " and cms_infos_pages.page_id = cms_page.id_page " ;
			$sql.= " and cms_struct_page.id_page = cms_page.id_page " ;
			$sql.= " and cms_struct_page.id_content = ".$oM->getId_content()." ";
			$aInfo = dbGetObjectsFromRequete("Cms_infos_page", $sql);
			$oInfo = $aInfo[0];
			 
			//page
			$aPage = dbGetObjectsFromRequete("Cms_page", $sql);
		
			if (count($aPage)>0){
		
			
				// poste par 
				$sql = " select * from cms_droit, bo_users ";
				$sql.= " where cms_droit.user_id  = bo_users.user_id "; 
				$sql.= " and cms_droit.id_content = ".$oM->getId_content()." ";
				$aUsr = dbGetObjectsFromRequete("bo_users", $sql);
				$oUsr = $aUsr[0];
				list($date, $heure) = explode(' ', $oM->getDateupd_content());
				list($annee, $mois, $jour)  = explode('-', $date);
				list($heure, $minute, $seconde) = explode(':', $heure);
				$sDate = getDateFR(date(" j F Y" , mktime(0, 0, 0, $mois, $jour, $annee))); 
				// node
				$nodeInfos = getNodeInfos($db, $oM->getNodeid_content());
				
				echo "<p class='content_date'><strong>".$sDate."</strong></p>";
				echo "<p class='content_titre'><strong>".$oPage->getName_page()."</strong></p><br />";
				
				
				// oeuvre 
				if ($oM->getObj_table_content()!="") {
					if (ereg("mp3", $oM->getObj_table_content())) {
						echo "<p class='telecharge_mp3'><a href=\"javascript:openBrWindow('/modules/player/index.php?id=".$oM->getId_content()."','Ecoute_mon_oeuvre',300,100,'scrollbars=no','true')\" title=\"Ecoute mon oeuvre\"><img src='/custom/img/picto_casque.gif' /></a></p>";
						echo "<p class='telecharge_mp3'><a href=\"javascript:openBrWindow('/modules/player/index.php?id=".$oM->getId_content()."','Ecoute_mon_oeuvre',300,100,'scrollbars=no','true')\" title=\"Ecoute mon oeuvre\">Ecoute mon oeuvre</a></p>";
					} 				
					else if (ereg("flv", $oM->getObj_table_content())) { 
						echo "<div align='center'>";
						$_GET['file'] = "/custom/upload/blog/".$oM->getId_content()."/".$oM->getObj_table_content();
						$_GET['autostart'] = 0; 
						include('backoffice/cms/utils/awsVideo.inc.php');
						echo "</div>";
						
					}
					else if (ereg("jpg", strtolower($oM->getObj_table_content())) || ereg("png", strtolower($oM->getObj_table_content())) || ereg("gif", strtolower($oM->getObj_table_content())) || ereg("bmp", strtolower($oM->getObj_table_content())) ) { 
						echo "<div align='center'>";
						echo "<a href=\"/custom/upload/blog/".$oM->getId_content()."/".$oM->getObj_table_content()."\" target=\"_blank\"><img width=\"350px\" src=\"/custom/upload/blog/".$oM->getId_content()."/".$oM->getObj_table_content()."\"></a>";
						echo "</div>";
						
					}
					else {
						echo "<p class='telecharge_pdf'><a href=\"/modules/utils/telecharger.php?file=".$oM->getObj_table_content()."&chemin=/custom/upload/blog/".$oM->getId_content()."/&\" title=\"Lis mon oeuvre\"><img src='/custom/img/picto_pdf.gif' width='70px' /></a></p>";
						echo "<p class='telecharge_pdf'><a href=\"/modules/utils/telecharger.php?file=".$oM->getObj_table_content()."&chemin=/custom/upload/blog/".$oM->getId_content()."/&\" title=\"Lis mon oeuvre\">Télécharger</a></p>";
					}
				 }	
					echo "".$oM->getHtml_content()."</p><br />";
				//echo "<p> <strong>Mot clés :</strong> ".$oInfo->getPage_motsclefs."</p>";
				echo "<p> <strong>Posté par : </strong> ".ucfirst(strtolower($oUsr->get_prenom()))." ".ucfirst(strtolower($oUsr->get_nom()))." à ".$heure.":".$minute."</p>";
				
				echo "<p> <strong>Rubrique : </strong> <a href='/content/blogeauagen/index.php?node=".$nodeInfos["id"]."'>".$nodeInfos["libelle"]."</a></p>";
				echo "<br />";
				echo "<p> Permalien [<a href='/content/blogeauagen/index.php?id=".$oM->getId_content()."'>#</a>]</p>";
				
				// connaitre le nombre de com
				$sql = " select * from cms_avis ";
				$sql.= " where avis_id_page = ".$oInfo->getPage_id() ;
				$sql.= " and avis_statut = ".DEF_ID_STATUT_LIGNE." ";
				$aAv = dbGetObjectsFromRequete("cms_avis", $sql);
				if (sizeof($aAv) >0) $nbCom = " (".sizeof($aAv).")";
				echo "<p> <a href='/content/blogeauagen/index.php?id=".$oM->getId_content()."'>Commentaires".$nbCom."</a></p>"; 
				
				if ($id=="")  {
					echo "<div class='content_milieu'></div>"; 
				}
				else {
					//partie commm
					?>
					<div class="content_com"></div>
					<p class='content_titre2'><strong>Commentaires</strong></p>
					<?php
					//  liste des commentaires précédents
					// titre
					$sql = " select * from cms_avis ";
					$sql.= " where avis_id_page = ".$oInfo->getPage_id() ;
					$sql.= " and avis_statut = ".DEF_ID_STATUT_LIGNE." ";
					$aAv = dbGetObjectsFromRequete("cms_avis", $sql);
					 
					foreach ($aAv as $avis) {
						?>
						<p><strong><?php echo $avis->get_titre()?></strong></p>
						<p><?php echo $avis->get_texte()?></p>
						<?php
						if ($avis->get_nomweb1()!="") {
							if (!ereg("http://", $avis->get_nomweb1()) && !ereg("https://", $avis->get_nomweb1())) $web = "http://".$avis->get_nomweb1();
							$contact = "<a href='".$web."'>".$avis->get_nomcontact()."</a>";
						}
						else $contact =  $avis->get_nomcontact() ;
						
						//date
						list($date, $heure) = explode(' ', $avis->get_dcreat());
						list($annee, $mois, $jour)  = explode('-', $date);
						list($heure, $minute, $seconde) = explode(':', $heure);
						$sDate = getDateFR(date(" j F Y" , mktime(0, 0, 0, $mois, $jour, $annee))); 
						?>
						<p><strong>Posté par :</strong> <?php echo $contact?>, <?php echo $sDate?> à <?php echo $heure.":".$minute?></p>
						<?php	 
					}				
					?>
					<div class="content_post_com"></div>
					<p class='content_titre2'><strong>Poste un com</strong><br /></p>
					<?php
					if ($_POST["operation"] == "posteuncom") {
						$oAv = new Cms_avis(); 
						$oAv->set_id_page($oInfo->getPage_id());
						$oAv->set_titre($_POST["titre"]);
						$oAv->set_texte($_POST["com"]);
						$oAv->set_nomcontact($_POST["nom"]);
						$oAv->set_mailcontact($_POST["mail"]);
						$oAv->set_nomweb1($_POST["url"]);
						$oAv->set_web1("");
						$oAv->set_nomweb2("");
						$oAv->set_web2("");
						$oAv->set_note(-1);
						$oAv->set_statut(5);
						$oAv->set_dcreat(date("Y/m/d/H:m:s"));
						$oAv->set_dmaj(getDateNow());
						$oAv->set_ucreat(-1);
						$oAv->set_umaj(-1);
						$oAv->set_id_site($idSite); 
						$bRetour = dbInsertWithAutoKey($oAv);
						if ($bRetour) 
							echo "<p>Merci pour ton commentaire. Celui-ci passera par validation avant d'être mis en ligne.</p>"; 
						else
							echo "<p>Une erreur est survenue, tu peux retenter de poster un com.</p>"; 
					}
					?>
					<!--<div class="content_post_com"></div>-->
					<form name="commentaires" id="commentaires" method="post" action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $oM->getId_content()?>" ENCTYPE="multipart/form-data">
						<input name="operation" id="operation" type="hidden" /> 
						<div class="ligne_form"> <label for="nom">Nom et prénom </label> 	  <input type="text" id="nom" name="nom" value="<?php echo $_POST['nom']?>"/> 		</div>
						<div class="ligne_form"> <label for="email">Adresse email * </label>  <input type="text" id="mail" name="mail" value="<?php echo $_POST['mail']?>"/> 	</div>
						<div class="ligne_form"> <label for="url">Site web (url)  </label> <input type="text" id="url" name="url" value="<?php echo $_POST['url']?>"/> 		</div>
						<div class="ligne_form"> <label for="titre">Titre </label> 	  <input type="text" id="titre" name="titre" value="<?php echo $_POST['titre']?>"/> 		</div>
						<div class="ligne_form"> <label for="com">Commentaires </label> 	  <textarea  id="com" name="com" ></textarea>		</div> 
						
						<div class="ligne_form"><a href="#" onClick="javascript:validformul();">Envoyer</a></div>
					</form>
					<?php
				}
			}
		} 
			 
	}
	else {		
		echo '<div>Pas de message</div>';		
	}
}
?>