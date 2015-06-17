<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*======================================

objet de BDD cms_assoenquetequestion :: class cms_assoenquetequestion

SQL mySQL:

DROP TABLE IF EXISTS cms_assoenquetequestion;
CREATE TABLE cms_assoenquetequestion
(
	xsd_id			int (11) PRIMARY KEY not null,
	xsd_cms_enquete			int (11),
	xsd_cms_question			int (11),
	xsd_ordre			int (11)
)

SQL Oracle:

DROP TABLE cms_assoenquetequestion
CREATE TABLE cms_assoenquetequestion
(
	xsd_id			number (11) constraint xsd_pk PRIMARY KEY not null,
	xsd_cms_enquete			number (11),
	xsd_cms_question			number (11),
	xsd_ordre			number (11)
)


<class name="cms_assoenquetequestion" prefix="xsd" display="enquete" abstract="question" is_asso="true">
<item name="id" type="int" length="11" isprimary="true" notnull="true" default="-1" list="false"/>
<item name="cms_enquete" libelle="Enquete" type="int" length="11" default="-1" order="true"  fkey="cms_enquete"  list="true"/>
<item name="cms_question" libelle="Question" type="int" length="11" default="-1" order="true"  fkey="cms_question"  list="true"/>
<item name="ordre" libelle="Ordre" type="int" length="11" list="true" order="true" />
</class>


==========================================*/

class cms_assoenquetequestion
{
var $id;
var $cms_enquete;
var $cms_question;
var $ordre;


var $XML = "<class name=\"cms_assoenquetequestion\" prefix=\"xsd\" display=\"enquete\" abstract=\"question\" is_asso=\"true\">
<item name=\"id\" type=\"int\" length=\"11\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"false\"/>
<item name=\"cms_enquete\" libelle=\"Enquete\" type=\"int\" length=\"11\" default=\"-1\" order=\"true\"  fkey=\"cms_enquete\"  list=\"true\"/>
<item name=\"cms_question\" libelle=\"Question\" type=\"int\" length=\"11\" default=\"-1\" order=\"true\"  fkey=\"cms_question\"  list=\"true\"/>
<item name=\"ordre\" libelle=\"Ordre\" type=\"int\" length=\"11\" list=\"true\" order=\"true\" />
</class>";

var $sMySql = "CREATE TABLE cms_assoenquetequestion
(
	xsd_id			int (11) PRIMARY KEY not null,
	xsd_cms_enquete			int (11),
	xsd_cms_question			int (11),
	xsd_ordre			int (11)
)

";

// constructeur
function cms_assoenquetequestion($id=null)
{
	if (istable("cms_assoenquetequestion") == false){
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
		$this->cms_enquete = -1;
		$this->cms_question = -1;
		$this->ordre = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Xsd_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Xsd_cms_enquete", "entier", "get_cms_enquete", "set_cms_enquete");
	$laListeChamps[]=new dbChamp("Xsd_cms_question", "entier", "get_cms_question", "set_cms_question");
	$laListeChamps[]=new dbChamp("Xsd_ordre", "entier", "get_ordre", "set_ordre");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_cms_enquete() { return($this->cms_enquete); }
function get_cms_question() { return($this->cms_question); }
function get_ordre() { return($this->ordre); }


// setters
function set_id($c_xsd_id) { return($this->id=$c_xsd_id); }
function set_cms_enquete($c_xsd_cms_enquete) { return($this->cms_enquete=$c_xsd_cms_enquete); }
function set_cms_question($c_xsd_cms_question) { return($this->cms_question=$c_xsd_cms_question); }
function set_ordre($c_xsd_ordre) { return($this->ordre=$c_xsd_ordre); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("xsd_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_assoenquetequestion"); }
function getClasse() { return("cms_assoenquetequestion"); }
function getDisplay() { return("enquete"); }
function getAbstract() { return("question"); }


} //class


// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoenquetequestion")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoenquetequestion");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoenquetequestion/list_cms_assoenquetequestion.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoenquetequestion/maj_cms_assoenquetequestion.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoenquetequestion/show_cms_assoenquetequestion.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoenquetequestion/rss_cms_assoenquetequestion.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoenquetequestion/xml_cms_assoenquetequestion.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$xmlxls = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoenquetequestion/xmlxls_cms_assoenquetequestion.php", "w");
	$xmlxlsContent = "<"."?php include('cms-inc/autoClass/xmlxls.php'); ?".">";
	fwrite($xmlxls, $xmlxlsContent);
	fclose($xmlxls);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoenquetequestion/export_cms_assoenquetequestion.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms/cms_assoenquetequestion/import_cms_assoenquetequestion.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}
?>