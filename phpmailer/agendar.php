<?php
// Permitir peticiones desde el frontend
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

require_once __DIR__ . '/Templates/AgendarCita.php';
require_once __DIR__ . '/Services/SendMail.php';

// Recibir datos del formulario
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
$servicio = isset($_POST['servicio']) ? trim($_POST['servicio']) : '';
$mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : 'Sin mensaje adicional';

// Validaciones básicas
$errores = [];

if (empty($nombre)) {
    $errores[] = 'El nombre es obligatorio';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'Ingresa un correo electrónico válido';
}
if (empty($telefono)) {
    $errores[] = 'El teléfono es obligatorio';
}
if (empty($servicio) || $servicio === 'Selecciona un servicio') {
    $errores[] = 'Selecciona un servicio';
}

if (!empty($errores)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errores)]);
    exit;
}

// Armar datos para la plantilla
$data = [
    'nombre' => $nombre,
    'email' => $email,
    'telefono' => $telefono,
    'servicio' => $servicio,
    'mensaje' => nl2br($mensaje),
];

// Generar HTML del correo
$cuerpoCorreo = plantillaAgendarCita($data);

// Enviar el correo al email de CEFIRE
$config = require __DIR__ . '/backend/ConfigMail.php';
$destinatario = $config['from_email']; // Se envía a la cuenta de CEFIRE

$resultado = enviarCorreo(
    $destinatario,
    'Nueva solicitud de cita - ' . $servicio . ' - ' . $nombre,
    $cuerpoCorreo
);

if ($resultado === true) {
    echo json_encode([
        'success' => true,
        'message' => '¡Tu solicitud fue enviada con éxito! Nos pondremos en contacto contigo pronto.'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Hubo un error al enviar tu solicitud. Intenta de nuevo más tarde.'
    ]);
    error_log("Error al enviar correo de cita: " . json_encode($resultado));
}
