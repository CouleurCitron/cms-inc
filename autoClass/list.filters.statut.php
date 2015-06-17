<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

//visu link
	if (!isset($translator))
		$translator =& TslManager::getInstance(); 
 
	if ( $oRes->getGetterStatut() == "get_statut") {
	?>
	
	<div id="statutFilter" class="statutFilter blocItem">
		<div id="statutFilterLabel" class="statutFilterLabel"><?php $translator->echoTransByCode('Recherche_par_statut'); ?></div>
		<div id="statutFilterField" class="statutFilterField">
		<select name="eStatut" id="eStatut" class="arbo" onchange="filterChange()">
          <option value="-1">-- <?php $translator->echoTransByCode('tous'); ?> --</option>
		  <?php
		  $statusNode = getItemByName($aNodeToSort, "statut");
		  if (!isset($statusNode["children"])){ // cas typique		  
		  ?>
          <option value="<?php echo DEF_ID_STATUT_ATTEN?>" <?php
if($eStatut==DEF_ID_STATUT_ATTEN) echo 'selected';?>>
          <?php echo lib(DEF_ID_STATUT_ATTEN)?>
          </option>
          <option value="<?php echo DEF_ID_STATUT_LIGNE?>" <?php
if($eStatut==DEF_ID_STATUT_LIGNE) echo 'selected';?>>
          <?php echo lib(DEF_ID_STATUT_LIGNE)?>
          </option>
		   <option value="<?php echo DEF_ID_STATUT_ARCHI?>" <?php
if($eStatut==DEF_ID_STATUT_ARCHI) echo 'selected';?>>
          <?php echo lib(DEF_ID_STATUT_ARCHI)?>
          </option>
		  <?php
		  }
		  else{ // cas statut custom
		  	for ($iSta=0; $iSta<count($statusNode["children"]);$iSta++){
				if ($statusNode["children"][$iSta]["attrs"]["TYPE"] == "value"){
					echo "<option value=\"".$statusNode["children"][$iSta]["attrs"]["VALUE"]."\" ";
					if($eStatut==intval($statusNode["children"][$iSta]["attrs"]["VALUE"]))
						echo 'selected';
					echo ">";
					echo $translator->getText($statusNode["children"][$iSta]["attrs"]["LIBELLE"], $_SESSION['id_langue']);					
					echo "</option>";
				}
			}		  
		  }
		  ?>          
        </select>
		</div>
	 </div>
	 <?php 
	 }
?>