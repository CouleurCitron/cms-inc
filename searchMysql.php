<?php

//class de recherche en BDD en cas de limitation par swish.



include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

class searchMysql
{
    
    //tableau de résultat
    var $aResult = array();
    //Les noms des champs titre et url modifiables
    var $titre_ligne = "TITRE";
    var $url_ligne = "URL";
    var $err = FALSE;
    
    //var $translator =& TslManager::getInstance();
    
    /* 
     * premier cas, on recherche les résultats qui sont tous liés à une page
     * Dans ce cas, on ne donne pas de paramètre mis à part l'ID site le mot clé et un tableau d'ignore de class
     */
    function pageSearch($idSite, $keyword = ''){
        
        $oPage = new Page();

        
        //activation des traductions
        $translator =& TslManager::getInstance();
        //$translator->getByID();
        
        if($keyword == ''){
            die('pas de mot cl&eacute; renseign&eacute;.');
        }
        
        
        //$keyword = "&#1080;";
        
        if(!is_int($idSite) || $idSite == 0){
            $this->err = TRUE;
        }
        
        $aData = array();
        $arry_int = array();
        
        $sSql = "SELECT * FROM cms_page pages, cms_arbo_pages arbo, cms_infos_pages info WHERE arbo.node_id_site = '".$idSite."' AND pages.nodeid_page = arbo.node_id AND info.page_id = pages.id_page";
        $result = mysql_query($sSql);
        while($data = mysql_fetch_object($result)){
            $aData[] = $data;
        }
//        pre_dump($sSql);
//        pre_dump($aData); die();
        //pour chaque page on récupère tous les contenus
        foreach($aData as $oData){
            //on demande l'ensemble des objets de la page
            //pre_dump($oData);
            $searchFind = false; //on initialise notre variable. Elle sera à true si on a déjà trouvé le mot clé à notre page.
            
            //on vérifie les META
            if(preg_match('/'.strtolower($keyword).'/', strtolower($oData->page_titre)) || preg_match('/'.strtolower($keyword).'/', strtolower($oData->page_motsclefs)) || preg_match('/'.strtolower($keyword).'/', strtolower($oData->page_description))){
                $searchFind = true;

                //on ajoute la page au résultat de recherche
                $arry_int[$oData->id_page][$this->url_ligne] = 'http://'.$_SERVER['HTTP_HOST']."/content".$oData->node_absolute_path_name;
                $arry_int[$oData->id_page][$this->titre_ligne] = $oData->node_libelle;
            }
            
            
           $objects = $oPage->getObjectsForCurrentPage(null,  $oData->id_page);
            
            foreach($objects as $aDatas){
                
                
                if(!$searchFind){
                //si on a déjà trouvé du contenu pour cette page, on passe à la page suivante
                    foreach($aDatas as $oContent){
                        $aDataContent = get_object_vars($oContent);

                        //on va vérifier chaque contenu de la page
                        foreach($aDataContent as $k => $sSearch){
                            //on ne fait pas de recherche sur les id
                            if(!preg_match('#id#', $k)){
                                if((int)$sSearch != 0 && $sSearch != "-1"){
                                    //on est dan sle cas d'une traduction
                                    $txt = $translator->getByID((int)$sSearch);
                                } else {
                                   //sinon
                                   $txt = $sSearch;
                                }

                                if(preg_match('/'.strtolower($keyword).'/', strtolower(mb_convert_encoding((string)$txt, mb_detect_encoding((string)$keyword), mb_detect_encoding((string)$txt))))){
                                    $searchFind = true;

                                    //on ajoute la page au résultat de recherche
                                    $arry_int[$oData->id_page][$this->url_ligne] = 'http://'.$_SERVER['HTTP_HOST']."/content".$oData->node_absolute_path_name;
                                    $arry_int[$oData->id_page][$this->titre_ligne] = $oData->node_libelle;
                                }

                            }
                        }


                        //pre_dump($aDataContent);
                    }
                }
                
            }
            
            //pre_dump($oData->id_page);
            //pre_dump($objects);
            
        }
        
        if(count($arry_int)){
            return $arry_int;
        } else {
            return null;
        }
        
        
        
        
    }
    
    
    
