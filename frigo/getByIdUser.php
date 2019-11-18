<?php
include '../../../inc/dbinfo.inc';
$postdata = file_get_contents("php://input");
if(isset($postdata)){
    $request = json_decode($postdata);
    $userId = (int)$request->id_user;
    //$userId = (int)$_GET["id"];
    try{
        if(isset($userId)){
            $contenu = $pdo->prepare('SELECT mi.MI_Name, mi.MI_Identifier, mi.MI_CategorieIdentifier FROM `frigo2` f INNER JOIN masteringredient2 mi ON f.`FRI_MasterIngredientIdentifier` = mi.MI_Identifier WHERE FRI_UserIdentifier = ?');
            $contenu->execute(array($userId));
            $liste = $contenu->fetchAll(PDO::FETCH_ASSOC);
            $response = array();
            $code = true;
            $message = "Récupération réussie";
            $response = array("success"=>$code,"message"=>$message,"result"=>$liste);
        }else{
            $response = array();
            $code = false;
            $message = "Erreur mauvais format de données";
            $response = array("success"=>$code,"message"=>$message);
        }
    } catch (Exception $ex) {
        $response = array();
        $code = false;
        $message = "Utilisateur inconnu";
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