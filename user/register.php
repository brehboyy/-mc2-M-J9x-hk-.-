<?php
include '../../../inc/dbinfo.inc';
$postdata = file_get_contents("php://input");
try{
    if (isset($postdata)) {
        $request = json_decode($postdata);
        $name = $request->username;
        $email = $request->email;
        $password = $request->password;
        $contenu = $pdo->prepare('SELECT TRUE FROM user WHERE USER_Login = ? OR USER_Email = ? LIMIT 1');
        $contenu->execute(array($name, $email));
        $liste = $contenu->fetchAll();
        if(count($liste)>0)
        {
            $code = false;
            $message = "Utilisateur login ou mail deja existant";
            $response = array("success"=>$code,"message"=>$message);
        }
        else
        {
            $query = $pdo->prepare("INSERT INTO user VALUES (NULL,?,?,?,?)");
            $options =  array('cost' => 11);
            $hash = password_hash($password, PASSWORD_BCRYPT,$options);
            $query->execute(array($name,$hash,$email,1));

            if(!$query)
            {
                $code = false;
                $message = "Une erreur serveur ... Recommencez ...";
                $response = array("success"=>$code,"message"=>$message);
            }
            else
            {
                $code = true;
                $message = "Enregistrement réussi.";
                $response = array("success"=>$code,"message"=>$message);
            }
        }
    }	
    else{
        $code = false;
        $message = "Données non valide";
        $response = array("success"=>$code,"message"=>$message);
    }
}catch(Exception $ex){
    $response = array("success" => false, "message" => $ex->getMessage());
}

$pdo = null;

echo json_encode($response);