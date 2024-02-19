<?php
//on appelle la page init.inc.php
require('asset/config/init.inc.php');
//on affiche le tableau associatif $_FILES
// echo "<pre>";
// print_r($_FILES);
// echo "</pre>";

//création d'une fonction add qui va permettre d'insérer le nom de l'image dans la base de données
function add(){
    //création variable $filename qui prend le nom de l'image comme sur la machine de l'utilisateur
    $filename = $_FILES['image']['name'];
    //création variable $filenameTmp qui prend le nom temporaire de l'image dans le serveur
    $filenameTmp = $_FILES['image']['tmp_name'];
    //on appelle la variable $pdo à l'aide de global puisqu'elle est en dehors de la fonction
    global $pdo;
    //création d'une variable $newFile qui permet de rajouter un identifiant unique(uniqid) avant le nom de l'image
    $newFile = uniqid() . '-' . $filename;
    //fonction copy permet de créer une copie du fichier uploadé dans une nouvelle location (ici le dossier uploads)
    copy($filenameTmp, 'uploads/' . $newFile);

    //------------------------------- partie base de données--------------------------------------------------------------

    //remplissage de la bdd avec les infos rentrées dans le formulaire
    //insertion des données à l'aide de la méthode prepare
    extract($_POST);
    $pdoStatement = $pdo->prepare(
        'INSERT INTO `img`(`name`, `picture`) VALUES (
        :name,
        :picture
    )');
    //association à l'aide de la méthode bindValue des marqueurs nominatifs aux valeurs
    $pdoStatement->bindValue('name',$name,PDO::PARAM_STR);
    $pdoStatement->bindValue('picture',$newFile,PDO::PARAM_STR);
    //exécution de la requête préparée dans prepare à l'aide de la méthode execute
    $pdoStatement->execute();
    //redirection vers la page index pour que le formulaire redevienne vide et éviter la répétition d'images à chaque rafraîchissement
    header('location: index.php');
}

//création de la fonction qui va afficher nos images
function printP(){
    global $pdo;
    //on va récuperer les images dans le dossier uploads pour les afficher grâce à la base de données
    $pdoCount = $pdo->prepare('SELECT * FROM `img`');//on sélectionne tous les champs de la table img
    //execution de la requête
    $pdoCount->execute();
    $resultat = $pdoCount->fetchAll(PDO::FETCH_ASSOC);//on récupère sous forme d'un tableau associatif tout les éléments de notre base de données grâce à la méthode fetchAll

    // echo "<pre>";
    // var_dump($resultat);
    // echo "</pre>";

    //création d'une boucle for
    //initialisation d'une variable $i à 0
    //tant que $i est inférieur ou égal au nombre de lignes -1 dans la table img on entre dans la boucle
    //itération avec incrémentation de $i
    for($i=0;$i<=count($resultat)-1;$i++){//chaque tour de boucle va représenter une ligne de notre tableau
        //début de la ligne
        echo "<tr>";
        //première colonne
        echo "<td>";
        echo $resultat[$i]["name"];//on récupère le nom de l'image dans notre tableau $resultat
        echo "</td>";

        //deuxième colonne
        echo "<td>";
        ?>
        <!-- on récupère dans le dossier uploads l'image avec le bon nom (uniqid-nom.png)  -->
        <img src="uploads/<?=$resultat[$i]['picture'];?>" width="150px">
        <?php
        echo "</td>";
        //troisième colonne
        echo "<td>";
        ?>
        <!-- utilisation de la super globale $_GET grâce à un lien hyperText afin de supprimer les images en récupérant l'id de l'image à supprimer -->
        <a href="index.php?delete_image=<?=$resultat[$i]['id_picture']  ;?>" id="delete">supprimer</a>
        <?php
        echo "</td>";
        
        echo "</tr>";
    }
}


//création de la fonction qui va supprimer nos image dans notre base de données et dans notre fichiers uploads

function delete($positionImage){
    global $pdo;

    //------------------------------------------------ partie fichier uploads--------------------------------------------------------------

    //préparation de la requête
    $pdoCount = $pdo->prepare('SELECT * FROM `img`');
    //execution de la requête
    $pdoCount->execute();
    $resultat = $pdoCount->fetchAll(PDO::FETCH_ASSOC);

    //utilisation de unlink pour supprimer dans le dossier uploads les images que l'utilisateurs souhaite supprimer 
    unlink("uploads/" . $resultat[$positionImage-1]['picture']);
    //------------------------------------------------ partie base de données--------------------------------------------------------------
    
    //préparation de la requête
    $pdoDelete = $pdo->prepare("DELETE FROM `img` WHERE id_picture = $positionImage;
    -- réinitialisation de l'id de l'image à la première position dans la table img à 1 lorqu'on a supprimé des images
    SET @num := 0;
    UPDATE img SET id_picture = @num := (@num+1);
    ALTER TABLE img AUTO_INCREMENT = 1;");
    //exécution de la requête
    $pdoDelete->execute();
}
