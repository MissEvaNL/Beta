<?php
namespace App;

use App\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail {
    public static function send($to, $subject, $text, $html){
        $mail = new PHPMailer(true);
        $mail->isSMTP(1);
        
        $mail->SMTPAuth = true;
      
        if(Config::mailssl == true){
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );          
        }

        $mail->Host     = Config::mailhost;
        $mail->Username = Config::mailuser;
        $mail->Password = Config::mailpass;
        $mail->Port     = Config::mailport;
        
        //Recipients
        $mail->setFrom(Config::mailuser, config::get('sitename'));
        $mail->addAddress($to);     // Add a recipient

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $html;
        $mail->AltBody = $text;
        
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }
}