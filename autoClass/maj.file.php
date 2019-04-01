<?php


//echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";
$indexUpload++;

// champs multiple

$tempMultiple = (isset($aNodeToSort[$i]["attrs"]["MULTIPLE"])	&& $aNodeToSort[$i]["attrs"]["MULTIPLE"] == 'true') ? "true" : "false"; 


echo "<div id=\"div".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\">\n";
echo "<!-- upload field # ".$indexUpload."/".$numUploadFields." -->\n";
echo "<input type=\"hidden\" id=\"fUpload".$indexUpload."\" name=\"fUpload".$indexUpload."\" value=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" />\n";

// Affichage du champ de type FILE						
//print $Upload-> Field[$indexUpload];



if (defined("DEF_FCK_VERSION") && DEF_FCK_VERSION == "ckeditor" ) {
	echo "<input type=\"button\" onClick=\"openWYSYWYGWindow('/backoffice/cms/lib/ckeditor/Filemanager-master/index.php?dir=/custom/upload/".$classeName."/&langCode=".$_SESSION["site_langue"]."&', 'f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', null, null, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"HTML editor\" value=\"Parcourir le serveur\" />\n"; 
}
else {
	echo "&nbsp;ou&nbsp;<input type=\"button\" onClick=\"openWYSYWYGWindow('/backoffice/cms/lib/FCKeditor/editor/filemanager/browser/default/browser.html?Connector=connectors/php/connector.php', 'f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', null, null, 'scrollbars=yes', 'true','f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."', 'add_".$classePrefixe."_form');\" title=\"HTML editor\" value=\"Parcourir le serveur\" />\n"; 
}

echo '&nbsp;taille max: '.$MaxFilesize.' Mo ';
if ($tempMultiple == "true") echo "&nbsp;-&nbsp;Vous pouvez télécharger plusieurs images"; 
echo "<input  class=\"arbo\" size=\"80\"  type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\" value=\"".$eKeyValue."\" />\n";

// permet de stocker l'info multiple ou non  
	echo "<input type=\"hidden\" id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_multiple\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_multiple\" value=\"".$tempMultiple."\">"; 
	

