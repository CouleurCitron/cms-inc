<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


// Classe pour gérer les données sondage


include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/cms_survey_ask.class.php');

// needs to extend BaseModuleModel
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleModel.php');


class SurveyModel extends BaseModuleModel {

	// constructor
	function SurveyModel () {}


	// extract
	/**
	 * Extract survey data from database
	 *
	 * @param	Int		$id		The survey question ID
	 * @return	Array	Survey question and answers structure
	 */
	function extract ($id) {

		$oAsk = new cms_survey_ask($id);

		$sql = "	SELECT	*
			FROM	cms_survey_answer
			WHERE	cms_ask = ".$id;

		$aAnswer = dbGetObjectsFromRequete("cms_survey_answer", $sql);   
				
		$survey = Array(	'question'	=> Array(	'id'		=> $id,
								'title'		=> $oAsk->get_libelle(),
								'multiple'	=> ($oAsk->get_multiple() == 'Y' ? true : false)),
				'answers'	=> Array() );

		for ($i = 0; $i<newSizeOf($aAnswer);$i++)
			$survey['answers'][] = Array(	'id'	=> $aAnswer[$i]->get_id(),
							'title'	=> $aAnswer[$i]->get_libelle() );

		return (Array) $survey;

	}

	// handleVote
	/**
	 * Handle user vote
	 *
	 * @param	Int	$id		The survey question ID
	 * @return	Bool	Valid vote
	 */
	function handleVote ($id) {

		global $db;

		$valid_vote = true;

		$oAsk = new cms_survey_ask($id);
		$multiple = $oAsk->get_multiple() == 'Y' ? true : false;
	
		//viewArray($_POST, 'POST');
		$process = false;
		if ($multiple) {
			$multipile = Array();
			while (list($key, $val) = each($_POST)) {
				if (preg_match('/^answer_/', $key))
					$multipile[] = $val;
			}
			if (!empty($multipile))
				$process = true;
		} elseif ($_POST['answer'] > 0)
			$process = true;
	
			if ($process) {
			// Test si l'internaute à déjà répondu à la question
			$already = false;
			$track_val = '';
			if (MOD_SURVEY_TRACK_COOKIE) {
				include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/CookieManager.php');
				// Récupération du cookie du client
				$track_val = CookieManager::get('survey_'.$_SESSION['rep_travail'].'_'.$id);
			} elseif (MOD_SURVEY_TRACK_IP)
				// Récupération de l'adresse IP du client
				$track_val = $_SERVER['REMOTE_ADDR'];

			//echo "TRACK ? ".$track_val."<br/>";
			if ($track_val != '') {
				// contrôle IP ou cookie pour CE sondage
				$sql = "	SELECT	cms_survey_reponse.cms_id
					FROM	cms_survey_reponse,
						cms_survey_answer
					WHERE	cms_survey_reponse.cms_ip='{$track_val}'
					AND	cms_survey_reponse.cms_answer=cms_survey_answer.cms_id
					AND	cms_survey_answer.cms_ask={$id};";
				//echo $sql;
				$res = $db->Execute($sql);
				if (!$res->EOF)
					$already = true;
			}
	
			if (!$already) {
				if (MOD_SURVEY_TRACK_COOKIE) {
					ob_clean();
					include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/CookieManager.php');
					$id_cookie = 'survey_'.$_SESSION['rep_travail'].'_'.$id;
					$track_val = md5(date('Y/m/d_H-m-s').rand(0, 10000));
					$expire = time()+60*60*24*365*10;
					CookieManager::set($id_cookie, $track_val, $expire, '/');
				} elseif (MOD_SURVEY_TRACK_IP)
					$track_val = $_SERVER['REMOTE_ADDR'];
				else	$track_val = '';
	
				if ($multiple) {
					foreach ($multipile as $id_answer) {
						$answer = new cms_survey_reponse();
						$answer->set_ip($track_val);
						$answer->set_answer($id_answer);
						$answer->set_date(date("d/m/Y"));
						$bRetour = dbInsertWithAutoKey($answer);
					}
				} else {
					$answer = new cms_survey_reponse();
					$answer->set_ip($track_val);
					$answer->set_answer($_POST['answer']);
					$answer->set_date(date("d/m/Y"));
					$bRetour = dbInsertWithAutoKey($answer);
				}
			} else {
				$valid_vote = false;
			}
		}
		return $valid_vote;
	}
	

