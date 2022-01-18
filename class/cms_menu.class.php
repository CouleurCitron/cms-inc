<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*
sponthus 09/07/2005

objet menu (ne représente pas d'enregistrement en BDD)
permet de monter le menu du backoffice

*/

class Menu {

		var $Noeud;
		var $Id;
		var $Titre;
		var $Url;
		
	// constructeur
	function __construct($cNoeud, $cId, $cTitre, $cUrl)
	{
		$this->Noeud = $cNoeud;
		$this->Id = $cId;
		$this->Titre = $cTitre;
		$this->Url = $cUrl;
	}

	function getNoeud(){return($this->Noeud);}
	function getId() {return($this->Id);}
	function getTitre(){return($this->Titre);}
	function getUrl(){return($this->Url);}
}

?>