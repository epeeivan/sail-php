<?php

namespace app\libraries;

use system\Loader;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

Loader::load('app/libraries/PHPMailer/src/Exception.php');
Loader::load('app/libraries/PHPMailer/src/PHPMailer.php');
Loader::load('app/libraries/PHPMailer/src/SMTP.php');
class mailSender extends PHPMailer
{

    public function __construct()
    {
        parent::__construct();
    }
    public function configure()
    {
        //Server settings
        // $this->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $this->isSMTP();                                            //Send using SMTP
        $this->Host       = 'mail.multipresta.com';                     //Set the SMTP server to send through
        $this->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->Username   = 'oas@multipresta.com';                     //SMTP username
        $this->Password   = '_oas2023';                               //SMTP password
        $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $this->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $this->setFrom('oas@multipresta.com', 'oas');
        $this->addAddress('epeeivan@gmail.com', 'Joe User');     //Add a recipient
        // $this->addAddress('ellen@example.com');               //Name is optional
        $this->addReplyTo('oas@multipresta.com', 'Information');
        $this->addCC('oas@multipresta.com');
        $this->addBCC('oas@multipresta.com');
        //Attachments
        // $this->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $this->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $this->isHTML(true);                                  //Set email format to HTML
        // $this->Subject = 'Here is the subject';

        ob_start();
        Loader::view("mail", ["title" => $this->Subject, "content" => $this->AltBody]);
        $mail = ob_get_clean();

        $this->Body    = $mail;
        // $this->AltBody = 'This is the body in plain text for non-HTML mail clients';
    }
    public function send()
    {
        try {
            $this->configure();
            parent::send();
            // echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->ErrorInfo}";
        }
    }
}
