<?php
header('Content-Type: application/json');
require_once('common.php');



$lastname = $_POST['lastname'];
if(!isset($lastname)){
 echo "";
 header('http/1.1 403 Forbiden');
 retun;
}


$requete = $db->prepare("SELECT interventions.id as id_inter, date_inter, time_inter, clients.firstname, clients.lastname, company, address1, address2, clients.zipcode, clients.city, clients.phone, motives.label as motive,  report, pending, duration
                          from interventions
                          inner join clients on clients.id = id_client
                          inner join motives on motives.id = id_motive
                          inner join employees on id_employee = employees.id
                          where employees.lastname ='$lastname' and date_inter >= getdate()  order by pending, date_inter DESC ");

$requete->execute();
$result = $requete->fetchAll();

// a faire : traiter l'erreur de requete PDO

$retour["nb"] = count($result);

$retour["liste_int"] = $result;

echo json_encode($retour);
?>
