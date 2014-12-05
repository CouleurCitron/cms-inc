<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*
sponthus 02/07/2005

objet recherche
permet de définir des filtres de recherche


*/

class dbRecherche {

		var $NomBD;
		var $TypeBD;
		var $ValeurRecherche;
		var $TableBD;
		var $JointureBD;
		var $PureJointure;
						

	// constructeur
	function dbRecherche()
	{
		$this->NomBD = "";
		$this->TypeBD = "";
		$this->ValeurRecherche = "";
		$this->TableBD = "";
		$this->JointureBD = "";
		$this->PureJointure = 0;
	}
	
	// getters
	function getNomBD(){return($this->NomBD);}
	function getTypeBD(){return($this->TypeBD);}
	function getValeurRecherche(){return($this->ValeurRecherche);}
	function getTableBD(){return($this->TableBD);}
	function getJointureBD(){return($this->JointureBD);}	
	function getPureJointure(){return($this->PureJointure);}	

	// setters
	function setNomBD($c_NomBD) { return($this->NomBD=$c_NomBD); } 
	function setTypeBD($c_TypeBD) { return($this->TypeBD=$c_TypeBD); } 
	function setValeurRecherche($c_ValeurRecherche) { return($this->ValeurRecherche=$c_ValeurRecherche); } 	
	function setTableBD($c_TableBD) { return($this->TableBD=$c_TableBD); } 
	function setJointureBD($c_JointureBD) { return($this->JointureBD=$c_JointureBD); } 
	function setPureJointure($c_PureJointure) { return($this->PureJointure=$c_PureJointure); } 

}

?>