<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================
objet tri
======================================*/

class dbTri {

	var $Nom;
	var $Sens;
						
	// constructeur
	function dbTri($sNom, $sSens)
	{
		$this->Nom = $sNom;
		$this->Sens = $sSens;
	}
	
	// getters
	function getNom() { return($this->Nom); }
	function getSens() { return($this->Sens); }

	// setters
	function setNom($c_Nom) { return($this->Nom=$c_Nom); } 
	function setSens($c_Sens) { return($this->Sens=$c_Sens); } 
}
?>