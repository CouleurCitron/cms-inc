<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle glossary form content rendering through chosen rendering

// structures des donnéees de formulaires:
// prefix 'glossary_' de données POST

// - glossary_firstname
// - glossary_lastname
// - glossary_email
// - glossary_company
// - glossary_function
// - glossary_message

// include glossary model
include_once('class.GlossaryModel.php');

// needs to extend BaseModuleController
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');


class GlossaryController extends BaseModuleController {

	var $mod_name = 'glossary';

	 

	// constructor
	function GlossaryController ($view=null) {
		 
		$this->models['glossary'] = new GlossaryModel();
		if ($view != '') 
			$this->$view = $view;
	}


	// build
	/**
	 * Build the Controller and launch desired view rendering
	 * May be called for preset action processsing
	 * or just to launch given render class with given wrap mode
	 *
	 * @param	$view		the name of a specific render class to bypass action selector
	 * @param	$wrapped		in case a specific render class is given, wrap it or not with HTML
	 * @return	void
	 */
	function build ($view=null, $wrapped=false) {

		$params = Array(); 
		$params['abc'] = $this->models['glossary']->getABC( true, true );   
		$params['abecedaire'] = $this->models['glossary']->getAbecedaire("&nbsp;", true, true, $params['abc']); 


		
		// verify View render class exists
		if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$view.'.php')) {
			include_once('modules/'.$this->mod_name.'/custom/class.'.$view.'.php');
			$this->view = new $view($this);
		} else	die("ERROR : wrong render class for this process : modules/'.$this->mod_name.'/custom/class.".$view.".php");

		 

		if (!is_null($this->view))
			$this->view->render($params);
		else	echo "No View could be found to display <br/>";
	} 
	
	function getName ($lettre, $id) {
		$params = Array(); 
		$params['abc'] = $this->models['glossary']->getABC( true, true );   
		$params['abecedaire'] = $this->models['glossary']->getAbecedaire("&nbsp;", true, true, $params['abc']);  
		 
		  
	 
		if (isset($id) && $id != '' &&  isset($lettre) && $lettre != '' ) { 
			 
			$definition = $params['abc'][$lettre][$id];
			return $definition["titrecourt"];
		}
		else if (isset($id) && $id != '' &&   $lettre == '' ) {  
			foreach ($params['abc'] as $k => $lettre) {  
				foreach ($lettre as $j => $def) {  
					if ($j == $id) {  
						$definition = $def;
						break; 
					}
				} 
			}    
			return $definition["titrecourt"];
		}
		else {
			return false;
		}

	}
	
	function getDefinition ($lettre, $id) {
		$params = Array(); 
		$params['abc'] = $this->models['glossary']->getABC( true, true );   
		$params['abecedaire'] = $this->models['glossary']->getAbecedaire("&nbsp;", true, true, $params['abc']); 
		
		if (isset($id) && $id != '' &&  isset($lettre) && $lettre != '' ) { 
			$definition = $params['abc'][$lettre][$id];
			return $definition["textelong"];
		}
		else if (isset($id) && $id != '' &&   $lettre == '' ) {  
			foreach ($params['abc'] as $k => $lettre) {  
				foreach ($lettre as $j => $def) {  
					if ($j == $id) {   
						$definition = $def;
						break; 
					}
				} 
			}    
			return $definition["textelong"];
		}
		else {
			return false;
		}

	}
	
}

 
	
	
	function getName ($params, $id) { 
		if (isset($id) && $id != '' ) {  
			foreach ($params['abc'] as $k => $lettre) {  
				foreach ($lettre as $j => $def) {  
					if ($j == $id) {   
						$definition = $def;
						break; 
					}
				} 
			}    
			return $definition["titrecourt"];
		}
		else {
			return false;
		}

	}
	
	 
	
	
	function getDefinition ($params, $id) {
	
	if (isset($id) && $id != ''  ) {  
			foreach ($params['abc'] as $k => $lettre) {  
				foreach ($lettre as $j => $def) {  
					if ($j == $id) {   
						$definition = $def;
						break; 
					}
				} 
			}    
			return $definition["textelong"];
		}
		else {
			return false;
		}

	}

?>
