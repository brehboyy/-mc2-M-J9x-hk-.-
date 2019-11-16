<?php
require '../connexion.php';
$postdata = file_get_contents("php://input");
if(isset($postdata)){
    $request = json_decode($postdata);
    $ingredientId = $request->id_ingredient;
    $userId = (int)$request->id_user;

    $statement = "DELETE FROM frigo2 WHERE FRI_UserIdentifier=? AND FRI_MasterIngredientIdentifier=?";
    try{
        $req = $pdo->prepare($statement);
        $req->execute(array($userId, $ingredientId));
        $code = true;
        $message = "Ingrédient supprimé";
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