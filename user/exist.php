<?php
include '../../../inc/dbinfo.inc';
$postdata = file_get_contents("php://input");

if (isset($postdata))
{	 
    try{
        $request = json_decode($postdata);
        $idUser = $request->id_user;
        $contenu = $pdo->prepare('SELECT TRUE FROM user WHERE USER_Identifier = ?');
        $contenu->execute(array($idUser));
        $liste = $contenu->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($liste);

        if(count($liste)>0)
        {
            $code = true;
            $message = "Utilisateur existant";
            $res = array("success" => $code, "message" => $message);
        }
        else
        {
            $code = false;
            $message = "Utilisateur inexsistant";
            $res = array("success" => $code, "message" => $message);
            
        }
    }catch(Exception $ex){
        $res = array("success" => false, "message" => $ex->getMessage());
    }
    
}
else
{
    $code = false;
    $message = "Données non valide";
    $res = array("success"=>$code,"message"=>$message);
}
$pdo = null;
echo json_encode($res);