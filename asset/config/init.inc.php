<?php
//page de connexion à la base de données

//gestion des erreurs
ini_set('display_errors',1);
error_reporting(E_ALL);

//configuration de la base de données
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,//permet d'afficher les erreurs, en mode prod on utilise ERRMODE_SILENT pour éviter d'afficher les erreurs à de potentiels hackers
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',//permet de définir le charset
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,//permet de récupérer les données sous la forme d'un tableau associatif
];

//définition d'une constante nommée CONNECTBDD qui relie notre bdd
define('CONNECTBDD',array(
    'type'=>'mysql',//type de la bdd
    'host'=>'mysql-projet-gestion-images.alwaysdata.net',//hôte de la bdd
    'user'=>'347675_img',//utilisateur
    'pass'=>'#5sqapqwfTBykscs56yeJKQqKD?',//mdp vide sous windows
    'database'=>'projet-gestion-images_mohamed'//nom de la bdd exploitée
));

//méthode du try and catch afin de tester le code et de détecter les potentielles erreurs
try{
    //création d'une instance $pdo pour la class PDO qui relie une base de données sql à un fichier php
    $pdo = new PDO(CONNECTBDD['type'] . ':host=' . CONNECTBDD['host'] . ';dbname=' . CONNECTBDD['database'],CONNECTBDD['user'],CONNECTBDD['pass'],$options);
}catch(PDOException $e){
    //PDOException est une class qui représente les erreurs émises par la class PDO
    //$e représente un objet de la class PDOException
    die('<p>Erreur lors de la connexion à la base de données : ' . $e->getMessage() . '</p>');
    //getMessage est une méthode de la class PDOException qui s'applique à l'objet $e et qui affiche le message d'erreur
}
