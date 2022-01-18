<?php
header('Content-Type: text/html; charset=ISO-8859-1');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 
// sponthus 20/06/2005
// si présence de paramètres dans la définition des menus, 
// les paramètres suivants s'ajoutent avec le caractère "&"

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

if (!isset($translator)){
	$translator =& TslManager::getInstance(); 
}
if (!isset($_SESSION['BO']['cms_texte'])){
	$translator->loadAllTransToSession();
}

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/secure.php'); 
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/check_rights.php'); 
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/selectSite.php');

////////////////////////////////////////////////////////
// SITE DE TRAVAIL
////////////////////////////////////////////////////////

if ($_SERVER['PHP_SELF']=='/backoffice/index.php'){
	
	// idSite envoyé par : 
	// 1- le select des sites (une recherche)
	// 2- l'arbo
	// 3- le site de travail
	// 4- le site connecté
	if (is_post('connectSite')) {
		$_POST['searchSite'] = $_POST['connectSite'];
	}
	if (is_post('searchSite')) {
		$_POST['idSite'] = $_POST['searchSite'];
		//error_log('++++ 1 '.$_POST['idSite']);
	}
	elseif (is_get('idSite')){
		$idSite = $_GET['idSite'];
		$_POST['idSite'] = $idSite;
		//error_log('++++ GET '.$_POST['idSite']);
	}
	elseif (isset($idSite)&&($idSite != "")){
		$_POST['idSite'] = $idSite;
		//error_log('++++ 2 '.$_POST['idSite']);
	}
	elseif ($_SESSION['idSite'] != ""){
		$_POST['idSite'] = $_SESSION['idSite'];
		//error_log('++++ 4 '.$_POST['idSite']);
	}
	elseif ($_SESSION['idSite_travail'] != ""){
		$_POST['idSite'] = $_SESSION['idSite_travail'];
		//error_log('++++ 3 '.$_POST['idSite']);
	}
	
	
	if ($_POST['idSite'] == -1){
		$_POST['idSite'] = 1; // cas site unique mal inité
	}
		
	// idSite envoyé par l'url de l'arbo
	
	if ((!isset($idSite))||($idSite == "")){
		$idSite = $_POST['idSite'];
	}
	
	// site de travail
	$oSite = new Cms_site($_POST['idSite']);
	sitePropsToSession($oSite);
	
	if (is_post('connectSite')) {
		header('Location: /backoffice/');
	}
}
else{
	if(is_post('idSite')){
		$idSite = $_POST['idSite'];		
	}
	elseif(is_get('idSite')){
		$idSite = $_GET['idSite'];		
	}
	else{
		$idSite=$_SESSION['idSite'];
	}
	
	if ($_SESSION['idSite'] != $idSite){ // si change of site
		$oSite = new Cms_site($idSite);
		sitePropsToSession($oSite);
	}
}

////////////////////////////////////////////////////////
	
