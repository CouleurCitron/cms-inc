<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/** 
 * Buggy inside Firebug - Advanced 
 *  
 * @package GONX 
 * @author hatem <hatem at php dot net> 
 * @website http://phpmagazine.net 
 * @copyright Copyright (c) 2005-2007 
 * @version $Id: buggy.class.php,v 1.1 2013-09-30 09:30:37 raphael Exp $ 
 * @access public 
 *
 * http://ajax.phpmagazine.net/2007/02/how_to_use_firebug_to_debug_ph.html
 *
 * @author Dominique Vial
 * 
 **/  
class buggy {  
  
	/**
	* Activation de l'affichage des messages
	*
	* @author Dominique Vial
	*
	* @var boolean
	*/
	var $enabled;
	
    function _init(){  
        $old_error_handler = set_error_handler(array("buggy","ErrorHandler"));  
        define ("FATAL",E_USER_ERROR);  
        define ("WARNING",E_USER_WARNING);  
        define ("NOTICE",E_USER_NOTICE);  
        // configure reporting level  
        error_reporting (FATAL | WARNING | NOTICE);
		
		// Pour des raisons de sécurité, l'affichage est désactivé par défaut
		$this->enabled = false;
		
    }  
  
  
  
  
    /** 
     * Buggy::getmicrotime() 
     *  
     * @return  
     **/  
    function getmicrotime(){  
        $mtime = microtime();  
        $mtime = explode(" ",$mtime);  
        $mtime = $mtime[1] + $mtime[0];  
        return ($mtime);  
    }  
  
    /** 
     * Buggy::SetMicroTime() 
     *  
     * @param $module 
     * @return  
     **/  
    function SetMicroTime($module)  
    {  
        global $Buggy;  
        $Buggy[$module] = Buggy::getmicrotime();  
        return $Buggy[$module];  
    }  
      
    /** 
     * Buggy::GetExecutionTime() 
     *  
     * @param $module 
     * @return  
     **/  
    function GetExecutionTime($module)  
    {  
        global $Buggy;  
        $end = Buggy::getmicrotime();  
        $res = $end - $Buggy[$module];  
        return $res;  
    }  
  
    /** 
     * Buggy::Track() 
     * 
     * @param $module 
     * @param $msg      additional message to display 
     * @return  
     **/  
    function Track($module, $msg = '') {  
      
        global $Buggy;  
      
        if (!isset($Buggy[$module])) {               
            Buggy::SetMicroTime($module);               
        } else {               
            $duration = Buggy::GetExecutionTime($module);  
            Buggy::logMessage($module,'Notice',$msg,$duration);            
        }  
      
    }  
      
    /** 
     * Buggy::logMessage() 
     *  
     * @param string $module 
     * @param string $type 
     * @param string $message 
     * @param string $duration 
     * @return  
     **/  
    function logMessage($module = '', $type = '', $message = '', $duration = ''){
		if ($this->enabled == true) {
			if ($module == 'PHPError') {  
			  
				if ($type == 'WARNING') {  
					echo "<script>console.warn(\"[Buggy] - $module [$type] - $message\")</script>\n";  
				} elseif ($type == 'Fatal') {  
					echo "<script>console.error(\"[Buggy] - $module [$type] - $message\")</script>\n";  
				}else{  
					echo "<script>console.info(\"[Buggy] - $module [$type] - $message\")</script>\n";  
				}  
					  
			} else {  
				echo "<script>console.info(\"[Buggy] - $module [$type] - $message - Execution Time: $duration \")</script>\n";  
			}
		}
    } 

	/**
	* Buggy::groupBegin
	*
	* Ouvre un bloc d'affichage dans Firebug.
	* La label du bloc est passé en paramètre.
	*
	* Utiliser groupEnd() pour le fermer;
	*
	* @author Dominique Vial
	*
	* @param string Label du bloc pour l'affichage Firebug
	**/
	function groupBegin ($message) {
		if ($this->enabled === true) {
			$sortie = "<script>console.group('".addslashes($message)."')</script>\n";
			echo $sortie;
		}
	}

	/**
	* Buggy::groupEnd
	*
	* Ferme un bloc d'affichage dans Firebug.
	*
	* @author Dominique Vial
	*
	* @param 
	**/	
	function groupEnd () {
		if ($this->enabled === true) {
			$sortie = "<script>console.groupEnd()</script>\n";
			echo $sortie;
		}
	}
      
    /** 
     * Buggy errors manager 
     *  
     * @param $errno 
     * @param $errstr 
     * @param $errfile 
     * @param $errline 
     * @return  
     **/  
    function ErrorHandler ($errno, $errstr, $errfile, $errline) {  
      switch ($errno) {  
        case FATAL:  
        Buggy::logMessage('PHPError', 'Fatal', "{$errno} : $errstr - Fatal error in line ".$errline." of file ".$errfile);  
        exit(1);  
        break;  
          
        case WARNING:  
            Buggy::logMessage('PHPError', 'WARNING', "{$errno} : $errstr - WARNING error in line ".$errline." of file ".$errfile);  
        break;  
          
        default:  
            //Buggy::logMessage('PHPError', 'Notice', "{$errno} : $errstr - Notice error in line ".$errline." of file ".$errfile);  
        break;  
      }  
    }
	
	function dump($data, $name='Data'){

		if ( $this->enabled == true ) {
			if (is_array($data)){
				echo "\n";
				echo Buggy::dumpArray($data, $name);
				echo "\n";
			}else{
				echo "<script>console.info(\"[Buggy] - [dump] - "."\$".$name." = ".
				addslashes($data)."\")</script>\n";
			}
		}

	}

	/**
	* @todo [BUG] La présence d'un retour chariot plante le script
	**/
	function dumpArray($arr,$name,$sublevel=false) {
		$output = "";
		if($sublevel===false){
			$output .= "<script>console.group('Array \$".addslashes($name)."');</script>\n";
		}else{
			$output .= "<script>console.group('Array [".addslashes($name)."]');</script>\n";
		}
		foreach($arr AS $key=>$val){
			if (is_array($val) || is_object($val)) {
				$output .= Buggy::dumpArray($val,$key,true);
			}else{
			$output .= "<script>console.log('[".addslashes($key)."] => ".nl2br(trim($val,"\x7f..\xff\x0..\x1f"))."');</script>\n";
			}
		}
		$output .= "<script>console.groupEnd();</script>\n";
		return $output;
	}	


 
     /** 
     * Buggy::activate()
	 *
	 * Activation des traces
     * 
	 * @author Dominique Vial
     * @return  
     **/  
    function activate () {
		$this->enabled = true;
    }  

      /** 
     * Buggy::deactivate() 
	 *
	 * Désactivation des traces
	 *
	 * @author Dominique Vial	 
     * 
     * @return  
     **/  
    function deactivate () {
		$this->enabled = false;
    }  
	
}
?>