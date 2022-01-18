<?php 
 
							
	$bUseCriteres = 0;
	
	$oNews = $oRes;
	
	if ( $oNews->get_theme() != -1) {
		$themeNews = $oNews->get_theme();
		$oTheme = new news_theme($themeNews);
		
		if (method_exists($oTheme, 'get_abon_criteres')){
			$bUseCriteres = str_replace(array('Y', 'N'), array(1, 0), $oTheme->get_abon_criteres());
		}
		else{
			$bUseCriteres = 0;
		}
		if (method_exists($oTheme, 'get_abon_multiple')){
			$bUseMultiple = str_replace(array('Y', 'N'), array(1, 0), $oTheme->get_abon_multiple());
		}
		else{
			$bUseMultiple = 0;
		}
		if (method_exists($oTheme, 'get_allow_edit')){
			$bAllowEdit = str_replace(array('Y', 'N'), array(1, 0), $oTheme->get_allow_edit());
		}
		else{
			$bAllowEdit = 0;
		}
		
		if ($bUseCriteres == 1){
			$sBodyHTML = rewriteNewsletterBody($oNews->get_html(), 0, $id, $themeNews, $bUseCriteres, $bUseMultiple, $_SESSION['id_langue'], $oNews->get_libelle()); // langue du site
		}
		else{
			$sBodyHTML = $oNews->get_html();
		}
	}

		
	echo $sBodyHTML;	 
		

?>

