<?php
require '../connexion.php';
$postdata = file_get_contents("php://input");
if(isset($postdata)){
    $request = json_decode($postdata);
    $ingredientId = $request->id_ingredient;
    $userId = intval($request->id_user);
    //$quantity = (int)$request->quantity;
    if($ingredientId != 0){
        try{
            $statement = "INSERT INTO frigo2 VALUES (NULL,?,?,?)";
            $select = $pdo->prepare($statement);
            $select->execute(array( $userId, $ingredientId, 1));
            $code = true;
            $message = "Ajout réussi";
            $response = array("success"=>$code,"message"=>$message);
        }catch(Exception $ex){
            $response = array();
            $code = false;
            $message = $ex->getMessage();
            $response = array("success"=>$code,"message"=>$message);
        }
    }
}
$pdo = NULL;
echo json_encode($response);

