<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../../vendor/autoload.php';



class MailService {
      private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);  // Línea 9, si falla aquí, puede ser porque PHPMailer no está cargado
    }
    
   public function enviarRecuperacion($email, $nombre, $token) {
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

        $mensaje1 = "Recuperación de contraseña";
        $mensaje2 = "Hola $nombre,\n\nEste es el token para recuperar su Password $token\n\nSaludos,\nMNJ Tecno";


        $this->mail->Subject = $mensaje1;
        $this->mail->Body = $mensaje2;


        $this->mail->send();
          echo json_encode("Correo enviado a $email");
         echo json_encode(["LLEGUE POR ACA LOS DATOS SON: $email Y TAMBIEN $nombre"]);
        return true;
    } catch (Exception $e) {
        echo json_encode("Error al enviar correo: " . $this->mail->ErrorInfo);
        return false;
    }
}


}
