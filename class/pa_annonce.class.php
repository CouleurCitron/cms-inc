<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD pa_annonce :: class pa_annonce

SQL mySQL:

DROP TABLE IF EXISTS pa_annonce;
CREATE TABLE pa_annonce
(
	pa_id			int (11) PRIMARY KEY not null,
	pa_type			int (2),
	pa_pa_inscrit			int (11),
	pa_titre			varchar (255),
	pa_pa_quartier			int (11),
	pa_prenom			varchar (255),
	pa_nom			varchar (255),
	pa_prenoms_enfants			varchar (255),
	pa_ages_enfants			varchar (255),
	pa_adresse			varchar (255),
	pa_code_postal			varchar (5),
	pa_ville			varchar (255),
	pa_telephone			varchar (12),
	pa_telephone_autre			varchar (12),
	pa_mail			varchar (255),
	pa_duree			int (2),
	pa_a_partir			date,
	pa_plage_horaire			int (2),
	pa_temps_de_travail			int (2),
	pa_detail			varchar (1024),
	pa_est_validee			int (2),
	pa_est_supprimee			int (2),
	pa_est_relance			int (2),
	pa_soumission			date,
	pa_validation			date,
	pa_peremption			date,
	pa_dtcrea			date,
	pa_dtmod			date,
	pa_statut			int (11) not null
)

SQL Oracle:

