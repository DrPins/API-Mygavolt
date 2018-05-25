<?php

require_once('common.php');


$id_inter = $_POST['id_intervention'];
$report = $_POST['report'];
$duration = $_POST['duration'];
if(!isset($id_inter) || !isset($report) || !isset($duration) ){
 echo "";
 header('http/1.1 403 Forbiden');
 return;
}

// si l'intervention fini, on ajouter en base le temps et le rapport et on passe pending à 1

$requete = $db->prepare("UPDATE interventions SET pending = 1, report ='$report', duration='$duration'  where id = '$id_inter'");
$requete->execute();

?>
