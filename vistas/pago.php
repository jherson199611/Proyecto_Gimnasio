
<?php

require_once "../controladores/pagoController.php";

$pagoController = new PagoController();

if (isset($_POST["registrar"])) {

    $fechaPago = $_POST["fechaPago"];
    $monto = $_POST["monto"];
    $idMembresia = $_POST["idMembresia"];

    $resultado = $pagoController->registrar(
        $fechaPago,
        $monto,
        $idMembresia
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

        <label>Fecha de Pago</label>
        <input type="date" name="fechaPago" required>
        <br><br>

        <label>Monto</label>
        <input type="number" name="monto" step="0.01" min="0" required>
        <br><br>

        <label>ID de Membresía</label>
        <input type="number" name="idMembresia" min="1" required>
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
            <th>Fecha de Pago</th>
            <th>Monto</th>
            <th>Estado</th>
        </tr>

        <?php foreach ($pagos as $pago) { ?>

        <tr>
            <td><?php echo $pago["id_pago"]; ?></td>
            <td><?php echo $pago["fecha_pago"]; ?></td>
            <td><?php echo $pago["monto"]; ?></td>
            <td><?php echo $pago["estado"]; ?></td>
        </tr>

        <?php } ?>

    </table>

</body>
</html>
