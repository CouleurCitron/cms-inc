<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/arbopage.lib.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/pages.lib.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_site.class.php');

if (isset($_GET['page']) && (intval($_GET['page']) != 0)){
	$idPage = $_GET['page'];
	$aPage = getPageById($idPage);
	$idNode = $aPage["node_id"];
	$aNode = getNodeInfos($db, $idNode);
	if ($aNode["id"] != "0"){
		$redirect = "/content".$aNode["path"].$aPage["name"].".php?".$_SERVER["QUERY_STRING"];
	}
	else{
		$oSite = new Cms_site($aNode["id_site"]);		
		$redirect = "/content/".$oSite->get_rep().$aNode["path"].$aPage["name"].".php?".$_SERVER["QUERY_STRING"];
	}
}
else{
	$redirect = "/";
}
header("Location: ".$redirect);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="refresh" content="0; url=<?php echo $redirect; ?>" />
<title>REDIRECTION</title>
<script language="javascript" type="text/javascript">
document.location.href = "<?php echo $redirect; ?>";
</script>
</head>
<body>
</body>
</html>