<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../../vendor/autoload.php';



class MailService
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);  // Línea 9, si falla aquí, puede ser porque PHPMailer no está cargado
    }

    public function EnvioMail($email, $nombre, $token, $mensaje1, $mensaje2, $html)
    {
        try {
            $this->mail->isSMTP();
            $this->mail->Host = 'mail.tallerphp.uy';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'mnjtecno@tallerphp.uy';
            $this->mail->Password = 'mnjTecno12345';  // Tu contraseña de aplicación
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port = 465;

            // Usa el mismo email que Username
            $this->mail->setFrom('mnjtecno@tallerphp.uy', 'MNJ Tecno');
            $this->mail->addAddress($email, $nombre);

            $this->mail->Subject = $mensaje1;
            $this->mail->Body = $mensaje2;
            $this->mail->isHTML($html);
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Encoding = 'base64';

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            echo json_encode("Error al enviar correo: " . $this->mail->ErrorInfo);
            return false;
        }
    }
}
