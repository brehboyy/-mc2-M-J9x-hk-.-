<?php
require '../connexion.php';
$postdata = file_get_contents("php://input");
if(isset($postdata)){
    $request = json_decode($postdata);
    $repasid = (int)$request->id_repas;

    
    try{
        $statement = $pdo->prepare("DELETE FROM repas WHERE REP_Identifier = ?");
        $statement->execute(array($repasid));
        $code = true;
        $message = "Repas supprimé";
        $response = array("success"=>$code,"message"=>$message);
    } catch (Exception $ex) {
        $response = array();
        $code = false;
        $message = $ex->getMessage();
        $response = array("success"=>$code,"message"=>$message);
    }   
}
else
{
    $response = array();
    $code = false;
    $message = "Données non valide";
    $response = array("success"=>$code,"message"=>$message);
}
$pdo = NULL;
echo json_encode($response);