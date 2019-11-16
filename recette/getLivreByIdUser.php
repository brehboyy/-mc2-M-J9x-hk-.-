<?php
require '../connexion.php';
$postdata = file_get_contents("php://input");
if(isset($postdata)){
    try{
        $request = json_decode($postdata);
        
        $userId = (int)$request->id_user;
        $contenu = $pdo->prepare(
            'SELECT 
                `REC_Identifier`,
                `REC_Title`,
                `REC_Category`,
                `REC_Rating`,
                `REC_Description`,
                `REC_Photourl` 
            FROM 
                recette r 
                INNER JOIN (
                    SELECT DISTINCT 
                        RECING_RecetteIdentifier
                    FROM 
                        recette_ingredient2
                    WHERE 
                        RECING_RecetteIdentifier 
                        NOT IN 
                        (
                            SELECT DISTINCT 
                                RECING_RecetteIdentifier 
                            FROM 
                                recette_ingredient2 ri 
                            WHERE 
                                `RECING_IngredientIdentifier` 
                                NOT IN (
                                    SELECT
                                        f.FRI_MasterIngredientIdentifier 
                                    FROM 
                                        frigo2 f 
                                    WHERE 
                                        f.FRI_UserIdentifier = ?
                                )
                        )
                ) 
                l ON r.REC_Identifier = l.RECING_RECETTEIdentifier'
        );
        $contenu->execute(array($userId));
        $liste = $contenu->fetchAll(PDO::FETCH_ASSOC);
        $response = array();
        $code = true;
        $response = array("success"=>$code,"result"=>$liste);
    }catch(Exeption $e){
        $response = array();
        $code = false;
        $message = "Pb dans l'api!!!";
        $response = array("success"=>$code,"message"=>$e);
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