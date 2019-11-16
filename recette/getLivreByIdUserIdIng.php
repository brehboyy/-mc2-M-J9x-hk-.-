<?php
require '../connexion.php';
$postdata = file_get_contents("php://input");
if(isset($postdata)){
    $request = json_decode($postdata);
    $userId = (int)$request->id_user;
    $ingredientId = (int)$request->id_ingredient;
    $contenu = $pdo->prepare(
     'SELECT 
     `REC_Identifier`,
     `REC_Title`,
     `REC_Category`,
     `REC_Rating`,
     `REC_Description`,
     `REC_Photourl` 
 FROM 
     Recette r 
     INNER JOIN (
         SELECT DISTINCT 
             RECING_RecetteIdentifier
         FROM 
             recette_ingredient2
         WHERE 
             RECING_RecetteIdentifier = ? 
             AND RECING_RecetteIdentifier
             NOT IN 
             (
                SELECT DISTINCT 
                    RECING_RecetteIdentifier
                FROM 
                    recette_ingredient2 ri 
                    INNER JOIN ingredient i ON ri.RECING_IngredientIdentifier = i.ING_Identifier
                WHERE 
                    i.ING_Option = TRUE 
                    OR 
                    `RECING_IngredientIdentifier` 
                    NOT IN (
                        SELECT
                            f.FRI_MasterIngredientIdentifier 
                        FROM 
                            Frigo2 f 
                        WHERE 
                            f.FRI_UserIdentifier = ?
                    )
             )
     ) 
     l ON r.REC_Identifier = l.RECING_RECETTEIdentifier'
    );
    $contenu->execute(array($userId, $ingredientId));
    $liste = $contenu->fetchAll(PDO::FETCH_ASSOC);
    $response = array();
    $code = true;
    $response = array("success"=>$code,"result"=>$liste);
    echo json_encode($response);
}
else
{
    $response = array();
    $code = false;
    $message = "Données non valide";
    $response = array("success"=>$code,"message"=>$message);
    echo json_encode($response);
}
$pdo = NULL;
echo json_encode($response);