<?php
/**
 * Created by PhpStorm.
 * User: Benjamin-pc
 * Date: 30-11-2017
 * Time: 12:21
 */


$Newto = $_REQUEST['nyemail'];

$uri = "http://restmarsvineservicebamm.azurewebsites.net/service1.svc/Contactinfo/6";
$jsondata = file_get_contents($uri);

$convertToAssociativeArray = true;
$user = json_decode($jsondata, $convertToAssociativeArray);

$to = $user['Mail'];
$id = $user['Id'];
$phoneno  = $user['PhoneNo'];

//sender en email til den gamle bruger om at kontakten er blevet ændret
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com;';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'bammbruger@gmail.com';                 // SMTP username
    $mail->Password = 'mik112mik112';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('bammbruger@gmail.com', 'MarsvineService');
    $mail->addAddress($to, 'MarsvineInfo');     // Add a recipient
    $mail->addReplyTo('bammbruger@gmail.com', 'Information');
    //$mail->addBCC('bcc@example.com');
    //$mail->addCC('cc@example.com');

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Ændret email på MarsvineService';
    $mail->Body    = 'Din Kontakt Email er blevet ændret til ' . $Newto;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}

// til at opdaterer email idatabasen
$data = array("Mail"=>$Newto, "PhoneNo"=>$phoneno);

$json_string = json_encode($data);

$uri1 = "http://restmarsvineservicebamm.azurewebsites.net/service1.svc/UpdateContactinfo/6";

$ch = curl_init($uri1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json_string))
);

$jsondata = curl_exec($ch);


$theUpdatedTeacher = json_decode($jsondata, true);


$host = $_SERVER['HTTP_HOST'];
header("Location: http://{$host}/MarsvineWebPage/Controller/");
return;
