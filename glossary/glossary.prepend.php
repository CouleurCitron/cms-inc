<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
	
	
	 
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/glossary/class.GlossaryController.php');
	 
	
	$controller = new GlossaryController();

	 
	if ((isset($_GET['id']) && $_GET['id'] != '') &&  (isset($_GET['lettre']) && $_GET['lettre'] != '')) {
		$id = $_GET['id'];
		$lettre = $_GET['lettre'];
		$mot_libelle = $controller->getName ($lettre, $id) ;
		$definition = $controller->getDefinition($lettre, $id);
	}
	else if ((isset($_GET['id']) && $_GET['id'] != '') &&  (!isset($_GET['lettre']) )) {
		$id = $_GET['id'];
		$mot_libelle = $controller->getName ('', $id) ;
		$definition = $controller->getDefinition('', $id);
	}

	function callback($buffer){ 
		
		global $mot_libelle, $definition;
		 
		$replace= $mot_libelle ;	
		
		//<meta name="KEYWORDS" content="" />
		//<meta name="DESCRIPTION" content="" />	
		
		 
		$buffer = preg_replace('@<title>(.*)</title>@i', '<title>'.$mot_libelle.'</title>', $buffer);
		
		$buffer = preg_replace('/<meta[^>]*name=["|\']keywords["|\'][^>]*content=["|\'](.*)["|\']\s*\/>/Ui', '<meta name="KEYWORDS" content="'.$mot_libelle.','.'$1'.'" />', $buffer);
		$buffer = preg_replace('/<meta[^>]*name=["|\']description["|\'][^>]*content=["|\'](.*)["|\']\s*\/>/Ui', '<meta name="DESCRIPTION" content="'.'$1'.' '.$mot_libelle.': '.substr($definition, 0, 156).'" />', $buffer);

		 
		return $buffer; 
		 
		//return str_replace ("<title>(.*)</title>", "<title>toto</title>", $buffer);
		
	} 
	
	ob_start("callback"); 
	 
	  
?>