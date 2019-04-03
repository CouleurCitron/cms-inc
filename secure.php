<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

if (strpos($_SERVER['REQUEST_URI'], 'secure.php')!==false){
	// hack attempt
	error_log('hack attempt on '.$_SERVER['PHP_SELF'].' from '.$_SERVER['REMOTE_ADDR']);
	die();	
}

if (!isset($translator)){
	$translator =& TslManager::getInstance(); 
}
if (!isset($_SESSION['BO']['cms_texte'])){
	$translator->loadAllTransToSession();
}

// déconnexion :: vidage session
if (is_post('operation')){
	if ($_POST['operation'] == "logoff") {
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/session_end.php'); 
		if (preg_match('/^\/.+$/msi', $_POST['returnurl'])){
			$_SESSION['BO']=array();
			$_SESSION['BO']['returnurl']=$_POST['returnurl'];	
		}
	}
}

$user = new bo_users();

// utilisateurs par défaut
$aUserLogin = explode(";", DEF_USERLOGIN);
$aUserNom = explode(";", DEF_USERNOM);
$aUserPrenom = explode(";", DEF_USERPRENOM);
$aUserMail = explode(";", DEF_USERMAIL);
$aUserTel = explode(";", DEF_USERTEL);
$aUserPasswd = explode(";", DEF_USERPASSWD);

for ($i=0; $i<sizeof($aUserLogin); $i++)
{
	$user->nom = $aUserNom[$i];
	$user->prenom = $aUserPrenom[$i];
	$user->mail = $aUserMail[$i];
	$user->login = $aUserLogin[$i];
	$user->telephone = $aUserTel[$i];
	$user->mdpCrypte = md5($aUserPasswd[$i]);
	// les utilisateurs par défaut sont créés avec :
	// - comme validés
	$user->valide = 1;
	// - le rang ADMIN
	$user->rank = DEF_ADMIN;
	// - pour le premier site par défaut
	$user->id_site = 1;

	$eUser = getCount("bo_users", "user_id", "user_login", $user->login, $sTypeWhere="TEXT");
	if ($eUser == 0){	
		$user->setcms_id(1);
		$user->setValidauto(1) ;
		$user->setPrefsite(1);
		$user->setGroupe(1);
		$user->add();
	}
}

$aListeSite = listSite("ALL");

