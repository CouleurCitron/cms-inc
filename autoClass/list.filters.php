<script type="text/javascript">

function rechercher() {
	document.<?php echo $classePrefixe; ?>_rech_form.action = "<?php echo $_SERVER['PATH_INFO']; ?>";
	document.<?php echo $classePrefixe; ?>_rech_form.operation.value = ""; 
	document.<?php echo $classePrefixe; ?>_rech_form.submit();
}
function filterChange() {
<?php
	unset($_SESSION["adodb_curr_page"]);
	if (!ereg("backoffice", $_SERVER['PHP_SELF'])){
?>
	document.<?php echo $classePrefixe; ?>_rech_form.action = "<?php echo $_SERVER['SCRIPT_URI']; ?>";
	document.<?php echo $classePrefixe; ?>_rech_form.submit();
<?php
	}
?>
}
function reinit() { 	 
         document.location.href="list_<?php echo $classeName; ?>.php"; 	 
         document.<?php echo $classePrefixe; ?>_rech_form.sTexte.value = ""; 	 
         document.<?php echo $classePrefixe; ?>_rech_form.operation.value = "REINIT"; 	 
         document.<?php echo $classePrefixe; ?>_rech_form.submit(); 	
}

</script>

<form name="<?php echo $classePrefixe; ?>_rech_form" method="post">

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
<style>
#filters.arbo, .pagination{
float:left;
}
.search_gauche{
	/*float:right;
        margin: 0 auto;
	/*width:404px;
	margin:0 40px 0 20px;*/
        width: 80%;
        margin: 0 auto;
        padding-bottom: 10px;
        clear: both;
        position: relative;
}
.search_droite {
    border-top: 1px dotted #A0AA44;
    /*float: left;
    margin: 0 30px 0 100px;
    width: 374px;
    min-height:133px;
    margin: 0 auto;*/
    width: 80%;
    margin: 0 auto;
    padding: 10px 0;
    clear: both;
}
h2.titleSearch{
    text-indent: -10px;
    color: white;
    margin-top: 0;
    margin-bottom: 0;
}
.blocItem{
    width:50%;
    float: left;
    padding: 3px 0;
}
.blocItem.dateblocItem:first-child {
	clear: left;
}
.blocItem.dateblocItem > div {
	float: left;
	margin-right: 10px;
	width: 150px;
}
.blocItem.dateblocItem input {
	width: 110px;
}
.blocItem.dateblocItem img {
	vertical-align: middle;
}

.new_search select{
	width:300px;
	margin:2px 0 3px 0;
	border:1px solid #A0AA44;
}
.new_search input{
	width:300px;
	margin:2px 0 3px 0;
	border:1px solid #A0AA44;
}
#keywordFilter .bloc_bt_search{
    /*text-align:right;
    margin:0 94px 0 0;*/
    clear:both;
    text-align: center;
    position:absolute;
    bottom: 3px;
    right: -150px;
}
.bloc_bt_search{
    /*text-align:right;
    margin:0 94px 0 0;*/
    clear:both;
    text-align: center;
}
.new_search input.bt_search{
    margin:0 10px 0 0;
    border:0;
    padding:2px 15px;
    cursor:pointer;
    max-width: 100px;
    width: 100px;
}
input#searchId{
    width: 58%;
}
#idFilter{
    float: right;
    width: 27%;
}


</style>

<script>
$(document).ready(function(){
    function displayVals() {
	     var singleValues = $.trim($(".search_droite").text());
	     if (singleValues == ""){
	    	 //$(".search_gauche").css('margin-right','260px');
	    	 $(".search_droite").css('display','none');
	     }
 
    }
 
    displayVals();
});
 
</script>

<?php
 
eval("$"."oRes = new ".$classeName."();");
// rech par kword only si champs text dans la table
$numChamptext = 0;
$laListeChamps = $oRes->getListeChamps();

foreach($laListeChamps as  $field => $odbChamp){
	//pre_dump($odbChamp);

	if (preg_match("/text.*/i", $odbChamp->getTypeBD()) || preg_match("/node/i", $odbChamp->getGetter())  ){
		$numChamptext++;
	}
}

$numChamptext = 1;

?>
	<div id="filters" class="arbo">
		<div class='search_gauche new_search'>
                        <div style="clear:both">&nbsp;</div>
                            <h2 class="titleSearch"><?php echo $translator->getTransByCode('Recherche_par'); ?></h2>
<?php
if ($numChamptext != 0) {
	// ------------------------------------------------------
	// recherche by id --------------------------------------
	// recherche by keyword ---------------------------------
	// ------------------------------------------------------
	if (preg_match("/backoffice/si", $_SERVER['PHP_SELF'])==1) {
		?>
		<div id="keywordFilter" class="keywordFilter blocItem">		
		  <div align="left" id="keywordFilterLabel" class="keywordFilterlabel"><?php $translator->echoTransByCode('Recherche_par_mots_cles'); ?></div>
		  <div align="left" id="keywordFilterField" class="keywordFilterField" style="position:relative;">
		  <input type="text" name="sTexte" id="sTexte" value="<?php echo $_SESSION['sTexte']; ?>" class="arbo" size="40"/><?php include("list.filters.bouton.php");   ?></div>
		</div>
		<?php                 
        include_once ("list.filters.id.php");                 
	 }
	 
	// en BO
	if (preg_match("/backoffice/si", $_SERVER['PHP_SELF'])==1){ 
		?>
                            <div style="clear:both">&nbsp;</div>
			</div>
			<div class="search_droite new_search">
                            <div style="clear:both">&nbsp;</div>
	   <?php
	   	// ------------------------------------------------------
		// recherche by statut ----------------------------------  
		// ------------------------------------------------------ 
	   include_once ("list.filters.statut.php");

	   include_once ("list.filters.date.php");
		
	} //en BO
	else{ //en FO
		echo "<input type=\"hidden\" name=\"eStatut\" id=\"eStatut\" value=\"".DEF_ID_STATUT_LIGNE."\">\n";	
	}	
	
	// ------------------------------------------------------
	// recherche by enum ----------------------------------  
	// ------------------------------------------------------ 
	include('list.filters.enum.php');
	
	// ------------------------------------------------------
	// recherche by bool ----------------------------------  
	// ------------------------------------------------------ 
	include('list.filters.bool.php');	
	
	// ------------------------------------------------------
	// recherche by fkey ----------------------------------  
	// ------------------------------------------------------ 
	include('list.filters.fkey.php');
		
	// ------------------------------------------------------
	// recherche by asso ----------------------------------  
	// ------------------------------------------------------ 
	include('list.filters.asso.php');	
		

} // end if($numChamptext != 0){
?> 
                            <div style="clear:both">&nbsp;</div>
                            <?php include ("list.filters.bouton.php");   ?>
	</div><!-- <div class='search_gauche new_search'> -->
	</div><!-- <div id="filters" class="arbo"> -->
</form>