	// extract
	/**
	 * get last 10 questions with their results
	 *
	 * @param	Int	$ask_id		The current survey question ID
	 * @param	Bool	$voted		User just voted to the current survey
	 * @return	Array	A list of survey questions and results
	 */
	function getLastVotes ($ask_id, $voted) {

		global $db;

		if ($voted || empty($ask_id)) {
			// select current survey
			$sql = "	SELECT		q.*,
						a.cms_id as id_answer,
						a.cms_libelle as libelle_answer
				FROM		cms_survey_ask q
				LEFT JOIN	cms_survey_answer a
				ON		a.cms_ask = q.cms_id
				WHERE		q.cms_id_site=".$_SESSION['idSite']."
				ORDER BY		q.cms_dateadd DESC,
						a.cms_dateadd ASC
				LIMIT		10;";
		
		} else	$sql = "	SELECT		q.*,
						a.cms_id as id_answer,
						a.cms_libelle as libelle_answer
				FROM		cms_survey_ask q
				LEFT JOIN	cms_survey_answer a
				ON		a.cms_ask = q.cms_id
				WHERE		q.cms_id=".$ask_id."
				AND		q.cms_id_site=".$_SESSION['idSite']."
				ORDER BY		a.cms_dateadd ASC;";
		//echo "test : ".$sql."<br/>";
		$rsAsk = $db->Execute($sql);
		$askpile = Array();
		$track = 0;
		$cnt = -1;
		while (!$rsAsk->EOF) {
			if ($rsAsk->fields['cms_id'] != $track) {
				$cnt++;
				$track = $rsAsk->fields['cms_id'];
				$askpile[$cnt] = Array();
				$askpile[$cnt]['id'] = $track;
				$askpile[$cnt]['title'] = $rsAsk->fields['cms_libelle'];
				$askpile[$cnt]['multiple'] = $rsAsk->fields['cms_multiple'] == 'Y' ? true : false;
				$askpile[$cnt]['answers'] = Array();
			}
			$askpile[$cnt]['answers'][$rsAsk->fields['id_answer']] = Array( 'title'	=> $rsAsk->fields['libelle_answer'] );
			$rsAsk->MoveNext();
		}

		foreach ($askpile as $index => $ask) {
			$sql = "	SELECT	count(r.cms_ip) AS cnt,
					r.cms_id,
					r.cms_ip,
					r.cms_answer
				FROM	cms_survey_reponse r 
				WHERE	cms_answer IN (".implode(',', array_keys($ask['answers'])).")
				GROUP BY	cms_ip;";
			//echo  'sql : '.$sql.'<br/>';
			$rsCnt = $db->Execute($sql);
			$cnt = 0;
			$total = 0;
			while (!$rsCnt->EOF) {
				$cnt++;
				$total += $rsCnt->fields['cnt'];
				$rsCnt->MoveNext();
			}
			$askpile[$index]['votes'] = $cnt;
			$askpile[$index]['total'] = $total;
		
			// Calculate votes for each answer
			$sql = "	SELECT	count(cms_id) as cnt,
					cms_answer
				FROM	cms_survey_reponse 
				WHERE	cms_answer IN (".implode(',', array_keys($ask['answers'])).")
				GROUP BY cms_answer;";
			$rsVote = $db->Execute($sql);
		
			while (!$rsVote->EOF) {
				$askpile[$index]['answers'][$rsVote->fields['cms_answer']]['votes'] = $rsVote->fields['cnt'];
				$rsVote->MoveNext();
			}
		}
		
		//viewArray($askpile, 'ask');

		return (Array) $askpile;
	}


}

?>
