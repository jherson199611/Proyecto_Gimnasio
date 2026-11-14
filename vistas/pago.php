

<?php

require_once "../controladores/pagoController.php";

$pagoController = new PagoController();

if (isset($_POST["registrar"])) {

    $membresia_id = $_POST["membresia_id"];
    $fecha = $_POST["fecha"];
    $monto = $_POST["monto"];
    $metodo = $_POST["metodo"];
    $referencia = $_POST["referencia"];

    $resultado = $pagoController->registrar(
        $membresia_id,
        $fecha,
        $monto,
        $metodo,
        $referencia
    );

    if ($resultado > 0) {
        echo "Pago registrado correctamente";
    } else {
        echo "No se pudo registrar el pago";
    }
}

$pagos = $pagoController->consultar();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Pago</title>
</head>
<body>

    <h2>Registrar Pago</h2>

    <form action="" method="POST">

        <label>ID de Membresía</label>
        <input type="number" name="membresia_id" min="1" required>
        <br><br>

        <label>Fecha de Pago</label>
        <input type="date" name="fecha" required>
        <br><br>

        <label>Monto</label>
        <input type="number" name="monto" step="0.01" min="0" required>
        <br><br>

        <label>Método de Pago</label>
        <select name="metodo" required>
            <option value="">Seleccionar método</option>
            <option value="Efectivo">Efectivo</option>
            <option value="Yape">Yape</option>
            <option value="Tarjeta">Tarjeta</option>
        </select>
        <br><br>

        <label>Referencia</label>
        <input type="text" name="referencia" maxlength="100">
        <br><br>

        <button type="submit" name="registrar">
            Registrar Pago
        </button>

    </form>

    <hr>

    <h3>Consultar Pagos</h3>

    <table border="1">

        <tr>
            <th>ID Pago</th>
            <th>ID Membresía</th>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Método</th>
            <th>Referencia</th>
        </tr>

        <?php foreach ($pagos as $pago) { ?>

        <tr>
            <td><?php echo $pago["id"]; ?></td>
            <td><?php echo $pago["membresia_id"]; ?></td>
            <td><?php echo $pago["fecha"]; ?></td>
            <td><?php echo $pago["monto"]; ?></td>
            <td><?php echo $pago["metodo"]; ?></td>
            <td><?php echo $pago["referencia"]; ?></td>
        </tr>

        <?php } ?>

    </table>

</body>
</html>
