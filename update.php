<?php

require_once('common.php');


$id_inter = $_POST['id_intervention'];
$report = $_POST['report'];
$duration = $_POST['duration'];
$distance = $_POST['distance'];
if(!isset($id_inter) || $report = "" || $duration = "00:00:00.0000000"){
 echo "";
 header('http/1.1 403 Forbiden');
 return;
}

// si l'intervention fini, on ajouter en base le temps et le rapport et on passe pending à 1

$requete = $db->prepare("UPDATE interventions SET pending = 1, report ='$report', duration='$duration', distance='$distance'  where id = '$id_inter'");
$requete->execute();

?>
