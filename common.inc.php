<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/arbopage.lib.php');

$oSite = detectSite();
$idSite = $oSite->get_id();
$rep = $oSite->get_rep(); 
sitePropsToSession($oSite);
?>