DROP TABLE pa_annonce
CREATE TABLE pa_annonce
(
	pa_id			number (11) constraint pa_pk PRIMARY KEY not null,
	pa_type			number (2),
	pa_pa_inscrit			number (11),
	pa_titre			varchar2 (255),
	pa_pa_quartier			number (11),
	pa_prenom			varchar2 (255),
	pa_nom			varchar2 (255),
	pa_prenoms_enfants			varchar2 (255),
	pa_ages_enfants			varchar2 (255),
	pa_adresse			varchar2 (255),
	pa_code_postal			varchar2 (5),
	pa_ville			varchar2 (255),
	pa_telephone			varchar2 (12),
	pa_telephone_autre			varchar2 (12),
	pa_mail			varchar2 (255),
	pa_duree			number (2),
	pa_a_partir			date,
	pa_plage_horaire			number (2),
	pa_temps_de_travail			number (2),
	pa_detail			varchar2 (1024),
	pa_est_validee			number (2),
	pa_est_supprimee			number (2),
	pa_est_relance			number (2),
	pa_soumission			date,
	pa_validation			date,
	pa_peremption			date,
	pa_dtcrea			date,
	pa_dtmod			date,
	pa_statut			number (11) not null
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class	name 	= "pa_annonce"
		libelle	= "Annonce"
		display = "titre"
		abstract= "type"
        prefix="pa"
		>
		
<item	name		= "id"
		type		= "int"
		length		= "11"
		isprimary	= "true"
		notnull		= "true"
		default		= "-1"
		list		= "true"
		order		= "true" />

<!-- ============== -->		
<!--     DONNEES	    -->
<!-- ============== -->
    
 <item name	= "type"
            libelle	= "Type"
            type	= "int"
            length	= "2"
            list	= "true"
            order	= "true"
            option	= "enum"
                oblig="true"    
            >
	<option type="value" value="0" libelle="Une famille avec nounou cherchant une autre famille" />
	<option type="value" value="1" libelle="Une famille cherchant une autre famille" />
	<option type="value" value="2" libelle="Une famille cherchant une nounou" />
  <option type="value" value="3" libelle="Une famille cherchant une garde après l&apos;école" />
  <option type="value" value="4" libelle="Une nounou cherchant une famille" />
</item>     

<item	name		= "pa_inscrit"
		libelle		= "Inscrit"
		type		= "int"
		length		= "11"
		list		= "true"
		order		= "true"
		oblig		= "true"
		fkey		= "pa_inscrit"
		/>
    
<item	name	= "titre"
		libelle	= "Titre"
		type	= "varchar"
		length	= "255"
		list	= "true"
    oblig		= "true"
		order	= "true">
    <option type="if" item="type" value="1" />
    <option type="if" item="type" value="2" /> 
    <option type="if" item="type" value="5" />  
  </item>
    
<item	name		= "pa_quartier"
		libelle		= "Quartier"
		type		= "int"
		length		= "11"
		list		= "true"
		order		= "true"
		oblig		= "true"
		fkey		= "pa_quartier"
		/>       
    
 <item	name	= "prenom"
		libelle	= "Prénom"
		type	= "varchar"
		length	= "255"
		list	= "true"
		order	= "true">
    <option type="if" item="type" value="3" />
    <option type="if" item="type" value="4" /> 
    <option type="if" item="type" value="5" />  
  </item>
 
 <item	name	= "nom"
		libelle	= "Nom"
		type	= "varchar"
		length	= "255"
		list	= "true"
		order	= "true">
    <option type="if" item="type" value="3" />
    <option type="if" item="type" value="4" /> 
    <option type="if" item="type" value="5" />  
  </item> 
    
		
<item	name	= "prenoms_enfants"
		libelle	= "Prénom(s) enfant(s)"
		type	= "varchar"
		length	= "255"
		list	= "false"
        oblig="true"    
		order	= "true" >
    <option type="if" item="type" value="1" />
    <option type="if" item="type" value="2" />    
  </item>
    
 <item	name	= "ages_enfants"
		libelle	= "Age(s) enfant(s)"
		type	= "varchar"
		length	= "255"
		list	= "false"  
		order	= "true"  >
    <option type="if" item="type" value="1" />
    <option type="if" item="type" value="2" />    
  </item>   

 <item	name	= "adresse"
		libelle	= "Adresse"
		type	= "varchar"
		length	= "255"
		list	= "false"
        oblig="true"    
		order	= "true" /> 
    
  <item	name	= "code_postal"
		libelle	= "Code Postal"
		type	= "varchar"
		length	= "5"
		list	= "false"
        oblig="true"    
		order	= "true" />    

 <item	name	= "ville"
		libelle	= "Ville"
		type	= "varchar"
		length	= "255"
		list	= "true"
        oblig="true"    
		order	= "true" /> 
 
 <item	name	= "telephone"
		libelle	= "Téléphone"
		type	= "varchar"
		length	= "12"
		list	= "false"
        oblig="true"    
		order	= "true" />
    
 <item	name	= "telephone_autre"
		libelle	= "Téléphone (autre)"
		type	= "varchar"
		length	= "12"
		list	= "false"
		order	= "true" />      
 
 <item	name	= "mail"
		libelle	= "e-Mail"
		type	= "varchar"
		length	= "255"
		list	= "false"
        oblig="true"    
		order	= "true" />
    
<item name	= "duree"
            libelle	= "Durée"
            type	= "int"
            length	= "2"
            list	= "true"
            order	= "true"
                oblig="true"    
            option	= "enum"
            >
	<option type="value" value="1" libelle="1 mois" />
	<option type="value" value="2" libelle="2 mois" />
	<option type="value" value="3" libelle="3 mois" />
	<option type="value" value="6" libelle="6 mois" />
	<option type="value" value="9" libelle="9 mois" />
	<option type="value" value="12" libelle="12 mois" />
  <option type="value" value="24" libelle="2 ans" />
  <option type="value" value="36" libelle="3 ans" />

    <option type="if" item="type" value="1" />  
    <option type="if" item="type" value="2" />        
    <option type="if" item="type" value="3" />
    <option type="if" item="type" value="4" /> 
</item>
  
  <item	name	= "a_partir"
		libelle	= "A partir"
		type	= "date"
		list	= "false"
		order	= "false"
		oblig	= "false"		
		format	= "l j F Y">
        oblig="true"    
</item>



<item name	= "plage_horaire"
            libelle	= "Plage horaire"
            type	= "int"
            length	= "2"
            list	= "true"
            order	= "true"
            option	= "enum"
            >
	<option type="value" value="1" libelle="1 h" />
	<option type="value" value="2" libelle="2 h" />
	<option type="value" value="3" libelle="3 h" />
	<option type="value" value="4" libelle="4 h" />
	<option type="value" value="5" libelle="5 h" />
	<option type="value" value="6" libelle="6 h" />
	<option type="value" value="7" libelle="7 h" />
	<option type="value" value="8" libelle="8 h" />
	<option type="value" value="9" libelle="9 h" />
	<option type="value" value="10" libelle="10 h" />
	<option type="value" value="11" libelle="plus" />
  
      <option type="if" item="type" value="1" />  
    <option type="if" item="type" value="2" />        
</item>

  <!-- temps_de_travail -->
<item name	= "temps_de_travail"
            libelle	= "Temps de travail"
            type	= "int"
            length	= "2"
            list	= "true"
            order	= "true"
            option	= "enum"
            >
	<option type="value" value="1" libelle="temps plein" />
	<option type="value" value="2" libelle="mi temps" />
	<option type="value" value="3" libelle="temps partiel" />
      <option type="if" item="type" value="3" />
    <option type="if" item="type" value="4" /> 
    <option type="if" item="type" value="5" />  
</item>  
  
<item	name	= "detail"
		libelle = "Détail"
		type	= "varchar"
		length	= "1024"
		list	= "false"
		order	= "true"
		option	= "textarea"
 />  
  
<item	name	= "est_validee"
		libelle	= "Validée ?"
		type	= "int"
		length	= "2"
		list	= "true"
		order	= "true"
		option	= "bool" />


<item	name	= "est_supprimee"
		libelle	= "Supprimée ?"
		type	= "int"
		length	= "2"
		list	= "true"
		order	= "true"
		option	= "bool" />
		
<item	name	= "est_relance"
		libelle	= "Relancé ?"
		type	= "int"
		length	= "2"
		list	= "false"
		order	= "false"
		oblig	= "false"		
		option	= "bool" /> 
    
<item	name	= "soumission"
		libelle	= "Soumission"
		type	= "date"
		list	= "false"
		order	= "false"
		oblig	= "false"		
		format	= "l j F Y" />

<item	name	= "validation"
		libelle	= "Validation"
		type	= "date"
		list	= "false"
		order	= "false"
		oblig	= "false"		
		format	= "l j F Y" />

<item	name	= "peremption"
		libelle	= "Péremption"
		type	= "date"
		list	= "false"
		order	= "false"
		oblig	= "false"		
		format	= "l j F Y" /> 
		


<!-- ============== -->		
<!-- ADMINISTRATION	-->
<!-- ============== -->
<item	name	= "dtcrea"
		libelle	= "Date de création"
		type	= "date"
		list	= "true"
		order	= "true" />
		
<item	name	= "dtmod"
		libelle	= "Date de modification"
		type	= "date"
		list	= "true"
		order	= "true" />
		
<item	name	= "statut"
		libelle	= "Statut"
		type	= "int"
		length	= "11"
		notnull	= "true"
		default	= "DEF_CODE_STATUT_DEFAUT"
		list	= "true"
		order	= "true" />
    
<langpack lang="fr">
<norecords>Aucune annonce pour le moment !</norecords>
</langpack>    

</class> 


==========================================*/

class pa_annonce
{
var $id;
var $type;
var $pa_inscrit;
var $titre;
var $pa_quartier;
var $prenom;
var $nom;
var $prenoms_enfants;
var $ages_enfants;
var $adresse;
var $code_postal;
var $ville;
var $telephone;
var $telephone_autre;
var $mail;
var $duree;
var $a_partir;
var $plage_horaire;
var $temps_de_travail;
var $detail;
var $est_validee;
var $est_supprimee;
var $est_relance;
var $soumission;
var $validation;
var $peremption;
var $dtcrea;
var $dtmod;
var $statut;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class	name 	= \"pa_annonce\"
		libelle	= \"Annonce\"
		display = \"titre\"
		abstract= \"type\"
        prefix=\"pa\"
		>
		
<item	name		= \"id\"
		type		= \"int\"
		length		= \"11\"
		isprimary	= \"true\"
		notnull		= \"true\"
		default		= \"-1\"
		list		= \"true\"
		order		= \"true\" />

<!-- ============== -->		
<!--     DONNEES	    -->
<!-- ============== -->
    
 <item name	= \"type\"
            libelle	= \"Type\"
            type	= \"int\"
            length	= \"2\"
            list	= \"true\"
            order	= \"true\"
            option	= \"enum\"
                oblig=\"true\"    
            >
	<option type=\"value\" value=\"0\" libelle=\"Une famille avec nounou cherchant une autre famille\" />
	<option type=\"value\" value=\"1\" libelle=\"Une famille cherchant une autre famille\" />
	<option type=\"value\" value=\"2\" libelle=\"Une famille cherchant une nounou\" />
  <option type=\"value\" value=\"3\" libelle=\"Une famille cherchant une garde après l&apos;école\" />
  <option type=\"value\" value=\"4\" libelle=\"Une nounou cherchant une famille\" />
</item>     

<item	name		= \"pa_inscrit\"
		libelle		= \"Inscrit\"
		type		= \"int\"
		length		= \"11\"
		list		= \"true\"
		order		= \"true\"
		oblig		= \"true\"
		fkey		= \"pa_inscrit\"
		/>
    
<item	name	= \"titre\"
		libelle	= \"Titre\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"true\"
    oblig		= \"true\"
		order	= \"true\">
    <option type=\"if\" item=\"type\" value=\"1\" />
    <option type=\"if\" item=\"type\" value=\"2\" /> 
    <option type=\"if\" item=\"type\" value=\"5\" />  
  </item>
    
<item	name		= \"pa_quartier\"
		libelle		= \"Quartier\"
		type		= \"int\"
		length		= \"11\"
		list		= \"true\"
		order		= \"true\"
		oblig		= \"true\"
		fkey		= \"pa_quartier\"
		/>       
    
 <item	name	= \"prenom\"
		libelle	= \"Prénom\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"true\"
		order	= \"true\">
    <option type=\"if\" item=\"type\" value=\"3\" />
    <option type=\"if\" item=\"type\" value=\"4\" /> 
    <option type=\"if\" item=\"type\" value=\"5\" />  
  </item>
 
 <item	name	= \"nom\"
		libelle	= \"Nom\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"true\"
		order	= \"true\">
    <option type=\"if\" item=\"type\" value=\"3\" />
    <option type=\"if\" item=\"type\" value=\"4\" /> 
    <option type=\"if\" item=\"type\" value=\"5\" />  
  </item> 
    
		
<item	name	= \"prenoms_enfants\"
		libelle	= \"Prénom(s) enfant(s)\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"false\"
        oblig=\"true\"    
		order	= \"true\" >
    <option type=\"if\" item=\"type\" value=\"1\" />
    <option type=\"if\" item=\"type\" value=\"2\" />    
  </item>
    
 <item	name	= \"ages_enfants\"
		libelle	= \"Age(s) enfant(s)\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"false\"  
		order	= \"true\"  >
    <option type=\"if\" item=\"type\" value=\"1\" />
    <option type=\"if\" item=\"type\" value=\"2\" />    
  </item>   

 <item	name	= \"adresse\"
		libelle	= \"Adresse\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"false\"
        oblig=\"true\"    
		order	= \"true\" /> 
    
  <item	name	= \"code_postal\"
		libelle	= \"Code Postal\"
		type	= \"varchar\"
		length	= \"5\"
		list	= \"false\"
        oblig=\"true\"    
		order	= \"true\" />    

 <item	name	= \"ville\"
		libelle	= \"Ville\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"true\"
        oblig=\"true\"    
		order	= \"true\" /> 
 
 <item	name	= \"telephone\"
		libelle	= \"Téléphone\"
		type	= \"varchar\"
		length	= \"12\"
		list	= \"false\"
        oblig=\"true\"    
		order	= \"true\" />
    
 <item	name	= \"telephone_autre\"
		libelle	= \"Téléphone (autre)\"
		type	= \"varchar\"
		length	= \"12\"
		list	= \"false\"
		order	= \"true\" />      
 
 <item	name	= \"mail\"
		libelle	= \"e-Mail\"
		type	= \"varchar\"
		length	= \"255\"
		list	= \"false\"
        oblig=\"true\"    
		order	= \"true\" />
    
<item name	= \"duree\"
            libelle	= \"Durée\"
            type	= \"int\"
            length	= \"2\"
            list	= \"true\"
            order	= \"true\"
                oblig=\"true\"    
            option	= \"enum\"
            >
	<option type=\"value\" value=\"1\" libelle=\"1 mois\" />
	<option type=\"value\" value=\"2\" libelle=\"2 mois\" />
	<option type=\"value\" value=\"3\" libelle=\"3 mois\" />
	<option type=\"value\" value=\"6\" libelle=\"6 mois\" />
	<option type=\"value\" value=\"9\" libelle=\"9 mois\" />
	<option type=\"value\" value=\"12\" libelle=\"12 mois\" />
  <option type=\"value\" value=\"24\" libelle=\"2 ans\" />
  <option type=\"value\" value=\"36\" libelle=\"3 ans\" />

    <option type=\"if\" item=\"type\" value=\"1\" />  
    <option type=\"if\" item=\"type\" value=\"2\" />        
    <option type=\"if\" item=\"type\" value=\"3\" />
    <option type=\"if\" item=\"type\" value=\"4\" /> 
</item>
  
  <item	name	= \"a_partir\"
		libelle	= \"A partir\"
		type	= \"date\"
		list	= \"false\"
		order	= \"false\"
		oblig	= \"false\"		
		format	= \"l j F Y\">
        oblig=\"true\"    
</item>



<item name	= \"plage_horaire\"
            libelle	= \"Plage horaire\"
            type	= \"int\"
            length	= \"2\"
            list	= \"true\"
            order	= \"true\"
            option	= \"enum\"
            >
	<option type=\"value\" value=\"1\" libelle=\"1 h\" />
	<option type=\"value\" value=\"2\" libelle=\"2 h\" />
	<option type=\"value\" value=\"3\" libelle=\"3 h\" />
	<option type=\"value\" value=\"4\" libelle=\"4 h\" />
	<option type=\"value\" value=\"5\" libelle=\"5 h\" />
	<option type=\"value\" value=\"6\" libelle=\"6 h\" />
	<option type=\"value\" value=\"7\" libelle=\"7 h\" />
	<option type=\"value\" value=\"8\" libelle=\"8 h\" />
	<option type=\"value\" value=\"9\" libelle=\"9 h\" />
	<option type=\"value\" value=\"10\" libelle=\"10 h\" />
	<option type=\"value\" value=\"11\" libelle=\"plus\" />
  
      <option type=\"if\" item=\"type\" value=\"1\" />  
    <option type=\"if\" item=\"type\" value=\"2\" />        
</item>

  <!-- temps_de_travail -->
<item name	= \"temps_de_travail\"
            libelle	= \"Temps de travail\"
            type	= \"int\"
            length	= \"2\"
            list	= \"true\"
            order	= \"true\"
            option	= \"enum\"
            >
	<option type=\"value\" value=\"1\" libelle=\"temps plein\" />
	<option type=\"value\" value=\"2\" libelle=\"mi temps\" />
	<option type=\"value\" value=\"3\" libelle=\"temps partiel\" />
      <option type=\"if\" item=\"type\" value=\"3\" />
    <option type=\"if\" item=\"type\" value=\"4\" /> 
    <option type=\"if\" item=\"type\" value=\"5\" />  
</item>  
  
<item	name	= \"detail\"
		libelle = \"Détail\"
		type	= \"varchar\"
		length	= \"1024\"
		list	= \"false\"
		order	= \"true\"
		option	= \"textarea\"
 />  
  
<item	name	= \"est_validee\"
		libelle	= \"Validée ?\"
		type	= \"int\"
		length	= \"2\"
		list	= \"true\"
		order	= \"true\"
		option	= \"bool\" />


<item	name	= \"est_supprimee\"
		libelle	= \"Supprimée ?\"
		type	= \"int\"
		length	= \"2\"
		list	= \"true\"
		order	= \"true\"
		option	= \"bool\" />
		
<item	name	= \"est_relance\"
		libelle	= \"Relancé ?\"
		type	= \"int\"
		length	= \"2\"
		list	= \"false\"
		order	= \"false\"
		oblig	= \"false\"		
		option	= \"bool\" /> 
    
<item	name	= \"soumission\"
		libelle	= \"Soumission\"
		type	= \"date\"
		list	= \"false\"
		order	= \"false\"
		oblig	= \"false\"		
		format	= \"l j F Y\" />

<item	name	= \"validation\"
		libelle	= \"Validation\"
		type	= \"date\"
		list	= \"false\"
		order	= \"false\"
		oblig	= \"false\"		
		format	= \"l j F Y\" />

<item	name	= \"peremption\"
		libelle	= \"Péremption\"
		type	= \"date\"
		list	= \"false\"
		order	= \"false\"
		oblig	= \"false\"		
		format	= \"l j F Y\" /> 
		


<!-- ============== -->		
<!-- ADMINISTRATION	-->
<!-- ============== -->
<item	name	= \"dtcrea\"
		libelle	= \"Date de création\"
		type	= \"date\"
		list	= \"true\"
		order	= \"true\" />
		
<item	name	= \"dtmod\"
		libelle	= \"Date de modification\"
		type	= \"date\"
		list	= \"true\"
		order	= \"true\" />
		
<item	name	= \"statut\"
		libelle	= \"Statut\"
		type	= \"int\"
		length	= \"11\"
		notnull	= \"true\"
		default	= \"DEF_CODE_STATUT_DEFAUT\"
		list	= \"true\"
		order	= \"true\" />
    
<langpack lang=\"fr\">
<norecords>Aucune annonce pour le moment !</norecords>
</langpack>    

</class> ";

var $sMySql = "CREATE TABLE pa_annonce
(
	pa_id			int (11) PRIMARY KEY not null,
	pa_type			int (2),
	pa_pa_inscrit			int (11),
	pa_titre			varchar (255),
	pa_pa_quartier			int (11),
	pa_prenom			varchar (255),
	pa_nom			varchar (255),
	pa_prenoms_enfants			varchar (255),
	pa_ages_enfants			varchar (255),
	pa_adresse			varchar (255),
	pa_code_postal			varchar (5),
	pa_ville			varchar (255),
	pa_telephone			varchar (12),
	pa_telephone_autre			varchar (12),
	pa_mail			varchar (255),
	pa_duree			int (2),
	pa_a_partir			date,
	pa_plage_horaire			int (2),
	pa_temps_de_travail			int (2),
	pa_detail			varchar (1024),
	pa_est_validee			int (2),
	pa_est_supprimee			int (2),
	pa_est_relance			int (2),
	pa_soumission			date,
	pa_validation			date,
	pa_peremption			date,
	pa_dtcrea			date,
	pa_dtmod			date,
	pa_statut			int (11) not null
)

";

// constructeur
function __construct($id=null)
{
	if (istable("pa_annonce") == false){
		dbExecuteQuery($this->sMySql);
	}

	if($id!=null) {
		$tempThis = dbGetObjectFromPK(get_class($this), $id);
		foreach ($tempThis as $tempKey => $tempValue){
			$this->$tempKey = $tempValue;
		}
		$tempThis = null;
	} else {
		$this->id = -1;
		$this->type = -1;
		$this->pa_inscrit = -1;
		$this->titre = "";
		$this->pa_quartier = -1;
		$this->prenom = "";
		$this->nom = "";
		$this->prenoms_enfants = "";
		$this->ages_enfants = "";
		$this->adresse = "";
		$this->code_postal = "";
		$this->ville = "";
		$this->telephone = "";
		$this->telephone_autre = "";
		$this->mail = "";
		$this->duree = -1;
		$this->a_partir = date("d/m/Y");
		$this->plage_horaire = -1;
		$this->temps_de_travail = -1;
		$this->detail = "";
		$this->est_validee = -1;
		$this->est_supprimee = -1;
		$this->est_relance = -1;
		$this->soumission = date("d/m/Y");
		$this->validation = date("d/m/Y");
		$this->peremption = date("d/m/Y");
		$this->dtcrea = date("d/m/Y");
		$this->dtmod = date("d/m/Y");
		$this->statut = DEF_CODE_STATUT_DEFAUT;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Pa_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Pa_type", "entier", "get_type", "set_type");
	$laListeChamps[]=new dbChamp("Pa_pa_inscrit", "entier", "get_pa_inscrit", "set_pa_inscrit");
	$laListeChamps[]=new dbChamp("Pa_titre", "text", "get_titre", "set_titre");
	$laListeChamps[]=new dbChamp("Pa_pa_quartier", "entier", "get_pa_quartier", "set_pa_quartier");
	$laListeChamps[]=new dbChamp("Pa_prenom", "text", "get_prenom", "set_prenom");
	$laListeChamps[]=new dbChamp("Pa_nom", "text", "get_nom", "set_nom");
	$laListeChamps[]=new dbChamp("Pa_prenoms_enfants", "text", "get_prenoms_enfants", "set_prenoms_enfants");
	$laListeChamps[]=new dbChamp("Pa_ages_enfants", "text", "get_ages_enfants", "set_ages_enfants");
	$laListeChamps[]=new dbChamp("Pa_adresse", "text", "get_adresse", "set_adresse");
	$laListeChamps[]=new dbChamp("Pa_code_postal", "text", "get_code_postal", "set_code_postal");
	$laListeChamps[]=new dbChamp("Pa_ville", "text", "get_ville", "set_ville");
	$laListeChamps[]=new dbChamp("Pa_telephone", "text", "get_telephone", "set_telephone");
	$laListeChamps[]=new dbChamp("Pa_telephone_autre", "text", "get_telephone_autre", "set_telephone_autre");
	$laListeChamps[]=new dbChamp("Pa_mail", "text", "get_mail", "set_mail");
	$laListeChamps[]=new dbChamp("Pa_duree", "entier", "get_duree", "set_duree");
	$laListeChamps[]=new dbChamp("Pa_a_partir", "date_formatee", "get_a_partir", "set_a_partir");
	$laListeChamps[]=new dbChamp("Pa_plage_horaire", "entier", "get_plage_horaire", "set_plage_horaire");
	$laListeChamps[]=new dbChamp("Pa_temps_de_travail", "entier", "get_temps_de_travail", "set_temps_de_travail");
	$laListeChamps[]=new dbChamp("Pa_detail", "text", "get_detail", "set_detail");
	$laListeChamps[]=new dbChamp("Pa_est_validee", "entier", "get_est_validee", "set_est_validee");
	$laListeChamps[]=new dbChamp("Pa_est_supprimee", "entier", "get_est_supprimee", "set_est_supprimee");
	$laListeChamps[]=new dbChamp("Pa_est_relance", "entier", "get_est_relance", "set_est_relance");
	$laListeChamps[]=new dbChamp("Pa_soumission", "date_formatee", "get_soumission", "set_soumission");
	$laListeChamps[]=new dbChamp("Pa_validation", "date_formatee", "get_validation", "set_validation");
	$laListeChamps[]=new dbChamp("Pa_peremption", "date_formatee", "get_peremption", "set_peremption");
	$laListeChamps[]=new dbChamp("Pa_dtcrea", "date_formatee", "get_dtcrea", "set_dtcrea");
	$laListeChamps[]=new dbChamp("Pa_dtmod", "date_formatee", "get_dtmod", "set_dtmod");
	$laListeChamps[]=new dbChamp("Pa_statut", "entier", "get_statut", "set_statut");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_type() { return($this->type); }
function get_pa_inscrit() { return($this->pa_inscrit); }
function get_titre() { return($this->titre); }
function get_pa_quartier() { return($this->pa_quartier); }
function get_prenom() { return($this->prenom); }
function get_nom() { return($this->nom); }
function get_prenoms_enfants() { return($this->prenoms_enfants); }
function get_ages_enfants() { return($this->ages_enfants); }
function get_adresse() { return($this->adresse); }
function get_code_postal() { return($this->code_postal); }
function get_ville() { return($this->ville); }
function get_telephone() { return($this->telephone); }
function get_telephone_autre() { return($this->telephone_autre); }
function get_mail() { return($this->mail); }
function get_duree() { return($this->duree); }
function get_a_partir() { return($this->a_partir); }
function get_plage_horaire() { return($this->plage_horaire); }
function get_temps_de_travail() { return($this->temps_de_travail); }
function get_detail() { return($this->detail); }
function get_est_validee() { return($this->est_validee); }
function get_est_supprimee() { return($this->est_supprimee); }
function get_est_relance() { return($this->est_relance); }
function get_soumission() { return($this->soumission); }
function get_validation() { return($this->validation); }
function get_peremption() { return($this->peremption); }
function get_dtcrea() { return($this->dtcrea); }
function get_dtmod() { return($this->dtmod); }
function get_statut() { return($this->statut); }


// setters
function set_id($c_pa_id) { return($this->id=$c_pa_id); }
function set_type($c_pa_type) { return($this->type=$c_pa_type); }
function set_pa_inscrit($c_pa_pa_inscrit) { return($this->pa_inscrit=$c_pa_pa_inscrit); }
function set_titre($c_pa_titre) { return($this->titre=$c_pa_titre); }
function set_pa_quartier($c_pa_pa_quartier) { return($this->pa_quartier=$c_pa_pa_quartier); }
function set_prenom($c_pa_prenom) { return($this->prenom=$c_pa_prenom); }
function set_nom($c_pa_nom) { return($this->nom=$c_pa_nom); }
function set_prenoms_enfants($c_pa_prenoms_enfants) { return($this->prenoms_enfants=$c_pa_prenoms_enfants); }
function set_ages_enfants($c_pa_ages_enfants) { return($this->ages_enfants=$c_pa_ages_enfants); }
function set_adresse($c_pa_adresse) { return($this->adresse=$c_pa_adresse); }
function set_code_postal($c_pa_code_postal) { return($this->code_postal=$c_pa_code_postal); }
function set_ville($c_pa_ville) { return($this->ville=$c_pa_ville); }
function set_telephone($c_pa_telephone) { return($this->telephone=$c_pa_telephone); }
function set_telephone_autre($c_pa_telephone_autre) { return($this->telephone_autre=$c_pa_telephone_autre); }
function set_mail($c_pa_mail) { return($this->mail=$c_pa_mail); }
function set_duree($c_pa_duree) { return($this->duree=$c_pa_duree); }
function set_a_partir($c_pa_a_partir) { return($this->a_partir=$c_pa_a_partir); }
function set_plage_horaire($c_pa_plage_horaire) { return($this->plage_horaire=$c_pa_plage_horaire); }
function set_temps_de_travail($c_pa_temps_de_travail) { return($this->temps_de_travail=$c_pa_temps_de_travail); }
function set_detail($c_pa_detail) { return($this->detail=$c_pa_detail); }
function set_est_validee($c_pa_est_validee) { return($this->est_validee=$c_pa_est_validee); }
function set_est_supprimee($c_pa_est_supprimee) { return($this->est_supprimee=$c_pa_est_supprimee); }
function set_est_relance($c_pa_est_relance) { return($this->est_relance=$c_pa_est_relance); }
function set_soumission($c_pa_soumission) { return($this->soumission=$c_pa_soumission); }
function set_validation($c_pa_validation) { return($this->validation=$c_pa_validation); }
function set_peremption($c_pa_peremption) { return($this->peremption=$c_pa_peremption); }
function set_dtcrea($c_pa_dtcrea) { return($this->dtcrea=$c_pa_dtcrea); }
function set_dtmod($c_pa_dtmod) { return($this->dtmod=$c_pa_dtmod); }
function set_statut($c_pa_statut) { 
	if (intval($c_pa_statut)==1){
		$this->set_est_validee(0);
		$this->set_est_supprimee(0);	
	}
	elseif (intval($c_pa_statut)==4){
		$this->set_est_validee(1);
		$this->set_est_supprimee(0);	
	}
	elseif (intval($c_pa_statut)==5){
		$this->set_est_validee(1);
		$this->set_est_supprimee(1);	
	}
	return($this->statut=$c_pa_statut); 
}


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("pa_id"); }
// statut
function getGetterStatut() {return("get_statut"); }
function getFieldStatut() {return("pa_statut"); }
//
function getTable() { return("pa_annonce"); }
function getClasse() { return("pa_annonce"); }
function getDisplay() { return("titre"); }
function getAbstract() { return("type"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_annonce")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_annonce");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_annonce/list_pa_annonce.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_annonce/maj_pa_annonce.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_annonce/show_pa_annonce.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_annonce/rss_pa_annonce.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_annonce/xml_pa_annonce.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_annonce/xmlxls_pa_annonce.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_annonce/export_pa_annonce.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/pa_annonce/import_pa_annonce.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>