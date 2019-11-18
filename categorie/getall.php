<?php
include '../../../inc/dbinfo.inc';

try{
    $statement = "SELECT CAT_Identifier, CAT_Name FROM categorie";
    $contenu = $pdo->prepare($statement);
    $contenu->execute();
    $liste = $contenu->fetchAll(PDO::FETCH_ASSOC);
    $code = true;
    $message = "Ajout réussi";
    $response = array("success"=>$code,"message"=>$message, "result" => $liste);
}catch(Exception $ex){
    $code = false;
    $message = $ex->getMessage();
    $response = array("success"=>$code,"message"=>$message);
}
echo json_encode($response);
$pdo = NULL;