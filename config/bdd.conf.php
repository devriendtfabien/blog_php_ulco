<?php
try{
    $bdd = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'srvweb', 'srvweb' );
    $bdd->exec("set name utf8");
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Excetion $e) {
    die ('Erreur : ' .$e->getMessage());
}