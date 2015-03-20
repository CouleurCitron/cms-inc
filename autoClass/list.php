<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

$temps = microtime();
$temps = explode(' ', $temps);
$debut = $temps[1] + $temps[0];
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}
//------------------------------------------------------------------------------------------------------
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/arbopage.lib.php');

unset($_SESSION['BO']['CACHE']);

// translation engine
// Added by Luc - 6 oct. 2009
//if (DEF_APP_USE_TRANSLATIONS) {
	$translator =& TslManager::getInstance();
	$langpile = $translator->getLanguages();
//}

/*
$temps = microtime();
$temps = explode(' ', $temps);
$debut = $temps[1] + $temps[0];*/

/* CACHE */

// stock en cache le XML de la classe
// stock en parallèle les infos concernant les différentes classes liées
cacheClasseXMLAndObjects($classeName);

// permet de dérouler le menu contextuellement
if (function_exists('activateMenu')){
	activateMenu('gestion'.$classeName);
} 

if (preg_match('/\?/', $_SERVER['HTTP_REFERER'])==1) {
	$point = strpos($_SERVER['HTTP_REFERER'], "?");  
}
else {
	$point = strlen($_SERVER['HTTP_REFERER']);
}
// pre_dump($_POST);
// pre_dump($_SESSION);
//  je réinitialise ma recherche  
if ($_POST['operation'] == 'REINIT' || (substr($_SERVER["HTTP_REFERER"], 0, $point)!="http://".$_SERVER['HTTP_HOST']."".$_SERVER["PHP_SELF"] && $_SESSION['classeName']!=$classeName) )  {   
	
	initFilterSession();
	initFilterSession("assoFiltre");
} 

$rows_per_page = 25;
include_once('list.process.php');
?>
<div class="ariane"><span class="arbo2">MODULE >&nbsp;</span><span class="arbo3"><?php echo $translator->getText($classeLibelle).'&nbsp;>&nbsp;'.$translator->getTransByCode('liste'); ?></span></div>
<?php
$aListTools = array();

// Si on a les droits de création
if ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/c/', $customActionControl[$_SESSION['rank']])) {

	if (is_file('maj_'.$classeName.'.php'))
		$aListTools[] = '<a href="javascript:addEmp()" title="'.$translator->getTransByCode('ajouterunitem').'">'.$translator->getTransByCode('ajouterunitem').'&nbsp;&quot;'.$translator->getText($classeLibelle).'&quot;</a>';
	if (is_file('import_'.$classeName.'.php'))
		$aListTools[] = '<a href="import_'.$classeName.'.php" class="arbo" title="Import au format CSV">import&nbsp;CSV</a>';
	if (is_file('swfimport_'.$classeName.'.php'))
		$aListTools[] = '<a href="swfimport_'.$classeName.'.php" class="arbo" title="Import .SWF">Import&nbsp;.SWF</a>';
	if (is_file('flvimport_'.$classeName.'.php'))
		$aListTools[] = '<a href="flvimport_'.$classeName.'.php" class="arbo" title="Import .FLV">Import&nbsp;.FLV</a>';
	if (is_file('xmlimport_'.$classeName.'.php'))
		$aListTools[] = '<a href="xmlimport_'.$classeName.'.php" class="arbo" title="Import XML">Import&nbsp;XML</a>';

	/* si liste ordonnable! */
        eval("$"."oRes = new ".$classeName."();");
	if(!is_null($oRes->XML_inherited))
		$sXML = $oRes->XML_inherited;
	else
		$sXML = $oRes->XML;
	//$sXML = $oRes->XML;
	unset($stack);
	$stack = array();
	xmlClassParse($sXML);
	if(isset($stack[0]["attrs"]["ORDONABLE"])){
        $aListTools[] = "<a href='/backoffice/cms/order.item_class.php?classe=".$classeName."' class=\"arbo\" title=\"".$translator->getText('Ordonner les objets')."\">".$translator->getText('Ordonner les objets')."</a>";
        if($_SESSION['login'] == 'ccitron') {
        	$aListTools[] = "<a href='/backoffice/cms/generate.arbo_class.php?classe=".$classeName."' class=\"arbo\" title=\"".$translator->getText('Générer l\'arborescence')."\">".$translator->getText('Générer l\'arborescence')."</a>";
        }
    }

	echo '<div class="newelement">
		'.join('&nbsp;|&nbsp;', $aListTools).'
	</div>';
}



