<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

	$dateNodes = getItemsByType($aNodeToSort, "date");

	if (!isset($translator))
		$translator =& TslManager::getInstance(); 
	if ($dateNodes != false){
	?>
		<link rel="stylesheet" type="text/css" media="all" href="/backoffice/cms/lib/jscalendar/calendar-win2k-cold-1.css" title="win2k-cold-1">
		<script type="text/javascript" src="/backoffice/cms/lib/jscalendar/calendar.js"></script>
		<script type="text/javascript" src="/backoffice/cms/lib/jscalendar/lang/calendar-fr.js"></script>
		<script type="text/javascript" src="/backoffice/cms/lib/jscalendar/calendar-setup.js"></script>

		<?php
			$today = getdate();
			$today["mon"] = strlen($today["mon"]) == 1 ? '0'.$today["mon"] : $today["mon"];
			$formated_date = $today["mday"].'/'.$today["mon"].'/'.$today["year"];

			foreach($dateNodes as $key => $dateNode) :
		?>
			<div id="statutFilter" class="statutFilter blocItem dateblocItem">
				<!-- Champ début -->
				<div>
					<div id="statutFilterLabel" class="statutFilterLabel"><?php echo $translator->getTransByCode('Debut').' '.$dateNode["attrs"]["LIBELLE"] ?></div>
					<div id="statutFilterField" class="statutFilterField">
						<?php $value = 'filter'.ucfirst($classePrefixe).'_'.$dateNode["attrs"]["NAME"].'_startdate'; ?>
						<input type="tet" name="<?php echo $value ?>" id="<?php echo $value ?>" class="arbo" size="80" value="<?php echo $_POST[$value] ?>">
						<img src="/backoffice/cms/lib/jscalendar/img.gif" id="<?php echo $value ?>_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''">
						<script type="text/javascript" language="javascript">
							Calendar.setup({
								inputField     :    "<?php echo $value ?>",  // id of the input field
								ifFormat       :    "%d/%m/%Y %H:%M:%S",      // format of the input field
								button         :    "<?php echo $value ?>_trigger", // trigger ID
								align          :    "Tl",           // alignment (defaults to "Bl")
								showsTime      :    true,
								time24         :    true,
								singleClick    :    true
							});
						</script>
					</div>
				</div>
				<div>
					<!-- Champ fin -->
					<div id="statutFilterLabel" class="statutFilterLabel"><?php echo $translator->getTransByCode('Fin').' '.$dateNode["attrs"]["LIBELLE"] ?></div>
					<div id="statutFilterField" class="statutFilterField">
						<?php $value = 'filter'.ucfirst($classePrefixe).'_'.$dateNode["attrs"]["NAME"].'_enddate'; ?>
						<input type="text" name="<?php echo $value ?>" id="<?php echo $value ?>" class="arbo" size="80" value="<?php echo $_POST[$value] ?>">
						<img src="/backoffice/cms/lib/jscalendar/img.gif" id="<?php echo $value ?>_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''">
						<script type="text/javascript" language="javascript">
							Calendar.setup({
								inputField     :    "<?php echo $value ?>",  // id of the input field
								ifFormat       :    "%d/%m/%Y %H:%M:%S",      // format of the input field
								button         :    "<?php echo $value ?>_trigger", // trigger ID
								align          :    "Tl",           // alignment (defaults to "Bl")
								showsTime      :    true,
								time24         :    true,
								singleClick    :    true
							});
						</script>
					</div>
				</div>
		 	</div>

		 <?php 
		 	endforeach;
		 }
?>