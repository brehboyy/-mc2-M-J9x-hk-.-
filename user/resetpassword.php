<?php
include '../../../inc/dbinfo.inc';
$postdata = file_get_contents("php://input");
try{
    if (isset($postdata)) {
        $request = json_decode($postdata);
        $email = $request->email;
        $contenu = $pdo->prepare('SELECT USER_Email FROM user WHERE USER_Email = ?');
        $contenu->execute(array($email));
        $liste = $contenu->fetchAll(PDO::FETCH_ASSOC);
        if(count($liste)>0)
        {		
            $to      = 'ousmane16diarra@gmail.com';
            $subject = 'Equipe MealCheck';
            $headers = array('From' =>  'mealcheck22@gmail.com',
                    'Reply-To' => 'mealcheck22@gmail.com',
                'X-Mailer' => 'PHP/' . phpversion());
            $message = 'Un email vous a été envoyé'. 'PHP/' . phpversion();
            
            mail($to, $subject, $message, $headers);
            $msg="hello";
            mail("ousmane16diarra@gmail.com","My subject",$msg);
            $code = true;
            $response = array("success"=>$code, 'message' => $message);
        }else{
            $code = false;
            $message = "Email inexistant";
            $response = array("success"=>$code, 'message' => $message);
        }
    }
}catch(Exception $ex){
    $code = false;
    $response = array("success"=>$code, 'message' => $ex->getMessage());
}
echo json_encode($response);