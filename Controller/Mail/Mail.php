<?php
//https://www.espai.es/blog/2022/06/phpmailer-ya-no-envia-correos-a-traves-de-gmail/
//https://codigosdeprogramacion.com/2022/04/05/enviar-correo-electronico-con-phpmailer/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require_once __DIR__.'/phpmailer/src/Exception.php';
require_once __DIR__.'/phpmailer/src/PHPMailer.php';
require_once __DIR__.'/phpmailer/src/SMTP.php';
class Mail {
    static function sendmail($email, $name, $newpass)
    {
        try {
            $mail = new PHPMailer();
            //Configuración del servidor
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;             //Habilitar los mensajes de depuración
            $mail->isSMTP();                                   //Enviar usando SMTP
            $mail->Host       = 'smtp.zoho.eu';            //Configurar el servidor SMTP
            $mail->SMTPAuth   = true;                          //Habilitar autenticación SMTP
            $mail->Username   = Constantes::$MAILUsername;            //Nombre de usuario SMTP
            $mail->Password   = Constantes::$MAILPass;                  //Contraseña SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   //Habilitar el cifrado TLS
            $mail->Port       = 465;                           //Puerto TCP al que conectarse; use 587 si configuró `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Emisor
            $mail->setFrom(Constantes::$MAILUsername, Constantes::$MAILFromName);

            //Destinatarios
            $mail->addAddress($email, $name);     //Añadir un destinatario, el nombre es opcional

            //Destinatarios opcionales
            // $mail->addReplyTo('info@example.com', 'Information');  //Responder a
            // $mail->addCC('cc@example.com');                        //Copia pública
            // $mail->addBCC('bcc@example.com');                      //Copia oculta

            //Archivos adjuntos
            // $mail->addAttachment('files/comunicado.pdf', 'Comunicado');         //Agregar archivos adjuntos, nombre opcional

            //Nombre opcional
            $mail->isHTML(true);                         //Establecer el formato de correo electrónico en HTMl
            $mail->Subject = "New Password!";
            $mail->Body    = "Su nueva contrase&ntilde;a es: <b>$newpass</b> <br>Desde Gitignore Tech esperemos que disfrute de su cuenta";
            

            $mail->send();    //Enviar correo eletrónico

            echo "llego hasta aquí";
        } catch (Exception $e) {
            echo $e;
        }
    }
}
