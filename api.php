<?php


header('Content-Type: application/json');
//$_POST['id_intervention']= 299998;
//$_POST['id_client']= 299998;
//$_POST['id_motive']= 299998;
$_POST['action'] = 'fin';

try{
    $db = new PDO('sqlsrv:Server=wserver.area42.fr;Database=mygavoltpins', 'mygavolt', 'k2Y*bswsaFyss3j7*Hsf',array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
    ));
    $retour["success"] = true;
    $retour["message"] = "Connexion gf base";

}catch(PDOException $e){
    $retour["success"] = false;
    $retour["message"] = "Connexion à la base de donnée impossible";
}


if(isset($_POST['action'])){
  $retour["action"] = $_POST['action'];
}
else{
  $retour["action"] = "no";
}


//##############################################################Interventions#####################################################################

if(!empty($_POST['id_intervention'])){
  $id = $_POST['id_intervention'];
  $requete  = $db->prepare("SELECT * FROM interventions where id ='$id' order by date_inter");
  $requete->execute();

}
else{
  $requete = $db->prepare("SELECT * FROM interventions order by date_inter");
$requete->execute();
}

$retour["interventions"]["nb"] = count($requete->fetch(PDO::FETCH_OBJ));
$requete->execute();
$retour["interventions"]["liste_int"] = $requete->fetch(PDO::FETCH_OBJ);

//##############################################################Clients#####################################################################

if(!empty($_POST['id_client'])){
  $id = $_POST['id_client'];
  $requete  = $db->prepare("SELECT * FROM clients where id ='$id'");
  $requete->execute();

}
else{
  $requete = $db->prepare("SELECT * FROM clients");
$requete->execute();
}

$retour["clients"]["nb"] = count($requete->fetch(PDO::FETCH_OBJ));
$requete->execute();
$retour["clients"]["liste_cli"] = $requete->fetch(PDO::FETCH_OBJ);

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

$retour["motifs"]["nb"] = count($requete->fetch(PDO::FETCH_OBJ));
$requete->execute();
$retour["motifs"]["liste_mot"] = $requete->fetch(PDO::FETCH_OBJ);






$_POST['report']  = "prout";
$_POST['duration']= "01:00:00";
$_POST['pending'] = 1;
$_POST['action']  = 'fin';
$_POST['id_inter']= '100000';



//##############################################################Interventions#####################################################################

if(!empty($_POST['action']) && !empty($_POST['id_inter'])){

  $id_inter=$_POST['id_inter'];

  if($_POST['action'] == 'debut'){
    // si l'intervention commence, on passe pending à 0
     $requete  = $db->prepare("UPDATE interventions SET pending = 0 where id = '$id_inter'");
     $requete->execute();
    }
  else if ($_POST['action'] == 'fin'){

    if(!empty($_POST['report']) && !empty($_POST['duration'])){

      // si l'intervention fini, on ajouter en base le temps et le rapport et on passe pending à 1
      $duration = $_POST['duration'];
      $report  = $_POST['report'];
      $requete = $db->prepare("UPDATE interventions SET pending = 1, report ='$report', duration='$duration'  where id = '$id_inter'");
      $requete->execute();

    }
    else{
      $retour["modif_intervention"]= false;
    }


  }
  else{
    $retour["modif_intervention"]= "code action incorrect";
  }





}
else{
  $retour["modif_intervention"]= false;
}


echo json_encode($retour);


