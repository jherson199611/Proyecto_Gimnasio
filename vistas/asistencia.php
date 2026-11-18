
<?php

require_once "../controladores/AsistenciaController.php";
require_once "../conexion/conn.php";

$asistenciaController = new AsistenciaController();

if (isset($_POST["registrar"])) {

    $socio_id = $_POST["socio_id"];
    $fecha_hora = $_POST["fecha_hora"];
    $observacion = $_POST["observacion"];

    $resultado = $asistenciaController->registrar(
        $socio_id,
        $fecha_hora,
        $observacion
    );

    if ($resultado > 0) {
        echo "Asistencia registrada correctamente";
    } else {
        echo "No se pudo registrar la asistencia";
    }
}

$connect = Conexion::getInstancia()->getPDO();

$sql = "SELECT * FROM socios";

$stmt = $connect->prepare($sql);
$stmt->execute();

$socios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$asistencias = $asistenciaController->consultar();

?>