    /*
     * Les contenus peuvent être lié à des pages de manières différentes
     * Alors il suffit de renseigner un tablea de paramètre comme cela :
     * 
     * 
     * $params = array(  'className' => $className    ,    'siteDependant' => $idSiteDependant      ,    'urlFormat' => $urlTypeResult      ,    'titleFormat' => $titleFormat    )
     * $idSiteDependant => getter_site (de la classe) ou false
     * $urlTypeResult => par ex : "/content/[get_id()]/[get_machin]-[get_title]-[get_description]"
     * $titleFormat => par ex : "[get_title] - blablabla - [get_description]"
     * $notBlacklist = array('$className' => array('nom_du_champ' => array('liste de blacklist') ) );
     */
    
    function contentSearch($idSite, $keyword, $params, $notBlacklist=''){
        
        
        //activation des traductions
        $translator =& TslManager::getInstance();
        
        
        //traitement du tableau des paramètres
        foreach ((array)$params as $value) {
            //pre_dump($value);
            if(!isset($notBlacklist[$value['className']])){
                //si on a pas des blacklist
                
                if($value['siteDependant'] != false){
                    //s'il y a une dépendance au site dans la classe
                    $aContent = dbGetObjectsFromFieldValue($value['className'], array($value['siteDependant']), array($idSite));
                } else {
                    //s'il n'y a pas de dépedance au site dans la classe, on recherche dans toute la base
                    $aContent = dbGetObjects($value['className']);
                }
            } else {
                
                $sSqlBlack = "SELECT * FROM ".$value['className']." WHERE ";
                
                $cnt = 0;
                foreach($notBlacklist[$value['className']] AS $k => $classNotBlack){
                    $cnt++;
                    $sSqlBlack .= $k." IN ( '".  implode('\',\'', $classNotBlack)."' ) ";
                    if($cnt != count($notBlacklist[$value['className']])){
                        $sSqlBlack .= "AND ";
                    }
                }
                $aContent = dbGetObjectsFromRequete($value['className'], $sSqlBlack);
                
            }
            
            $aMethods = get_class_methods($value['className']);
            
            
            if(count($aContent)){
                foreach($aContent as $oContent){
                    //on traite les contenus retrouvés
                    
                    
                    $aDataContent = get_object_vars($oContent);
                    

                        //on va vérifier chaque contenu de la page
                        foreach($aDataContent as $k => $sSearch){
                            
                            
                            //on ne fait pas de recherche sur les id
                            if(!preg_match('#id#', $k)){
                                if((int)$sSearch != 0 && $sSearch != "-1"){
                                    
                                    //pre_dump($sSearch);
                                    //on est dan sle cas d'une traduction
                                    $txt = $sSearch;
                                } else {
                                   //sinon
                                   $txt = $sSearch;
                                }

                                if(preg_match('/'.strtolower($keyword).'/', strtolower((string)$txt))){
                                    
//                                    pre_dump(strtolower($keyword));
//                                    pre_dump(strtolower((string)$txt)); die();
                                    $searchFind = true;

                                    
                                    $url = $value['urlFormat'];
                                    $title_page = $value['titleFormat'];
                                    
                                    foreach($aMethods as $sMethod){
                                        if(preg_match('#get_#', $sMethod)){
                                            $url = str_replace('['.$sMethod.']', $oContent->$sMethod(), $url);
                                            $title_page = str_replace('['.$sMethod.']', $oContent->$sMethod(), $title_page);
                                        }
                                    }
                                    
                                    //on ajoute la page au résultat de recherche
                                    $arry_int[$url][$this->url_ligne] = 'http://'.$_SERVER['HTTP_HOST'].$url;
                                    $arry_int[$url][$this->titre_ligne] = $title_page;
                                }

                            }
                        }
                    
                    
                    
                    
                }
            }
            
            
        }
        
        
        if(count($arry_int)){
            return $arry_int;
        } else {
            return null;
        }
        
        
        
        
    }
    
    
    
}