if (isset($_SESSION['id_langue'])){
	// nada
}
elseif (DEF_APP_LANGUE > 0){
	$_SESSION['id_langue'] = DEF_APP_LANGUE;
}
else{
	$_SESSION['id_langue'] = 1;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="Content-Language" content="fr" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
	<!--[if gt IE 8]><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /><![endif]-->
	<!--[if IE 10]><meta http-equiv="X-UA-Compatible" content="requiresActiveX=true" /><![endif]-->
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<title>Backoffice - <?php echo $_SESSION['site_travail']; ?></title>
	<style type="text/css">
		@import "<?php echo $URL_ROOT?>/backoffice/cms/css/menu.css";
	</style>
	<?php
	//if (preg_match('/backoffice\/cms/si', $_SERVER['PHP_SELF'])==1){
	//}
	if (preg_match('/backoffice\/cms\/site\/pageLiteEditor3\.php/si', $_SERVER['PHP_SELF'])==1){
	?><link href="<?php echo $URL_ROOT;?>/custom/css/fo_<?php echo  strtolower($_SESSION['site_travail']) ; ?>.css" rel="stylesheet" type="text/css" />
	<?php	
	}
	//elseif (preg_match('/backoffice\/cms/si', $_SERVER['PHP_SELF'])==1){
	//} 	
	if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/css/bo_".strtolower($_SESSION['site_travail']).".css")){ ?>
		<link rel="stylesheet" href="<?php echo $URL_ROOT;?>/custom/css/bo_<?php echo strtolower($_SESSION['site_travail']); ?>.css" type="text/css" />
	<?php
	}
	else { ?>
		<link rel="stylesheet" href="<?php echo $URL_ROOT;?>/backoffice/cms/css/bo.css" type="text/css" />
                <link rel="stylesheet" href="<?php echo $URL_ROOT;?>/backoffice/cms/css/bo2013.css" type="text/css" />
                <link rel="stylesheet" href="<?php echo $URL_ROOT;?>/backoffice/cms/img/2013/theme1/style.css" type="text/css" />
	<?php
	}
	?>
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/jquery-1.6.4.min.js" type="text/javascript"></script>
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/modernizr.js" type="text/javascript"></script>
    <!-- sortable list -->
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/jquery-ui.js" type="text/javascript"></script>
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/jquery.mjs.nestedSortable.js" type="text/javascript"></script>
    
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/fojsutils.js" type="text/javascript"></script>
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/fojsutils2013.js" type="text/javascript"></script>
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/utils.js" type="text/javascript"></script>
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/AnimTree.js" type="text/javascript"></script>
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/persistentTree.js" type="text/javascript"></script>
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/validForm.js" type="text/javascript"></script>
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/confirmations.js" type="text/javascript"></script>
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/AC_RunActiveContent.js" type="text/javascript"></script>
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/XHRConnector.js" type="text/javascript"></script>
	<?php
	// fancybox
	if (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/lib/fancybox-1.3.4/jquery.fancybox-1.3.4.pack.js')){
		echo '	<script src="/backoffice/cms/lib/fancybox-1.3.4/jquery.fancybox-1.3.4.pack.js" type="text/javascript"></script>
	<link href="/backoffice/cms/lib/fancybox-1.3.4/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet" type="text/css" /> ';
	}
	elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/lib/fancybox-1.3.1/jquery.fancybox-1.3.1.pack.js')){
		echo '	<script src="/backoffice/cms/lib/fancybox-1.3.1/jquery.fancybox-1.3.1.pack.js" type="text/javascript"></script>
	<link href="/backoffice/cms/lib/fancybox-1.3.1/jquery.fancybox-1.3.1.css" media="screen" rel="stylesheet" type="text/css" /> ';
	}
	elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/lib/fancybox/jquery.fancybox-1.3.1.pack.js')){
		echo '	<script src="/backoffice/cms/lib/fancybox/jquery.fancybox-1.3.1.pack.js" type="text/javascript"></script>
	<link href="/backoffice/cms/lib/fancybox/jquery.fancybox-1.3.1.css" media="screen" rel="stylesheet" type="text/css" /> ';
	}
	else{
		// ben, rien.
	}
	
	//jquery-ui
//	if (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/js/jquery-ui-1.8.17.custom.min.js')){
//		echo '  <script src="/backoffice/cms/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>';
//	}
	//tablesorter
	if (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/js/jquery.tablesorter.js')){
		echo '  <script src="/backoffice/cms/js/jquery.tablesorter.js" type="text/javascript"></script>
		<link href="/backoffice/cms/css/tableorder.css" media="screen" rel="stylesheet" type="text/css" />';
	}
	?>
	<script type="text/javascript">
	var bOpen=true;
	<!--
<?php 
if (!is_get('menuOpen')) { // Si on a de valeur par défaut on essaye de charger celle en mémoire
	if($_SESSION['menuOpen']!="") { // On a justement un état du menu en mémoire
		$_GET['menuOpen'] = $_SESSION['menuOpen'];
	}
}
if (is_get('menuOpen')	&&	strlen($_GET['menuOpen']) > 0)
	$menustate = $_GET['menuOpen'];
