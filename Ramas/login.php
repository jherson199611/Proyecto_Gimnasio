8
<?php
session_start();

require_once __DIR__ . '/clases/Usuario.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo = $_POST['tipo_acceso'] ?? '';
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    $u = (new Usuario())->iniciarSesion($usuario, $password);

    if ($u) {

        $rol = strtolower(trim($u['rol']));

        $esAdmin = ($rol === 'administrador');
        $esCliente = ($rol === 'socio' || $rol === 'cliente');

        if (
            ($tipo === 'administrador' && $esAdmin) ||
            ($tipo === 'cliente' && $esCliente)
        ) {

            session_regenerate_id(true);
            $_SESSION['usuario'] = $u;

            if ($esAdmin) {
                header('Location: index.php');
            } else {
                header('Location: cliente/index.php');
            }

            exit;

        } else {
            $error = 'Tu cuenta no tiene permisos para este tipo de acceso.';
        }

    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Gym System</title>
</head>

<body>

    <h1>GYM SYSTEM</h1>
    <p>Sistema Integral de Gestión de Gimnasio</p>

    <?php if ($error): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">

        <p>Selecciona tu tipo de acceso:</p>

        <input type="radio" name="tipo_acceso"
               value="administrador" required>
        Administrador

        <br>

        <input type="radio" name="tipo_acceso"
               value="cliente" required>
        Cliente

        <br><br>

        <label>Usuario:</label>
        <input type="text" name="usuario" required>

        <br><br>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit">Ingresar</button>

    </form>

</body>
</html>

