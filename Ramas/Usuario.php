
<?php

require_once "conexion.php";

class Usuario {

    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }

    // REGISTRAR USUARIO
    public function registrar($nombre, $correo, $password, $id_rol) {

        $sql = "INSERT INTO usuarios
                (nombre, correo, password, id_rol)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            $nombre,
            $correo,
            password_hash($password, PASSWORD_DEFAULT),
            $id_rol
        ]);
    }

    // INICIAR SESION
    public function iniciarSesion($correo, $password) {

        $sql = "SELECT * FROM usuarios WHERE correo = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$correo]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }

        return false;
    }

    // ACTUALIZAR USUARIO
    public function actualizar($id, $nombre, $correo, $id_rol) {

        $sql = "UPDATE usuarios
                SET nombre = ?, correo = ?, id_rol = ?
                WHERE id_usuario = ?";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            $nombre,
            $correo,
            $id_rol,
            $id
        ]);
    }
}
?>
