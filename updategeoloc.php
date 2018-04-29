<?php

require_once('common.php');


$lastname = $_POST['lastname'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];


if(!isset($lastname) || !isset($coordinates)){
 echo "";
 header('http/1.1 403 Forbiden');
 retun;
}

//$id = $s->id;

// si l'intervention fini, on ajouter en base le temps et le rapport et on passe pending à 1

$requete = $db->prepare("INSERT INTO positions (id_employee, date_position, lat, lng) VALUES ((select id from employees where lastname = '$lastname'),GETDATE(), '$lat', '$lng' )");
$requete->execute();

?>
