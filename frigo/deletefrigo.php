<?php
include '../../../inc/dbinfo.inc';
$postdata = file_get_contents("php://input");
if(isset($postdata)){
    $request = json_decode($postdata);
    $userId = (int)$request->id_user;

    $statement = "DELETE FROM frigo2 WHERE FRI_UserIdentifier= ?";
    try{
        $req = $pdo->prepare($statement);
        $req->execute(array($userId));
        $code = true;
        $message = "frigo supprimé";
        $response = array("success"=>$code,"message"=>$message);
    } catch (Exception $ex) {
        $code = false;
        $message = $ex->getMessage();
        $response = array("success"=>$code,"message"=>$message);
    }   
}
else
{
    $code = false;
    $message = "Données non valide";
    $response = array("success"=>$code,"message"=>$message);
}
$pdo = NULL;
echo json_encode($response);