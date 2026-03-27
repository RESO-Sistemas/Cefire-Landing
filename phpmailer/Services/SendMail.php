<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/Exception.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/SMTP.php';

function enviarCorreo($destino, $asunto, $mensaje)
{
    $configFile = __DIR__ . '/../backend/ConfigMail.php';
    
    if (!file_exists($configFile)) {
        return "Error: Configuración de correo no encontrada";
    }

    $config = require $configFile;

    $mail = new PHPMailer(true);

    try {
        // Habilitar depuración SMTP detallada
        $mail->SMTPDebug = 2; // 2: client and server messages
        
        // Redirigir la salida de depuración al log de errores de PHP para no romper JSON
        $mail->Debugoutput = function($str, $level) {
            error_log("PHPMailer Debug: $str");
        };

        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['smtp_secure'];
        $mail->Port = $config['port'];

        // Desactivar verificación SSL temporalmente para entorno de desarrollo local
        // Esto soluciona problemas comunes con certificados autofirmados o configuraciones locales
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $fromEmail = $config['from_email']; // Correo
        $fromName = isset($config['from_name']) ? $config['from_name'] : 'Sistema CEFIRE'; // Nombre, con valor por defecto

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($destino);

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        $mail->send();

        return true;

    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}