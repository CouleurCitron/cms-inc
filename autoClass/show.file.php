<?php
 
if ($eKeyValue != "") {
	$inherited = getCorrectInheritedClass($oRes->inherited_list, $aNodeToSort[$i]["attrs"]["NAME"]);
	if (!is_null($inherited))
		$correctName = $inherited->getClasse();
	else	$correctName = $classeName; 
	  
	/*if (is_file($_SERVER['DOCUMENT_ROOT'].'custom/upload/'.$correctName.'/'.$eKeyValue))
		$aFiles=array($eKeyValue);
	elseif (count(explode(';',$eKeyValue))>0)
		$aFiles=explode(';',$eKeyValue);		*/					
	
	echo '&nbsp;(actuellement)';							
	
	/*foreach($aFiles as $kFile => $sFile){
		echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$correctName."/".$sFile."\" title=\"Visualiser le fichier : '".$sFile."'\">".$sFile."</a>";
		echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$correctName."/".$sFile."\" title=\"TÚlÚcharger le fichier : '".$sFile."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"TÚlÚcharger le fichier : '".$sFile."\" /></a>";
	}
	echo ")\n";*/
	
	
	preg_match_all("/{([^{}].*?)}/", $eKeyValue, $matches);
	  
	$allFiles = array();
	
	if (sizeof($matches[1]) == 0) {
		$allFiles[] = $eKeyValue;
	}
	else {
		$allFiles = $matches[1];
	}
	
	
	foreach ($allFiles as $nbimg => $eKeyValue) {	
	 
							
		$aFiles = explode(';', $eKeyValue); 
		$img = 0;	
		
		 
		
		for($if=0;$if<1;$if++){
		
			$sFile = $aFiles[$if];
			$sFile = preg_replace ("/\[.*\]/", "", $sFile) ;  // on supprime la zone commentaires entre crochets
			
			if (is_file($_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$correctName.'/'.$sFile)){
			
			
				echo "<div id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_".$nbimg."\">"; 
				if ($if == 0)  echo "&nbsp;<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$correctName."/".$sFile."\" target=\"_blank\" title=\"".$translator->getTransByCode('visualiserlefichier')." '".$sFile."'\"><img src='/custom/upload/".$correctName."/".$sFile."' width='70' /></a>&nbsp; ";
				echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$correctName."/".$sFile."\" target=\"_blank\" title=\"".$translator->getTransByCode('visualiserlefichier')." '".$sFile."'\">".$sFile."</a>\n";
				
				echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$correctName."/".$sFile."\" title=\"".$translator->getTransByCode('telechargerlefichier')." '".$sFile."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"".$translator->getTransByCode('telechargerlefichier')." '".$sFile."\" /></a>\n";
				echo '<input type="hidden" id="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_'.$nbimg.'_name" name="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_'.$nbimg.'_name"  value="'.$nbimg."_".$sFile.'" />';
				
				$img++;
				
				echo "</div>"; 
				
				
			}
		}
	 	
	
		if ($img == 0) {
			if (is_file($_SERVER['DOCUMENT_ROOT'].''.$eKeyValue)){
				$namefile = basename($eKeyValue);
				
				echo "<a href=\"/backoffice/cms/utils/viewer.php?file=".$eKeyValue."\" target=\"_blank\" title=\"".$translator->getTransByCode('visualiserlefichier')." '".$namefile."'\">".$namefile."</a>\n";
				echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=".$eKeyValue."\" title=\"".$translator->getTransByCode('telechargerlefichier')." '".$namefile."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"".$translator->getTransByCode('telechargerlefichier')." '".$namefile."\" /></a>\n";  
				
			}
			
		}
		 	
		echo "<br />\n";
	}	 
	
	
	
	
	
	
} else	echo "&nbsp;(pas de fichier)<br />";

/*
						echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$correctName."/".$eKeyValue."\" title=\"Visualiser le fichier : '".$eKeyValue."'\">".$eKeyValue."</a>\n";
						echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$correctName."/".$eKeyValue."\" title=\"TÚlÚcharger le fichier : '".$eKeyValue."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"TÚlÚcharger le fichier : '".$eKeyValue."\" /></a>\n";*/




?>