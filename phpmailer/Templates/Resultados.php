<?php

function plantillaResultados($data)
{
    $html = "
    <p>Estimado Dr./Dra. {{nombre_medico}},</p>

    <p>Le informamos que se han actualizado los resultados del paciente:</p>

    <p>
    <strong>Paciente:</strong> {{nombre_paciente}}<br>
    <strong>Fecha:</strong> {{fecha_cita}}<br>
    <strong>Estudios:</strong> {{cantidad_estudios}}
    </p>

    <p>
    <a href='{{link_al_sistema}}'>Ver resultados</a>
    </p>

    <p>
    Atentamente,<br>
    {{nombre_clinica_o_sistema}}<br>
    Tel: {{telefono_contacto}}<br>
    {{correo_contacto}}
    </p>
    ";

    foreach ($data as $key => $value) {
        $html = str_replace("{{" . $key . "}}", $value, $html);
    }

    return $html;
}