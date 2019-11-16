<?php
require '../connexion.php';
$postdata = file_get_contents("php://input");

if (isset($postdata))
{	 
    try{
        $request = json_decode($postdata);
        $name = $request->username;
        $password = $request->password;
        $contenu = $pdo->prepare('SELECT USER_Identifier,USER_Password, USER_Login FROM user WHERE UPPER(USER_Login) =?');
        $contenu->execute(array(strtoupper($name)));
        $liste = $contenu->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($liste);

        if(count($liste)>0)
        {
            if(password_verify($password, $liste[0]['USER_Password'])){
                $code = true;
                $message = "Bienvenue dans le Bendo ".$name;
                $response = array("success" => $code, "message" => $message, "result" => $liste[0]["USER_Identifier"]);
            }else{
                $code = false;
                $message = "Mot de passe ou Username non valide";
                $response = array("success"=>$code,"message"=>$message);
            }
        //	var_dump($response);
            
        }
        else
        {
            $code = false;
            $message = "Mot de passe ou Username non valide";
            $response = array("success"=>$code,"message"=>$message);
            
        }
    }catch(Exception $ex){
        $response = array("success" => false, "message" => $ex->getMessage());
    }
    
}
else
{
    $code = false;
    $message = "Données non valide";
    $response = array("success"=>$code,"message"=>$message);
}
$pdo = null;
echo json_encode($response);