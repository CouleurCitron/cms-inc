<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

//pre_dump($_POST);
//die();

if(isset($_POST['item'])){
    $order = 0;
    
    foreach($_POST['item'] as $key => $parent){
        
        $order++;
        $elem = new cms_assoclassepage($key);
        $elem->set_order( $order );
        
        if($parent != "null"){
            $elem->set_parent( (int)$parent );
        } else {
            $elem->set_parent( 0 );
        }
        
        
        $up = dbUpdate($elem);
        
        
        
        //pre_dump($parent);
        //pre_dump($elem);
    }
}



//if(isset($_POST['orderid']) && $_POST['orderid'] != ''){
//	$ids = explode(',',$_POST['orderid']);
//	
//	foreach($ids as $key=>$id){
//		$elem = new cms_assoclassepage($id);
//		$elem->set_order( ($key+1) );
//		dbUpdate($elem);
//	}
//}

?>