if(is_array($_SESSION['BO']) && isset($_SESSION['BO']['LOGGED'])) {
	// on ne fait rien 
} else {
	$erreur = 0;
	if(is_post('login')) {		
		// controles spéciaux pour le compte ccitron // http://couleurcitron.com/allow.php
		if (trim($_POST['login'])=='ccitron'){
			
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])	&&	($_SERVER["HTTP_X_FORWARDED_FOR"] != $_SERVER['REMOTE_ADDR'])	){
				$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_X_FORWARDED_FOR"];
			}
			
			if (preg_match('/127\.0\.0\.1/', $_SERVER['REMOTE_ADDR'])==1){
				//ok localhost
			}
            else if (preg_match('/192\.168\.0\.[0-9]{1,3}/', $_SERVER['REMOTE_ADDR'])==1){
				//ok local
			}			
			elseif (preg_match('/128\.1\.145\.[0-9]{1,3}/', $_SERVER['REMOTE_ADDR'])==1){
				//ok local thales
			}
			elseif (preg_match('/10\.70\.25\.[0-9]{1,3}/', $_SERVER['REMOTE_ADDR'])==1){
				//ok local thales tls
			}
			elseif (preg_match('/10\.69\.[0-9]{1,3}\.[0-9]{1,3}/', $_SERVER['REMOTE_ADDR'])==1){
				//ok local thales tls
			}
			else{				
				if(defined('DEF_IP_ADMIN')){
					$aIPs = explode(',', str_replace(' ', '', DEF_IP_ADMIN));
					if (in_array($_SERVER['REMOTE_ADDR'], $aIPs)){ // test IP
						$contents = 'ok';
					}
				}				
					
				if(!isset($contents)	&&	function_exists(curl_init)){				
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, 'https://couleurcitron.com/allow.php');
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('IP'=>$_SERVER['REMOTE_ADDR'])));		
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); 
					curl_setopt($ch, CURLOPT_TIMEOUT, 20); //timeout in seconds	   
					$contents = curl_exec($ch);		
					curl_close($ch);
				}
				
				if ((!$contents) || ($contents=='incorrect usage') || ($contents=='forbidden')){ // pas de réponse, on teste localement
					$aNames = array('suhali.dyndns.org'); 
					$aIPs = array('37.1.253.222', '37.1.253.217', '82.124.17.60', '82.228.89.184', '82.228.167.148', '81.249.110.2', '82.234.79.170', '88.124.114.41', '82.238.143.116', '92.245.150.148', '37.1.253.217');
					

					if (in_array($_SERVER['REMOTE_ADDR'], $aIPs)){ // test IP
						$contents = 'ok';
					}
					elseif (in_array(gethostbyaddr($_SERVER['REMOTE_ADDR']), $aNames)){ // test name
						$contents = 'ok';
					}
					else{
						$_POST['password']='';
						$_POST['login']='';
						$contents='';
					}
				}
				
				if (preg_match('/ok/', $contents)==1){
					// ok
				}
				else{// pas ok, on boule
					$_POST['password']='';
					$_POST['login']='';
				}
			}			
		}
		
		$user = new bo_users();
		$user->authentificate($_POST['login'], $_POST['password']);
		if($user->id > 0) {
			$user = new bo_users($user->id);
			$idSite = $user->get_cms_site();
			$oSite = new Cms_site($idSite);			
		
			$oRank = new Bo_rank($user->rank);
			$_SESSION['rank'] = $oRank->get_libelle();
			$_SESSION['user'] = $user->nom;
			$_SESSION['userid'] = $user->get_id();
			$_SESSION['login'] = $user->get_login();
			$_SESSION['user'] = $user->nom;
			$_SESSION['groupe'] = $user->bo_groupes;
			$oGroupe = new Bo_groupes($user->bo_groupes);
			$_SESSION['groupeordre'] = $oGroupe->get_ordre();
			
			// user authentique, on passe
			$_SESSION['BO']['LOGGED'] = serialize($user);
			
			sitePropsToSession($oSite);
			
			if ($user->get_prefsite() == -1){
				$_SESSION['BO']['id_langue'] = DEF_APP_LANGUE;
			}
			else{
				$_SESSION['BO']['id_langue'] = $user->get_prefsite();
				$oLangue = new Cms_langue($user->get_prefsite());	
				$_SESSION['BO']['site_langue']=strtolower($oLangue->get_libellecourt());	
			}	
			
			unset($translator);
			$translator =& TslManager::getInstance(); 
			$translator->downloadLangPacks(false);

			unset($_SESSION['BO']['cms_texte']);
			$translator->loadAllTransToSession($_SESSION['BO']['id_langue']);
			
			// habilitation OK -> redirection
			if ($erreur	== 0) {
			
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<script type="text/javascript">
	document.location = '<?php echo $_SERVER['REQUEST_URI']; ?>';
</script>
</body>
</html>
<?php
			}
			$_SESSION['BO']['URL'] = '';
			$_SESSION['BO']['QUERY'] = '';
			if ($erreur	== 0) exit;
		} else {
			// l'identification a échoué
			$erreur++;
		} // fin if($user->id > 0) {
	} else {
		// login non saisi				
		//$_SESSION['BO'] = array();
		$_SESSION['BO']['URL'] = $_SERVER['REQUEST_URI']; // c be
		$_SESSION['BO']['QUERY'] = $_SERVER['QUERY_STRING'];
	} // fin if(strlen($_POST['login'])>0) {
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title>Identification backoffice</title>
<script src="/backoffice/cms/js/fojsutils.js" type="text/javascript"></script>
<script src="/backoffice/cms/js/validForm.js" type="text/javascript"></script>
<script src="/backoffice/cms/js/cufon-yui.js" type="text/javascript"></script>
<script src="/backoffice/cms/js/Helvetica_Neue.js" type="text/javascript"></script>
<link rel="stylesheet" href="/backoffice/cms/css/login-bo.css" type="text/css" />
<script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/jquery-1.6.4.min.js" type="text/javascript"></script>
<!-- appel d'une autre feuille de style pour l'accueil-->
<?php
 	if (getCount_where("cms_site", array("cms_url"), array($_SERVER["HTTP_HOST"]), array("TEXT"))) {	
		$sql = "select * from cms_site where cms_url ='".$_SERVER["HTTP_HOST"]."' order by cms_id";
		$aSite = dbGetObjectsFromRequete("cms_site", $sql);
		$oSite = $aSite[0];  
		$_SESSION['idSite'] = $oSite->get_id();
		if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/css/home_".strtolower($oSite->get_name()).".css")){ ?>
			<link rel="stylesheet" href="http://<?php echo $_SERVER["HTTP_HOST"];?>/custom/css/home_<?php echo strtolower($oSite->get_name()); ?>.css" type="text/css"></link>
		<?php
		}
		if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/css/bo_".strtolower($oSite->get_name()).".css")){ ?>
			<link rel="stylesheet" href="http://<?php echo $_SERVER["HTTP_HOST"];?>/custom/css/bo_<?php echo $oSite->get_name(); ?>.css" type="text/css"></link>
			<?php
		}
	}
	
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
?>
<script type="text/javascript">
window.onload = function(){
	if (document.getElementById("outer") != undefined){
		window.document.frm_log.login.focus();
	}
}

