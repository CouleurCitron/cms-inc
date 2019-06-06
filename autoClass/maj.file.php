<?php

$indexUpload++;

// champs multiple

$tempMultiple = (isset($aNodeToSort[$i]["attrs"]["MULTIPLE"])	&& $aNodeToSort[$i]["attrs"]["MULTIPLE"] == 'true') ? "true" : "false"; 

$classMultiple= "";
if($tempMultiple == "true"){
    $classMultiple = "multiple";
} else {
    $classMultiple = "single";
}


echo "<div id=\"div".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" class=\"file_show $classMultiple'\">\n";
echo "<!-- upload field # ".$indexUpload."/".$numUploadFields." -->\n";
echo "<input type=\"hidden\" id=\"fUpload".$indexUpload."\" name=\"fUpload".$indexUpload."\" value=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" />\n";

// Affichage du champ de type FILE	


if (defined("DEF_FCK_VERSION") && DEF_FCK_VERSION == "ckeditor" ) {
    if( !defined( 'DEF_FILEMANAGER' ) || DEF_FILEMANAGER == 'filemanager-master' ){
        $url_filemanager = "/backoffice/cms/lib/ckeditor/Filemanager-master/index.php?dir=/custom/upload/".$classeName."/&langCode=".$_SESSION["site_langue"]."&";
    } else {
    	$param = '';
    	if($classMultiple = "multiple") {
    		$param = 'multiple=multiple&';
    	}
        $url_filemanager = "/backoffice/cms/lib/ckeditor/fileman/index.html?dir=/custom/upload/".$classeName."/&langCode=".$_SESSION["site_langue"]."&integration=field&".$param;
    }
    
    
	echo "<input type=\"button\" onClick=\"openWYSYWYGWindow('$url_filemanager', 'f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', null, null, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"HTML editor\" value=\"".$translator->getTransByCode('Parcourir_le_serveur')."\" />\n"; 
}
else {
	echo "&nbsp;ou&nbsp;<input type=\"button\" onClick=\"openWYSYWYGWindow('/backoffice/cms/lib/FCKeditor/editor/filemanager/browser/default/browser.html?Connector=connectors/php/connector.php', 'f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', null, null, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"HTML editor\" value=\"".$translator->getTransByCode('Parcourir_le_serveur')."\" />\n"; 
}

echo '&nbsp;'.$translator->echoTransByCode('taille_max').' '.$MaxFilesize.' Mo ';
if ($tempMultiple == "true") echo "- ".$translator->getTransByCode('Vous_pouvez_telecharger_plusieurs_images'); 
echo "<input  class=\"arbo\" size=\"80\"  type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";

// permet de stocker l'info multiple ou non  
	echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_multiple\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_multiple\" value=\"".$tempMultiple."\">"; 
	
