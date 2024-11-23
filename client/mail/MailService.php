<?php

namespace MailService;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require_once './user.php';
require 'vendor/autoload.php';
// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';

define('USERNAME_EMAIL', 'vuahatdinhduongngon@gmail.com'); // thay bằng email của các bạn
define('PASSWORD_EMAIL', 'awarczolkewoknsw'); // thay bằng password của các bạn
class MailService
{
    public static function send($from = 'vuahatdinhduongngon@gmail.com', $to = 'phatttpk03754@gmail.com', $subject = 'notfication', $content = '')
    {
        try {
            $mail = new PHPMailer();
            // $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = USERNAME_EMAIL;                 // SMTP username
            $mail->Password = PASSWORD_EMAIL;                           // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to
            $mail->CharSet = 'UTF-8';
            //Recipients

            $mail->setFrom($to, 'Vua hạt');
            $mail->addAddress($from);               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Hiển thị thông báo gỡ lỗi
            $mail->Subject = $subject;
            $mail->Body    = $content;
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }
}