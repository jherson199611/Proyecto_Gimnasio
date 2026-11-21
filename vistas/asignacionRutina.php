
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

<?php

// Consultar socios
$connect = Conexion::getInstancia()->getPDO();

$sql = "SELECT * FROM socios";
$stmt = $connect->prepare($sql);
$stmt->execute();
$socios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consultar rutinas
$sql = "SELECT * FROM rutinas";
$stmt = $connect->prepare($sql);
$stmt->execute();
$rutinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignación de Rutinas</title>
</head>
<body>

<h2>Asignar Rutina a Socio</h2>

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

    <label>Seleccionar Rutina</label>
    <select name="rutina_id" required>
        <option value="">Seleccionar rutina</option>

        <?php foreach ($rutinas as $rutina) { ?>
            <option value="<?php echo $rutina["id"]; ?>">
                <?php echo $rutina["nombre"]; ?>
            </option>
        <?php } ?>

    </select>

    <br><br>

    <label>Fecha de Inicio</label>
    <input type="date" name="fecha_inicio" required>

    <br><br>

    <label>Fecha de Fin (opcional)</label>
    <input type="date" name="fecha_fin">

    <br><br>

    <button type="submit" name="asignar">Asignar Rutina</button>

</form>

<hr>