<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

sponthus 12/08/05
objet d'AFFICHAGE :: class Cms_affich_droitcontenu

cette classe construit un objet d'affichage des droits d'un contenu


==========================================*/

class Cms_affich_droitcontenu
{

var $id_zone;
var $zone;
var $id_content;
var $content;
var $id_user;
var $nom;

// constructeur
function Cms_affich_droitcontenu() 
{
	global $db;

	$this->id_zone = -1;
	$this->zone = '';
	$this->id_content = -1;
	$this->content = '';
	$this->id_user = -1;
	$this->nom = '';

}

// getters
function getId_zone() { return($this->id_zone); } 
function getZone() { return($this->zone); } 
function getId_content() { return($this->id_content); } 
function getContent() { return($this->content); } 
function getId_user() { return($this->id_user); } 
function getNom() { return($this->nom); } 

// setters
function setId_zone($c_id_zone) { return($this->id_zone=$c_id_zone); } 
function setZone($c_zone) { return($this->zone=$c_zone); } 
function setId_content($c_id_content) { return($this->id_content=$c_id_content); } 
function setContent($c_content) { return($this->content=$c_content); } 
function setId_user($c_id_user) { return($this->id_user=$c_id_user); } 
function setNom($c_nom) { return($this->nom=$c_nom); } 

} // fin class
?>