<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


// Classe pour gérer les données sondage

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_avis.class.php');

// needs to extend BaseModuleModel
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleModel.php');


class OpinionModel extends BaseModuleModel {
	
//	var $types = Array(	'page'		=> 'cms_page',
//				'mod_news'	=> 'cms_news',
//				'mod_survey'	=> 'cms_survey_ask' );

	// constructor
	function OpinionModel () {}


	// handleComment
	/**
	 * Handle user comment submission
	 *
	 * @param	String		$type		The opinion attachment type
	 * @param	Int		$id		The attachment target ID
	 * @param	Array		$values		The submitted comment values
	 * @return	Bool	Recorded OK
	 */
	function handleComment ($type, $id, $values) {

		$comment = new cms_avis();
		$comment->set_type_reference($type);
		$comment->set_id_reference($id);
		foreach ($values as $name => $value) {
			$setter = "set_".$name;
			$comment->$setter($value);
		}
		$comment->set_statut(1);
		$comment->set_created(date("YYYY/mm/dd HH:ii:ss"));

		return dbInsertWithAutoKey($comment);
	}
	

	// getPublishedComments
	/**
	 * get moderated opinions for public display
	 *
	 * @param	String		$type		The opinion attachment type
	 * @param	Int		$id		The attachment target ID
	 * @return	Array	A list of moderated opinions
	 */
	function getPublishedComments ($type, $id) {

		global $db;

		$sql = "	SELECT	a.*
			FROM	cms_avis a
			WHERE	a.cms_avs_type_reference = '{$type}'
			AND	a.cms_avs_id_reference = {$id}
			AND	a.cms_avs_statut=4
			ORDER BY	a.cms_avs_created DESC;";
		//echo "test : ".$sql."<br/>";
		$rsAvis = $db->Execute($sql);
		$respile = Array();
		while (!$rsAvis->EOF) {
			$respile[] = $rsAvis->fields;

			$rsAvis->MoveNext();
		}

		return (Array) $respile;
	}


}

?>
