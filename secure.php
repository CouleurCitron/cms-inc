<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*
	$Id: secure.php,v 1.82 2014-09-11 09:20:43 pierre Exp $
	$Author: pierre $

	$Log: secure.php,v $
	Revision 1.82  2014-09-11 09:20:43  pierre
	*** empty log message ***

	Revision 1.81  2014-05-16 13:12:47  pierre
	*** empty log message ***

	Revision 1.80  2014-04-08 13:17:36  pierre
	*** empty log message ***

	Revision 1.79  2014-02-19 14:39:13  pierre
	*** empty log message ***

	Revision 1.78  2014-02-18 14:06:20  pierre
	*** empty log message ***

	Revision 1.77  2013-09-24 09:20:33  pierre
	*** empty log message ***

	Revision 1.76  2013-09-24 08:24:37  pierre
	*** empty log message ***

	Revision 1.75  2013-09-05 11:46:23  pierre
	*** empty log message ***

	Revision 1.74  2013-08-22 08:56:14  raphael
	*** empty log message ***

	Revision 1.73  2013-07-09 16:05:30  pierre
	*** empty log message ***

	Revision 1.72  2013-06-11 07:41:57  pierre
	*** empty log message ***

	Revision 1.71  2013-05-14 12:22:40  thao
	*** empty log message ***

	Revision 1.70  2013-03-22 13:41:51  raphael
	*** empty log message ***

	Revision 1.69  2013-03-01 10:33:59  pierre
	*** empty log message ***

	Revision 1.68  2013-01-14 16:45:59  pierre
	*** empty log message ***

	Revision 1.67  2012-12-13 16:03:26  pierre
	*** empty log message ***

	Revision 1.66  2012-10-01 09:59:42  pierre
	*** empty log message ***

	Revision 1.65  2012-09-12 16:16:37  pierre
	*** empty log message ***

	Revision 1.64  2012-07-31 14:24:46  pierre
	*** empty log message ***

	Revision 1.63  2012-06-27 09:08:54  pierre
	*** empty log message ***

	Revision 1.62  2012-06-13 14:40:50  pierre
	*** empty log message ***

	Revision 1.61  2012-05-07 08:24:04  pierre
	*** empty log message ***

	Revision 1.60  2012-04-05 12:50:53  thao
	pbl langue sur BO (traduction en session)

	Revision 1.59  2011-11-21 13:27:19  thao
	ajout d'une condition pour tester l'existence de curl_init()

	Revision 1.58  2011-11-03 11:15:06  pierre
	*** empty log message ***

	Revision 1.57  2011-11-03 09:35:51  pierre
	*** empty log message ***

	Revision 1.56  2011-07-25 09:42:27  thao
	ajout ip sophie

	Revision 1.55  2011-07-07 15:57:37  pierre
	*** empty log message ***

	Revision 1.54  2011-07-01 13:19:50  pierre
	*** empty log message ***

	Revision 1.53  2011-06-30 13:22:54  pierre
	*** empty log message ***

	Revision 1.52  2011-06-30 12:23:02  quentin
	Ajout nouvelle charte BO

	Revision 1.51  2011-06-29 16:10:34  pierre
	*** empty log message ***

	Revision 1.50  2011-06-29 13:21:33  pierre
	*** empty log message ***

	Revision 1.49  2011-06-16 08:40:20  pierre
	*** empty log message ***

	Revision 1.48  2011-06-07 07:28:54  pierre
	*** empty log message ***

	Revision 1.47  2011-05-31 08:44:58  pierre
	*** empty log message ***

	Revision 1.46  2011-05-30 10:38:54  pierre
	*** empty log message ***

	Revision 1.45  2010-12-15 10:33:40  pierre
	optimisation usage des sessions idSite en BO

	Revision 1.44  2010-10-04 10:11:34  pierre
	*** empty log message ***

	Revision 1.43  2010-05-20 12:54:40  pierre
	*** empty log message ***

	Revision 1.42  2010-03-08 12:15:26  pierre
	*** empty log message ***

	Revision 1.41  2009-10-16 13:39:07  pierre
	*** empty log message ***

	Revision 1.40  2009-09-24 08:52:32  pierre
	*** empty log message ***

	Revision 1.39  2009-06-08 12:52:40  pierre
	*** empty log message ***

	Revision 1.38  2009-06-08 09:41:06  pierre
	*** empty log message ***

	Revision 1.37  2009-05-12 14:57:48  pierre
	*** empty log message ***

	Revision 1.36  2009-04-10 09:15:05  pierre
	*** empty log message ***

	Revision 1.35  2009-03-03 09:13:19  thao
	*** empty log message ***

	Revision 1.34  2009-03-02 14:07:27  thao
	*** empty log message ***

	Revision 1.33  2008-12-05 15:18:25  pierre
	*** empty log message ***

	Revision 1.32  2008-12-01 10:53:23  thao
	*** empty log message ***

	Revision 1.31  2008-11-28 15:14:40  pierre
	*** empty log message ***

	Revision 1.30  2008-11-27 19:37:30  pierre
	*** empty log message ***

	Revision 1.29  2008-10-21 09:20:46  pierre
	*** empty log message ***

	Revision 1.27  2008/05/14 09:06:43  thao
	*** empty log message ***
	
	Revision 1.26  2008/04/16 14:07:57  pierre
	*** empty log message ***
	
	Revision 1.25  2008/04/16 09:42:33  pierre
	*** empty log message ***
	
	Revision 1.24  2008/04/15 14:25:37  pierre
	*** empty log message ***
	
	Revision 1.23  2008/03/10 09:32:23  pierre
	*** empty log message ***
	
	Revision 1.22  2008/02/28 17:31:42  pierre
	*** empty log message ***
	
	Revision 1.21  2008/01/22 17:15:06  pierre
	*** empty log message ***
	
	Revision 1.20  2007/11/20 09:43:35  thao
	*** empty log message ***
	
	Revision 1.19  2007/11/20 09:16:04  thao
	*** empty log message ***
	
	Revision 1.18  2007/11/20 08:41:24  thao
	*** empty log message ***
	
	Revision 1.17  2007/11/16 08:57:58  remy
	*** empty log message ***
	
	Revision 1.16  2007/11/15 15:10:36  remy
	*** empty log message ***
	
	Revision 1.15  2007/11/15 14:51:32  remy
	*** empty log message ***
	
	Revision 1.14  2007/11/06 17:25:56  remy
	*** empty log message ***
	
	Revision 1.13  2007/10/11 16:32:57  thao
	*** empty log message ***
	
	Revision 1.12  2007/09/10 10:23:51  pierre
	*** empty log message ***
	
	Revision 1.11  2007/09/03 09:56:04  thao
	*** empty log message ***
	
	Revision 1.10  2007/09/03 09:55:40  thao
	*** empty log message ***
	
	Revision 1.9  2007/08/29 07:51:17  pierre
	*** empty log message ***
	
	Revision 1.8  2007/08/28 15:53:32  pierre
	*** empty log message ***
	
	Revision 1.7  2007/08/28 10:01:22  pierre
	*** empty log message ***
	
	Revision 1.6  2007/08/27 09:59:17  pierre
	*** empty log message ***
	
	Revision 1.5  2007/08/24 15:12:21  pierre
	*** empty log message ***
	
	Revision 1.4  2007/08/08 14:14:23  thao
	*** empty log message ***
	
	Revision 1.3  2007/08/08 13:53:33  thao
	*** empty log message ***
	
	Revision 1.2  2007/08/08 13:43:53  thao
	*** empty log message ***
	
	Revision 1.1  2007/08/08 13:42:54  thao
	*** empty log message ***
	
	Revision 1.6  2007/08/08 13:25:29  thao
	*** empty log message ***
	
	Revision 1.5  2007/07/03 10:52:34  thao
	*** empty log message ***
	
	Revision 1.4  2007/03/02 09:11:31  pierre
	*** empty log message ***
	
	Revision 1.3  2007/02/28 16:38:31  pierre
	*** empty log message ***
	
	Revision 1.2  2006/12/19 13:56:00  pierre
	*** empty log message ***
	
	Revision 1.1.1.1  2006/01/25 15:14:27  pierre
	projet CCitron AWS 2006 Nouveau Website
	
	Revision 1.3  2005/12/19 13:16:36  sylvie
	*** empty log message ***
	
	Revision 1.2  2005/11/02 10:44:51  sylvie
	*** empty log message ***
	
	Revision 1.1.1.1  2005/10/20 13:10:54  pierre
	Espace V2
	
	Revision 1.1.1.1  2005/04/18 13:53:29  pierre
	again
	
	Revision 1.1.1.1  2005/04/18 09:04:21  pierre
	oremip new
	
	Revision 1.2  2004/11/09 09:29:24  ddinside
	correction menu
	
	Revision 1.1.1.1  2004/09/29 15:38:21  ddinside
	import initial Boite a Pizza
	
	Revision 1.2  2004/06/16 15:23:19  ddinside
	inclusion corrections
	
	Revision 1.1  2004/06/01 14:25:19  ddinside
	ajout petites annonce dont gardes finies
	newslettrer
*/

// déconnexion :: vidage session
if (is_post('operation')){
	if ($_POST['operation'] == "logoff") {
		include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/session_end.php'); 
	}
}

$user = new User();

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
					curl_setopt($ch, CURLOPT_URL, 'http://couleurcitron.com/allow.php');
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
					$aIPs = array('37.1.253.222', '82.124.17.60', '82.228.89.184', '82.228.167.148', '81.249.110.2', '82.234.79.170', '88.124.114.41', '82.238.143.116');
					

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
		
		$user = new User();
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
	document.location = '<?php echo $_SESSION['BO']['URL']; ?>';
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
		$_SESSION['BO'] = array();
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