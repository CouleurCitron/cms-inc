<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); require_once('html2fpdf.php');
// activate Output-Buffer:
ob_start(); ?>
<html>
<head>
<title>HTML 2 (F)PDF Project</title>
</head>
<body>
<h2 align="center">FEATURES</h2>
Current version: <?php echo  HTML2FPDF_VERSION ; ?>
<p>
This script supports the following tags:
<code><br />
<?php //Automatically get html2fpdf tag info
$pdf = new HTML2FPDF();
$pdf->DisableTags();
$pdf->DisplayPreferences('FullScreen');
$tags = strtoupper($pdf->enabledtags);
$tags = str_replace('>','>, ',$tags);
$tags{strlen($tags)-2} = ' '; //erase last comma
$tags = htmlspecialchars($tags);
echo $tags;
?></code></p>
<p>
This script supports the following CSS properties (not perfectly or fully
supported): <br />
<i>width, height, border, font-family, font-size, font-style, font-weight,
text-decoration, text-align, text-transform, direction, background, color.</i>
</p>
This script supports the following image types: <i>jpg, png, gif</i>.<br />
This script is not 100% accurate! It simply helps in normal html conditions.
Works best with XHTML 1.0<br />
<br /><br />
<div style='background:#ccc;border:thin dashed black'>
This page was dinamically created using PHP ob_get_contents and HTML2FPDF
class.<br />
Read more on FAQ on how to make this or check the 2<sup>nd</sup> page (use the
'PageDown' keyboard key)</div><newpage>
<div style='background:#eee;font-weight:bold'><code>
<?php $metaphp = htmlspecialchars($metaphp);
$metaphp = str_replace("\n",'<br>',$metaphp);
echo "&lt;?".$metaphp."?&gt;"; ?>
</div></code>
</body>
</html>
<?php // Output-Buffer in variable:
$html=ob_get_contents();

// delete Output-Buffer
ob_end_clean();
$pdf->AddPage();
$pdf->WriteHTML($html);
$pdf->Output('doc.pdf','I'); ?>