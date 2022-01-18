<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (!ispatched('cms_pays')){
	$rs = $db->Execute('SHOW COLUMNS FROM `cms_pays`');
	if ($rs) {
		$names = Array();
		while(!$rs->EOF) {
			$names[] = $rs->fields["Field"];
			//pre_dump($rs->fields);
			$rs->MoveNext();
		
		}
		if (!in_array('cms_pay_coords', $names))	
			$rs = $db->Execute("ALTER TABLE `cms_pays` ADD `cms_pay_coords` VARCHAR( 128 ) NOT NULL AFTER `cms_pay_nom_fr`;");
	}
}

/*======================================

objet de BDD cms_pays :: class cms_pays

SQL mySQL:

DROP TABLE IF EXISTS cms_pays;
CREATE TABLE cms_pays
(
	cms_pay_id			int (11) PRIMARY KEY not null,
	cms_pay_nom_en			varchar (255),
	cms_pay_nom_fr			varchar (255),
	cms_pay_coords			varchar (128),
	cms_pay_ordre			int
)

SQL Oracle:

DROP TABLE cms_pays
CREATE TABLE cms_pays
(
	cms_pay_id			number (11) constraint pay_pk PRIMARY KEY not null,
	cms_pay_nom_en			varchar2 (255),
	cms_pay_nom_fr			varchar2 (255),
	cms_pay_coords			varchar2 (128),
	cms_pay_ordre			number
)


<?xml version="1.0" encoding="ISO-8859-1" ?>
<class name="cms_pays" prefix="cms_pay" display="nom_en" abstract="nom_fr">
<item name="id" type="int" length="3" isprimary="true" notnull="true" default="-1" list="true"  order="true" />
<item name="nom_en" type="varchar" length="255" list="true" order="true"  />
<item name="nom_fr" type="varchar" length="255" list="true" order="true"  /> 
<item name="coords" libelle="Coordonnées" type="varchar" length="128" option="geocoords" />
<item name="ordre" type="int" list="true" order="true" />
</class>



==========================================*/

class cms_pays
{
var $id;
var $nom_en;
var $nom_fr;
var $coords;
var $ordre;


var $XML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<class name=\"cms_pays\" prefix=\"cms_pay\" display=\"nom_en\" abstract=\"nom_fr\">
<item name=\"id\" type=\"int\" length=\"3\" isprimary=\"true\" notnull=\"true\" default=\"-1\" list=\"true\"  order=\"true\" />
<item name=\"nom_en\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  />
<item name=\"nom_fr\" type=\"varchar\" length=\"255\" list=\"true\" order=\"true\"  /> 
<item name=\"coords\" libelle=\"Coordonnées\" type=\"varchar\" length=\"128\" option=\"geocoords\" />
<item name=\"ordre\" type=\"int\" list=\"true\" order=\"true\" />
</class>
";

var $sMySql = "CREATE TABLE cms_pays
(
	cms_pay_id			int (11) PRIMARY KEY not null,
	cms_pay_nom_en			varchar (255),
	cms_pay_nom_fr			varchar (255),
	cms_pay_coords			varchar (128),
	cms_pay_ordre			int
)

";

// constructeur
function __construct($id=null)
{
	if (istable("cms_pays") == false){
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
		$this->nom_en = "";
		$this->nom_fr = "";
		$this->coords = "";
		$this->ordre = -1;
	}
}


// liste des champs de l'objet
function getListeChamps()
{
	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp
	$laListeChamps[]=new dbChamp("Cms_pay_id", "entier", "get_id", "set_id");
	$laListeChamps[]=new dbChamp("Cms_pay_nom_en", "text", "get_nom_en", "set_nom_en");
	$laListeChamps[]=new dbChamp("Cms_pay_nom_fr", "text", "get_nom_fr", "set_nom_fr");
	$laListeChamps[]=new dbChamp("Cms_pay_coords", "text", "get_coords", "set_coords");
	$laListeChamps[]=new dbChamp("Cms_pay_ordre", "entier", "get_ordre", "set_ordre");
	return($laListeChamps);
}


// getters
function get_id() { return($this->id); }
function get_nom_en() { return($this->nom_en); }
function get_nom_fr() { return($this->nom_fr); }
function get_coords() { return($this->coords); }
function get_ordre() { return($this->ordre); }


// setters
function set_id($c_cms_pay_id) { return($this->id=$c_cms_pay_id); }
function set_nom_en($c_cms_pay_nom_en) { return($this->nom_en=$c_cms_pay_nom_en); }
function set_nom_fr($c_cms_pay_nom_fr) { return($this->nom_fr=$c_cms_pay_nom_fr); }
function set_coords($c_cms_pay_coords) { return($this->coords=$c_cms_pay_coords); }
function set_ordre($c_cms_pay_ordre) { return($this->ordre=$c_cms_pay_ordre); }


// autres getters
function getGetterPK() { return("get_id"); }
function getSetterPK() { return("set_id"); }
function getFieldPK() { return("cms_pay_id"); }
// statut
function getGetterStatut() {return("none"); }
function getFieldStatut() {return("none"); }
//
function getTable() { return("cms_pays"); }
function getClasse() { return("cms_pays"); }
function getDisplay() { return("nom_en"); }
function getAbstract() { return("nom_fr"); }


} //class

