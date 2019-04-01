<div class="arbo"><u><b><?php if($status!="") echo $status; ?></b></u></div><br>
	<table border="0" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF" class="arbo"><?php
	if($bRetour) {		
		// Récapitulatif de la saisie
		$id = $bRetour;
		eval("$"."oRes = new ".$classeName."($"."bRetour);");
		$qryref = dbGetArrayOneFieldFromRequete($_SESSION['sqlpag']);
		for ($i=0;$i<count($qryref);$i++) {
			if ($qryref[$i]==$id)
				$_SESSION['pag']=$i+1;
		}
		if ($redirString!="") {
		
			if (preg_match ('/\?/', $redirString)) {
				$redirString.="&id=".$id."&adodb_next_page=".$_SESSION['pag'];
			}
			else {
				$redirString.="?id=".$id."&adodb_next_page=".$_SESSION['pag'];
			}
			if ($_GET["display"]!="")
				$redirString.="&display=".$_GET["display"];
		} else {
			if ($classeName == "cms_tableau") {
				$redirString = "/backoffice/cms/cms_tableau/show_".$classeName.".php?id=".$id."&adodb_next_page=".$_SESSION['pag'];
			}
			else if ($classeName == "cms_tag") {
				$_POST["actiontodo"] = "";
				$redirString = "maj2_".$classeName.".php?id=".$id."&adodb_next_page=".$_SESSION['pag'];
			}
			else if (is_get('noMenu')) {
				$redirString = "show_".$classeName.".php?id=".$id."&adodb_next_page=".$_SESSION['pag']."&noMenu=true";
			}
			else  {
				$redirString = "show_".$classeName.".php?id=".$id."&adodb_next_page=".$_SESSION['pag'];
			}
		}
		if ($listParam!="") {
			$redirString .= "&".$listParam;
		}
		if (preg_match('/id=[1-9]+/msi', $redirString) && preg_match('/id=-1/msi', $redirString))
			// 2 fois id= , on garde la value > 0
			$redirString = preg_replace('/([?&]{1})id=-1/msi', '$1', $redirString);		
?>
<script type="text/javascript">
	if (window.name.indexOf("if")==0){	// ifframe fancybox	
		callingItem = window.name.substr(2);
		ajaxReloadFunc = eval("window.parent.ajax"+callingItem+"");
		
		if (ajaxReloadFunc!=undefined){ 
			console.log("window.parent.ajax"+callingItem+"("+<?php echo $id; ?>+")");
			ajaxReloadFunc.call(null, <?php echo $id; ?>);
		}
		else{
			ajaxReloadFunc = eval("window.parent.ajaxDelayed_associations"); 
			
			if (ajaxReloadFunc!=undefined){
				console.log("window.parent.ajaxDelayed_associations(<?php echo $id; ?>, '<?php echo $classeName; ?>')");
				ajaxReloadFunc.call(null, <?php echo $id; ?>, '<?php echo $classeName; ?>');
			}
			else{
				console.log("no callback available");
			}
		}
		//alert("L'enregistrement a bien été pris en compte, vous pouvez désormais le piocher dans le menu déroulant");
		
		window.parent.$.fancybox.close();
	}
	else{
		window.location.href="<?php echo $redirString; ?>";
	}
</script>
<?php if ($aCustom["Sendmail"] == true)  { ?>
			<tr><td colspan="2" class="arbo"><?php include ("send_".$classeName.".php"); ?>&nbsp;</td></tr> 
<?php } //if ($aCustom["Sendmail"] == true) 
	} // Fin si pas d'erreur d'ajout
?>
</table>