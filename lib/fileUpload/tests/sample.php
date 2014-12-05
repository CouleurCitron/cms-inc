<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
function pArray($array) {
	print '<pre style="background:#faebd7">';
	print_r($array);
	print '</pre>';
}

// Chargement de la classe
require_once('../upload.class.php');

// Instanciation d'un nouvel objet "upload"
$Upload = new Upload();

/**
 * Gestion lors de la soumission du formulaire
 **/

if (!Empty($_POST['submit'])) {
    // Si vous voulez renommer le fichier...
    //$Upload-> Filename     = 'fichier';
    
    // Si vous voulez ajouter un préfixe au nom du fichier...
    //$Upload-> Prefixe = 'pre_';
    
    // Si vous voulez ajouter un suffixe au nom du fichier...
    //$Upload-> Suffice = '_suf';
    
    // Pour changer le mode d'écriture (entre 0 et 3)
    //$Upload-> WriteMode    = 0;
    
    // Pour filtrer les fichiers par extension
    $Upload-> Extension = '.gif;.jpg;.jpeg;.bmp;.png';
    
    // Pour filtrer les fichiers par entête
    //$Upload-> MimeType  = 'image/gif;image/pjpeg;image/bmp;image/x-png'; 
    
    // Pour tester la largeur / hauteur d'une image
    //$Upload-> ImgMaxHeight = 200;
    //$Upload-> ImgMaxWidth  = 200;
    //$Upload-> ImgMinHeight = 100;
    //$Upload-> ImgMinWidth  = 100;
    
    // Pour vérifier la page appelante
    //$Upload-> CheckReferer = 'http://mondomaine/mon_chemin/mon_fichier.php';
    
    // Pour générer une erreur si les champs sont obligatoires
    //$Upload-> Required     = false;
    
    // Pour interdire automatiquement tous les fichiers considérés comme "dangereux"
    //$Upload-> SecurityMax  = true;
    
    // Définition du répertoire de destination
    $Upload-> DirUpload    = '.';
    
    // On lance la procédure d'upload
    $Upload-> Execute();
    
    // Gestion erreur / succès
    if ($UploadError) {
        print 'Il y a eu une erreur :'; 
        pArray($Upload-> GetError());
    } else {
        print 'Upload effectuée avec succès :';
        pArray($Upload-> GetSummary());
    }
}

/**
 * Création du formulaire
 **/

// Pour limiter la taille d'un fichier (exprimée en ko)
$Upload-> MaxFilesize  = '1024';

// Pour ajouter des attributs aux champs de type file
$Upload-> FieldOptions = 'style="border-color:black;border-width:1px;"';

// Pour indiquer le nombre de champs désiré
$Upload-> Fields       = 2;

// Initialisation du formulaire
$Upload-> InitForm();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Exemple Classe Upload</title>
</head>

<body>
<form method="post" enctype="multipart/form-data" name="formulaire" id="formulaire" action="sample.php">
<?php
// Affichage du champ MAX_FILE_SIZE
print $Upload-> Field[0];

// Affichage du premier champ de type FILE
print $Upload-> Field[1] . '<br>';

// Affichage du second champ de type FILE
print $Upload-> Field[2];
?>
<br>
<input type="submit" value="Envoyer" name="submit">
</form>
</body>
</html>