echo '
	
	
	<script type="text/javascript">
	
	
	$(function() {	
	
		$( "#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_conteneur" ).sortable({
			start: function(e, ui) {
				// puts the old positions into array before sorting
				var old_position = $(this).sortable(\'toArray\'); 
			},
			
			update: function(event, ui) {
                                
                                var old_meta_data = $("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'").val();
                                console.log( "old data : " + old_meta_data );
				// grabs the new positions now that we\'ve finished sorting
				var new_position = $(this).sortable(\'toArray\'); 
				var regex = new RegExp( "f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_([0-9]*)"  );  
				var new_string; 
				new_string = "";
				//console.log(new_position);
				for (var i = 0; i < new_position.length; i++) {
					
					if ( new_position[i] != \'\') {

						//console.log(new_position[i]);
						var aId = new_position[i].match(regex);
			   			id = aId[1];
						
						//alert(new_position[i]+\' \'+id);
						
						var mon_fichier = $("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_listfile_"+id ).val(); 
						
						new_string += "{"+mon_fichier+"}";
						
						
					}
				}
				 console.log( "new data : " + new_string );
				$("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'").val(new_string); 
				
			}  
			
			
		});	 
	});	
	
	
	$(document).ready(function(){ 
	
	$("a[id^=\'f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_\']").live("click", function(){

		var list_image;
		
		var mon_label = $(this).prev("input").val();
		
		list_image = ($("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'").val());
		getImage = new RegExp( "({.*?})" ,"g"  );
	
		var aList_image = list_image.match(getImage);
		
		var regex = new RegExp( "([0-9]*)_(.*)"  );
		var idimg = 0 ;
		 
	 	var aId = mon_label.match(regex);			
		
	 	idimg = aId[1];  
		
		if (aList_image == null) {
			var new_string; 
			new_string = \'\';
		}
		else {
			var new_string; 
			new_string = \'\';
			//alert("aList_image.length "+ aList_image.length) ; 
			for (var i = 0; i < aList_image.length ; i++ ) {
				if (i != idimg)  {
					new_string+=aList_image[i];	
				} 
				else {
					new_string+=\'{}\';	
				}
			}
		} 
		$("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_"+idimg).html(\'\');
		$("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'").val(new_string);		

		return false; 
	});
	}); 
	
	
	$("a[id^=\'f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_edit_\']").live("click", function(){
	  	
		var mon_label = $(this).prev().prev("input").val(); 
		var regex = new RegExp( "([0-9]*)_(.*)"  );
		var idimg = 0 ; 
	 	var aId = mon_label.match(regex); 
	 	idimg = aId[1];  
		var id_requete; 
		var reg=new RegExp(".", "gm");
    var mes_fichiers = $("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_listfile_"+idimg ).val(); 
		mes_fichiers = mes_fichiers.split(".").join("**");
		
		$.fancybox({ 
			href: \'/include/cms-inc/autoClass/maj.file.edit.php?id=\'+idimg+\'&source=\'+mes_fichiers+\'\',  
			type:\'iframe\',
			width	: 1000, 		
            height	: 800,
			overlayShow: true,
			onClosed: function() { 
                                //console.log( "return value = " + $_returnvalue);
				if ($_returnvalue != false){
                                        $("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_listfile_"+idimg ).val( $_returnvalue );
                                        console.log( "insert val into : #f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_listfile_"+idimg  );
                                        var regex = new RegExp( "f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_([0-9]*)"  );  
                                        var new_fusion; 
                                        new_fusion = "";
                                        var new_position10 = $( "#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_conteneur" ).sortable(\'toArray\')
                                       
                                        for (var i = 0; i < new_position10.length; i++) {

                                                if ( new_position10[i] != \'\') {

                                                        var aId = new_position10[i].match(regex);
                                                        id = aId[1];

                                                        var mon_fichier = $("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_listfile_"+id ).val(); 

                                                        new_fusion += "{"+mon_fichier+"}";


                                                }
                                        }                                        
                                        $("#f'.ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"].'").val(new_fusion);
                                }
			}
		}); 
		
		
		return false; // on desactive le lien
	});
	
	var $_returnvalue = false;

	</script>
	'; 
	
//if ($eKeyValue != ""){	
if (preg_match_all("/{([^{]+)}/ms", $eKeyValue, $matches)){
	echo '<br /> ('.$translator->getTransByCode('actuellement').') <br /> ';	
	
	//preg_match_all("/{([^{}].*?)}/ms", $eKeyValue, $matches);
  
	  
	$allFiles = array();
	
	if (count($matches[1]) == 0) {
		$allFiles[] = $eKeyValue;
	}
	else {
		$allFiles = $matches[1];
	}
	
	
	echo "<ol id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_conteneur\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_conteneur\" ";
        if ($tempMultiple == "true") echo "class=\"new_diapo\" ";
        else echo "class=\"one_img\"";
        echo ">"; 
  
	foreach ($allFiles as $nbimg => $eKeyValue) {		 
							
		$aFiles = explode(';', $eKeyValue); 
		$img = 0;   
		
		for($if=0;$if<count($aFiles) ;$if++){      

			$sFile = preg_replace ("/\[.*\]/", "", $aFiles[$if]) ;  // on supprime la zone commentaires entre crochets
			
			if (is_file($_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$classeName.'/'.$sFile) && $if < 1){
			 
				echo "<li id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_".$nbimg."\">"; 
                                
        echo "<div>";

				if (($if == 0)&&(preg_match('/^.*\.(jpg|jpeg|png|gif)$/i', $sFile)	)){
					echo '<a rel="'.$classeName.'_'.$aNodeToSort[$i]["attrs"]["NAME"].'" href="/custom/upload/'.$classeName.'"/"'.$sFile.'" target="_blank" title="'.$translator->getTransByCode('visualiserlefichier').'" "'.$sFile.'" class="visuel"><img src="/custom/upload/'.$classeName.'/'.$sFile.'" width="70" /></a>';
				}
				elseif ($if == 0){
					echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$sFile."\" target=\"_blank\" title=\"".$translator->getTransByCode('telechargerlefichier')." '".$sFile."'\">".$sFile."</a>\n";
				}
				echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$sFile."\" target=\"_blank\" title=\"".$translator->getTransByCode('visualiserlefichier')." '".$sFile."'\" class='name_img_diapo'>".$sFile."</a>\n";
				
				echo "<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$classeName."/".$sFile."\" title=\"".$translator->getTransByCode('telechargerlefichier')." '".$sFile."'\" class='picto_download' \"><img src=\"/backoffice/cms/img/2013/icone/right.png\" alt=\"".$translator->getTransByCode('telechargerlefichier')." '".$sFile."\" border=\"0\"></a>\n";
				echo '<input type="hidden" id="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_'.$nbimg.'_name" name="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_'.$nbimg.'_name"  value="'.$nbimg."_".$sFile.'" />';
				
				
				if ($if == 0)  echo '<a id="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_'.$nbimg.'" href="#_" class="picto_del"  title="delete recipient"><img src="/backoffice/cms/img/2013/icone/supprimer.png" border="0" alt="Suppression de l\'enregistrement"></a>'; 
				
				if ($if == 0)  echo '<a id="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_edit_'.$nbimg.'" href="#_"  title="edit" class="picto_edit"><img src="/backoffice/cms/img/2013/icone/modifier.png" border="0" alt="Modifier"></a>'; 
				
				if ($if == 0)   echo '<input type="hidden" id="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_listfile_'.$nbimg.'" name="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_listfile_'.$nbimg.'"  value="'.$eKeyValue.'" />';
				$img++;
                                
        echo "</div>";
                                
				echo "</li>"; 
				
				
			}
      else{        
        echo '<!-- file is missing: '.$sFile.' -->';
      }
		}
	 	
	
		if ($img == 0) {
			if (is_file($_SERVER['DOCUMENT_ROOT'].''.$eKeyValue)){
				$namefile = basename($eKeyValue);
				
				echo "<a href=\"/backoffice/cms/utils/viewer.php?file=".$eKeyValue."\" target=\"_blank\" title=\"".$translator->getTransByCode('visualiserlefichier')." '".$namefile."'\">".$namefile."</a>\n";
				echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=".$eKeyValue."\" title=\"".$translator->getTransByCode('telechargerlefichier')." '".$namefile."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"".$translator->getTransByCode('telechargerlefichier')." '".$namefile."\" /></a>\n";  
				
			}
			
		}

	}	 
	echo "</ol>\n";	
	echo "<div class='data_files'><div class='delete_file'><input type=\"checkbox\" id=\"fDeleteFile".$indexUpload."\" name=\"fDeleteFile".$indexUpload."\" value=\"true\" /><label for='fDeleteFile".$indexUpload."'>".$translator->getTransByCode('supprimer_le_les_fichiers')."</label></div>\n";
}
else{

	echo "<div id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_conteneur\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_conteneur\" >";
	echo "<div class='no_file'>(".$translator->getTransByCode('pas_de_fichier').")</div>";
	echo "</div><br />"; 
	
}

if ($aNodeToSort[$i]["attrs"]["OPTION"] == "geomapfile") {
	echo "\n<script type=\"text/javascript\">";
	echo "\nfunction check_{$aNodeToSort[$i]["attrs"]["NAME"]}_Coordinates() {";
	echo "\n\tvar frm = document.forms['add_{$classePrefixe}_form'];";
	echo "\n\tvar available = true;";
	echo "\n\tvar fields = Array();";
	foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode){
		if ($childNode["name"] == "OPTION" && $childNode["attrs"]["TYPE"] == "if") { // on a un node d'option conditionnant l'affichage du checkbox
			echo "\n\t if (frm['f".ucfirst($classePrefixe)."_".$childNode["attrs"]["ITEM"]."'].value == '' || frm['f".ucfirst($classePrefixe)."_".$childNode["attrs"]["ITEM"]."'].value == -1) {";
			echo "\n\t\tfields[fields.length] = '{$childNode["attrs"]["ITEM"]}';";
			echo "\n\t\tavailable = false;";
			echo "\n\t}";
		}
	}
	echo "\n\t if (!available) {";
	echo "\n\t\t if (fields.length == 1)";
	echo "\n\t\talert('Vous devez renseigner auparavant le champ '+fields[0]);";
	echo "\n\t\telse\talert('Vous devez renseigner auparavant les champs '+fields.join(' et '));";
	echo "\n\t\tfrm['fGenerateFile".$indexUpload."'].checked = false;";
	echo "\n\t}";
	echo "\n}";
	echo "\n</script>";
	echo "<input type=\"checkbox\" id=\"fGenerateFile".$indexUpload."\" name=\"fGenerateFile".$indexUpload."\" value=\"true\" onclick=\"if (this.checked) check_{$aNodeToSort[$i]["attrs"]["NAME"]}_Coordinates()\" />&nbsp;générer le fichier depuis GoogleMaps\n";
	
} elseif (isset($aNodeToSort[$i]["children"]) && (count($aNodeToSort[$i]["children"]) > 0)) {
	foreach ($aNodeToSort[$i]["children"] as $childKey => $childNode) {
		if($childNode["name"] == "OPTION"){ // on a un node d'option	
			echo "<div class='file_informations'>\n";
		
			if (($childNode["attrs"]["TYPE"] != "") && ($childNode["attrs"]["TYPE"] != "if")){
				echo "<p><span class='label'>".$translator->getTransByCode('_Type_de_fichier')."</span> : ".$childNode["attrs"]["TYPE"]."</p>\n";
			}
			if ($childNode["attrs"]["WIDTH"] != ""){
				echo "<p><span class='label'>".$translator->getTransByCode('Largeur_nominale_de_limage')."</span> : ".$childNode["attrs"]["WIDTH"]." pixels</p>\n";
			}
			elseif ($childNode["attrs"]["MAXWIDTH"] != ""){
				echo "<p><span class='label'>".$translator->getTransByCode('Largeur_maximale_de_limage')."</span> : ".$childNode["attrs"]["MAXWIDTH"]." pixels</p>\n";
			}
			if ($childNode["attrs"]["HEIGHT"] != ""){
				echo "<p><span class='label'>".$translator->getTransByCode('Hauteur_nominale_de_limage')."</span> : ".$childNode["attrs"]["HEIGHT"]." pixels</p>\n";
			}
			elseif ($childNode["attrs"]["MAXHEIGHT"] != ""){
				echo "<p><span class='label'>".$translator->getTransByCode('Hauteur_maximale_de_limage')."</span> : ".$childNode["attrs"]["MAXHEIGHT"]." pixels</p>\n";
			}
                        echo "</div>";
		}
	}
}
echo "</div><div class='spacer'>&nbsp;</div></div>\n";
