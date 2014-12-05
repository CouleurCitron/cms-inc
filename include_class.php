<?php
if(!$includesOk){

    include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
    // sponthus 26/07/2005
    // include de toutes les class

    // class
    include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/classe.class.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_custom.class.php'); // classe de customisation des classes

    dirExists('/include/bo/class/');

    $aClasse = dbGetObjectsFromFieldValue3('classe', array('get_statut', 'get_iscms'), array('equals', 'equals'), array(DEF_ID_STATUT_LIGNE, 0), NULL, NULL);
    //$aClasse = dbGetObjectsFromFieldValue("classe", array('get_statut', 'get_iscms'),  array(DEF_ID_STATUT_LIGNE, 0), NULL);

    if ((count($aClasse) > 0)&&($aClasse!=false)){
            foreach($aClasse as $cKey => $oClasse){

                    if (preg_match('/^cms_/', $oClasse->get_nom())==0){ // les classe CMS sont déjà chargées

                            $sFileClasse = $_SERVER['DOCUMENT_ROOT'].'/include/bo/class/'.$oClasse->get_nom().'.class.php';
                            if (is_file($sFileClasse)){
                                    include_once($sFileClasse);
                            }
                            else{
                                    echo 'la classe '.$oClasse->get_nom().' n\'est pas accessible :<br />';
                                    echo $sFileClasse.'<br />';
                            }
                    }
            }
    }

    $includesOk = true;
}
?>