<?php
require '../connexion.php';
$postdata = file_get_contents("php://input");

if(isset($postdata)){
    $request = json_decode($postdata);
    $userId = (int)$request->id_user;

    try{
        $contenu = $pdo->prepare('SELECT REC_Identifier, REC_Title, REC_PhotoUrl, REP_Identifier as id,REP_Date as date FROM repas rep INNER JOIN recette rec ON rec.REC_Identifier = rep.REP_RecetteIdentifier WHERE REP_UserIdentifier=?');
        $contenu->execute(array($userId));
        $liste = $contenu->fetchAll(PDO::FETCH_ASSOC);
    
        $lstRepas = array();
        
        foreach ($liste as $key => $value) {

            $heure = idate('H', strtotime($value['date']));
            switch ($heure) {
                case 9:
                $liste[$key]["moment"] = "matin";
                    # code...
                    break;
                case 12:
                $liste[$key]["moment"] = "midi";
                    # code...
                    break;
                case 16:
                $liste[$key]["moment"] = "collation";
                    # code...
                    break;
                case 19:
                $liste[$key]["moment"] = "soir";
                    # code...
                    break;
                default:
                    # code...
                    break;
            }
        }
        $code = true;
        $message = "Récupération réussie";
        $response = array("success"=>$code,"message"=>$message,"mealplanner"=>$liste);
    } catch (Exception $ex) {
        $code = false;
        $message = "Utilisateur Inconnu";
        $response = array("success"=>$code,"message"=>$ex->getMessage());
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