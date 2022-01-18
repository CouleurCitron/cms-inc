<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
} 
 

include('cms-inc/swish.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
 
if (function_exists('activateMenu')){
	activateMenu('gestion'.$classeName);
}  


// objet 
eval("$"."oRes = new ".$classeName."();");

$sXML = $oRes->XML;
xmlClassParse($sXML);

$classeName = $stack[0]["attrs"]["NAME"];
if (isset($stack[0]["attrs"]["LIBELLE"]) && ($stack[0]["attrs"]["LIBELLE"] != "")){
	$classeLibelle = stripslashes($stack[0]["attrs"]["LIBELLE"]);
}
else{
	$classeLibelle = $classeName;
}
$classePrefixe = $stack[0]["attrs"]["PREFIX"];
$aNodeToSort = $stack[0]["children"];

	?> 
<div class="ariane"><span class="arbo2">MODULE >&nbsp;</span><span class="arbo3"><?php echo $classeLibelle; ?>&nbsp;>&nbsp;Recherche</span></div>
<script language='JavaScript' type="text/javascript">

function validformul(){
	document.recherche.actiontodo.value="rechercher";
	if (document.getElementById('textfield').value=="") {
		alert ("Merci de saisir un mot-clé");
	}
	else {
		document.recherche.action="search_<?php echo $classeName; ?>.php?keyword="+document.getElementById('textfield').value;
		document.recherche.submit();
	}
	
}

function erase() {
 
	document.getElementById('textfield').value ="";
	 
}
</script> 

 




<form id="recherche" name="recherche" method="post" action="" >
<input type="hidden" name="actiontodo" id="actiontodo" value="" />
<div id="filters" class="arbo">
<br/>
<br/>
<table cellpadding="0" cellpadding="0" border="0" >
	<tr><td>
		<div id="keywordFilter" class="keywordFilter">
 			<div align="left" id="keywordFilterLabel" class="keywordFilterlabel">recherche par mot clé</div>
			<?php 
			if ($_POST["textfield"]!="")$sValue = $_POST["textfield"];
			else  $sValue = "Rechercher";
			?>
			<div align="left" id="keywordFilterField" class="keywordFilterField"><input onkeypress="if(event.keyCode==13) validformul()" id="textfield"  onclick="javascript:erase()" name="textfield" value="<?php echo $sValue; ?>"  class="arbo" size="40"/></div>
		</div>
	</td></tr>
	<tr><td align="left">
		<input id="btChercher" class="arbo" type="button" value="chercher" onclick="validformul();" name="btChercher"/>
	</td></tr>
</table>
</div>
</form>



<?php

if ($_POST["actiontodo"]!="") {

	$idSite = $_SESSION["idSite"];
	$oCms_site = new Cms_site($idSite);
	$siteName = $oCms_site->get_name();
	 
	$sFileName = $_SERVER['DOCUMENT_ROOT']."/custom/search/".strtoupper($siteName)."/donnees.index";  
	$sFileName_pdf = $_SERVER['DOCUMENT_ROOT']."/custom/search/".strtoupper($siteName)."/howto-pdf.index";  
	 
	$words = $_GET['keyword'];  
	$words = utf8_decode($words);
	
	
	$aTempResults = array();
			
	// $words = chars2htmlcodes($words);
	if(strlen($words)>0) {
	 
		$wordList = split(' ',$words);
		$args = join("* ", $wordList);
		$argspath = join(" AND ", $wordList);
		//$args = $args."*\" OR -w \"swishdocpath=( ".$argspath." )"; // Recherche aussi dans l'URL de la page
		$args = $args."*"; 
		$swishObj = new swish($sFileName);
		$swishObj->set_params($args);
		$results = $swishObj->get_result();
		$aObjets = array(); 
		
		/* partie pdf */
		$swishObj_pdf = new swish($sFileName_pdf);
		$swishObj_pdf->set_params($args);
		$results_pdf = $swishObj_pdf->get_result();
		 /* partie pdf */
		 
		$sql = "SELECT ".$classeName.".* ";
		$sRechercheTexte = ""; 
		for ($k=0; $k<newSizeOf($wordList); $k++) { 
			if ($wordList[$k]!="") {
				$urlFile= "../../custom/upload/".$classeName; 
				$aTempResults_=getFileByName($urlFile, $wordList[$k]); 
				if (newSizeOf($aTempResults_)>0) {
					for ($j=0; $j<newSizeOf(aTempResults_);$j++) {
						array_push ($aTempResults,$aTempResults_[$j]); 
					}
				} 
				//$_SESSION['sTexte']=$sTexte;
		
				$oRech = new dbRecherche();
				
				$oRech->setValeurRecherche("declencher_recherche");
				$oRech->setTableBD($classeName);
				
				$cptvarchar=0;
				$cpt =0;
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
						if ($cptvarchar==$cpt && $k == (newSizeOf($wordList)-1)){	
							if($cpt==1 && $k == 0){$sRechercheTexte .="(";}
							$sRechercheTexte .= ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]." like'%".$wordList[$k]."%' )";
							//$sRechercheTexte .= ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]." like'%".$sTexte."%'";
						}
						else {				
							if($cpt==1&& $k == 0){$sRechercheTexte .="(";}
							$sRechercheTexte .= ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]." like'%".$wordList[$k]."%' OR ";
						}
						 
					}//fin if (($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text"))
				}// fin for ($i=0;$i<count($aNodeToSort);$i++)	
			
			
			
			}
			
		}  
		$oRech->setJointureBD($sRechercheTexte);
		$oRech->setPureJointure(1);
		$aRecherche[] = $oRech; 
		$sql.= dbMakeRequeteWithCriteres2($classeName, $aRecherche, $aGetterOrderBy, $aGetterSensOrderBy);
		$aObjets= dbGetObjectsFromRequete($classeName, $sql); 
		?>
	<table class="pagination" cellspacing="0" cellpadding="0" border="0" align="center" >
	<tbody>
	<tr>
	<td>Terme(s) recherch&eacute;(s) : &quot;&nbsp;<strong><?php echo $sResultatdelarecherchesur; ?><?php echo $words; ?></strong>&nbsp;&quot; : <?php echo newSizeOf($results)+newSizeOf($results_pdf); ?> résultats  (fiches non comptabilisées)</td>
	</tr>
	</tbody>
	</table> 
	<br><br>
	<table class="arbo" cellspacing="0" cellpadding="5" bordercolor="#ffffff" border="0" align="center" width="100%">
