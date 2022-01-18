<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/arbopage.lib.php');

/**
 * Traitement de l'erreur 404 multi-domaine
 * on redirige automatiquement vers l'url du site incriminé sur le fichier 404.php à la racine.
 */

//pre_dump("/content/".$_SESSION["site"]."/404.php");
//die();

header("Location:/content/".$_SESSION["site"]."/404.php");

?>