// s'il y a des enregistrements à afficher et qu'on a les droits pour exporter
if(sizeof($aListe_res) > 0 && ($_SESSION['login'] == 'ccitron' || empty($customActionControl) || preg_match('/x/', $customActionControl[$_SESSION['rank']]))) {
	// du rss ?
	$rssFields = 0;
	for ($iFile=0;$iFile<count($aNodeToSort);$iFile++){
		if ($aNodeToSort[$iFile]['name'] == 'ITEM'){
			if (isset($aNodeToSort[$iFile]['attrs']['RSS']) && ($aNodeToSort[$iFile]['attrs']['RSS'] != '')) 
				$rssFields++;
		}
	}
	
	$aListTools = array();
	
	if (is_file('xlsx_'.$classeName.'.php'))
		$aListTools[] = '<a href="xlsx_'.$classeName.'.php" class="arbo" title="'.$translator->getTransByCode('exportxlsx').'">excel</a>';
	elseif (is_file('xmlxls_'.$classeName.'.php'))
		$aListTools[] = '<a href="xmlxls_'.$classeName.'.php" class="arbo" title="'.$translator->getTransByCode('exportxmlxls').'">excel</a>';
	if (is_file('exportcsv_'.$classeName.'.php'))
		$aListTools[] = '<a href="exportcsv_'.$classeName.'.php" class="arbo" title="'.$translator->getTransByCode('exportcsv').'">csv</a>';
	if (is_file('export_'.$classeName.'.php') && !is_file('xlsx_'.$classeName.'.php'))
		$aListTools[] = '<a href="export_'.$classeName.'.php" class="arbo" title="'.$translator->getTransByCode('exporthtml').'">html</a>';
	if (($_SESSION['login'] == 'ccitron') && is_file('xml_'.$classeName.'.php'))
		$aListTools[] = '<a href="xml_'.$classeName.'.php" class="arbo" target="_blank" title="'.$translator->getTransByCode('exportxml').'">xml</a>';
	if (is_file('delete_'.$classeName.'.php'))
		$aListTools[] = '<a href="javascript:deleteAll()" class="arbo" title="'.$translator->getTransByCode('supprimertout').'">tout supprimer</a>';
	if (is_file('rss_'.$classeName.'.php') && ($rssFields > 0))
		$aListTools[] = '<a href="rss_'.$classeName.'.php" class="arbo" target="_blank" title="Export au format RSS">rss</a>';
	if (is_file('sql_'.$classeName.'.php'))
		$aListTools[] = '<a href="sql_'.$classeName.'.php" class="arbo" target="_blank" title="Export au format SQL">sql</a>';
	if (is_file('preview_'.$classeName.'.php'))
		$aListTools[] = '<a href="preview_'.$classeName.'.php" class="arbo" target="_blank" title="'.$translator->getTransByCode('Previsualiser').'">'.$translator->getTransByCode('Previsualiser').'</a>';
	if (is_file('exportemail_'.$classeName.'.php'))
		$aListTools[] = '<a href="exportemail_'.$classeName.'.php" class="arbo" target="_blank" title="Export E-mails en .xlsx">e-mails</a>';


	echo '<div class="export">
		'.join(' | ', $aListTools).'
	</div>';

}
?>

