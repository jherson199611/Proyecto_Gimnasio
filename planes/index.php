<?php
require_once __DIR__ . '/plan.php';

$mensaje = "";
$planObj = new Plan();

// Procesar el formulario cuando se envía por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre        = trim($_POST['nombre'] ?? '');
    $precio        = trim($_POST['precio'] ?? '');
    $duracion_dias = trim($_POST['duracion_dias'] ?? '');
    $descripcion   = trim($_POST['descripcion'] ?? '');

    if (!empty($nombre) && !empty($precio) && !empty($duracion_dias)) {
        try {
            $resultado = $planObj->registrar($nombre, $precio, $duracion_dias, $descripcion);
            if ($resultado) {
                $mensaje = "<p style='color: green;'>¡Plan registrado exitosamente!</p>";
            } else {
                $mensaje = "<p style='color: red;'>Error al registrar el plan.</p>";
            }
        } catch (PDOException $e) {
            $mensaje = "<p style='color: red;'>Error: El nombre del plan ya existe o los datos son inválidos.</p>";
        }
    } else {
        $mensaje = "<p style='color: red;'>Por favor, completa los campos obligatorios.</p>";
    }
}

// Obtener la lista actual de planes
$planes = $planObj->consultar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Planes - Gimnasio J</title>
</head>
<body>
    <h2>Formulario de Registro de Planes</h2>

    <?= $mensaje ?>

    <form action="" method="POST">
        <label>Nombre del Plan:</label><br>
        <input type="text" name="nombre" placeholder="Ej. Premium" required><br><br>
        
        <label>Precio (S/.):</label><br>
        <input type="number" step="0.01" name="precio" placeholder="0.00" required><br><br>
        
        <label>Duración (días):</label><br>
        <input type="number" name="duracion_dias" placeholder="30" required><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion" placeholder="Detalles del plan..."></textarea><br><br>
        
        <button type="submit">Guardar Plan</button>
    </form>

    <hr>

    <h2>Lista de Planes</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Duración (Días)</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($planes)): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No hay planes registrados todavía.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($planes as $plan): ?>
                    <tr>
                        <td><?= htmlspecialchars($plan['id']) ?></td>
                        <td><?= htmlspecialchars($plan['nombre']) ?></td>
                        <td>S/. <?= number_format($plan['precio'], 2) ?></td>
                        <td><?= htmlspecialchars($plan['duracion_dias']) ?></td>
                        <td><?= htmlspecialchars($plan['descripcion']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>