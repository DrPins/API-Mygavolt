<?php


header('Content-Type: application/json');
//$_POST['id_intervention']= 1000;
//$_POST['id_motive']= 299998;
//$_POST['id_client']= 299998;
//$_POST['lastname'] = 'Ramoloss';
//$_POST['action'] = 'fin';
//$_POST['report'] = 'tata';
//$_POST['duration'] = '02:00:00';

//##############################################################Connexion à la base#####################################################################
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

//##############################################################Affichage des valeurs des variables #####################################################
if(isset($_POST['action'])){
  $retour["action"] = $_POST['action'];
}
else{
  $retour["action"] = "no";
}



if(isset($_POST['report'])){
  $retour["report"] = $_POST['report'];
}
else{
  $retour["report"] = "";
}



if(isset($_POST['duration'])){
  $retour["duration"] = $_POST['duration'];
}
else{
  $retour["duration"] = "";
}


//var_dump($_POST);
//var_dump($retour);

/*
if(isset($_POST['action'])){
  $retour["action"] = $_POST['action'];
}
else{
  $retour["action"] = "no";
}

$retour["action"] = $_POST['action'];
$retour["modif_intervention"] = "non";
$retour["id_inter"] = $_POST['id_intervention'];
$retour["report"] = $_POST['report'];
$retour["duration"] = $_POST['duration'];
*/

//##############################################################Interventions#####################################################################



if(isset($_POST['id_intervention']) && isset($_POST['lastname']) ){

  //selection d'une seule intervention
  $id_inter = $_POST['id_intervention'];
  $lastname = $_POST['lastname'];



  $requete  = $db->prepare("SELECT interventions.id as id_inter, date_inter, clients.firstname, clients.lastname, company, address1, address2, clients.zipcode, clients.city, clients.phone, motives.label as motive,  report, pending, duration
                            from interventions
                            inner join clients on clients.id = id_client
                            inner join motives on motives.id = id_motive
                            inner join employees on id_employee = employees.id
                            where employees.lastname ='$lastname' and interventions.id = $id_intervention");
  $requete->execute();

  $retour["employeeFound"] = count($requete->fetchAll());
  $retour["employee"] = $_POST['lastname'];

}
else if (isset($_POST['lastname'])){
  // selection de toutes les interventions d'un employé
  $lastname = $_POST['lastname'];


  $requete = $db->prepare("SELECT interventions.id as id_inter, date_inter, clients.firstname, clients.lastname, company, address1, address2, clients.zipcode, clients.city, clients.phone, motives.label as motive,  report, pending, duration
                            from interventions
                            inner join clients on clients.id = id_client
                            inner join motives on motives.id = id_motive
                            inner join employees on id_employee = employees.id
                            where employees.lastname ='$lastname' order by date_inter");
  $requete->execute();

  $retour["employeeFound"] = count($requete->fetchAll());
  $retour["employee"] = $_POST['lastname'];
}

$retour["id_intervention_total"]["nb"] = count($requete->fetchAll());
$requete->execute();
$retour["id_intervention_total"]["liste_int"] = $requete->fetchAll();

//##############################################################Clients#####################################################################
/*
if(!empty($_POST['id_client'])){
  $id = $_POST['id_client'];
  $requete  = $db->prepare("SELECT * FROM clients where id ='$id'");
  $requete->execute();

}
else{
  $requete = $db->prepare("SELECT * FROM clients");
$requete->execute();
}

$retour["clients"]["nb"] = count($requete->fetchAll());
$requete->execute();
$retour["clients"]["liste_cli"] = $requete->fetchAll();

//##############################################################Motives#####################################################################

if(!empty($_POST['id_motive'])){
  $id = $_POST['id_motive'];
  $requete  = $db->prepare("SELECT * FROM motives where id ='$id'");
  $requete->execute();

}
else{
  $requete = $db->prepare("SELECT * FROM motives");
$requete->execute();
}

$retour["motifs"]["nb"] = count($requete->fetchAll());
$requete->execute();
$retour["motifs"]["liste_mot"] = $requete->fetchAll();






//$_POST['report']  = "prout";
//$_POST['duration']= "01:00:00";
//$_POST['pending'] = 1;
//$_POST['action']  = 'fin';
//$_POST['id_inter']= '100000';

*/

//##############################################################Interventions#####################################################################


//echo $_POST['action'];
//echo $_POST['id_intervention'];


if(isset($_POST['action']) && isset($_POST['id_intervention'])){

  $id_inter=$_POST['id_intervention'];

  if($_POST['action'] == 'debut'){
    // si l'intervention coid_interventionmmence, on passe pending à 0
     $requete  = $db->prepare("UPDATE interventions SET pending = 0 where id = '$id_inter'");
     $requete->execute();
    }
  else if ($_POST['action'] == 'fin'){

    if(isset($_POST['report']) && isset($_POST['duration'])){



      // si l'intervention fini, on ajouter en base le temps et le rapport et on passe pending à 1
      $duration = $_POST['duration'];
      $report  = $_POST['report'];

      //echo "UPDATE interventions SET pending = 1, report ='$report', duration='$duration'  where id = '$id_inter'";

      $requete = $db->prepare("UPDATE interventions SET pending = 1, report ='$report', duration='$duration'  where id = '$id_inter'");
      $requete->execute();

    }
    else{
      $retour["modif_intervention"]= 'premiere boucle ok';
    }


  }
  else{
    $retour["modif_intervention"]= "code action incorrect";
  }





}
else{
  $retour["modif_intervention"]= 'pas rendtre dans la boucle';
}


echo json_encode($retour);