if ($eKeyValue != ""){
	echo '<br />&nbsp;(actuellement) <br /> ';	
	
	preg_match_all("/{([^{}].*?)}/", $eKeyValue, $matches);
	  
	$allFiles = array();
	
	if (sizeof($matches[1]) == 0) {
		$allFiles[] = $eKeyValue;
	}
	else {
		$allFiles = $matches[1];
	}
	
	/*
	$(function() {
		$( "#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_conteneur" ).sortable({
		
		start: function(e, ui) {
			// puts the old positions into array before sorting
			var old_position = $(this).sortable(\'toArray\'); 
		},
		update: function(event, ui) {
			// grabs the new positions now that we\'ve finished sorting
			var new_position = $(this).sortable(\'toArray\'); 
			
			for (var i = 0; i < new_position.length; i++) {
				alert(new_position[i]);
				if (new_position[i] != '') {
				
				//alert(fDia_image_delrecipient_1_name) ; 
				 
				
				//
				//var id = 0 ;
				
			//	$.each( $(\"div[id^=\'fDia_image_\']\"), function () {  
			//	  var aId = $(this).attr(\'id\').match(regex);
			//	  id = aId[1];
			//	});
				
			//	id = parseInt(id) + 1 ; 
				
			 
				
				}
			}
		}
		
		
		//});
		$( "#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_conteneur" ).disableSelection();
		});
	*/ 
	echo '
	
	
	<script type="text/javascript">
	
	
	$(function() {	
	
		$( "#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_conteneur" ).sortable({
			start: function(e, ui) {
				// puts the old positions into array before sorting
				var old_position = $(this).sortable(\'toArray\'); 
			},
			
			update: function(event, ui) {
				// grabs the new positions now that we\'ve finished sorting
				var new_position = $(this).sortable(\'toArray\'); 
				var regex = new RegExp( "fDia_image_([0-9]*)"  );  
				var new_string; 
				new_string = "";
				
				for (var i = 0; i < new_position.length; i++) {
					
					if ( new_position[i] != \'\') {

						
						var aId = new_position[i].match(regex);
			   			id = aId[1];
						
						//alert(new_position[i]+\' \'+id);
						
						var mon_fichier = $("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_listfile_"+id ).val(); 
						
						new_string += "{"+mon_fichier+"}";
						
						
					}
				}
				 
				$("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'").val(new_string); 
				
			}  
			
			
		});	 
	});	
	
	
	$(document).ready(function(){ 
	 
	
	
	//$("a[id^=\'f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_\']").click(function(){    
	
	
	$("a[id^=\'f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_\']").live("click", function(){
	
		
		//alert ("del");  
		var list_image;
		  
		
		//alert($(this).prev("input").val());
		
		var mon_label = $(this).prev("input").val();
		
		//alert(mon_label); 
		
		list_image = ($("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'").val());
		//alert(list_image); 
		getImage = new RegExp( "({.*?})" ,"g"  );
	
		var aList_image = list_image.match(getImage);
		
		var regex = new RegExp( "([0-9]*)_(.*)"  );
		var idimg = 0 ;
		 
	 	var aId = mon_label.match(regex);
		
		
		
	 	idimg = aId[1]; 
		
		//alert(idimg); 
		
		if (aList_image == null) {
			var new_string; 
			new_string = \'\';
		}
		else {
			var new_string; 
			new_string = \'\';
			alert("aList_image.length "+ aList_image.length) ; 
			for (var i = 0; i < aList_image.length ; i++ ) {
				if (i != idimg)  {
					new_string+=aList_image[i];	
				} 
				else {
					new_string+=\'{}\';	
				}
			}
		} 
		//alert(new_string); 
		$("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_"+idimg).html(\'\');
		$("#f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'").val(new_string)
		 
		

		return false; 
	});
	}); 
	
	
	$("a[id^=\'f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_edit_\']").live("click", function(){
	  	
		var mon_label = $(this).prev().prev("input").val(); 
		var regex = new RegExp( "([0-9]*)_(.*)"  );
		var idimg = 0 ; 
	 	var aId = mon_label.match(regex); 
	 	idimg = aId[1];  
		//alert(idimg);
		var id_requete; 
		
		var reg=new RegExp(".", "gm");
		var mes_fichiers = $("#f'.ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"].'").val();  
		mes_fichiers = mes_fichiers.split(".").join("**");
		//alert(mes_fichiers);
		
		
		
		$.fancybox({ 
			href: \'/include/cms-inc/autoClass/maj.file.edit.php?id=\'+idimg+\'&source=\'+mes_fichiers+\'\',  
			type:\'iframe\',
			width	: 1000, 		
            height	: 800,
			overlayShow: true,
			onClosed: function() { 
				if ($_returnvalue != false) 
					$("#f'.ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"].'").val($_returnvalue);
			}
		}); 
		
		
		
		
		//location.reload(); 
		return false; // on desactive le lien
	});
	
	var $_returnvalue = false;
	/*
	function f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient (id_enrg) {     
		
		//alert ( "del");  
		var list_image;
		 
		
		//alert("f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_"+id_enrg);
		
		  
		//alert(document.getElementById("f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_"+id_enrg+"_name").value); 
		
		var mon_label = document.getElementById("f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_"+id_enrg+"_name").value;
		
		list_image = document.getElementById("f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'").value;
		
		getImage = new RegExp( "({[^{}].*?})" ,"g"  );
	
		var aList_image = list_image.match(getImage);
		
		var regex = new RegExp( "([0-9]*)_(.*)"  );
		var idimg = 0 ;
		 
	 	var aId = mon_label.match(regex);
	 	idimg = aId[1]; 
		
		//alert(idimg); 
		
		if (aList_image == null) {
			var new_string; 
			new_string = \'\';
		}
		else {
			var new_string; 
			new_string = \'\'; 
			for (var i = 0; i < aList_image.length ; i++ ) {
				
				if (i != idimg)  {
					//alert("add"+aList_image[i]);
					new_string+=aList_image[i];	
				} 
				else {
					new_string+=\'{}\';	
				}
			}
		} 
		//alert("f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_"+id_enrg);
		
		document.getElementById("f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_"+id_enrg).innerHTML = "";
		document.getElementById("f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'").value = new_string;
		 
		

		return false; 
	}
	*/ 
	</script>
	'; 
	
	
	
	echo "<span id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_conteneur\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_conteneur\" >"; 
	foreach ($allFiles as $nbimg => $eKeyValue) {	
	 
							
		$aFiles = explode(';', $eKeyValue); 
		$img = 0;	
		
		 
		
		for($if=0;$if<1;$if++){
		
			$sFile = $aFiles[$if];
			$sFile = preg_replace ("/\[.*\]/", "", $sFile) ;  // on supprime la zone commentaires entre crochets
			
			if (is_file($_SERVER['DOCUMENT_ROOT'].'/custom/upload/'.$classeName.'/'.$sFile)){
			
			
				echo "<div id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_".$nbimg."\">"; 
				if ($if == 0)  echo "&nbsp;<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$sFile."\" target=\"_blank\" title=\"".$translator->getTransByCode('visualiserlefichier')." '".$sFile."'\"><img src='/custom/upload/".$classeName."/".$sFile."' width='70' /></a>&nbsp; ";
				echo "<a href=\"/backoffice/cms/utils/viewer.php?file=/custom/upload/".$classeName."/".$sFile."\" target=\"_blank\" title=\"".$translator->getTransByCode('visualiserlefichier')." '".$sFile."'\">".$sFile."</a>\n";
				
				echo "&nbsp;-&nbsp;<a href=\"/backoffice/cms/utils/telecharger.php?file=custom/upload/".$classeName."/".$sFile."\" title=\"".$translator->getTransByCode('telechargerlefichier')." '".$sFile."'\"><img src=\"/backoffice/cms/img/telecharger.gif\" width=\"14\" height=\"16\" border=\"0\" alt=\"".$translator->getTransByCode('telechargerlefichier')." '".$sFile."\" /></a>\n";
				echo '<input type="hidden" id="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_'.$nbimg.'_name" name="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_'.$nbimg.'_name"  value="'.$nbimg."_".$sFile.'" />';
				
				
				if ($if == 0)  echo '&nbsp;-&nbsp;<a id="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_delrecipient_'.$nbimg.'" href="#"  title="delete recipient">[del]</a>&nbsp;'; 
				
				if ($if == 0)  echo '&nbsp;-&nbsp;<a id="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_edit_'.$nbimg.'" href="#"  title="edit">[edit]</a>&nbsp;'; 
				
				if ($if == 0)   echo '<input type="hidden" id="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_listfile_'.$nbimg.'" name="f'.ucfirst($classePrefixe).'_'.$aNodeToSort[$i]["attrs"]["NAME"].'_listfile_'.$nbimg.'"  value="'.$eKeyValue.'" />';
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
	echo "</span>\n";	
	echo "<input type=\"checkbox\" id=\"fDeleteFile".$indexUpload."\" name=\"fDeleteFile".$indexUpload."\" value=\"true\" />&nbsp;supprimer le(s) fichier(s) \n";
}
else{

	echo "<br /><span id=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_conteneur\" name=\"f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."_conteneur\" >";
	echo "&nbsp;(pas de fichier)";
	echo "</span><br />"; 
	
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
			echo "<br />\n";
		
			if (($childNode["attrs"]["TYPE"] != "") && ($childNode["attrs"]["TYPE"] != "if")){
				echo "Type de fichier&nbsp;: ".$childNode["attrs"]["TYPE"]."<br />\n";
			}
			if ($childNode["attrs"]["WIDTH"] != ""){
				echo "Largeur nominale de l'image&nbsp;: ".$childNode["attrs"]["WIDTH"]." pixels<br />\n";
			}
			elseif ($childNode["attrs"]["MAXWIDTH"] != ""){
				echo "Largeur maximale de l'image&nbsp;: ".$childNode["attrs"]["MAXWIDTH"]." pixels<br />\n";
			}
			if ($childNode["attrs"]["HEIGHT"] != ""){
				echo "Hauteur nominale de l'image&nbsp;: ".$childNode["attrs"]["HEIGHT"]." pixels<br />\n";
			}
			elseif ($childNode["attrs"]["MAXHEIGHT"] != ""){
				echo "Hauteur maximale de l'image&nbsp;: ".$childNode["attrs"]["MAXHEIGHT"]." pixels<br />\n";
			}									
		}
	}
}
echo "</div>\n";
?>