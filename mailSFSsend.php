<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function

//php mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    
require 'resources/PHPMailer/src/Exception.php';
require 'resources/PHPMailer/src/PHPMailer.php';
require 'resources/PHPMailer/src/SMTP.php';   
//end of php mailer

// what i need to implement here //
// add subject body etc
// mailing lists etc
function sendMail($email,$name,$messageType,$subject,$message)
{
$mail = new PHPMailer(true); // Passing `true` enables exceptions
try {
    //Server settings
    //$mail->SMTPDebug = 2;// Enable verbose debug output
    $mail->isSMTP();// Set mailer to use SMTP
    $mail->Host = 'mail.mailer.co.za';// Specify main SMTP server
    $mail->SMTPAuth = true;// Enable SMTP authentication
    $mail->Username = 'mail@mailer.co.za';// SMTP username
    $mail->Password = 'pass';// SMTP password
    $mail->SMTPSecure = 'ssl';// Enable TLS encryption, `ssl` also accepted
    $mail->Port = '465';// TCP port to connect to

    //Recipients
    $mail->setFrom('admin@mailer.co.za', $messageType);
    $mail->addAddress($email, $name);     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(false);                                  // Set email format to not HTML
    $mail->Subject = $subject;                             // Plain text format now
    $mail->Body    = $message;
    $mail->AltBody = $message;

    $mail->send();
   // echo '<br><br><br><br>Message has been sent';
} catch (Exception $e) {
    echo '<br><br><br><br>Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
}

function alertSupervisors($reason,$wmsg)
{
    switch($reason)
    {
        case "createWork":
        {
            // to create emails here based on the list of users access > 1 < 4'
            // get a list of users , test their auth, all > 1 and <=3 are sent message
            $ul = new userList();
            $ul->getUsersData();
            $userL = array();
            $userL = $ul->fetchUserList();
            foreach($userL as $user)
            {
                if($user->getUserAuth()>1&&$user->getUserAuth()<=3)
                {
                    sendMail($user->getUserEmail(),$user->getUserName(),"Work created","Work has been created and added to your queue",$wmsg);                    
                }
            }
            break;
        }
        case "checkWork":
        {
            // to create emails here based on the list of users access > 4'
            // get a list of users , test their auth, all > 4 are sent message
            $ul = new userList();
            $ul->getUsersData();
            $userL = array();
            $userL = $ul->fetchUserList();
            foreach($userL as $user)
            {
                if($user->getUserAuth()>8)
                {
                    sendMail($user->getUserEmail(),$user->getUserName(),"work finalizing","Work has been checked and added to your queue for finalization",$wmsg);
                }
            }
            break;        
        }
        case "completeWork":
        {
            // to create emails here based on the list of users access > 1 < 4'
            // get a list of users , test their auth, all > 1 and <=3 are sent message
            $ul = new userList();
            $ul->getUsersData();
            $userL = array();
            $userL = $ul->fetchUserList();
            foreach($userL as $user)
            {
                if($user->getUserAuth()>1&&$user->getUserAuth()<=3)
                {
                    sendMail($user->getUserEmail(),$user->getUserName(),"Work completed","Work has been captured and is added to your queue for checking",$wmsg);
                }
            }
            break;            
        }
        case "finishWork":
        {
            // to create emails here based on the list of users access >4'
            // get a list of users , test their auth, all >4 are sent message
            $ul = new userList();
            $ul->getUsersData();
            $userL = array();
            $userL = $ul->fetchUserList();
            foreach($userL as $user)
            {
                if($user->getUserAuth()>1&&$user->getUserAuth()<=3)
                {
                    sendMail($user->getUserEmail(),$user->getUserName(),"Work finished","Work has been finalised and is marked as inactive",$wmsg);
                }
            }
            break;                        
        }
    }
}