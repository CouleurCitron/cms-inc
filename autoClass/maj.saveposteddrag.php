<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
 
	 
	$maxlegende = 0;
	$myTab = array();
	 
	foreach ($_POST as $k => $value) {
		
		if (preg_match ("/^labeldragvisible[0-9]*/", $k)) {
			
			$nblegende = preg_replace ("/^labeldragvisible([0-9]*)/", "$1", $k);   
			
			if ($_POST["left".$nblegende]!= 0 && $_POST["top".$nblegende]!= 0) {
				$myTab[$nblegende] = array();
				//$myTab[$nblegende]["title"] = htmlentities($value);
				//$myTab[$nblegende]["titleroll"] = htmlentities($_POST["labeldraginvisible".$nblegende]);
				//$myTab[$nblegende]["title"] = str_replace ('&quot;', "__GUILL__",$value);
				//$myTab[$nblegende]["titleroll"] = str_replace ('&quot;', "__GUILL__",$_POST["labeldraginvisible".$nblegende]);
				$myTab[$nblegende]["title"] = $value;
				$myTab[$nblegende]["titleroll"] = $_POST["labeldraginvisible".$nblegende];
				$myTab[$nblegende]["top"] = $_POST["top".$nblegende];
				$myTab[$nblegende]["left"] = $_POST["left".$nblegende];
				
				if ($nblegende > $maxlegende) $maxlegende = $nblegende;
				
				echo '<style>
			
					#draggable'.$nblegende.' { 
					position:absolute;
					left:'.$_POST["left".$nblegende].'px;
					top:'.$_POST["top".$nblegende].'px;
					} 
					#roll'.$nblegende.' { 
					position:relative;
					left:20px;
					top:-20px;
					} 
					
					</style>
					
					<script> 
					 
					  $(document).ready(function() {
					  	  $("#roll'.$nblegende.'").fadeTo(0,0);
						  $("#show'.$nblegende.'").mouseover(function() {
							$("#roll'.$nblegende.'").fadeTo("slow",1);  
						  });
						   $("#show'.$nblegende.'").mouseout(function() {
							$("#roll'.$nblegende.'").delay(1800).fadeTo("slow",0);  
						  });
 						});
					</script>
					';
					
					
				/*echo '
				<div id="draggable'.$nblegende.'" class="currdraggable"><a href="#_" id="show'.$nblegende.'" >'.$value.'</a> <div id="roll'.$nblegende.'" >'.$_POST["labeldraginvisible".$nblegende].'</div>
				<input type="hidden" id="labeldragvisible'.$nblegende.'" name="labeldragvisible'.$nblegende.'" value="'.$value.'" />
				<input type="hidden" id="labeldraginvisible'.$nblegende.'" name="labeldraginvisible'.$nblegende.'" value="'.$_POST["labeldraginvisible".$nblegende].'" />
				<input type="hidden" id="top'.$nblegende.'" name="top'.$nblegende.'" value="'.$_POST["top".$nblegende].'" />
				<input type="hidden" id="left'.$nblegende.'" name="left'.$nblegende.'" value="'.$_POST["left".$nblegende].'" />
				<a href="#_" onClick="javascript:updateDrag('.$nblegende.');" >update</a>&nbsp;<a href="#_" onClick="javascript:delDrag('.$nblegende.');" >del</a></div>';*/
			}
			
			
		}
		
		
	}   
 
	$serialiazeTab = base64_encode(serialize($myTab));  
	echo '<input type="hidden" id="maxlegende" name="maxlegende" value="'.$maxlegende.'" />';
	eval("$"."oRes->set_".$_POST["source"]."('".$serialiazeTab."');"); 
	$r = dbSauve ($oRes); 
	
	/*echo "<script type=\"text/javascript\">\n";		
	echo "alert(serialiazeTab_".$_POST["idField"].".value);"; 
			echo "serialiazeTab_".$_POST["idField"].".value= '".$serialiazeTab."';"; 
		echo "</script>\n"; */
		
		echo "<script language=\"Javascript\">  
			$(document).ready(function(){ 
				
				$(\"#editdrag\").fancybox({ 
					'padding'		: 10,
					'scrolling'		: 'auto',																						
					'title'			: this.title,																								
					'href'			: '/backoffice/cms/utils/popup_drag.php?id=".$id."&idField=f".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."&classeName=".$classeName."&refer=".$champ_refer."&source=".$aNodeToSort[$i]["attrs"]["NAME"]."&idForm=add_".$classePrefixe."_form'
				}).trigger(\"click\");
				
			})	;
			</script>
			";
 

?>