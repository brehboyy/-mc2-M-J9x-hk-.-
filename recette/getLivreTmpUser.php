<?php
include '../../../inc/dbinfo.inc';
$postdata = file_get_contents("php://input");
if(isset($postdata)){
    try{
        $request = json_decode($postdata);
        
        $listIngId = $request->listIngId;

        $qMarks = str_repeat('?,', count($listIngId) - 1) . '?';
        $sql = 'SELECT 
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
                        NOT IN ('.$qMarks.')
                )
        ) 
        l ON r.REC_Identifier = l.RECING_RECETTEIdentifier';
        $contenu = $pdo->prepare($sql);
        $contenu->execute($listIngId);
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