<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

/*
sponthus 02/07/2005

objet champ


*/

class dbChamp {

		var $NomBD;
		var $TypeBD;
		var $Getter;
		var $Setter;
		var $Target; //table enfant
						
	// constructeur
	function dbChamp($leNom, $leType, $leGetter, $leSetter)
	{
		$this->NomBD=$leNom;
		$this->TypeBD=$leType;
		$this->Getter=$leGetter;
		$this->Setter=$leSetter;	
	}
	
	function getNomBD(){return($this->NomBD);}
	function getTypeBD(){return($this->TypeBD);}
	function getGetter(){return($this->Getter);}
	function getSetter(){return($this->Setter);}	
}

?>