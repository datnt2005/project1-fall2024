<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './mail/vendor/autoload.php'; // Hoặc đường dẫn đến PHPMailer nếu không dùng Composer

function sendMail($toEmail, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'vuahatdinhduongngon@gmail.com'; // Email của bạn
        $mail->Password = 'awarczolkewoknsw'; // Mật khẩu email (hoặc App Password nếu dùng Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port = 465;

        // Cấu hình người gửi và người nhận
        $mail->setFrom('vuahatdinhduongngon@gmail.com', 'Vua Hạt Dinh Dưỡng Ngon');
        $mail->addAddress($toEmail);

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email không được gửi. Lỗi: {$mail->ErrorInfo}");
        return false;
    }
}
?>
