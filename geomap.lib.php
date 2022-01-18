<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

/**
 * Admin logs library
 *
 * PHP versions 4 > 5
 *
 * @category	library
 * @author	Luc Thibault <luc@suhali.net>
 *
 */


// getGeomapData
/**
 * Build XML list of geomaps or specific geomap structure when given a map ID
 *
 * @param	Int	$site		mini-site ID
 * @param	String	$render		map object substructure render class name
 * @param	Int	$map		map ID
 * @param	Boolean	$path		full path or not
 * @param	Boolean	$items		retrieve map items or not
 * @return	String
 */
function getGeomapData ($site, $render='', $map=null, $path=false, $items=false) {
	global $db;

	$data = '';
	$sql = "	SELECT	c.cms_gca_id as id,
			c.cms_gca_intitule as intitule,
			c.cms_gca_echelle as echelle,
			c.cms_gca_pivot as pivot,
			c.cms_gca_fichier as fichier
		FROM	`cms_geo_carte` c
		WHERE	c.cms_gca_id_site = {$site}
		AND	c.cms_gca_statut = 4
		";
	if ($map > 0)
		// the list render holds the current map ID
		$sql .= "AND	c.cms_gca_id = {$map}
		";
	$sql .= "ORDER BY	c.cms_gca_echelle ASC;";
	//echo $sql;


	$res = mysql_query($sql);
	while ($row = mysql_fetch_array($res)) {
		$data .= utf8_encode("\n\t<cms_geo_carte id=\"{$row['id']}\">");
		$data .= utf8_encode("\n\t\t<intitule><![CDATA[{$row['intitule']}]]></intitule>");
		$data .= utf8_encode("\n\t\t<echelle>{$row['echelle']}</echelle>");
		$data .= utf8_encode("\n\t\t<pivot>{$row['pivot']}</pivot>");
		$data .= utf8_encode("\n\t\t<fichier><![CDATA[".($path ? '/custom/upload/cms_geo_carte/' : '').$row['fichier']."]]></fichier>");
		if ($items && $render != '') {
			include_once('modules/geomap/class.'.$render.'.php');
			$maplist = new $render($row['id']);
			$data .= $maplist->render(false, $path);
		}
		$data .= "\n\t</cms_geo_carte>";
	}

	//return (String) "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?".">\n<root>{$data}\n</root>";
	return (String) "<?xml version=\"1.0\" encoding=\"ut"."f-8\" ?".">\n<root>{$data}\n</root>";
}


// getGeomapFiles
/**
 * Build an array of all declared map image files.
 *
 * @param	Int	$site		mini-site ID
 * @param	String	$render		map object substructure render class name
 * @param	Int	$map		map ID
 * @param	Boolean	$path		full path or not
 * @param	Boolean	$items		retrieve map items or not
 * @return	Array
 */
function getGeomapFiles ($site, $render='', $map=null, $path=false, $items=false) {
	global $db;

	$data = Array();
	$sql = "	SELECT	c.cms_gca_id as id,
			c.cms_gca_fichier as fichier
		FROM	`cms_geo_carte` c
		WHERE	c.cms_gca_id_site = {$site}
		AND	c.cms_gca_statut = 4
		";
	if ($map > 0)
		// the list render holds the current map ID
		$sql .= "AND	c.cms_gca_id = {$map}
		";
	$sql .= "ORDER BY	c.cms_gca_echelle ASC;";
	//echo $sql;

	$res = mysql_query($sql);
	while ($row = mysql_fetch_array($res)) {
		$data[] = ($path ? '/custom/upload/cms_geo_carte/' : '').$row['fichier'];
		if ($items && $render != '') {
			include_once('modules/geomap/class.'.$render.'.php');
			$maplist = new $render($row['id']);
			foreach ($maplist->render(true, $path) as $file)
				$data[] = $file;
		}
	}

	return (Array) $data;
}


?>
