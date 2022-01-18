<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/* 
$Author: raphael $
$Revision: 1.1 $

$Log: arbo_browse.php,v $
Revision 1.1  2013-09-30 09:28:18  raphael
*** empty log message ***

Revision 1.15  2013-03-01 10:33:57  pierre
*** empty log message ***

Revision 1.14  2012-07-31 14:24:45  pierre
*** empty log message ***

Revision 1.13  2012-04-13 07:33:15  pierre
*** empty log message ***

Revision 1.12  2009-09-24 08:52:32  pierre
*** empty log message ***

Revision 1.11  2008-11-28 15:14:40  pierre
*** empty log message ***

Revision 1.10  2008-11-28 14:09:40  pierre
*** empty log message ***

Revision 1.9  2008-11-27 11:37:07  pierre
*** empty log message ***

Revision 1.8  2008-11-06 12:03:52  pierre
*** empty log message ***

Revision 1.7  2008-10-21 09:20:45  pierre
*** empty log message ***

Revision 1.5  2008/07/16 10:55:38  pierre
*** empty log message ***

Revision 1.4  2008/07/16 10:04:20  pierre
*** empty log message ***

Revision 1.3  2007/08/08 14:14:23  thao
*** empty log message ***

Revision 1.2  2007/08/08 13:53:33  thao
*** empty log message ***

Revision 1.1  2007/08/08 13:07:18  thao
*** empty log message ***

Revision 1.1.1.1  2006/01/25 15:14:27  pierre
projet CCitron AWS 2006 Nouveau Website

Revision 1.2  2005/10/27 09:25:55  sylvie
*** empty log message ***

Revision 1.1.1.1  2005/10/24 13:37:05  pierre
re import fusion espace v2 et ADW v2

Revision 1.2  2005/10/21 10:46:46  sylvie
*** empty log message ***

Revision 1.1.1.1  2005/10/20 13:10:54  pierre
Espace V2

Revision 1.2  2005/05/23 10:17:54  michael
Ajout de la brique graphique
Traitement spécial pour la créa/édition/suppression/duplication

Revision 1.1.1.1  2005/04/18 13:53:29  pierre
again

Revision 1.1.1.1  2005/04/18 09:04:21  pierre
oremip new

Revision 1.1.1.1  2004/11/03 13:49:54  ddinside
lancement du projet - import de adequat

Revision 1.3  2004/06/16 15:23:19  ddinside
inclusion corrections

Revision 1.2  2004/04/26 08:07:09  melanie
*** empty log message ***

Revision 1.1.1.1  2004/04/01 09:20:27  ddinside
Création du projet CMS Couleur Citron nom de code : tipunch

Revision 1.4  2004/02/12 15:56:16  ddinside
mise à jour plein de choses en fait, mais je sais plus quoi parce que ça fait longtemps que je l'avais pas fait.
Mea Culpa...

Revision 1.3  2004/01/07 18:27:37  ddinside
première mise à niveau pour plein de choses

Revision 1.2  2003/11/26 13:08:42  ddinside
nettoyage des fichiers temporaires commités par erreur
ajout config spaw
corrections bug menu et positionnement des divs

Revision 1.1  2003/10/16 21:19:46  ddinside
suite dev gestio ndes composants
ajout librairies d'images
suppressions fichiers vi
ajout gabarit

Revision 1.2  2003/10/07 08:15:53  ddinside
ajout gestion de l'arborescence des composants

Revision 1.1  2003/09/25 14:55:16  ddinside
gestion de l'arborescene des composants : ajout
*/

// sopnthus 17/06/05
// ajout id_site

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/arbominisite.lib.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/composants.lib.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/selectSite.php');

activateMenu('gestionbrique');  //permet de dérouler le menu contextuellement

// site connecté
if ($idSite == "") $idSite = $_SESSION['idSite_travail'];


$virtualPath = 0;
if (strlen($_GET['v_comp_path']) > 0) $virtualPath = $_GET['v_comp_path'];

?>
<script type="text/javascript">document.title="Arbo - Briques";</script>
<link rel="stylesheet" type="text/css" href="/backoffice/cms/css/bo.css">
<script src="/backoffice/cms/js/preview.js" type="text/javascript"></script>

<script type="text/javascript">
<!--

	// affinage de la liste des briques
	// bandeau de recherche
	function recherche()
	{
		document.managetree.action = "arbo_browse.php";
		document.managetree.submit();
	}

-->
</script>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style><form name="managetree" action="arboaddnode.php" method="post">

<input type="hidden" name="urlRetour" value="<?php echo $_POST['urlRetour']; ?>">
<input type="hidden" name="idSite" value="<?php echo $idSite; ?>">

<div class="arbo"><b>Liste des briques</b></div>

