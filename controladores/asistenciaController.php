1. Código de AsistenciaController.php

<?php

require_once "../modelos/Asistencia.php";

class AsistenciaController
{
    public function registrar($fecha, $hora, $idSocio)
    {
        $asistencia = new Asistencia();

        return $asistencia->registrar(
            $fecha,
            $hora,
            $idSocio
        );
    }

    public function consultar()
    {
        $asistencia = new Asistencia();

        return $asistencia->consultar();
    }
}

?>