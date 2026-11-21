<?php
session_start();
require_once __DIR__ . '/clases/Usuario.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo_acceso'] ?? '';
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    $datos = (new Usuario())->iniciarSesion($usuario, $password);

    if ($datos) {
        $rol = strtolower($datos['rol']);

        if (($tipo == 'administrador' && $rol == 'administrador') ||
            ($tipo == 'cliente' && ($rol == 'cliente' || $rol == 'socio'))) {

            session_regenerate_id(true);
            $_SESSION['usuario'] = $datos;

            if ($rol == 'administrador') {
                header('Location: index.php');
            } else {
                header('Location: cliente/index.php');
            }
            exit;
        }

        $error = 'No tienes permiso para este acceso.';
    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
</head>
<body>

<h2>GYM SYSTEM</h2>

<p><?php echo htmlspecialchars($error); ?></p>

<form method="POST">
    <p>Tipo de acceso:</p>

    <input type="radio" name="tipo_acceso" value="administrador" required>
    Administrador

    <input type="radio" name="tipo_acceso" value="cliente" required>
    Cliente

    <p>Usuario:</p>
    <input type="text" name="usuario" required>

    <p>Contraseña:</p>
    <input type="password" name="password" required>

    <br><br>
    <button type="submit">Ingresar</button>
</form>

</body>
</html>

