<?php
include '../../../inc/dbinfo.inc';
$postdata = file_get_contents("php://input");
if(isset($postdata)){
    $request = json_decode($postdata);
    $userId = (int)$request->id_user;
    $recetteId = (int)$request->id_recette;
    //$userId = (int)$_GET["userId"];
    //$recetteId = (int)$_GET["recetteId"];
    try{
        $contenu = $pdo->prepare('SELECT count(REP_Identifier) AS NBOCC FROM repas WHERE REP_RecetteIdentifier=? AND REP_UserIdentifier=?');
        $contenu->execute(array($recetteId,$userId));
        $nb = $contenu->fetch();
        $code = true;
        $message = "Récupération réussie";
        $response = array("success"=>$code,"message"=>$message,"nb"=>(int)$nb["NBOCC"]);
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