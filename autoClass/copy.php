<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!isset($classeName)){
	$classeName = preg_replace('/[^_]*_(.*)\.php/', '$1', basename($_SERVER['PHP_SELF']));
}
//------------------------------------------------------------------------------------------------------

// Formulaire de saisie 

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

// Chargement de la classe Upload
require_once('cms-inc/lib/fileUpload/upload.class.php');
if (is_get("id")) {
	$id=$_GET['id'];
	$qryref = dbGetArrayOneFieldFromRequete($_SESSION['sqlpag']);
	for ($i=0;$i<count($qryref);$i++) {
		if ($qryref[$i]==$id) {
		//echo $qryref[$i].$i."<br>";
		$_SESSION['pag']=$i+1;
		}
	}
}
if ( $display > 0 ){
	 $operation = "UPDATEORINSERT";
}
elseif ( $id > 0 ){
	 $operation = "UPDATE";
}
else {
	$operation = "INSERT";
}
if ( $operation == "INSERT" ) { // Mode ajout
	eval("$"."oRes = new ".$classeName."();");
}
elseif ( $operation == "UPDATE" ) { // Mode mise à jour
	eval("$"."oRes = new ".$classeName."($"."id);");
}
else{ // Mode acces par display
	eval("$"."oRes = new ".$classeName."();");
}
 
$bRetour = dbInsertWithAutoKey($oRes);

if ($bRetour) {
		
	$redirString = "maj_".$classeName.".php?id=".$bRetour."&adodb_next_page=".$_SESSION['pag']; 
	if($listParam!=""){
		$redirString .= "&".$listParam;
	}
	if (preg_match("/id=[1-9]+/msi", $redirString) && preg_match("/id=\-1/msi", $redirString)){ // 2 fois id= , on garde la value > 0
		$redirString = preg_replace("/([?&]{1})id=-1/msi", "$1", $redirString);		
	}	
	?>
	<script language="javascript" type="text/javascript">
		window.location.href="<?php echo $redirString; ?>";
	</script>
	<?php
}