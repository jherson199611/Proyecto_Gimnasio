<?php
require_once '../includes/auth.php';
require_once '../clases/Rol.php';

$rol = new Rol();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rol->registrar($_POST['nombre'], $_POST['descripcion']);
    $mensaje = 'Rol registrado.';
}

$roles = $rol->consultar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Roles</title>
</head>
<body>

<h2>Registrar rol</h2>
<p><?php echo $mensaje; ?></p>

<form method="POST">
    <p>Nombre:</p>
    <input type="text" name="nombre" required>

    <p>Descripción:</p>
    <input type="text" name="descripcion">

    <br><br>
    <button type="submit">Registrar</button>
</form>

<h2>Roles registrados</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
    </tr>

    <?php foreach ($roles as $r): ?>
    <tr>
        <td><?php echo $r['id']; ?></td>
        <td><?php echo htmlspecialchars($r['nombre']); ?></td>
        <td><?php echo htmlspecialchars($r['descripcion']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
