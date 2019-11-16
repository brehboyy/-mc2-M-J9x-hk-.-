<?php
require '../connexion.php';
$postdata = file_get_contents("php://input");
if(isset($postdata)){
$userId = (int)$request->id_user;
    try{
        $contenu = $pdo->prepare('SELECT `MI_Identifier`, `MI_Name`, `MI_CategorieIdentifier` FROM masteringredient2 WHERE `MI_Identifier` NOT IN ( SELECT f.FRI_MasterIngredientIdentifier FROM frigo2 f WHERE f.FRI_UserIdentifier = 1 )');
        $contenu->execute(array($userId));
        $liste = $contenu->fetchAll(PDO::FETCH_ASSOC);
        $code = true;
        $response = array("success"=>$code,"result"=>$liste);
    }catch(Exception $ex){
        $code = false;
        $response = array("success"=>$code,"message"=>$ex->getMessage());
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