<?php
// site de travail
if (DEF_MENUS_MINISITES == "ON") print(putAfficheSite());
?>

      <div><img src="../../backoffice/cms/img/tt_gestion_brique.gif" width="127" height="18"></div>
      <div> &gt; <?php echo getAbsolutePathString($idSite, $db, $virtualPath); ?></div>

      
      <div class="arbo_col_content">
	<div class="arbo_col_gauche">
		<div class="bloc_titre">Arborescence :</div>
                <div class="arborescence">
                    <?php
                    print drawCompTree($idSite, $db, $virtualPath, null);
                    ?>
                </div>
                
                
                
        </div>
          <div class="arbo_col_droite">
              <p>Composants contenus dans le dossier en cours:</p>
               <table border="1" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF" class="arbo">
               <tr>
                     <td align="center" bgcolor="E6E6E6"><strong>&nbsp;&nbsp;Nom</strong></td>
                     <td width="5" align="center" bgcolor="EEEEEE"><strong>&nbsp;&nbsp;Dimensions</strong></td>
                     <td align="center" bgcolor="E6E6E6"><strong>&nbsp;&nbsp;Type</strong></td>
                     <td width="5" align="center" bgcolor="EEEEEE"><strong>&nbsp;&nbsp;Modifier</strong></td>
                     <td align="center" bgcolor="E6E6E6"><strong>&nbsp;&nbsp;Déplacer</strong></td>
                     <td align="center" bgcolor="EEEEEE"><strong>&nbsp;&nbsp;Dupliquer</strong></td>
                     <td align="center" bgcolor="E6E6E6"><strong>&nbsp;&nbsp;Supprimer</strong></td>
                     <td align="center" bgcolor="EEEEEE"><strong>Pages concernées</strong></td>
               </tr>
               <?php
                     $contenus = getFolderComposants($idSite, $virtualPath);
                     if(!is_array($contenus)) {
             ?>
               <tr>
                 <td align="center" colspan="9"><strong>&nbsp;Aucun élément à afficher</strong>
               </tr>
             <?php
                     } else {
                             foreach ($contenus as $k => $composant) {
                                     $nb_pages = newSizeOf(getPageUsingComposant($idSite, $composant['id']));

                                     // page de modif de la brique
                                     if ($composant['type'] == "Graphique") $sPageEdit = "graphic";
                                     else if ($composant['type'] == "formulaire") {
                                             $sPageEdit = "form";
                                             $sParam = "step=init&";
                                     }
                                     else if ($composant['type'] == "formulaireHTML") {
                                             $sPageEdit = "formulaire";
                                             $sParam = "step=init&";
                                     }
                                     else  $sPageEdit = "content";

                                     // site de la brique
                                     $oCms_site = new Cms_site($composant['id_site']);
             ?>
               <tr>
                <td align="center" bgcolor="F3F3F3">&nbsp;<a href="#" onClick="javascript:preview_content(<?php echo $composant['id'];?>,<?php echo $composant['width'];?>,<?php echo $composant['height'];?>)"><?php echo $composant['name'];?></a>&nbsp;</td>
                <td align="center" bgcolor="F7F7F7">&nbsp;<?php echo $composant['width'];?>x<?php echo $composant['height'];?>&nbsp;</td>
                <td align="center" bgcolor="F3F3F3">&nbsp;<?php echo $composant['type'];?>&nbsp;</td>
                <!-- CC Mkl : Modif si brique Graphique => edition avec la page graphicEditor.php -->
                <td align="center" bgcolor="F7F7F7">&nbsp;<a href="<?php echo $sPageEdit; ?>Editor.php?<?php echo $sParam; ?>id=<?php echo $composant['id'];?>&idSite=<?php echo $idSite; ?>&minisite=<?php echo $_GET['minisite']; ?>"><img onMouseOver='popup("Modifier la brique");' onMouseOut='kill();' src="/backoffice/cms/img/2013/icone/modifier.png" border="0"></a>&nbsp;</td>
                <td align="center" bgcolor="F3F3F3">&nbsp;<a href="moveComposant.php?id=<?php echo $composant['id'];?>"><img src="/backoffice/cms/img/2013/icone/deplacer.png" border="0"></a>&nbsp;</td>
                <td align="center" bgcolor="F7F7F7">&nbsp;<a href="duplicate<?php echo ($composant['type']=="Graphique")?"graphic":""; ?>Composant.php?id=<?php echo $composant['id'];?>"><img src="/backoffice/cms/img/2013/icone/dupliquer.png" border="0"></a>&nbsp;</td>
                <td align="center" bgcolor="F3F3F3">&nbsp;<a href="#" onClick="if(window.confirm('Etes vous sur(e) de vouloir supprimer cette brique ?')){ document.location='deleteComposant.php?id=<?php echo $composant['id'];?>';}"><img src="/backoffice/cms/img/2013/icone/supprimer.png" border="0"></a>&nbsp;</td>
                <td align="center" bgcolor="F7F7F7">&nbsp;<?php echo $nb_pages; ?>&nbsp;</td>
               </tr>
             <?php
                             }
                     }
               ?>
               </table>
                            <br>
                     <table cellpadding="8" cellspacing="0" border="1" bordercolor="#FFFFFF">
                       <tr>
                         <td bgcolor="D2D2D2"><?php
              print drawCompTree($idSite, $db, $virtualPath, null);
              ?></td>
                       </tr>
                   </table></td>
                <td colspan="3" class="arbo2"><span class="arbo2"><img src="/backoffice/cms/img/vide.gif" width="15"></span></td>
                <td align="left" valign="top" class="arbo2">
                <!-- contenu du dossier -->
                 <u><b><small><br>

                </td>
               </tr>
              </table>
          </div>
          
          
      </div>
    
    
    
       
</form>