<br>
<?php
include('list.filters.php');
?>
<div class="arbo" align="center"><strong><?php echo $sMessage; ?></strong></div>
<script src="/backoffice/cms/js/openBrWindow.js" type="text/javascript"></script>
<script type="text/javascript">
		function deleteAll(){
		sMessage = "<?php $translator->echoTransByCode('promptsuppr'); ?>";
  		if (confirm(sMessage)) {
			document.<?php echo $classePrefixe; ?>_list_form.action = "delete_<?php echo $classeName; ?>.php<?php if($_SERVER['QUERY_STRING']!="") echo "?".$_SERVER['QUERY_STRING'];?>";
			document.<?php echo $classePrefixe; ?>_list_form.submit();
		}
	}
	// fonction de tri
	function doTri(sElementTri, sSensTri) {
		$("table.tablesorter").each(function(){
			$(this).css("display", "none");
		});
		
		document.<?php echo $classePrefixe; ?>_list_form.champTri.value = sElementTri;

		// on change de tri
		if (document.<?php echo $classePrefixe; ?>_list_form.sensTri.value == "ASC") sSensTri = "DESC";
		else if (document.<?php echo $classePrefixe; ?>_list_form.sensTri.value == "DESC") sSensTri = "ASC";
		else if (document.<?php echo $classePrefixe; ?>_list_form.sensTri.value == "") sSensTri = "ASC";

		document.<?php echo $classePrefixe; ?>_list_form.sensTri.value = sSensTri;
		document.<?php echo $classePrefixe; ?>_list_form.eStatut.value = "<?php echo $_POST['eStatut']; ?>";
		document.<?php echo $classePrefixe; ?>_list_form.sTexte.value = "<?php echo $_POST['sTexte']; ?>";
		document.<?php echo $classePrefixe; ?>_list_form.action = "list_<?php echo $classeName; ?>.php<?php if($_SERVER['QUERY_STRING']!="") echo "?".$_SERVER['QUERY_STRING'];?>";		
		document.<?php echo $classePrefixe; ?>_list_form.submit();
		
	}

	// ajout d'un enregistrement
	function addEmp(){
		document.<?php echo $classePrefixe; ?>_list_form.actiontodo.value = "MODIF";
		document.<?php echo $classePrefixe; ?>_list_form.display.value = null;
		document.<?php echo $classePrefixe; ?>_list_form.action = "maj_<?php echo $classeName; ?>.php?id=-1<?php if($_SERVER['QUERY_STRING']!="") echo "&".str_replace("id=", "idprev=",ereg_replace("idprev=[^&]*&", "", $_SERVER['QUERY_STRING']));?>";
		document.<?php echo $classePrefixe; ?>_list_form.submit();
	}
	
	// visu de l'enregistrement
	function visuEmp(id) {
		document.<?php echo $classePrefixe; ?>_list_form.id.value = id;
		document.<?php echo $classePrefixe; ?>_list_form.display.value = null;
		document.<?php echo $classePrefixe; ?>_list_form.actiontodo.value = "";
		<?php if ($classeName == "cms_tableau") { ?>
			document.<?php echo $classePrefixe; ?>_list_form.action = "/backoffice/cms/cms_tableau/show_<?php echo $classeName; ?>.php?id="+id+"<?php if($_SERVER['QUERY_STRING']!="") echo "&".str_replace("id=", "idprev=",ereg_replace("idprev=[^&]*&", "", $_SERVER['QUERY_STRING']));?>";
		<?php }
		else { ?>
			document.<?php echo $classePrefixe; ?>_list_form.action = "show_<?php echo $classeName; ?>.php?id="+id+"<?php if($_SERVER['QUERY_STRING']!="") echo "&".str_replace("id=", "idprev=",ereg_replace("idprev=[^&]*&", "", $_SERVER['QUERY_STRING']));?>";
<?php 		} ?>
		
		document.<?php echo $classePrefixe; ?>_list_form.submit();
	}

	// modification de l'enregistrement
	function modifEmp(id) {
		document.<?php echo $classePrefixe; ?>_list_form.id.value = id;
		document.<?php echo $classePrefixe; ?>_list_form.display.value = null;
		document.<?php echo $classePrefixe; ?>_list_form.actiontodo.value = "MODIF";
		document.<?php echo $classePrefixe; ?>_list_form.action = "maj_<?php echo $classeName; ?>.php?id="+id+"<?php if($_SERVER['QUERY_STRING']!="") echo "&".str_replace("id=", "idprev=",ereg_replace("idprev=[^&]*&", "", $_SERVER['QUERY_STRING']));?>";
		document.<?php echo $classePrefixe; ?>_list_form.submit();
	}

	// suppression de l'enregtistrement
	function deleteEmp(id)	{
		sMessage = "<?php $translator->echoTransByCode('promptsupprun'); ?>";
  		if (confirm(sMessage)) {

			document.<?php echo $classePrefixe; ?>_list_form.action = "list_<?php echo $classeName; ?>.php<?php if($_SERVER['QUERY_STRING']!="") echo "?".$_SERVER['QUERY_STRING'];?>";
			document.<?php echo $classePrefixe; ?>_list_form.operation.value = "DELETE";
			document.<?php echo $classePrefixe; ?>_list_form.id.value = id;
			document.<?php echo $classePrefixe; ?>_list_form.display.value = null;
			document.<?php echo $classePrefixe; ?>_list_form.submit();
		}
	}
	
	// change le statut de plusieurs records
	function changeStatut(idStatut)	{
		cbToChange = "";
<?php
for ($m=0; $m<sizeof($aListe_res); $m++) {
	$oRes = $aListe_res[$m];	
	$cb = "cb_".ucfirst($classePrefixe)."_".$oRes->get_id();	
?>
		if (document.getElementById("<?php echo $cb; ?>").checked == true)
			cbToChange += "<?php echo $oRes->get_id(); ?>;";
<?php
}
?>
		if (cbToChange != "") {
			document.<?php echo $classePrefixe; ?>_list_form.cbToChange.value = cbToChange;
			document.<?php echo $classePrefixe; ?>_list_form.idStatut.value = idStatut;
			document.<?php echo $classePrefixe; ?>_list_form.action = "list_<?php echo $classeName; ?>.php<?php if($_SERVER['QUERY_STRING']!="") echo "?".$_SERVER['QUERY_STRING'];?>";
			document.<?php echo $classePrefixe; ?>_list_form.operation.value = "CHANGE_STATUT";
			document.<?php echo $classePrefixe; ?>_list_form.submit();
		} else {
			msg = "<?php $translator->echoTransByCode('selectionneraumoins1'); ?>";
			alert(msg);		
		}
	}


