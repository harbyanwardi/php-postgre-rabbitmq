<?php
require_once "connection.php";
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions


class SendMail {
    public function sendEmail($data_send) {
        $data = json_decode($data_send);
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'jokingoding@gmail.com';                     //SMTP username
            $mail->Password   = 'ppjuzgjjarthgewn';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;  
            $mail->SMTPSecure = 'ssl';                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom('jokingoding@gmail.com', 'Mail Joki');
            $mail->addAddress($data->to, 'Joe User');   
            
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $data->subject;
            $mail->Body    = $data->body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            echo 'Message has been sent';
            $resp = $this->updateLogMail($data->id_log);
            if($resp) {
                echo 'Success update log';
            } else {
                echo 'failed update';
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function updateLogMail($id) {
        $sql = "update log_email set is_true = 1 where id = '".$id."' ";
        return pg_affected_rows(pg_query($sql)); 

    }

}


