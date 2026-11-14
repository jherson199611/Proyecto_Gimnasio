
<?php

require_once "../modelos/Asistencia.php";

class AsistenciaController
{
    public function registrar($socio_id, $fecha_hora, $observacion)
    {
        $asistencia = new Asistencia();

        return $asistencia->registrar(
            $socio_id,
            $fecha_hora,
            $observacion
        );
    }

    public function consultar()
    {
        $asistencia = new Asistencia();

        return $asistencia->consultar();
    }
}

?>