<tbody>

 
 
		<?php
		if(is_array($results) || newSizeOf($aTempResults) >0 || newSizeOf($aObjets) > 0 || newSizeOf($results_pdf) > 0 ) {
 		
		?>
		<tr class="col_titre"> 
			<td nowrap="" align="center"><b>Fichier</b></td>
			<td nowrap="" align="center"><b>URL</b></td>
			<td nowrap="" align="center"><b>Recherche par</b></td>
		</tr>
		<?php 
			
			
			$results_ = array_merge ($results, $results_pdf);
			$results = $results_; 
			
			
			// recherche par contenu
			$resultTemps = array();
			$urlFile = str_replace("../..","",$urlFile)."/"; 
			foreach($results as $k => $v){
				$url = $v['URL']; 
				$url = str_replace ($_SERVER["DOCUMENT_ROOT"], "../../..",$url );
				$url = split("../../..",$url);   
				$url = $url[1];		 
				if( (preg_match("/\.php$/",$url)) || (preg_match("/\.doc$/",$url)) || (preg_match("/\.pdf$/",$url)) || (preg_match("/\.txt$/",$url))){ 
					$rep = preg_replace("`^/content/`","",$url);
					$rep = preg_replace("`\.[^\.]*$`","",$rep); 
					$rep = preg_replace("`/(index)*$`","",$rep);
					$rep = preg_replace("`^/`","",$rep);
					$rep = preg_replace("`/`"," > ",$rep); 
					$posNom = strrpos($rep, " > ");
					$rep2 = substr($rep, $posNom, strlen($rep));
					$rep2 = str_replace("_", " ", $rep2); 
					
					$file =  $v['TITRE']; 
					$posFile = strrpos($file, "/"); 
					$chemin = str_replace("$file", "", $url); 
				 
				 	$file = str_replace ($urlFile, "", $url);
					 
					if ($k%2 == 0) $sClass= "pair";
					else   $sClass= "impair";
					?>
					<tr class="<?php echo $sClass; ?>">
						<td nowrap="" align="left"> 
							<a href="/backoffice/cms/utils/telecharger.php?file=<?php echo $file; ?>&chemin=<?php echo $chemin; ?>">
								<?php echo stripslashes($rep2);?> 
							</a>
						</td>
						<td nowrap="" align="left"><a href="/backoffice/cms/utils/telecharger.php?file=<?php echo $file; ?>&chemin=<?php echo $urlFile; ?>"><?php echo $url;  ?></a> </td>
						<td nowrap="" align="left">Contenu <?php if (in_array($file, $aTempResults)) echo "et Nom du fichier "; ?></td>
					</tr>
					<?php
					$resultTemps[]=$url;
				}
		
			} 
			
			
			 
			
			
			
			// recherche par nom de fichier  
			 
			
			for ($l=0; $l<newSizeOf($aTempResults); $l++) {
				if (!in_array($urlFile.$aTempResults[$l], $resultTemps)) {
					if ($sClass =="impair") $sClass= "pair";
					else   $sClass= "impair"; 
				
					?>
					<tr class="<?php echo $sClass; ?>">
						<td nowrap="" align="left"> 
							<a href="/backoffice/cms/utils/telecharger.php?file=<?php echo $aTempResults[$l]; ?>&chemin=<?php echo $urlFile; ?>">
								<?php echo stripslashes(preg_replace("`\.[^\.]*$`","",$aTempResults[$l]));?> 
							</a>
						</td>
						<td nowrap="" align="left"><a href="/backoffice/cms/utils/telecharger.php?file=<?php echo $aTempResults[$l]; ?>&chemin=<?php echo $urlFile; ?>"><?php echo $urlFile.$aTempResults[$l];  ?></a> </td>
						<td nowrap="" align="left">Nom du fichier</td>
					</tr>
					<?php
				}
				 
			} 
			if (newSizeOf($aObjets) > 0) {
				for ($m=0; $m<newSizeOf($aObjets); $m++) { 
					$oObjet = $aObjets[$m]; 
					if ($sClass =="impair") $sClass= "pair";
					else   $sClass= "impair";   
					?>
					<tr class="<?php echo $sClass; ?>">
						<td nowrap="" align="left"> 
							<?php echo getItemValue($oObjet, $oObjet->getDisplay());?> 
							<?php echo getItemValue($oObjet, $oObjet->getAbstract());?> 
						</td>
						<td nowrap="" align="left">
						<?php if (is_file("../".$oObjet->getClasse()."/show_".$oObjet->getClasse().".php") == true){ 
							echo "<a href=\"../".$oObjet->getClasse()."/show_".$oObjet->getClasse().".php?id=".$oObjet->get_id()."\">";
							echo "voir la fiche";
							echo "</a>";
						} ?>
						</td>
						<td nowrap="" align="left">Fiche</td>
					</tr>
					<?php 
					 
				}
			}
			
			
			
		}
?>
</tbody>
</table>
<?php

	}
}

else {
	?>
	<table class="pagination" cellspacing="0" cellpadding="0" border="0" align="center" >
	<tbody>
	<tr>
	<td>&nbsp;</td>
	</tr>
	</tbody>
	</table> 
	<?php
}

?>