    </div>
   </section>
   
<!-- Bloc Footer -->
<footer><p class="logo"><img src="/backoffice/cms/img/2013/logo_couleur_citron.png" border="0" alt="Logo Couleur Citron"/></p></footer>


<!-- FIn Bloc Footer -->
  <?php
  if ($_SERVER['PHP_SELF'] != '/backoffice/cms/init.php'){
  ?>
  <iframe id="sessionsave" style="display:none" src="/backoffice/cms/init.php?idSite=<?php echo $_SESSION['idSite']; ?>"></iframe>
  <?php
 }
  ?>
 </body>
</html>
<?php
if((!isset($DONTDESTROY))||(!$DONTDESTROY)){
	$_SESSION['listeInscrit'] = null;
}
if((!isset($DONTDESTROYSELECTED))||(!$DONTDESTROYSELECTED)){
	$_SESSION['listeInscritSelect'] = null;
}
?>