else // si rien du tout n'est spécifier on ouvre le menu par défaut
	$menustate='true';
$_SESSION['menuOpen'] = $menustate;// On enregistre l'état du menu







 	// --------------------------------------
	// gestion des themes
 	// -------------------------------------- 
	$theme = $_SESSION['nom_theme'];
	if ($theme == ""){ 
		$theme = "theme1";
	}
	if (!empty($_POST['nom_theme'])) { 
		$theme = $_POST['nom_theme']; 
	}
	$_SESSION['nom_theme'] = $theme;
	// --------------------------------------
	// gestion des themes
 	// --------------------------------------
	
	
 
?>
var sOpenMenu = <?php echo $menustate; ?>;
if(sOpenMenu == false){
  bOpen = sOpenMenu;
}
//-->
</script>
<script type="text/javascript">
        // déconnexion
	function logoff()
	{
                //alert('logoff');
                $("form#accueilForm").attr('action', '<?php echo $_SERVER['REQUEST_URI']; ?>');
                $("form#accueilForm #operation").val("logoff");
				$("form#accueilForm #returnurl").val("<?php echo $_SERVER['REQUEST_URI']; ?>");
                $("form#accueilForm").submit();
	}
</script>
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/menu_bo.js" type="text/javascript"></script>
    <script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/openBrWindow.js" type="text/javascript"></script>
    <script type="text/javascript">    
      TreeParams = {
      OPEN_MULTIPLE_MENUS    : true,
      
      OPEN_WHILE_CLOSING     : true,
      TIME_DELAY             : 30,
      
      OPEN_MENU_ICON         : "<?php echo $URL_ROOT;?>/backoffice/cms/img/open-menu-blue.gif",
      CLOSED_MENU_ICON       : "<?php echo $URL_ROOT;?>/backoffice/cms/img/closed-menu-blue.gif" // don't add a comma after last property!  
  };
</script>    
</head>
<body>
<div id="dek" class="dek" style="position: absolute; visibility: visible; display: none; left: 0px; top: 0px;">
	
</div>
<script type="text/javascript">
Xoffset=-140;
Yoffset= 20; 
</script>
<script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/infobulle.js" type="text/javascript"></script>
<!-- Bloc Header -->
<header>	
	<h1><a href="/backoffice/index.php">Adéquat'<span>website<sup>&reg;</sup></span></a></h1>
	<div class="header_right">
		<p class="user_infos"><a href="http://documentation.adequation.cc/" target="_blank" title="Aide en ligne" onMouseOver="MM_swapImage('bthelp','','/backoffice/cms/img/aide-roll.png',1)" onMouseOut="MM_swapImgRestore()"><img src="/backoffice/cms/img/aide.png" width="22" height="22" border="0" alt="help" id="bthelp" name="bthelp" /></a></p>
		<p class="user_infos"><a class="deconnexion" href="javascript:logoff();" title="Déconnexion"><?php $translator->echoTransByCode('Header_Deconnexion'); ?></a></p>	
		<p class="user_infos"><?php $translator->echoTransByCode('Header_bienvenue'); ?> <span class="nom"><?php echo $_SESSION['user']; ?></span></p>
		<form action="/backoffice/"  name="accueilForm" id="accueilForm" method="post" >
			<input type="hidden" name="operation" id="operation" value="<?php if(is_post('operation')){echo $_POST['operation'];} ?>" />
      <input type="hidden" name="returnurl" id="returnurl" value="<?php if(is_post('returnurl')){echo $_POST['returnurl'];} ?>" />
			Site : 
			<select name="connectSite" onchange ='javascript:this.form.submit();' >
				<?php $listSite = listSite('ALL');  ?>
                            <?php foreach($listSite as $site){ ?>
                            <option value="<?php echo $site->id; ?>"<?php if($_SESSION['idSite_travail'] == $site->id) echo " SELECTED"; ?>><?php echo $site->name; ?></option>
                            <?php } ?>
                            
			</select>
                        
                        <?php //pre_dump($_SESSION); ?>
		</form>
	</div>	
