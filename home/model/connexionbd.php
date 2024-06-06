<?php
function connexion()
{
    define('BD_HOST', 'localhost');
    define('BD_DBNAME', 'sae');
    define('BD_USER', 'root');
    define('BD_PWD', ''); //definie le login à la bd
    try {
        $bdd = new PDO('mysql:host=' . BD_HOST . ';dbname=' . BD_DBNAME . ';charset=utf8', BD_USER, BD_PWD);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $bdd;
    } catch (Exception $e) {
        die(' Erreur : ' . $e->getMessage()); //erreur en cas de prblm
    }
}
?>