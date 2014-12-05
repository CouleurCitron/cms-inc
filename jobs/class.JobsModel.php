<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


class JobsModel {
    
	// constructor
	function JobsModel () {}


	// getDomains
	/**
	 * Get Domain list
	 *
	 * @param	Int		$_domaine	Selected domaine
	 * @return	Array	Domaines list
	 */
	function getDomains ($_domaine=null) {

            if( !defined("DEF_APP_REF_TSL") ){
		if (DEF_APP_USE_TRANSLATIONS && DEF_APP_LANGUE != $_SESSION['id_langue'])
			$sql = "	SELECT	dom.*
				FROM	job_domaine dom,
					cms_chaine_traduite tsltd
				WHERE	tsltd.cms_ctd_id_reference = dom.job_libelle
				AND	tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}
				AND	job_statut = ".DEF_ID_STATUT_LIGNE."
				ORDER BY	tsltd.cms_ctd_chaine ASC;";
		else	$sql = "	SELECT	dom.*
				FROM	job_domaine dom,
					cms_chaine_reference tsl
				WHERE	tsl.cms_crf_id = dom.job_libelle
				AND	job_statut = ".DEF_ID_STATUT_LIGNE."
				ORDER BY	tsl.cms_crf_chaine ASC;";
                } else if(defined( "DEF_APP_REF_TSL" ) && DEF_APP_REF_TSL == "MD5" ){
                    if (DEF_APP_USE_TRANSLATIONS && DEF_APP_LANGUE != $_SESSION['id_langue'])
			$sql = "	SELECT	dom.*
				FROM	job_domaine dom,
					cms_chaine_traduite tsltd,
                                        cms_chaine_reference tsl
				WHERE	tsl.cms_crf_md5 = dom.job_libelle
                                AND     tsltd.cms_ctd_id_reference = tsl.cms_crf_id
				AND	tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}
				AND	job_statut = ".DEF_ID_STATUT_LIGNE."
				ORDER BY	tsltd.cms_ctd_chaine ASC;";
		else	$sql = "	SELECT	dom.*
				FROM	job_domaine dom,
					cms_chaine_reference tsl
				WHERE	tsl.cms_crf_md5 = dom.job_libelle
				AND	job_statut = ".DEF_ID_STATUT_LIGNE."
				ORDER BY	tsl.cms_crf_chaine ASC;";
                }

