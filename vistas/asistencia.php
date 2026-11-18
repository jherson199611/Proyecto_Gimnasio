
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


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Asistencia</title>
</head>

<body>

    <h2>Registrar Asistencia</h2>

    <form action="" method="POST">

        <label>Seleccionar Socio</label>
        <select name="socio_id" required>

            <option value="">Seleccionar socio</option>

            <?php foreach ($socios as $socio) { ?>

                <option value="<?php echo $socio["id"]; ?>">
                    <?php echo $socio["nombres"] . " " . $socio["apellidos"]; ?>
                </option>

            <?php } ?>

        </select>

        <br><br>

        <label>Fecha y Hora</label>
        <input type="datetime-local" name="fecha_hora" required>

        <br><br>

        <label>Observación</label>
        <input type="text" name="observacion" maxlength="150">

        <br><br>

        <button type="submit" name="registrar">
            Registrar Asistencia
        </button>

    </form>

    <hr>
</body>
</html>