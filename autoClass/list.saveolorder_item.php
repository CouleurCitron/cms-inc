<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');


//pre_dump($_POST['classe']);
//pre_dump($_POST['item']);
//pre_dump($_POST['ordered']);


$_POST['item'] = explode("&", str_replace(array("=null", "item[", "]"), array("", "", ""), $_POST['item']));

pre_dump($_POST['item']);


if(isset($_POST['item'])){
    
    foreach($_POST['item'] as $key => $data){
        pre_dump($key);
        
        
        if(preg_match('#=#', $data)){
            $aData = explode("=", $data);
            
            $idClasseItem = $aData[0];
            $parentItem = $aData[1];
            
            pre_dump("parent insert : ".$parentItem." with item id : ".$idClasseItem);
        } else {
            $idClasseItem = $data;
            $parentItem = "-1";
            
            pre_dump("not parent insert : ".$parentItem." with item id : ".$idClasseItem);
        }
        
        
        //pre_dump($key);
        //pre_dump($data);
        //unset($oClass);

        if($key < 10){
            $key2 = "00".$key;
        } else if($key < 100){
            $key2 = "0".$key;
        } else {
            $key2 = $key;
        }
        //pre_dump($key2);
        
        eval("$"."oClass = new ".$_POST['classe']."(".(int)$idClasseItem.");");
        eval("$"."oClass->set_".$_POST['ordered']."(\"".(string)$key2."\");");
        
        if($_POST["fieldparent"] != "") eval("$"."oClass->set_".$_POST['fieldparent']."(".(int)$parentItem.");");  

        //pre_dump($oClass);

        //$order++;
//        $elem = new cms_assoclassepage($key);
//        $elem->set_order( $order );
        
//        if($parent != "null"){
//            $elem->set_parent( (int)$parent );
//        } else {
//            $elem->set_parent( 0 );
//        }
        
        
        $up = dbUpdate($oClass);
        pre_dump($up);
        
        
        //pre_dump($parent);
        //pre_dump($elem);
    }
}


?>