function get_aPays_fr() {
	$sql = "SELECT * from cms_pays order by cms_pay_nom_fr";
	$aPays = dbGetObjectsFromRequete("cms_pays", $sql);  
	$aPays_FR = array();
	
	foreach ($aPays as $key => $oPays) {
		$aPays_FR[$oPays->get_id()] = $oPays->get_nom_fr();  
	}
	
	return $aPays_FR;	 
}

function get_aPays_en() {
	$sql = "SELECT * from cms_pays order by cms_pay_nom_en";
	$aPays = dbGetObjectsFromRequete("cms_pays", $sql);  
	$aPays_EN = array();
	
	foreach ($aPays as $key => $oPays) {
		$aPays_EN[$oPays->get_id()] = $oPays->get_nom_en();  
	}
	
	return $aPays_EN;	 
}
/*
// ecriture des fichiers
if (!is_dir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_pays")){
	mkdir($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_pays");
	$list = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_pays/list_cms_pays.php", "w");
	$listContent = "<"."?php include('cms-inc/autoClass/list.php'); ?".">";
	fwrite($list, $listContent);
	fclose($list);
	$maj = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_pays/maj_cms_pays.php", "w");
	$majContent = "<"."?php include('cms-inc/autoClass/maj.php'); ?".">";
	fwrite($maj, $majContent);
	fclose($maj);
	$show = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_pays/show_cms_pays.php", "w");
	$showContent = "<"."?php include('cms-inc/autoClass/show.php'); ?".">";
	fwrite($show, $showContent);
	fclose($show);
	$rss = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_pays/rss_cms_pays.php", "w");
	$rssContent = "<"."?php include('cms-inc/autoClass/rss.php'); ?".">";
	fwrite($rss, $rssContent);
	fclose($rss);
	$xml = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_pays/xml_cms_pays.php", "w");
	$xmlContent = "<"."?php include('cms-inc/autoClass/xml.php'); ?".">";
	fwrite($xml, $xmlContent);
	fclose($xml);
	$export = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_pays/export_cms_pays.php", "w");
	$exportContent = "<"."?php include('cms-inc/autoClass/export.php'); ?".">";
	fwrite($export, $exportContent);
	fclose($export);
	$import = fopen($_SERVER['DOCUMENT_ROOT']."/backoffice/cms_pays/import_cms_pays.php", "w");
	$importContent = "<"."?php include('cms-inc/autoClass/import.php'); ?".">";
	fwrite($import, $importContent);
	fclose($import);
}*/

