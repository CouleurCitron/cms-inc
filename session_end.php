<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

//var_dump($_SESSION);

unset($_SESSION);
session_destroy();
/*
session_unregister('idSite_travail');

// gestion des contenus
session_unregister('idStatut');
session_unregister('rech_bChercherOpen');
session_unregister('rech_fZone');
session_unregister('rech_selectGabarit');
session_unregister('rech_page_id');
session_unregister('rech_node_id');

session_unregister('classeName');
session_unregister('sqlpag');
session_unregister('adodb_curr_page');
session_unregister('sTexte');
session_unregister('eStatut');
session_unregister('listParam');
*/
?>
