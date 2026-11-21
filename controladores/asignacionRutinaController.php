
<?php
require_once "../modelos/AsignacionRutina.php";

class AsignacionRutinaController
{
    public function asignar($socio_id, $rutina_id, $fecha_inicio, $fecha_fin)
    {
        $asignacion = new AsignacionRutina();
        return $asignacion->asignar($socio_id, $rutina_id, $fecha_inicio, $fecha_fin);
    }

    public function finalizar($id)
    {
        $asignacion = new AsignacionRutina();
        return $asignacion->finalizar($id);
    }

    public function consultar()
    {
        $asignacion = new AsignacionRutina();
        return $asignacion->consultar();
    }
}
?>