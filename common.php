<?php


try{
    $db = new PDO('sqlsrv:Server=wserver.area42.fr;Database=mygavoltpins', 'mygavolt', 'k2Y*bswsaFyss3j7*Hsf',array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
    ));
    $retour["success"] = true;
    $retour["message"] = "Connexion base";

}catch(PDOException $e){
    $retour["success"] = false;
    $retour["message"] = "Connexion à la base de donnée impossible";
}

?>