//Partie Cuffon pour police
Cufon.replace('#header h1', {fontFamily: 'Helvetica Neue LT Std'});
Cufon.replace('#center h2', {fontFamily: 'Helvetica Neue LT Std'});
Cufon.replace('#center p.valider a', {fontFamily: 'Helvetica Neue LT Std'});

$(document).ready(function() {
	$("a#forget_password").bind("click", function() {
		$.fancybox({
			'href' : '/include/cms-inc/bo_password.php',
			'type' : 'iframe', 
			'height' : 250,
			'width' : 400,
			'hideOnOverlayClick': true,
			'showCloseButton'	: true,
			'titleShow'	: false 
		});
		
	});	
});

</script>
</head>
<body>
<div id="header">
	<h1>Adéquat'<span>website<sup>&reg;</sup></span></h1>
</div>
<div id="logo">
	<a href="http://www.couleur-citron.com" target="_blank" title="Couleur Citron"><img src="/backoffice/cms/img/logo_cc_bo.png" alt="Couleur Citron" border="0" /></a>
</div>
<div id="center">
	<form name="frm_log" id="frm_log" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<h2><?php $translator->echoTransByCode('bienvenuedansvotremodule'); ?></h2>
		<p><label><?php $translator->echoTransByCode('Identifiant'); ?></label><input name="login" type="text" class="champ" id="login" value="<?php if(is_post('login')){ echo $_POST['login']; } ?>" size="40" pattern="^.+$" errorMsg="<?php $translator->echoTransByCode('Vous_devez_saisir_votre_identifiant_login'); ?>" /></p>
		<p><label><?php $translator->echoTransByCode('Mot_de_passe'); ?></label><input name="password" type="password" class="champ" id="password" value="<?php if(is_post('password')){ echo $_POST['password']; } ?>" size="40" pattern="^.+$" errorMsg="<?php $translator->echoTransByCode('Vous_devez_saisir_votre_mot_de_passe.'); ?>" /></p>
		<p class="forget"><a id="forget_password" href="#"><?php $translator->echoTransByCode('Mot_de_passe_oublie'); ?></a></p>
		<p class="valider"><a href="javascript:submitValidForm(0);"><?php $translator->echoTransByCode('Valider'); ?></a></p>
		<?php
		if ($erreur>0) {
		?>
		<p class="erreur"><?php $translator->echoTransByCode('identifiantoumotdepasseerrone'); ?><br /><?php $translator->echoTransByCode('mercidereessayer'); ?><br /><?php echo $sMessageAuth; ?></p>
		<?php
		}
		?>
	</form>
</div>
<div id="footer">&nbsp;</div>
</body>
</html>
<?php
	exit;
}
?>