<?php
include '../../../inc/dbinfo.inc';
$postdata = file_get_contents("php://input");
if(isset($postdata)){
    $request = json_decode($postdata);
    $recetteId = (int)$request->id_recette;
    $userId = (int)$request->id_user;
    $moment = $request->moment;
    try{
        $contenu = $pdo->prepare("SELECT REP_Identifier FROM repas WHERE REP_UserIdentifier=?  AND REP_RecetteIdentifier=? AND REP_Date=?");
        $contenu->execute(array($userId,$recetteId,$moment));
        $liste = $contenu->fetchAll();
        if(count($liste) == 0){
            $message = "Repas inexistant";
            $code = false;
            $response = array("success"=>$code,"message"=>$message);
        }else{
            $response = array();
            $code = true;
            $message = "Repas déja existant";
            $response = array("success"=>$code,"message"=>$message);
        }
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