</header>
<!-- FIN Bloc Header -->

<section id="center">
  <?php 
  
	include_once('bo/menu.inc.php');

	function findValidMenuItem($item, $menuStruct){
		
		//echo 'findValidMenuItem('.$item.' in '.join(',',$menuStruct).')<br />';
		if (is_array($menuStruct)){
			foreach ($menuStruct as $k => $v){			
				if (isset($v[$item])&&is_array($v[$item])&&is_string($v[$item]['content'])&&preg_match('/backoffice/', $v[$item]['content'])==1){
					$item = $menuStruct['id'];
					return 	$item;
				
				}
				else{
					$item = findValidMenuItem($item, $v);
				}		
			}	
		}
		return $item;
	}
 
	function activateMenu($item) {
		if (strlen($item)) {
		  global $menuStruct;
		
      $item = findValidMenuItem($item, $menuStruct);		

      echo '<script type="text/javascript">'."\n";
      echo '<!--'."\n";
      echo 'refresh_menu("'.$_SERVER["REQUEST_URI"].'");'."\n";
      echo '//-->'."\n";
      echo '</script>'."\n";
		}
	}

	function generateMenu($menu_items, $depth=1	) {
		$menuStr = "";
                //pre_dump($menu_items);
		foreach ($menu_items as $k => $v) {
			$menuStr .= "
			<li class=\"niv".$depth." ".noAccent($v['label'])."\" id=\"".$k."\">
				<a href=\"javascript:void(0);\">";
					if($depth == 1) $menuStr .= "<img src=\"/backoffice/cms/img/2013/theme1/img/icone/".noAccent($v['label']).".png\" border=\"0\" alt=\"".$v['label']."\" />";
					$menuStr .= "<span>".$v['label']."</span>
				</a>
			
			<ul class=\"ss_menu\" id=\"".$k."Menu\">\n";
				if(isset($v['content'])	&&	($v['content'] != NULL) && (is_array($v['content']))){
					foreach($v['content'] as $key => $val) {
						if(is_array($val['content'])) {
							$menuStr .= generateMenu(array($key => $val), ($depth+1));
						} elseif($val['content'] != NULL) {
						
						// recherche du caractère ?
						// si des paramètres ont étés passés
						// -> passage des aramètres suivants avec le caractère &
						if ( strstr($val['content'], "?") ) $sCarParam = "&";
						else $sCarParam = "?";
						
						if (!is_get('menuOpen')){
							$_GET['menuOpen'] = false;					
						}
						$menuStr .= "
						<li class=\"niv".($depth+1)."\">
							<a href=\"".$val['content']."\">".$val['label']."</a>
						</li>";
					}
				}
			}
			$menuStr.="</ul></li>\n";

		}
		return $menuStr;
	}
?>
	<div id="col_left">	
		<a href="#" class="affichage_menu"><img src="/backoffice/cms/img/2013/picto/fleche_noire2.png" alt="cacher"/></a>
		<p class="site_travail"><span><?php $translator->echoTransByCode('sitedetravail'); ?>&nbsp;:	<?php  echo $_SESSION['site']; ?></span></p>
		<ul id="menu" class="open_menu">

  		
		<?php 
			echo generateMenu($menuStruct['main']['content'], 1);
		?>
		</ul>       
	</div>



  <script>
      $(window).load(function(){
          if( window.self === window.top ) { 
            } else { 
                $('header').css('display', 'none');
                $('#col_left').css('display', 'none');
                $('#col_right').css('width', '100%');
                $('footer').css('display', 'none');

                $('section').css('height', $(window).height());
              }
      });
  </script>
  <?php
  if(isset($_SESSION['BO']['menu']) && $_SESSION['BO']['menu'] == 'close'){
      ?>
        <script>
            $(window).load(function(){
                $("a.affichage_menu").trigger('click');
            });
        </script>
  <?php
  }
  ?>
  
  
	<div id="col_right">