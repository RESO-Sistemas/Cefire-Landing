<?php

require_once __DIR__ . '/../models/Citas.php';
require_once __DIR__ . '/../Templates/Resultados.php';
require_once __DIR__ . '/SendMail.php';

function enviarResultadosPorCita($idCita, $citas)
{
    error_log("📧 [MailService] Iniciando proceso de envío para Cita ID: " . $idCita);

    $datosBD = $citas->obtenerDatosCorreoResultados($idCita);

    if (!$datosBD) {
        error_log("❌ [MailService] No se encontraron datos de la cita o el médico en la BD.");
        return "No se encontraron datos";
    }

    error_log("📋 [MailService] Datos recuperados: Médico='{$datosBD['nombre_medico']}', Email='{$datosBD['correo_medico']}'");

    if (empty($datosBD['correo_medico'])) {
        error_log("⚠️ [MailService] El médico no tiene un correo registrado en el sistema.");
        return "El médico no tiene correo";
    }

    $data = [
        'nombre_medico' => $datosBD['nombre_medico'],
        'nombre_paciente' => $datosBD['nombre_paciente'],
        'fecha_cita' => date('d/m/Y', strtotime($datosBD['fecha_cita'])),
        'cantidad_estudios' => $datosBD['cantidad_estudios'],
        'nombres_analisis' => $datosBD['nombres_analisis'],
        'link_al_sistema' => 'http://localhost/cefire/resultados.php?id=' . $idCita,

        'nombre_clinica_o_sistema' => 'CEFIRE',
        'telefono_contacto' => $datosBD['telefono_paciente'],
        'correo_contacto' => $datosBD['correo_paciente']
    ];

    $mensaje = plantillaResultados($data);

    error_log("🚀 [MailService] Intentando enviar correo a: " . $datosBD['correo_medico']);

    $resultado = enviarCorreo(
        $datosBD['correo_medico'],
        'Resultados disponibles - CEFIRE',
        $mensaje
    );

    if ($resultado === true) {
        error_log("✅ [MailService] Correo enviado exitosamente.");
    } else {
        error_log("❌ [MailService] Falló el envío SMTP. Error: " . json_encode($resultado));
    }

    return $resultado;
}