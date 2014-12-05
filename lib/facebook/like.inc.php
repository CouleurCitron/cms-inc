<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
echo '<div id="fblike" class="fblike" style="float: left;">';
echo '<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2F'.$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']).'&amp;layout=standard&amp;show_faces=true&amp;width=350&amp;action=like&amp;colorscheme=light" scrolling="no" frameborder="0" allowTransparency="false" style="border:none; overflow:hidden; width:400px; height:25px;spacing:1px" id="iflike" class="iflike"></iframe>';
echo '</div>';
echo '<div style="float:left">';
echo '<!-- Place this tag in your head or just before your close body tag -->
<script type="text/javascript" src="http://apis.google.com/js/plusone.js">
{lang: \'fr\'}
</script><!-- Place this tag where you want the +1 button to render -->
<g:plusone></g:plusone>';
echo '</div>';
echo '<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="CouleurCitron">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
echo '<div style="clear: both; font-size:1px; height: 1px;">&nbsp;</div>';
?>
