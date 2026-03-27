<?php

function plantillaAgendarCita($data)
{
    $html = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;'>
        <div style='background-color: #D7296E; padding: 25px; text-align: center;'>
            <h1 style='color: #ffffff; margin: 0; font-size: 22px;'>Nueva Solicitud de Cita</h1>
            <p style='color: #f8d0df; margin: 5px 0 0; font-size: 14px;'>CEFIRE - Centro de Fisiología Respiratoria</p>
        </div>

        <div style='padding: 30px; background-color: #ffffff;'>
            <h2 style='color: #D7296E; font-size: 18px; margin-top: 0; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;'>Datos del Paciente</h2>
            
            <table style='width: 100%; border-collapse: collapse;'>
                <tr>
                    <td style='padding: 8px 0; color: #666; font-weight: bold; width: 140px;'>Nombre:</td>
                    <td style='padding: 8px 0; color: #333;'>{{nombre}}</td>
                </tr>
                <tr>
                    <td style='padding: 8px 0; color: #666; font-weight: bold;'>Correo:</td>
                    <td style='padding: 8px 0; color: #333;'><a href='mailto:{{email}}' style='color: #D7296E;'>{{email}}</a></td>
                </tr>
                <tr>
                    <td style='padding: 8px 0; color: #666; font-weight: bold;'>Teléfono:</td>
                    <td style='padding: 8px 0; color: #333;'>{{telefono}}</td>
                </tr>
                <tr>
                    <td style='padding: 8px 0; color: #666; font-weight: bold;'>Servicio:</td>
                    <td style='padding: 8px 0; color: #333; font-weight: bold;'>{{servicio}}</td>
                </tr>
            </table>

            <div style='margin-top: 20px;'>
                <h3 style='color: #D7296E; font-size: 16px; margin-bottom: 8px;'>Mensaje:</h3>
                <div style='background-color: #f9f9f9; padding: 15px; border-radius: 6px; border-left: 3px solid #D7296E; color: #555; line-height: 1.6;'>
                    {{mensaje}}
                </div>
            </div>
        </div>

        <div style='background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 12px; color: #999;'>
            <p style='margin: 0;'>Este correo fue enviado desde el formulario de contacto de cefire.mx</p>
        </div>
    </div>
    ";

    foreach ($data as $key => $value) {
        $html = str_replace("{{" . $key . "}}", htmlspecialchars($value), $html);
    }

    return $html;
}