function sel(nomForm, i, l){
    /*if (eval("document.<?php echo $classePrefixe; ?>_rech_form."."+i+".checked"))

    {
        eval("document."+nomForm+"."+l+".className='EnrSelectionne'");
    }
    else
    {   
        var noLigne=l.substring(5, l.length);

        var classe="impair";
        if (noLigne%2==0) classe="pair";   
   
        eval("document."+nomForm+"."+l+".className='"+classe+"'");
    }   */
}

function reinit(){ 	 
         document.location.href="list_<?php echo $classeName; ?>.php"; 	 
         document.<?php echo $classePrefixe; ?>_rech_form.sTexte.value = ""; 	 
         document.<?php echo $classePrefixe; ?>_rech_form.operation.value = "REINIT"; 	 
         document.<?php echo $classePrefixe; ?>_rech_form.submit(); 	 
}

</script>
<?php

// Custom list global actions scripts
if (!empty($aListCustom)) {
	foreach ($aListCustom as $custom) {
		//viewArray($custom);
		if (!empty($custom['JS'])) {
			echo "<script type=\"text/javascript\">\n";
			$search = array("##classePrefixe##", "##classeName##");
			$replace = array($classePrefixe, $classeName);
			echo str_replace($search, $replace, $custom['JS']);
			echo "\n</script>\n";
		}
	}
}

// Custom list local actions scripts
$custom_local_actions = Array();
if (!empty($aCustom['Action']))
	// retro-compatibility
	$custom_local_actions[] = $aCustom;
elseif (!empty($aCustom))
	$custom_local_actions = $aCustom;

if (!empty($custom_local_actions)) {
	foreach ($custom_local_actions as $custom) {
		if (!empty($custom['JS'])) {
			echo "<script type=\"text/javascript\">\n";
			$search = array("##classePrefixe##", "##classeName##", "##id##");
			$replace = array($classePrefixe, $classeName, $oRes->get_id());
			echo str_replace($search, $replace, $custom['JS']);
			echo "\n</script>\n";
		}
	}
}
//pre_dump($sensTri);
//pre_dump($_SESSION['sensTri_res']);
?>
<form name="<?php echo $classePrefixe; ?>_list_form" id="<?php echo $classePrefixe; ?>_list_form"  method="post">

<input type="hidden" name="urlRetour" id="urlRetour" value="<?php echo  $_SERVER['REQUEST_URI'] ; ?>" />
<input type="hidden" name="id" id="id" value="" />
<input type="hidden" name="display" id="display" value="" />
<input type="hidden" name="actionUser" id="actionUser" value="" />
<input type="hidden" name="operation" id="operation" value="<?php echo $operation; ?>" />
<input type="hidden" name="actiontodo" id="actiontodo" value="" />
<input type="hidden" name="sensTri" id="sensTri" value="<?php echo $sensTri; ?>" />
<input type="hidden" name="champTri" id="champTri" value="<?php echo $champTri; ?>" />
<input type="hidden" name="idStatut" id="idStatut" value="" />
<input type="hidden" name="cbToChange" id="cbToChange" value="" />
<input type="hidden" name="eStatut" id="eStatut" value="" />
<input type="hidden" name="sTexte" id="sTexte" value="<?php echo $_SESSION['sTexte']; ?>" />

<!-- Début Pagination --><div class="pagination">
<?php print($pager->bandeau); ?>
</div><!-- Fin Pagination -->

<?php
if(sizeof($aListe_res)>0) {// s'il y a des enregistrements à afficher
	eval("$"."oRes = new ".$classeName."();");
	if(!is_null($oRes->XML_inherited))
		$sXML = $oRes->XML_inherited;
	else
		$sXML = $oRes->XML;
	//$sXML = $oRes->XML;
	unset($stack);
	$stack = array();
	xmlClassParse($sXML);
	
	$classeName = $stack[0]["attrs"]["NAME"];
	$classePrefixe = $stack[0]["attrs"]["PREFIX"];
	$aNodeToSort = $stack[0]["children"];

	$bStatusControl = true;
	if ($_SESSION['rank'] == 'ADMIN')
		$bDeleteButtonControl = true;
	else	$bDeleteButtonControl = false;
	include('cms-inc/autoClass/list.table.php');
} 
else {
	echo '<div align="center" class="noresults">';
	$translator->echoTransByCode('aucunenregistrement');
	echo '</div>';
}
/*$temps = microtime();
$temps = explode(' ', $temps);
$fin = $temps[1] + $temps[0];
echo '<br/>Page exécutée en '.round(($fin - $debut),6).' secondes.<br/>';*/
?>
</form>