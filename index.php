<?php
//vérification bouton envoyer
extract($_POST);

//on utilise un require pour stoper le code avec une fatal error si l'inclusion de init.inc.php ne fonctionne pas
require('asset/config/functions.php');
// echo '<pre>';
// print_r($_FILES);
// echo '</pre>';
//si l'utilisateur clique sur envoyer qui a la valeur add et que le champ name est bien rempli
if(isset($_POST['envoyer']) == 'ajouter' && !empty($_POST["name"])){
    //alors on applique la fonction add
    add();
}

// echo '<pre>';
// print_r($_GET);
// echo '</pre>';

if(isset($_GET["delete_image"])){//la super global GET contient l'id des images stocker dans la base de donneés
    delete($_GET["delete_image"]);//grace a la super global GET lorsque l'on va clicker sur le lien hyper texte supprimer d'une image on va récupérer son id dans le l'url de l'image
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Images</title>
    <link rel="stylesheet" href="asset/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>Veuillez remplir le formulaire</legend>
            <label for="name">Entrez votre nom</label>
            <input type="text" name="name" id="name">
            <input type="hidden" name="MAX_FILE_SIZE" value="300000000"><!-- La value correspond à la taille de l'image, le champs MAX_FILE_SIZE est mesuré en octets -->
            <label for="image" id="images">Choisissez votre image</label>
            <input type="file" name="image" id="image">
            <input type="submit" value="ajouter" name="envoyer" id="envoyer">
        </fieldset>
    </form>
    <table>
        <tr>
            <th>Nom</th>
            <th>Image</th>
            <th>Supprimer</th>
        </tr>
        <?= printP(); ?>
    </table>

    
</body>
</html>