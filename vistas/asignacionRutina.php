
<?php

require_once "../controladores/AsignacionRutinaController.php";
require_once "../conexion/conn.php";

$asignacionController = new AsignacionRutinaController();

// Registrar una asignación
if (isset($_POST["asignar"])) {

    $socio_id = $_POST["socio_id"];
    $rutina_id = $_POST["rutina_id"];
    $fecha_inicio = $_POST["fecha_inicio"];
    $fecha_fin = !empty($_POST["fecha_fin"]) ? $_POST["fecha_fin"] : null;

    $resultado = $asignacionController->asignar(
        $socio_id,
        $rutina_id,
        $fecha_inicio,
        $fecha_fin
    );

    if ($resultado > 0) {
        echo "Rutina asignada correctamente";
    } else {
        echo "No se pudo asignar la rutina";
    }
}

// Finalizar una asignación
if (isset($_POST["finalizar"])) {

    $id = $_POST["id"];

    $resultado = $asignacionController->finalizar($id);

    if ($resultado > 0) {
        echo "Asignación finalizada correctamente";
    } else {
        echo "No se pudo finalizar la asignación";
    }
}
?>