/*
// struct table
CREATE TABLE cms_pays
(
	cms_pay_id			int (11) PRIMARY KEY not null,
	cms_pay_nom_en			varchar (255),
	cms_pay_nom_fr			varchar (255),
	cms_pay_ordre			int
);


INSERT INTO `cms_pays` VALUES (1, 'Afghanistan', 'Afghanistan', 10);
INSERT INTO `cms_pays` VALUES (2, 'Albania', 'Albanie', 20);
INSERT INTO `cms_pays` VALUES (3, 'Algeria', 'Algérie', 30);
INSERT INTO `cms_pays` VALUES (4, 'American samoa', 'Samoa américaines', 40);
INSERT INTO `cms_pays` VALUES (5, 'Andorra', 'Andorre', 50);
INSERT INTO `cms_pays` VALUES (6, 'Angola', 'Angola', 60);
INSERT INTO `cms_pays` VALUES (7, 'Anguilla', 'Anguilla', 70);
INSERT INTO `cms_pays` VALUES (8, 'Antarctica', 'Antarctique', 80);
INSERT INTO `cms_pays` VALUES (9, 'Antigua and barbuda', 'Antigua-et-Barbuda', 90);
INSERT INTO `cms_pays` VALUES (10, 'Argentina', 'Argentine', 100);
INSERT INTO `cms_pays` VALUES (11, 'Armenia', 'Arménie', 110);
INSERT INTO `cms_pays` VALUES (12, 'Aruba', 'Aruba', 120);
INSERT INTO `cms_pays` VALUES (13, 'Australia', 'Australie', 130);
INSERT INTO `cms_pays` VALUES (14, 'Austria', 'Autriche', 140);
INSERT INTO `cms_pays` VALUES (15, 'Azerbaijan', 'Azerbaïdjan', 150);
INSERT INTO `cms_pays` VALUES (16, 'Bahamas', 'Bahamas', 160);
INSERT INTO `cms_pays` VALUES (17, 'Bahrain', 'Bahreïn', 170);
INSERT INTO `cms_pays` VALUES (18, 'Bangladesh', 'Bangladesh', 180);
INSERT INTO `cms_pays` VALUES (19, 'Barbados', 'Barbade', 190);
INSERT INTO `cms_pays` VALUES (20, 'Belarus', 'Biélorussie', 200);
INSERT INTO `cms_pays` VALUES (21, 'Belgium', 'Belgique', 210);
INSERT INTO `cms_pays` VALUES (22, 'Belize', 'Belize', 220);
INSERT INTO `cms_pays` VALUES (23, 'Benin', 'Bénin', 230);
INSERT INTO `cms_pays` VALUES (24, 'Bermuda', 'Bermudes', 240);
INSERT INTO `cms_pays` VALUES (25, 'Bhutan', 'Bhoutan', 250);
INSERT INTO `cms_pays` VALUES (26, 'Bolivia', 'Bolivie', 260);
INSERT INTO `cms_pays` VALUES (27, 'Bosnia and herzegovina', 'Bosnie-Herzégovine', 270);
INSERT INTO `cms_pays` VALUES (28, 'Botswana', 'Botswana', 280);
INSERT INTO `cms_pays` VALUES (29, 'Bouvet island', 'Île Bouvet', 290);
INSERT INTO `cms_pays` VALUES (30, 'Brazil', 'Brésil', 300);
INSERT INTO `cms_pays` VALUES (31, 'British indian ocean territory', 'Territoire britannique de l''Océan Indien', 310);
INSERT INTO `cms_pays` VALUES (32, 'Brunei darussalam', 'Brunei darussalam', 320);
INSERT INTO `cms_pays` VALUES (33, 'Bulgaria', 'Bulgarie', 330);
INSERT INTO `cms_pays` VALUES (34, 'Burkina faso', 'Burkina faso', 320);
INSERT INTO `cms_pays` VALUES (35, 'Burundi', 'Burundi', 330);
INSERT INTO `cms_pays` VALUES (36, 'Cambodia', 'Cambodge', 340);
INSERT INTO `cms_pays` VALUES (37, 'Cameroon', 'Cameroun', 350);
INSERT INTO `cms_pays` VALUES (38, 'Canada', 'Canada', 360);
INSERT INTO `cms_pays` VALUES (39, 'Cape verde', 'Cap vert', 370);
INSERT INTO `cms_pays` VALUES (40, 'Cayman islands', 'Iles Caïmans', 380);
INSERT INTO `cms_pays` VALUES (41, 'Central african republic', 'République centrafricaine', 390);
INSERT INTO `cms_pays` VALUES (42, 'Chad', 'Tchad', 400);
INSERT INTO `cms_pays` VALUES (43, 'Chile', 'Chilie', 410);
INSERT INTO `cms_pays` VALUES (44, 'China', 'Chine', 420);
INSERT INTO `cms_pays` VALUES (45, 'Christmas island', 'Christmas Island', 430);
INSERT INTO `cms_pays` VALUES (46, 'Cocos (keeling) islands', 'Îles Cocos (Keeling)', 440);
INSERT INTO `cms_pays` VALUES (47, 'Colombia', 'Colombie', 450);
INSERT INTO `cms_pays` VALUES (48, 'Comoros', 'Comores', 460);
INSERT INTO `cms_pays` VALUES (49, 'Congo', 'Congo', 470);
INSERT INTO `cms_pays` VALUES (50, 'Congo, the democratic republic of the', 'La république démocratique du Congo', 480);
INSERT INTO `cms_pays` VALUES (51, 'Cook islands', 'Îles Cook', 490);
INSERT INTO `cms_pays` VALUES (52, 'Costa rica', 'Costa rica', 500);
INSERT INTO `cms_pays` VALUES (53, 'Ivory Coast', 'Cote d''ivoire', 510);
INSERT INTO `cms_pays` VALUES (54, 'Croatia', 'Croatie', 520);
INSERT INTO `cms_pays` VALUES (55, 'Cuba', 'Cuba', 530);
INSERT INTO `cms_pays` VALUES (56, 'Cyprus', 'Chypre', 540);
INSERT INTO `cms_pays` VALUES (57, 'Czech republic', 'République tchèque', 550);
INSERT INTO `cms_pays` VALUES (58, 'Denmark', 'Danemark', 560);
INSERT INTO `cms_pays` VALUES (59, 'Djibouti', 'Djibouti', 570);
INSERT INTO `cms_pays` VALUES (60, 'Dominica', 'Dominique', 580);
INSERT INTO `cms_pays` VALUES (61, 'Dominican republic', 'République dominicaine', 590);
INSERT INTO `cms_pays` VALUES (62, 'East timor', 'Timor oriental', 600);
INSERT INTO `cms_pays` VALUES (63, 'Ecuador', 'Équateur', 610);
INSERT INTO `cms_pays` VALUES (64, 'Egypt', 'Egypte', 620);
INSERT INTO `cms_pays` VALUES (65, 'El salvador', 'El salvador', 630);
INSERT INTO `cms_pays` VALUES (66, 'Equatorial guinea', 'Guinée équatoriale', 640);
INSERT INTO `cms_pays` VALUES (67, 'Eritrea', 'Érythrée', 650);
INSERT INTO `cms_pays` VALUES (68, 'Estonia', 'Estonie', 660);
INSERT INTO `cms_pays` VALUES (69, 'Ethiopia', 'Ethiopie', 670);
INSERT INTO `cms_pays` VALUES (70, 'Falkland islands (malvinas)', 'Îles Falkland (Malvinas)', 680);
INSERT INTO `cms_pays` VALUES (71, 'Faroe islands', 'Iles Féroé', 690);
INSERT INTO `cms_pays` VALUES (72, 'Fiji', 'Fidji', 700);
INSERT INTO `cms_pays` VALUES (73, 'Finland', 'Finlande', 710);
INSERT INTO `cms_pays` VALUES (74, 'France', 'France Métropolitaine', 720);
INSERT INTO `cms_pays` VALUES (75, 'French guiana', 'Guyane française', 730);
INSERT INTO `cms_pays` VALUES (76, 'French polynesia', 'Polynésie française', 740);
INSERT INTO `cms_pays` VALUES (77, 'French southern territories', 'Terres australes françaises', 750);
INSERT INTO `cms_pays` VALUES (78, 'Gabon', 'Gabon', 760);
INSERT INTO `cms_pays` VALUES (79, 'Gambia', 'Gambie', 770);
INSERT INTO `cms_pays` VALUES (80, 'Georgia', 'Géorgie', 780);
INSERT INTO `cms_pays` VALUES (81, 'Germany', 'Allemagne', 790);
INSERT INTO `cms_pays` VALUES (82, 'Ghana', 'Ghana', 800);
INSERT INTO `cms_pays` VALUES (83, 'Gibraltar', 'Gibraltar', 810);
INSERT INTO `cms_pays` VALUES (84, 'Greece', 'Grèce', 820);
INSERT INTO `cms_pays` VALUES (85, 'Greenland', 'Groenland', 830);
INSERT INTO `cms_pays` VALUES (86, 'Grenada', 'Grenade', 840);
INSERT INTO `cms_pays` VALUES (87, 'Guadeloupe', 'Guadeloupe', 850);
INSERT INTO `cms_pays` VALUES (88, 'Guam', 'Guam', 860);
INSERT INTO `cms_pays` VALUES (89, 'Guatemala', 'Guatemala', 870);
INSERT INTO `cms_pays` VALUES (90, 'Guinea', 'Guinée', 880);
INSERT INTO `cms_pays` VALUES (91, 'Guinea,bissau', 'Guinée, Bissau', 890);
INSERT INTO `cms_pays` VALUES (92, 'Guyana', 'Guyane', 900);
INSERT INTO `cms_pays` VALUES (93, 'Haiti', 'Haiti', 910);
INSERT INTO `cms_pays` VALUES (94, 'Heard island and mcdonald islands', 'L''île Heard et les îles McDonald', 920);
INSERT INTO `cms_pays` VALUES (95, 'Holy see (vatican city state)', 'Saint-Siège (Vatican ville-État)', 930);
INSERT INTO `cms_pays` VALUES (96, 'Honduras', 'Honduras', 940);
INSERT INTO `cms_pays` VALUES (97, 'Hong kong', 'Hong kong', 950);
INSERT INTO `cms_pays` VALUES (98, 'Hungary', 'Hongrie', 960);
INSERT INTO `cms_pays` VALUES (99, 'Iceland', 'Iceland', 970);
INSERT INTO `cms_pays` VALUES (100, 'India', 'Inde', 980);
INSERT INTO `cms_pays` VALUES (101, 'Indonesia', 'Indonésie', 990);
INSERT INTO `cms_pays` VALUES (102, 'Iran, islamic republic of', 'Iran, République islamique', 1000);
INSERT INTO `cms_pays` VALUES (103, 'Iraq', 'Iraq', 1010);
INSERT INTO `cms_pays` VALUES (104, 'Ireland', 'Ireland', 1020);
INSERT INTO `cms_pays` VALUES (105, 'Israel', 'Israel', 1030);
INSERT INTO `cms_pays` VALUES (106, 'Italy', 'Italie', 1040);
INSERT INTO `cms_pays` VALUES (107, 'Jamaica', 'Jamaique', 1050);
INSERT INTO `cms_pays` VALUES (108, 'Japan', 'Japon', 1060);
INSERT INTO `cms_pays` VALUES (109, 'Jordan', 'Jordanie', 1070);
INSERT INTO `cms_pays` VALUES (110, 'Kazakhstan', 'Kazakhstan', 1080);
INSERT INTO `cms_pays` VALUES (111, 'Kenya', 'Kenya', 1090);
INSERT INTO `cms_pays` VALUES (112, 'Kiribati', 'Kiribati', 1100);
INSERT INTO `cms_pays` VALUES (113, 'Korea', 'Corée', 1110);
INSERT INTO `cms_pays` VALUES (115, 'Kuwait', 'Koweït', 1130);
INSERT INTO `cms_pays` VALUES (116, 'Kyrgyzstan', 'Kirghizistan', 1140);
INSERT INTO `cms_pays` VALUES (117, 'Lao people''s democratic republic', 'Démocratique populaire Lao, République', 1150);
INSERT INTO `cms_pays` VALUES (118, 'Latvia', 'Lettonie', 1160);
INSERT INTO `cms_pays` VALUES (119, 'Lebanon', 'Liban', 1170);
INSERT INTO `cms_pays` VALUES (120, 'Lesotho', 'Lesotho', 1180);
INSERT INTO `cms_pays` VALUES (121, 'Liberia', 'Liberia', 1190);
INSERT INTO `cms_pays` VALUES (122, 'Libyan arab jamahiriya', 'Jamahiriya arabe libyenne', 1200);
INSERT INTO `cms_pays` VALUES (123, 'Liechtenstein', 'Liechtenstein', 1210);
INSERT INTO `cms_pays` VALUES (124, 'Lithuania', 'Lituanie', 1220);
INSERT INTO `cms_pays` VALUES (125, 'Luxembourg', 'Luxembourg', 1230);
INSERT INTO `cms_pays` VALUES (126, 'Macao', 'Macao', 1240);
INSERT INTO `cms_pays` VALUES (127, 'Macedonia, the former yugoslav republic of', 'Macédoine, ancienne république yougoslave', 1250);
INSERT INTO `cms_pays` VALUES (128, 'Madagascar', 'Madagascar', 1260);
INSERT INTO `cms_pays` VALUES (129, 'Malawi', 'Malawi', 1270);
INSERT INTO `cms_pays` VALUES (130, 'Malaysia', 'Malaisie', 1280);
INSERT INTO `cms_pays` VALUES (131, 'Maldives', 'Maldives', 1290);
INSERT INTO `cms_pays` VALUES (132, 'Mali', 'Mali', 1300);
INSERT INTO `cms_pays` VALUES (133, 'Malta', 'Malte', 1310);
INSERT INTO `cms_pays` VALUES (134, 'Marshall islands', 'Îles Marshall', 1320);
INSERT INTO `cms_pays` VALUES (135, 'Martinique', 'Martinique', 1330);
INSERT INTO `cms_pays` VALUES (136, 'Mauritania', 'Mauritanie', 1340);
INSERT INTO `cms_pays` VALUES (137, 'Mauritius', 'Maurice', 1350);
INSERT INTO `cms_pays` VALUES (138, 'Mayotte', 'Mayotte', 1360);
INSERT INTO `cms_pays` VALUES (139, 'Mexico', 'Mexique', 1370);
INSERT INTO `cms_pays` VALUES (140, 'Micronesia, federated states of', 'Micronésie, États fédérés de', 1380);
INSERT INTO `cms_pays` VALUES (141, 'Moldova, republic of', 'Moldova, République de', 1390);
INSERT INTO `cms_pays` VALUES (142, 'Monaco', 'Monaco', 1400);
INSERT INTO `cms_pays` VALUES (143, 'Mongolia', 'Mongolie', 1410);
INSERT INTO `cms_pays` VALUES (144, 'Montserrat', 'Montserrat', 1420);
INSERT INTO `cms_pays` VALUES (145, 'Morocco', 'Maroc', 1430);
INSERT INTO `cms_pays` VALUES (146, 'Mozambique', 'Mozambique', 1440);
INSERT INTO `cms_pays` VALUES (147, 'Myanmar', 'Myanmar', 1450);
INSERT INTO `cms_pays` VALUES (148, 'Namibia', 'Namibie', 1460);
INSERT INTO `cms_pays` VALUES (149, 'Nauru', 'Nauru', 1470);
INSERT INTO `cms_pays` VALUES (150, 'Nepal', 'Népal', 1480);
INSERT INTO `cms_pays` VALUES (151, 'Netherlands', 'Pays-Bas', 1490);
INSERT INTO `cms_pays` VALUES (152, 'Netherlands antilles', 'Antilles néerlandaises', 1500);
INSERT INTO `cms_pays` VALUES (153, 'New caledonia', 'Nouvelle Calédonie', 1510);
INSERT INTO `cms_pays` VALUES (154, 'New zealand', 'Nouvelle Zélande', 1520);
INSERT INTO `cms_pays` VALUES (155, 'Nicaragua', 'Nicaragua', 1530);
INSERT INTO `cms_pays` VALUES (156, 'Niger', 'Niger', 1540);
INSERT INTO `cms_pays` VALUES (157, 'Nigeria', 'Nigeria', 1550);
INSERT INTO `cms_pays` VALUES (158, 'Niue', 'Niue', 1560);
INSERT INTO `cms_pays` VALUES (159, 'Norfolk island', 'Norfolk island', 1570);
INSERT INTO `cms_pays` VALUES (160, 'Northern mariana islands', 'Îles Mariannes du Nord', 1580);
INSERT INTO `cms_pays` VALUES (161, 'Norway', 'Norvège', 1590);
INSERT INTO `cms_pays` VALUES (162, 'Oman', 'Oman', 1600);
INSERT INTO `cms_pays` VALUES (163, 'Pakistan', 'Pakistan', 1610);
INSERT INTO `cms_pays` VALUES (164, 'Palau', 'Palau', 1620);
INSERT INTO `cms_pays` VALUES (165, 'Palestinian territory, occupied', 'Territoire palestinien, occupé', 1630);
INSERT INTO `cms_pays` VALUES (166, 'Panama', 'Panama', 1640);
INSERT INTO `cms_pays` VALUES (167, 'Papua new guinea', 'Papouasie-nouvelle Guinée', 1650);
INSERT INTO `cms_pays` VALUES (168, 'Paraguay', 'Paraguay', 1660);
INSERT INTO `cms_pays` VALUES (169, 'Peru', 'Pérou', 1670);
INSERT INTO `cms_pays` VALUES (170, 'Philippines', 'Philippines', 1680);
INSERT INTO `cms_pays` VALUES (171, 'Pitcairn', 'Pitcairn', 1690);
INSERT INTO `cms_pays` VALUES (172, 'Poland', 'Pologne', 1700);
INSERT INTO `cms_pays` VALUES (173, 'Portugal', 'Portugal', 1710);
INSERT INTO `cms_pays` VALUES (174, 'Puerto rico', 'Puerto rico', 1720);
INSERT INTO `cms_pays` VALUES (175, 'Qatar', 'Qatar', 1730);
INSERT INTO `cms_pays` VALUES (176, 'Reunion', 'Réunion', 1740);
INSERT INTO `cms_pays` VALUES (177, 'Romania', 'Romanie', 1750);
INSERT INTO `cms_pays` VALUES (178, 'Russia', 'Russie', 1760);
INSERT INTO `cms_pays` VALUES (179, 'Rwanda', 'Rwanda', 1770);
INSERT INTO `cms_pays` VALUES (180, 'Saint helena', 'Saint Hélène', 1780);
INSERT INTO `cms_pays` VALUES (181, 'Saint kitts and nevis', 'Saint-Kitts-et-Nevis', 1790);
INSERT INTO `cms_pays` VALUES (182, 'Saint lucia', 'Sainte Lucie', 1800);
INSERT INTO `cms_pays` VALUES (183, 'Saint pierre and miquelon', 'Saint pierre etmiquelon', 1810);
INSERT INTO `cms_pays` VALUES (184, 'Saint vincent and the grenadines', 'Saint-Vincent-et-les Grenadines', 1820);
INSERT INTO `cms_pays` VALUES (185, 'Samoa', 'Samoa', 1830);
INSERT INTO `cms_pays` VALUES (186, 'San marino', 'San marino', 1840);
INSERT INTO `cms_pays` VALUES (187, 'Sao tome and principe', 'Sao Tome et Principe', 1850);
INSERT INTO `cms_pays` VALUES (188, 'Saudi arabia', 'Arabie Saoudite', 1860);
INSERT INTO `cms_pays` VALUES (189, 'Senegal', 'Sénégal', 1870);
INSERT INTO `cms_pays` VALUES (190, 'Seychelles', 'Seychelles', 1880);
INSERT INTO `cms_pays` VALUES (191, 'Sierra leone', 'Sierra leone', 1890);
INSERT INTO `cms_pays` VALUES (192, 'Singapore', 'Singapour', 1900);
INSERT INTO `cms_pays` VALUES (193, 'Slovakia', 'Slovaquie', 1910);
INSERT INTO `cms_pays` VALUES (194, 'Slovenia', 'Slovénie', 1920);
INSERT INTO `cms_pays` VALUES (195, 'Solomon islands', 'Iles Salomon', 1930);
INSERT INTO `cms_pays` VALUES (196, 'Somalia', 'Somalie', 1940);
INSERT INTO `cms_pays` VALUES (197, 'South africa', 'Afrique du Sud', 1950);
INSERT INTO `cms_pays` VALUES (198, 'South georgia', 'Géorgie du sud', 1960);
INSERT INTO `cms_pays` VALUES (199, 'Spain', 'Espagne', 1970);
INSERT INTO `cms_pays` VALUES (200, 'Sri lanka', 'Sri lanka', 1980);
INSERT INTO `cms_pays` VALUES (201, 'Sudan', 'Soudan', 1990);
INSERT INTO `cms_pays` VALUES (202, 'Suriname', 'Surinam', 2000);
INSERT INTO `cms_pays` VALUES (203, 'Svalbard and jan mayen', 'Svalbard et Jan Mayen', 2010);
INSERT INTO `cms_pays` VALUES (204, 'Swaziland', 'Swaziland', 2020);
INSERT INTO `cms_pays` VALUES (205, 'Sweden', 'Suède', 2030);
INSERT INTO `cms_pays` VALUES (206, 'Switzerland', 'Suisse', 2040);
INSERT INTO `cms_pays` VALUES (207, 'Syrian arab republic', 'République arabe syrienne', 2050);
INSERT INTO `cms_pays` VALUES (208, 'Taiwan, province of china', 'Taiwan, province de Chine', 2060);
INSERT INTO `cms_pays` VALUES (209, 'Tajikistan', 'Tajikistan', 2070);
INSERT INTO `cms_pays` VALUES (210, 'Tanzania, united republic of', 'Tanzanie, République Unie de', 2080);
INSERT INTO `cms_pays` VALUES (211, 'Thailand', 'Thailand', 2090);
INSERT INTO `cms_pays` VALUES (212, 'Togo', 'Togo', 2100);
INSERT INTO `cms_pays` VALUES (213, 'Tokelau', 'Tokelau', 2110);
INSERT INTO `cms_pays` VALUES (214, 'Tonga', 'Tonga', 2120);
INSERT INTO `cms_pays` VALUES (215, 'Trinidad and tobago', 'Trinité-et-Tobago', 2130);
INSERT INTO `cms_pays` VALUES (216, 'Tunisia', 'Tunisie', 2140);
INSERT INTO `cms_pays` VALUES (217, 'Turkey', 'Turquie', 2150);
INSERT INTO `cms_pays` VALUES (218, 'Turkmenistan', 'Turkménistan', 2160);
INSERT INTO `cms_pays` VALUES (219, 'Turks and caicos islands', 'Îles Turques et Caïques', 2170);
INSERT INTO `cms_pays` VALUES (220, 'Tuvalu', 'Tuvalu', 2180);
INSERT INTO `cms_pays` VALUES (221, 'Uganda', 'Ouganda', 2190);
INSERT INTO `cms_pays` VALUES (222, 'Ukraine', 'Ukraine', 2200);
INSERT INTO `cms_pays` VALUES (223, 'United arab emirates', 'Émirats arabes unis', 2210);
INSERT INTO `cms_pays` VALUES (226, 'United states minor outlying islands', 'Américaines du Pacifique', 2240);
INSERT INTO `cms_pays` VALUES (227, 'Uruguay', 'Uruguay', 2250);
INSERT INTO `cms_pays` VALUES (228, 'Uzbekistan', 'Ouzbékistan', 2260);
INSERT INTO `cms_pays` VALUES (229, 'Vanuatu', 'Vanuatu', 2270);
INSERT INTO `cms_pays` VALUES (230, 'Venezuela', 'Venezuela', 2280);
INSERT INTO `cms_pays` VALUES (231, 'Viet nam', 'Viêt-nam', 2290);
INSERT INTO `cms_pays` VALUES (232, 'Virgin islands, british', 'Îles Vierges britanniques', 2300);
INSERT INTO `cms_pays` VALUES (233, 'Virgin islands, u.s.', 'Îles Vierges, Etats-Unis', 2310);
INSERT INTO `cms_pays` VALUES (234, 'Wallis and futuna', 'Wallis et futuna', 2320);
INSERT INTO `cms_pays` VALUES (235, 'Western sahara', 'Sahara occidental', 2330);
INSERT INTO `cms_pays` VALUES (236, 'Yemen', 'Yémen', 2340);
INSERT INTO `cms_pays` VALUES (237, 'Yugoslavia', 'Yougoslavie', 2350);
INSERT INTO `cms_pays` VALUES (238, 'Zambia', 'Zambie', 2360);
INSERT INTO `cms_pays` VALUES (239, 'Zimbabwe', 'Zimbabwe', 2370);
INSERT INTO `cms_pays` VALUES (243, 'USA', 'Etats-Unis', 2255);
INSERT INTO `cms_pays` VALUES (247, 'Hawaii', 'Hawaii', 915);
INSERT INTO `cms_pays` VALUES (248, 'United kingdom', 'Royaume-Uni', 2220);
INSERT INTO `cms_pays` VALUES (254, 'Serbia and montenegro', 'Serbie-et-Monténégro', 1875);
INSERT INTO `cms_pays` VALUES (260, 'Dubai', 'Dubai', 595);

*/
?>