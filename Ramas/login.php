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
<title>Acceso - Gym System</title>
<link rel="stylesheet" href="assets/estilos.css">

<style>
.login { max-width: 480px; margin: 50px auto; }
.accesos { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin:20px 0; }
.acceso input { display:none; }
.acceso label {
    display:block;
    text-align:center;
    padding:22px 10px;
    border:2px solid #dce2ea;
    border-radius:12px;
    cursor:pointer;
}
.acceso input:checked + label {
    border-color:#2378bd;
    background:#edf6ff;
}
.acceso strong { display:block; margin-top:10px; }
.acceso span { font-size:13px; color:#64748b; }
.login button { width:100%; margin-top:18px; }
</style>
</head>

<body>

<div class="panel login">

    <h1>GYM SYSTEM</h1>
    <p class="muted">Sistema Integral de Gestión de Gimnasio</p>

    <?php if ($error): ?>
        <div class="msg error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label>Selecciona tu tipo de acceso</label>

        <div class="accesos">

            <div class="acceso">
                <input type="radio" name="tipo_acceso"
                       id="admin" value="administrador" required>
                <label for="admin">
                    <span style="font-size:30px">🛡️</span>
                    <strong>ADMINISTRADOR</strong>
                    <span>Gestión del gimnasio</span>
                </label>
            </div>

            <div class="acceso">
                <input type="radio" name="tipo_acceso"
                       id="cliente" value="cliente" required>
                <label for="cliente">
                    <span style="font-size:30px">🏋️</span>
                    <strong>CLIENTE</strong>
                    <span>Mi cuenta personal</span>
                </label>
            </div>

        </div>

        <label>Usuario</label>
        <input type="text" name="usuario" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <button type="submit">INGRESAR AL SISTEMA</button>

    </form>

</div>

</body>
</html>
