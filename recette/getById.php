<?php
require '../connexion.php';
$postdata = file_get_contents("php://input");
if(isset($postdata)){
    $request = json_decode($postdata);
    $recetteId = (int)$request->id_recette;
    //$recetteId = $args['id'];
    $contenu = $pdo->prepare("SELECT r.REC_Identifier, r.REC_Title,r.REC_Description, r.REC_DescriptionFR,r.REC_Source, r.REC_PhotoUrl, r.REC_YieldNB, r.REC_Time, r.REC_ActiveTime, r.REC_CountReviews, GROUP_CONCAT(CONCAT(ri.RECING_Identifier, ' / ', i.ING_NameFR, ' / ', ri.RECING_Quantity) SEPARATOR ';') AS listIngredient FROM recette_ingredient2 ri INNER JOIN recette r ON r.REC_Identifier = ri.RECING_RecetteIdentifier INNER JOIN ingredient i ON ri.RECING_IngredientIdentifier = i.ING_Identifier WHERE REC_Identifier = ?");
    $contenu->execute(array($recetteId));
    $liste = $contenu->fetchAll(PDO::FETCH_ASSOC);
    $liste[0]['listIngredient'] = explode(";",$liste[0]['listIngredient']);
    foreach($liste[0]['listIngredient'] as $key => $value){
        $liste[0]['listIngredient'][$key]  = explode(" / ", $value);
    }
    $code = true;
    $response = array("success"=>$code,"result"=>$liste[0]);
}
else
{
    $code = false;
    $message = "Données non valide";
    $response = array("success"=>$code,"message"=>$message);
}
$pdo = NULL;
echo json_encode($response);