//		$sql = "	SELECT	*
//			FROM	job_domaine
//			ORDER BY	job_ordre;";

		if ($this->debug)
			echo "JobsModel::getDomains > {$sql}<br/>";
		$aDomains = dbGetObjectsFromRequete("job_domaine", $sql);
		
		$list = Array();
		for ($i=0; $i<sizeof($aDomains); $i++){
			$oDomain = $aDomains[$i];
			$list[] = Array(	'id'		=> $oDomain->get_id(),
					'selected'	=> ($_type == $oDomain->get_id() ? true : false),
					'name'		=> $oDomain->get_libelle() );
		}
		return (Array) $list;
	}


	// getQualifications
	/**
	 * Get Qualification list
	 *
	 * @param	Int		$_qualification	Selected qualification
	 * @return	Array	Qualifications list
	 */
	function getQualifications ($_qualification=null) {

		$sql = "	SELECT	*
			FROM	job_qualification
			WHERE	job_statut = ".DEF_ID_STATUT_LIGNE."
			ORDER BY	job_ordre;";

		if ($this->debug)
			echo "JobsModel::getQualifications > {$sql}<br/>";
		$aQualifications = dbGetObjectsFromRequete("job_qualification", $sql);
		
		$list = Array();
		for ($i=0; $i<sizeof($aQualifications); $i++){
			$oQualification = $aQualifications[$i];
			$list[] = Array(	'id'		=> $oQualification->get_id(),
					'selected'	=> ($_type == $oQualification->get_id() ? true : false),
					'name'		=> $oQualification->get_libelle() );
		}
		return (Array) $list;
	}


	// getTypes
	/**
	 * Get Type list
	 *
	 * @param	String		$_type		The type
	 * @return	Array	Types list
	 */
	function getTypes ($_type=null) {

            if( !defined("DEF_APP_REF_TSL") ){
		if (DEF_APP_USE_TRANSLATIONS && DEF_APP_LANGUE != $_SESSION['id_langue'])
			$sql = "	SELECT	ctr.*
				FROM	job_contrat ctr,
					cms_chaine_traduite tsltd
				WHERE	tsltd.cms_ctd_id_reference = ctr.job_libelle
				AND	tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}
				AND	job_statut = ".DEF_ID_STATUT_LIGNE."
				ORDER BY	tsltd.cms_ctd_chaine ASC;";
		else	$sql = "	SELECT	ctr.*
				FROM	job_contrat ctr,
					cms_chaine_reference tsl
				WHERE	tsl.cms_crf_id = ctr.job_libelle
				AND	job_statut = ".DEF_ID_STATUT_LIGNE."
				ORDER BY	tsl.cms_crf_chaine ASC;";
                
            } else if(defined( "DEF_APP_REF_TSL" ) && DEF_APP_REF_TSL == "MD5" ){
                
                if (DEF_APP_USE_TRANSLATIONS && DEF_APP_LANGUE != $_SESSION['id_langue'])
			$sql = "	SELECT	ctr.*
				FROM	job_contrat ctr,
					cms_chaine_traduite tsltd,
                                        cms_chaine_reference tsl
				WHERE	tsl.cms_crf_md5 = ctr.job_libelle
                                AND     tsltd.cms_ctd_id_reference = tsl.cms_crf_id
				AND	tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}
				AND	job_statut = ".DEF_ID_STATUT_LIGNE."
				ORDER BY	tsltd.cms_ctd_chaine ASC;";
		else	$sql = "	SELECT	ctr.*
				FROM	job_contrat ctr,
					cms_chaine_reference tsl
				WHERE	tsl.cms_crf_md5 = ctr.job_libelle
				AND	job_statut = ".DEF_ID_STATUT_LIGNE."
				ORDER BY	tsl.cms_crf_chaine ASC;";
                    
            }

		if ($this->debug)
			echo "JobsModel::getTypes > {$sql}<br/>";
		$aTypes = dbGetObjectsFromRequete("job_contrat", $sql);
		
		$list = Array();
		for ($i=0; $i<sizeof($aTypes); $i++){
			$oType = $aTypes[$i];
			$list[] = Array(	'id'		=> $oType->get_id(),
					'selected'	=> ($_type == $oType->get_id() ? true : false),
					'name'		=> $oType->get_libelle() );
		}
		return (Array) $list;
	}


	// getExperiences
	/**
	 * Get Experience list
	 *
	 * @param	String		$_experience		The type
	 * @return	Array	Experiences list
	 */
	function getExperiences ($_experience=null) {

		$sql = "	SELECT	*
			FROM	job_experience
			WHERE	job_statut = ".DEF_ID_STATUT_LIGNE."
			ORDER BY	job_ordre;";

		if ($this->debug)
			echo "JobsModel::getExpriences > {$sql}<br/>";
		$aExperiences = dbGetObjectsFromRequete("job_rubrique", $sql);
		
		$list = Array();
		for ($i=0; $i<sizeof($aExperiences); $i++){
			$oExperience = $aExperiences[$i];
			$list[] = Array(	'id'		=> $oExperience->get_id(),
					'selected'	=> ($_experience == $oExperience->get_id() ? true : false),
					'name'		=> $oExperience->get_libelle() );
		}
		return (Array) $list;
	}


	// getFunctions
	/**
	 * Get Functions list
	 *
	 * @param	String/Array	$_function		The current function(s)
	 * @return	Array	Functions list
	 */
	function getFunctions ($_function) {
                if( !defined("DEF_APP_REF_TSL") ){
                    
                    if (DEF_APP_USE_TRANSLATIONS && DEF_APP_LANGUE != $_SESSION['id_langue'])
                            $sql = "	SELECT	met.*
                                    FROM	job_metier met,
                                            cms_chaine_traduite tsltd
                                    WHERE	tsltd.cms_ctd_id_reference = met.job_libelle
                                    AND	tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}
                                    AND	job_statut = ".DEF_ID_STATUT_LIGNE."
                                    ORDER BY	tsltd.cms_ctd_chaine ASC;";
                    else	$sql = "	SELECT	met.*
                                    FROM	job_metier met,
                                            cms_chaine_reference tsl
                                    WHERE	tsl.cms_crf_id = met.job_libelle
                                    AND	job_statut = ".DEF_ID_STATUT_LIGNE."
                                    ORDER BY	tsl.cms_crf_chaine ASC;";
                } else if(defined( "DEF_APP_REF_TSL" ) && DEF_APP_REF_TSL == "MD5" ){
                    
                    if (DEF_APP_USE_TRANSLATIONS && DEF_APP_LANGUE != $_SESSION['id_langue'])
                            $sql = "	SELECT	met.*
                                    FROM	job_metier met,
                                            cms_chaine_traduite tsltd,
                                            cms_chaine_reference tsl
                                    WHERE   tsl.cms_crf_md5 = met.job_libelle
                                    AND     tsltd.cms_ctd_id_reference = tsl.cms_crf_id
                                    AND     tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}
                                    AND     job_statut = ".DEF_ID_STATUT_LIGNE."
                                    ORDER BY	tsltd.cms_ctd_chaine ASC;";
                    else	$sql = "	SELECT	met.*
                                    FROM	job_metier met,
                                            cms_chaine_reference tsl
                                    WHERE	tsl.cms_crf_md5 = met.job_libelle
                                    AND	job_statut = ".DEF_ID_STATUT_LIGNE."
                                    ORDER BY	tsl.cms_crf_chaine ASC;";
                    
                }
		if ($this->debug)
			echo "JobsModel::getFunctions > {$sql}<br/>";
		$aFunctions = dbGetObjectsFromRequete("job_metier", $sql);
		
		$list = Array();
		for ($i=0; $i<sizeof($aFunctions); $i++){
			$oFunction = $aFunctions[$i];
			$list[] = Array(	'id'		=> $oFunction->get_id(),
					'selected'	=> ((is_array($_function) && in_array($oFunction->get_id(), array_keys($_function)) || $_function == $oFunction->get_id()) ? true : false),
					'name'		=> $oFunction->get_libelle() );
		}
		return (Array) $list;
	}


	// getPlaces
	/**
	 * Get Places list
	 *
	 * @param	String/Array	$_place		The current place(s)
	 * @return	Array	Sites list
	 */
	function getPlaces ($_place) {

            if( !defined("DEF_APP_REF_TSL") ){
            
		if (DEF_APP_USE_TRANSLATIONS && DEF_APP_LANGUE != $_SESSION['id_langue'])
			$sql = "	SELECT	lie.*
				FROM	job_lieu lie,
					cms_pays pay,
					cms_chaine_traduite tsltd
				WHERE	tsltd.cms_ctd_id_reference = lie.job_libelle
				AND	pay.cms_pay_id = lie.job_pays
				AND	tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}
				AND	job_statut = ".DEF_ID_STATUT_LIGNE."
				ORDER BY	tsltd.cms_ctd_chaine ASC;";
		else	$sql = "	SELECT	lie.*
				FROM	job_lieu lie,
					cms_pays pay,
					cms_chaine_reference tsl
				WHERE	tsl.cms_crf_id = lie.job_libelle
				AND	pay.cms_pay_id = lie.job_pays
				AND	job_statut = ".DEF_ID_STATUT_LIGNE."
				ORDER BY	tsl.cms_crf_chaine ASC;";
                
            } else if(defined( "DEF_APP_REF_TSL" ) && DEF_APP_REF_TSL == "MD5" ){
                if (DEF_APP_USE_TRANSLATIONS && DEF_APP_LANGUE != $_SESSION['id_langue'])
			$sql = "	SELECT	lie.*
				FROM	job_lieu lie,
					cms_pays pay,
					cms_chaine_traduite tsltd,
                                        cms_chaine_reference tsl
				WHERE	tsl.cms_crf_md5 = lie.job_libelle
                                AND     tsltd.cms_ctd_id_reference = tsl.cms_crf_id
				AND	pay.cms_pay_id = lie.job_pays
				AND	tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}
				AND	job_statut = ".DEF_ID_STATUT_LIGNE."
				ORDER BY	tsltd.cms_ctd_chaine ASC;";
		else	$sql = "	SELECT	lie.*
				FROM	job_lieu lie,
					cms_pays pay,
					cms_chaine_reference tsl
				WHERE	tsl.cms_crf_md5 = lie.job_libelle
				AND	pay.cms_pay_id = lie.job_pays
				AND	job_statut = ".DEF_ID_STATUT_LIGNE."
				ORDER BY	tsl.cms_crf_chaine ASC;";
            }
		if ($this->debug)
			echo "JobsModel::getPlaces > {$sql}<br/>";
		$aPlaces = dbGetObjectsFromRequete("job_lieu", $sql);
		
		$list = Array();
		for ($i=0; $i<sizeof($aPlaces); $i++){
			$oPlace = $aPlaces[$i];
			$list[] = Array(	'id'		=> $oPlace->get_id(),
					'selected'	=> ((is_array($_place) && in_array($oPlace->get_id(), array_keys($_place)) || $_place == $oPlace->get_id()) ? true : false),
					'name'		=> $oPlace->get_libelle() );
		}
		return (Array) $list;
	}
        


	// getLanguages
	/**
	 * Get spoken languages list
	 *
	 * @return	Array	Languages list
	 */
	function getLanguages () {
                if( !defined("DEF_APP_REF_TSL") ){
                    if (DEF_APP_USE_TRANSLATIONS && DEF_APP_LANGUE != $_SESSION['id_langue'])
                            $sql = "	SELECT	lg.*
                                    FROM	job_langue lg,
                                            cms_chaine_traduite tsltd
                                    WHERE	tsltd.cms_ctd_id_reference = lg.job_libelle
                                    AND	tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}
                                    AND	job_statut = ".DEF_ID_STATUT_LIGNE."
                                    ORDER BY	tsltd.cms_ctd_chaine ASC;";
                    else	$sql = "	SELECT	*
                                    FROM	job_langue lg,
                                            cms_chaine_reference tsl
                                    WHERE	tsl.cms_crf_id = lg.job_libelle
                                    AND	job_statut = ".DEF_ID_STATUT_LIGNE."
                                    ORDER BY	tsl.cms_crf_chaine ASC;";
                } else if(defined( "DEF_APP_REF_TSL" ) && DEF_APP_REF_TSL == "MD5" ){
                    
                    if (DEF_APP_USE_TRANSLATIONS && DEF_APP_LANGUE != $_SESSION['id_langue'])
                            $sql = "	SELECT	lg.*
                                    FROM    job_langue lg,
                                            cms_chaine_traduite tsltd,
                                            cms_chaine_reference tsl
                                    WHERE   tsl.cms_crf_md5 = lg.job_libelle
                                    AnD     tsltd.cms_ctd_id_reference = tsl.cms_crf_id
                                    AND	tsltd.cms_ctd_id_langue = {$_SESSION['id_langue']}
                                    AND	job_statut = ".DEF_ID_STATUT_LIGNE."
                                    ORDER BY	tsltd.cms_ctd_chaine ASC;";
                    else	$sql = "	SELECT	*
                                    FROM	job_langue lg,
                                            cms_chaine_reference tsl
                                    WHERE	tsl.cms_crf_md5 = lg.job_libelle
                                    AND	job_statut = ".DEF_ID_STATUT_LIGNE."
                                    ORDER BY	tsl.cms_crf_chaine ASC;";
                    
                }
		if ($this->debug)
			echo "JobsModel::getLanguages > {$sql}<br/>";
		$aLanguages = dbGetObjectsFromRequete("job_langue", $sql);
		
		$list = Array();
		for ($i=0; $i<sizeof($aLanguages); $i++){
			$oLanguage = $aLanguages[$i];
			$list[] = Array(	'id'		=> $oLanguage->get_id(),
					'name'		=> $oLanguage->get_libelle() );
		}
		return (Array) $list;
	}


	// getLangLevels
	/**
	 * Get spoken languages levels list
	 *
	 * @return	Array	Language levels list
	 */
	function getLangLevels () {

		$sql = "	SELECT	*
			FROM	job_niveaulangue
			WHERE	job_statut = ".DEF_ID_STATUT_LIGNE."
			ORDER BY	job_ordre;";

		if ($this->debug)
			echo "JobsModel::getLangLevels > {$sql}<br/>";
		$aLangLevels = dbGetObjectsFromRequete("job_niveaulangue", $sql);
		
		$list = Array();
		for ($i=0; $i<sizeof($aLangLevels); $i++){
			$oLevel = $aLangLevels[$i];
			$list[] = Array(	'id'		=> $oLevel->get_id(),
					'name'		=> $oLevel->get_libelle() );
		}
		return (Array) $list;
	}


	// getOffers
	/**
	 * Get Offers list
	 *
	 * @param	String		$_type		The contract type filter value
	 * @param	String		$_place		The place filter value
	 * @param	String		$_function	The function filter value
	 * @param	String		$_experience	The experience filter value
	 * @param	String		$_text		The open text filter value
	 * @param	String		$_ref		The reference filter value
	 * @param	String		$_published	The publication date filter value
	 * @param	String		$_start		The start date filter value
	 * @return	Array		Offers list
	 */
	function getOffers ($_type, $_place, $_function, $_experience, $_text, $_ref, $_published, $_start) {
		if ($this->debug)
			echo 'getOffers ($_type='.$_type.', $_place='.$_place.', $_function='.$_function.', $_experience='.$_experience.', $_text='.$_text.', $_ref='.$_ref.', $_published='.$_published.', $_start='.$_start.') <br />';
		//construction de la requete
		$sql = "	SELECT	*
			FROM	job_offre
			WHERE	job_statut=".DEF_ID_STATUT_LIGNE."
			";
		if ($_type != "-1" || $_place != "-1" || $_function != "-1" || $_experience != "-1" || $_text != "" || $_ref != "") {
		
			// Should publication end date be restrictive ?
			if (!DEF_JOBS_DIS_PUB_END_DATE)
				$sql .+ "AND	job_date_pub_fin >= NOW()
				";
			if ($_place != "-1" && $_place != "")
				$sql.= "AND	job_lieu={$_place}
				";
			if ($_type != "-1" && $_type != "")
				$sql.= "AND	job_contrat={$_type}
				";
			if ($_function != "-1" && $_function != "")
				$sql.= "AND	job_metier={$_function}
				";
			if ($_experience != "-1" && $_experience != "")
				$sql.= "AND	job_experience={$_experience}
				";
			if ($_ref != "")
				$sql.= "AND	job_reference LIKE '%{$_ref}%'
				";
			if (!empty($_published))
				//$sql .= "AND	TIMESTAMPDIFF(DAY, job_date_pub_debut, NOW()) < {$_published}
				//";
				$sql .= "AND	TIMESTAMPDIFF(DAY, job_date_pub_debut, NOW()) = 0
				";
			if ($_start != '')
				$sql .= "AND	job_date_debut = '{$_start}'
				";
			if ($_text != "") {
				$aTxt = explode(' ', $_text);
				$aCondTLS = Array();
				$sqlTLS = 'SELECT DISTINCT * FROM cms_chaine_reference LEFT OUTER JOIN cms_chaine_traduite ON cms_chaine_traduite.cms_ctd_id_reference = cms_chaine_reference.cms_crf_id WHERE ';
				foreach ($aTxt as $txt) {
					if ($txt != '') {
						$aCondTLS[] = " cms_crf_chaine LIKE '%{$txt}%' ";
						$aCondTLS[] = " cms_ctd_chaine LIKE '%{$txt}%'";
					}
				}
				$sqlTLS.= "(".implode(' OR ', $aCondTLS).")";
				$aCacheIdTLS_ref =  array();
				$aObjects = dbGetObjectsFromRequete('cms_chaine_reference', $sqlTLS);	
				if (!empty($aObjects) > 0) {
                                    
                                    if(!defined( "DEF_APP_REF_TSL" )){
                                        foreach ($aObjects as $oObject){
						$aCacheIdTLS_ref[] = $oObject->get_id();
                                        }
					$in_select_ref = implode(",", $aCacheIdTLS_ref);
                                    } else if(defined( "DEF_APP_REF_TSL" ) && DEF_APP_REF_TSL == "MD5" ){
                                        foreach ($aObjects as $oObject){
						$aCacheIdTLS_ref[] = $oObject->get_md5();
                                        }
					$in_select_ref = "'".implode("','", $aCacheIdTLS_ref)."'";
                                    }
                                    
                                    
					
                                        
                                        
                                        
                                        
                                        
                                        
                                        
				}
				$sql .= " AND job_libelle IN ({$in_select_ref}) ";
			}
			$sql.= "ORDER BY	job_date_pub_debut DESC;"; 
			
		} else {
			if (!empty($_published))
				//$sql .= "AND	TIMESTAMPDIFF(DAY, job_date_pub_debut, NOW()) < {$_published}
				//";
				$sql .= "AND	TIMESTAMPDIFF(DAY, job_date_pub_debut, NOW()) = 0
				";
			if (!DEF_JOBS_DIS_PUB_END_DATE)
				$sql .= "AND	job_date_pub_fin >= NOW()
				";
			$sql .= "ORDER BY	job_date_pub_debut DESC;"; 
		}
		if ($this->debug)
			echo "JobsModel::getOffers > {$sql}<br/>";
		$aOffres = dbGetObjectsFromRequete("job_offre", $sql);
		
		$list = Array();
		for ($i=0; $i<sizeof($aOffres); $i++){
			$oOffre = $aOffres[$i];
                        
                        
			$tmp = Array(	'id'	=> $oOffre->get_id(),
					'detail'	=> $oOffre->get_detail() );
			if ($oOffre->get_reference() != "")
				$tmp['reference'] = $oOffre->get_reference();
			if ($oOffre->get_libelle() != "")
				$tmp['libelle'] = $oOffre->get_libelle();
			if ($oOffre->get_contrat() != "" && $oOffre->get_contrat() != -1) {
				$contrat = new job_contrat($oOffre->get_contrat());
				$tmp['type'] = $contrat->get_libelle();
			}
			if ($oOffre->get_lieu() != "" && $oOffre->get_lieu() != -1) {
				$lieu = new job_lieu($oOffre->get_lieu());
				$tmp['lieu'] = $lieu->get_libelle();
			}
			if ($oOffre->get_date_pub_debut() != "")
				$tmp['publication'] = $oOffre->get_date_pub_debut();
			if ($oOffre->get_date_debut() != "")
				$tmp['debut'] = $oOffre->get_date_debut();
			if ($oOffre->get_experience() != "")
				$tmp['experience'] = $oOffre->get_experience();
			if ($oOffre->get_remuneration() != "")
				$tmp['remuneration'] = $oOffre->get_remuneration();
			if ($oOffre->get_qualification() != -1) {
				//$oQ = new Qualification($oOffre->get_qualification()); 
                                $oQ = new job_qualification($oOffre->get_qualification()); 
                                
                                if (!isset($translator)){
                                        $translator =& TslManager::getInstance(); 
                                }
                                
				$tmp['qualification'] = $translator->getByID($oQ->get_libelle(), $idSite);
			}
                        
                        
			$list[] = $tmp;
		}
                
		return (Array) $list;
	}


	// getOffer
	/**
	 * Get Offer
	 *
	 * @param	Int		$_id	Selected offer
	 * @return	Array	Offer properties
	 */
	function getOffer ($_id) {

		$offer = new job_offre($_id);
		$data = Array(	'id'		=> $_id,
				'bo_users'	=> $offer->get_bo_users(),
				'reference'	=> $offer->get_reference(),
				'libelle'	=> $offer->get_libelle(),
				'entreprise'	=> $offer->get_entreprise(),
				'detail'		=> $offer->get_detail(),
				'profil'		=> $offer->get_profil(),
				'statut'		=> $offer->get_statut(),
				'date_pub_debut'	=> $offer->get_date_pub_debut(),
				'date_pub_fin'	=> $offer->get_date_pub_fin(),
				'date_debut'	=> $offer->get_date_debut(),
				'remuneration'	=> $offer->get_remuneration() );

		if ($offer->get_contrat() > 0) {
			$contrat = new job_contrat($offer->get_contrat());
			$data['type'] = Array(	'id'		=> $contrat->get_id(),
						'libelle'	=> $contrat->get_libelle() );
		}
		if ($offer->get_domaine() > 0) {
			$domain = new job_domaine($offer->get_domaine());
			$data['domaine'] = Array(	'id'		=> $domain->get_id(),
						'libelle'	=> $domain->get_libelle() );
		}
		if ($offer->get_metier() > 0) {
			$metier = new job_metier($offer->get_metier());
			$data['metier'] = Array(	'id'		=> $metier->get_id(),
						'libelle'	=> $metier->get_libelle() );
		}
		if ($offer->get_lieu() > 0) {
			$place = new job_lieu($offer->get_lieu());
			$data['lieu'] = Array(	'id'		=> $place->get_id(),
						'libelle'	=> $place->get_libelle() );
		}
		if ($offer->get_experience() > 0) {
			$experience = new job_experience($offer->get_experience());
			$data['experience'] = Array(	'id'		=> $experience->get_id(),
							'libelle'	=> $experience->get_libelle() );
		}

		return $data;
	}


	// getCandidate
	/**
	 * Get Candidate
	 *
	 * @param	Int		$_id	Candidate ID
	 * @return	Array	Offer properties
	 */
	function getCandidate ($_id) {

		$candidate = new shp_client($_id);
		if ($candidate->get_langue() > 0) {
			$lang = new cms_langue($candidate->get_langue());
			$language = Array(	'id'	=> $lang->get_id(),
						'code'	=> $lang->get_libellecourt() );
		} else 	$language = Array();
		$data = Array(	'id'		=> $_id,
				'langue'		=> $language,
				'anonyme'	=> $candidate->get_anonyme(),
				'gender'		=> $candidate->get_civilite(),
				'nom'		=> $candidate->get_nom(),
				'prenom'		=> $candidate->get_prenom(),
				'email'		=> $candidate->get_email(),
				'tel'		=> $candidate->get_tel(),
				'portable'	=> $candidate->get_portable(),
				'adresse_1'	=> $candidate->get_adresse_1(),
				'adresse_2'	=> $candidate->get_adresse_2(),
				'adresse_3'	=> $candidate->get_adresse_3(),
				'ville'		=> $candidate->get_ville(),
				'cp'		=> $candidate->get_cp(),
				'pays'		=> $candidate->get_pays(),
				'naissance'	=> $candidate->get_naissance(),
				'nationalite'	=> $candidate->get_nationalite(),
				'situation'	=> $candidate->get_situation(),
				'salaire'	=> $candidate->get_salaire(),
				'experience'	=> $candidate->get_experience(),
				'contrat'	=> $candidate->get_contrat(),
				'parcours'	=> $candidate->get_parcours(),
				'competences'	=> $candidate->get_competences(),
				'interets'	=> $candidate->get_interets(),
				'fichier_cv'	=> $candidate->get_fichier_cv(),
				'formations'	=> $this->getCandidateAbilities($_id),
				'langues'	=> $this->getCandidateLanguages($_id),
				'metiers'	=> $this->getCandidateFunctions($_id),
				'sites'		=> $this->getCandidatePlaces($_id) );

		return $data;
	}

	
	// getCandidateAbilities
	/**
	 * Get Candidate Abilities
	 *
	 * @param	Int		$_id	Candidate ID
	 * @return	Array	list of Candidate abilities
	 */
	function getCandidateAbilities ($_id) {

		global $db;

		$data = Array();
		$sql = "	SELECT	cq.job_id as asso_id,
				d.job_id as domaine_id,
				d.job_libelle as domaine_libelle,
				q.job_id as qualif_id,
				q.job_libelle as qualif_libelle,
				cq.job_diplome as qualif_diplome,
				cq.job_ecole as qualif_ecole,
				cq.job_annee as qualif_annee
			FROM	job_domaine d,
				job_qualification q,
				job_assocandidatqualification cq
			WHERE	cq.job_candidat = {$_id}
			AND	cq.job_domaine = d.job_id
			AND	cq.job_qualification = q.job_id;";
		
		if ($this->debug)
			echo "JobsModel::getCandidateAbilities > {$sql}<br/>";
		$rs = $db->Execute($sql);
		if ($rs) {
			while (!$rs->EOF) {
				$data[] = Array(	'asso_id'	=> $rs->fields['asso_id'],
						'domaine_id'	=> $rs->fields['domaine_id'],
						'domaine_name'	=> $rs->fields['domaine_libelle'],
						'qualif_id'	=> $rs->fields['qualif_id'],
						'qualif_name'	=> $rs->fields['qualif_libelle'],
						'qualif_diplome'	=> $rs->fields['qualif_diplome'],
						'qualif_ecole'	=> $rs->fields['qualif_ecole'],
						'qualif_annee'	=> $rs->fields['qualif_annee'] );
				$rs->MoveNext();
			}
		}

		return $data;
	}


	// getCandidateLanguages
	/**
	 * Get Candidate Languages
	 *
	 * @param	Int		$_id	Candidate ID
	 * @return	Array	list of Candidate languages
	 */
	function getCandidateLanguages ($_id) {

		global $db;

		$data = Array();
		$sql = "	SELECT	cl.job_id as asso_id,
				l.job_id as langue_id,
				l.job_libelle as langue_libelle,
				n.job_id as niveau_id,
				n.job_libelle as niveau_libelle
			FROM	job_langue l,
				job_niveaulangue n,
				job_assocandidatlangue cl
			WHERE	cl.job_candidat = {$_id}
			AND	cl.job_langue = l.job_id
			AND	cl.job_niveaulangue = n.job_id;";
		if ($this->debug)
			echo "JobsModel::getCandidateLanguages > {$sql}<br/>";
		$rs = $db->Execute($sql);
		if ($rs) {
			while (!$rs->EOF) {
				$data[] = Array(	'asso_id'	=> $rs->fields['asso_id'],
						'langue_id'	=> $rs->fields['langue_id'],
						'langue_name'	=> $rs->fields['langue_libelle'],
						'niveau_id'	=> $rs->fields['niveau_id'],
						'niveau_name'	=> $rs->fields['niveau_libelle'] );
				$rs->MoveNext();
			}
		}

		return $data;
	}


	// getCandidateFunctions
	/**
	 * Get Candidate Functions
	 *
	 * @param	Int		$_id	Candidate ID
	 * @return	Array	list of Candidate functions
	 */
	function getCandidateFunctions ($_id) {

		global $db;

		$data = Array();
		$sql = "	SELECT	m.job_id as metier_id,
				m.job_libelle as metier_libelle
			FROM	job_metier m,
				job_assocandidatmetier cm
			WHERE	cm.job_candidat = {$_id}
			AND	cm.job_metier = m.job_id;";
		if ($this->debug)
			echo "JobsModel::getCandidateFunctions > {$sql}<br/>";
		$rs = $db->Execute($sql);
		if ($rs) {
			while (!$rs->EOF) {
				$data[$rs->fields['metier_id']] = $rs->fields['metier_libelle'];
				$rs->MoveNext();
			}
		}

		return $data;
	}


	// getCandidatePlaces
	/**
	 * Get Candidate Places
	 *
	 * @param	Int		$_id	Candidate ID
	 * @return	Array	list of Candidate places
	 */
	function getCandidatePlaces ($_id) {

		global $db;

		$data = Array();
		$sql = "	SELECT	l.job_id as lieu_id,
				l.job_libelle as lieu_libelle
			FROM	job_lieu l,
				job_assocandidatlieu cl
			WHERE	cl.job_candidat = {$_id}
			AND	cl.job_lieu = l.job_id;";
		if ($this->debug)
			echo "JobsModel::getCandidatePlaces > {$sql}<br/>";
		$rs = $db->Execute($sql);
		if ($rs) {
			while (!$rs->EOF) {
				$data[$rs->fields['lieu_id']] = $rs->fields['lieu_libelle'];
				$rs->MoveNext();
			}
		}

		return $data;
	}


	// createCandidateAbility
	/**
	 * Create a new candidate ability in a domain
	 *
	 * @param	Array		$a_ability		Ability domain and level properties
	 * @return	Object		Created ability DAO
	 */
	function createCandidateAbility ($a_ability) {
		
		$ability = new job_assocandidatqualification();
		foreach ($a_ability as $champ => $value) {
			//echo $champ." ".$value."<br />";
			$setter = 'set_'.$champ;
			$ability->$setter($value);
		}
		
		if (dbInsertWithAutoKey($ability)) {
			if ($this->debug)
				echo "JobsModel.updateCandidateAbility > Candidate Ability was created : ".$ability->get_id()."<br/>";
			return $ability;
		} else	return null;
	}

	
	// updateCandidateAbility
	/**
	 * Update candidate ability in a domain
	 *
	 * @param	Array		$a_ability		Ability domain and level properties
	 * @param	Object		$ability			Given target Ability
	 * @return	Object		Updated ability DAO
	 */
	function updateCandidateAbility ($a_ability, $ability=null) {
		
		if ($ability == null)
			$ability = new job_assocandidatqualification($a_ability['id']);
		foreach ($a_ability as $champ => $value) {
			$setter = 'set_'.$champ;
			$ability->$setter($value);
		}
		if (dbUpdate($ability)) {
			if ($this->debug)
				echo "JobsModel.updateCandidateAbility > Candidate Ability was updated : ".$ability->get_id()."<br/>";
			return $ability;
		} else	return false;
	}


	// deleteCandidateAbility
	/**
	 * Delete an existing candidate ability
	 *
	 * @param	Array		$a_values		Ability properties
	 * @return	Bool		Success
	 */
	function deleteCandidateAbility ($a_values) {
		return dbDeleteWhere('job_assocandidatqualification', $a_values);
	}

	
	// createCandidateLanguage
	/**
	 * Create a new candidate ability in a domain
	 *
	 * @param	Array		$a_language		Language properties
	 * @return	Object		Created language DAO
	 */
	function createCandidateLanguage ($a_language) {
		
		$language = new job_assocandidatlangue();
		foreach ($a_language as $champ => $value) {
			//echo $champ." ".$value."<br />";
			$setter = 'set_'.$champ;
			$language->$setter($value);
		}
		
		if (dbInsertWithAutoKey($language)) {
			if ($this->debug)
				echo "JobsModel.createCandidateLanguage > Candidate Language was created : ".$language->get_id()."<br/>";
			return $language;
		} else	return null;
	}

	
	// updateCandidateLanguage
	/**
	 * Update candidate ability in a domain
	 *
	 * @param	Array		$a_language		Ability domain and level properties
	 * @param	Object		$language		Given target Language
	 * @return	Object		Updated ability DAO
	 */
	function updateCandidateLanguage ($a_language, $language=null) {
		
		if ($language == null)
			$language = new job_assocandidatlangue($a_language['id']);
		foreach ($a_language as $champ => $value) {
			$setter = 'set_'.$champ;
			$language->$setter($value);
		}
		if (dbUpdate($language)) {
			if ($this->debug)
				echo "JobsModel.updateCandidateLanguage > Candidate Language was updated : ".$language->get_id()."<br/>";
			return $language;
		} else	return false;
	}


	// deleteCandidateLanguage
	/**
	 * Delete an existing candidate ability
	 *
	 * @param	Array		$a_values		Ability properties
	 * @return	Bool		Success
	 */
	function deleteCandidateLanguage ($a_values) {
		return dbDeleteWhere('job_assocandidatlangue', $a_values);
	}

	
	// createCandidateFunction
	/**
	 * Create a new candidate function
	 *
	 * @param	Array		$a_function		Function properties
	 * @return	Object		Created function DAO
	 */
	function createCandidateFunction ($a_function) {
		
		$function = new job_assocandidatmetier();
		foreach ($a_function as $champ => $value) {
			//echo $champ." ".$value."<br />";
			$setter = 'set_'.$champ;
			$function->$setter($value);
		}
		
		if (dbInsertWithAutoKey($function)) {
			if ($this->debug)
				echo "JobsModel.createCandidateFunction > Candidate Function was created : ".$function->get_id()."<br/>";
			return $function;
		} else	return null;
	}


	// deleteCandidateFunction
	/**
	 * Delete an existing candidate function
	 *
	 * @param	Array		$a_values		Function properties
	 * @return	Bool		Success
	 */
	function deleteCandidateFunction ($a_values) {
		return dbDeleteWhere('job_assocandidatmetier', $a_values);
	}

	
	// createCandidatePlace
	/**
	 * Create a new candidate place
	 *
	 * @param	Array		$a_place		Place properties
	 * @return	Object		Created place DAO
	 */
	function createCandidatePlace ($a_place) {
		
		$place = new job_assocandidatlieu();
		foreach ($a_place as $champ => $value) {
			//echo $champ." ".$value."<br />";
			$setter = 'set_'.$champ;
			$place->$setter($value);
		}
		
		if (dbInsertWithAutoKey($place)) {
			if ($this->debug)
				echo "JobsModel.createCandidatePlace > Candidate Place was created : ".$place->get_id()."<br/>";
			return $place;
		} else	return null;
	}

	
	// deleteCandidatePlace
	/**
	 * Delete an existing candidate place
	 *
	 * @param	Array		$a_values		Place properties
	 * @return	Bool		Success
	 */
	function deleteCandidatePlace ($a_values) {
		return dbDeleteWhere('job_assocandidatlieu', $a_values);
	}

	
	// createApplication
	/**
	 * Create a new job application
	 *
	 * @param	Array		$a_application	Application properties
	 * @return	Object		Created application DAO
	 */
	function createApplication ($a_application) {
		
		$application = new job_candidature ();
		foreach ($a_application as $champ => $value) {
			//echo $champ." ".$value."<br />";
			$setter = 'set_'.$champ;
			$application->$setter($value);
		}
		
		if (dbInsertWithAutoKey($application))
			return $application;
		else	return null;
	}

	
	// checkAlreadyApplied
	/**
	 * Did candidate already applied to the given offer ?
	 *
	 * @param	Int		$_candidate_id	Candidate ID
	 * @param	Int		$_offer_id	Offer ID
	 * @return	Boolean		Candidate already applied
	 */
	function checkAlreadyApplied($_candidate_id, $_offer_id) {
		
		if (getCount_where('job_candidature', Array('job_candidat', 'job_offre'), Array($_candidate_id, $_offer_id), Array('int', 'int')) > 0)
			return true;
		else	return false;
	}


	// countCandidateApplications
	/**
	 * Get number of applications for the given candidate
	 *
	 * @param	Int		$_candidate_id	Candidate ID
	 * @return	Int		Created application DAO
	 */
	function countCandidateApplications ($_candidate_id) {
		
		//return getCount_where('job_candidature', Array('job_candidat'), Array($_candidate_id), Array('int'));
		global $db;

		$sql = "	SELECT	cdt.job_id
			FROM	job_candidature cdt
			WHERE	cdt.job_candidat = {$_candidate_id}
			AND	cdt.job_offre > 0;";
		if ($this->debug)
			echo "JobsModel::countCandidateApplications > {$sql}<br/>";
		$rs = $db->Execute($sql);
		$cnt = 0;
		if ($rs) {
			while (!$rs->EOF) {
				$cnt++;
				$rs->MoveNext();
			}
		}

		return $cnt;
	}


	// updateApplicationStatus
	/**
	 * Set Appllication status
	 *
	 * @param	String		$_application_id		Application ID
	 * @param	Object		$_status			Desired status
	 * @return	Object		Updated application DAO
	 */
	function updateApplicationStatus ($_application_id, $_status) {
		
		$application = new job_candidature($_application_id);
		$application->set_statut($_status);
		if (dbUpdate($application)) {
			if ($this->debug)
				echo "JobsModel.updateApplicationStatus > Application status was updated : ".$application->get_id()." : ".$_status."<br/>";
			// Log status change
			logObjectStatusChange($application);
			return $application;
		} else	return false;
	}


	// getOfferRecipients
	/**
	 * Get administrator recipients for 
	 *
	 * @param	Int		$_offer		The current offer ID
	 * @return	Array	Sites list
	 */
	function getOfferRecipients ($_offer) {

		$sql = "	SELECT	dest.*
			FROM	job_destinataire dest,
				job_assooffredestinataire asso
			WHERE	asso.job_offre = {$_offer}
			AND	asso.job_destinataire = dest.job_id;";
		 
		if ($this->debug)
			echo "JobsModel::getOfferRecipients > {$sql}<br/>";
		$aRecipients = dbGetObjectsFromRequete("job_destinataire", $sql);
		
		$list = Array();
		for ($i=0; $i<sizeof($aRecipients); $i++)
			$list[] = $aRecipients[$i]->get_mail();
		return (Array) $list;
	}


	// getReplies
	/**
	 * Get Type list
	 *
	 * @return	Array	Types list
	 */
	function getReplies () {

		$sql = "	SELECT	*
			FROM	job_lettre
			WHERE	job_statut = ".DEF_ID_STATUT_LIGNE.";";
			//ORDER BY	sstype_libelle;";

		if ($this->debug)
			echo "JobsModel::getReplies > {$sql}<br/>";
		$aReplies = dbGetObjectsFromRequete("job_lettre", $sql);
		
		$list = Array();
		for ($i=0; $i<sizeof($aReplies); $i++){
			$oReply = $aReplies[$i];
			$list[] = Array(	'id'		=> $oReply->get_id(),
					'type'		=> $oReply->get_type(),
					'title'		=> $oReply->get_titre(),
					'short'		=> $oReply->get_resume(),
					'long'		=> $oReply->get_texte() );
		}
		return (Array) $list;
	}


	// createResponse
	/**
	 * Create a new job application response to a candidate
	 *
	 * @param	Array		$a_response	Response properties
	 * @return	Object		Created response DAO
	 */
	function createResponse ($a_response) {
		
		$response = new job_reponse ();
		foreach ($a_response as $champ => $value) {
			//echo $champ." ".$value."<br />";
			$setter = 'set_'.$champ;
			$response->$setter($value);
		}
		
		if (dbInsertWithAutoKey($response))
			return $response;
		else	return null;
	}

	// createNewsSubscription
	/**
	 * Create a new Newsleter subscription with given criteria
	 *
	 * @param	Array		$a_criteria		Subscription criteria properties
	 * @return	Object		Created criteria DAO
	 */
	function createNewsSubscription ($a_criteria) {
		
		$criteria = new news_criteres();
		foreach ($a_criteria as $champ => $value) {
			//echo $champ." ".$value."<br />";
			$setter = 'set_'.$champ;
			$criteria->$setter($value);
		}
		
		if (dbInsertWithAutoKey($criteria)) {
			if ($this->debug)
				echo "JobsModel.createNewsSubscription > Candidate Subcription Criteria were created : ".$criteria->get_id()."<br/>";
			return $language;
		} else	return null;
	}

	
	

}

?>
