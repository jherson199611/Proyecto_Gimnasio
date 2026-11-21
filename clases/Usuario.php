<?php
require_once __DIR__ . '/../config/Conexion.php';

class Usuario
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->conectar();
    }

    public function registrar($nombre, $usuario, $correo, $password, $rol_id)
    {
        $sql = "INSERT INTO usuarios (nombre, usuario, correo, password, rol_id)
                VALUES (?, ?, ?, ?, ?)";

        $consulta = $this->db->prepare($sql);

        return $consulta->execute([
            $nombre,
            $usuario,
            $correo,
            password_hash($password, PASSWORD_DEFAULT),
            $rol_id
        ]);
    }

    public function iniciarSesion($usuario, $password)
    {
        $sql = "SELECT usuarios.*, roles.nombre AS rol
                FROM usuarios
                INNER JOIN roles ON usuarios.rol_id = roles.id
                WHERE usuarios.usuario = ? AND usuarios.estado = 1";

        $consulta = $this->db->prepare($sql);
        $consulta->execute([$usuario]);

        $datos = $consulta->fetch();

        if ($datos && password_verify($password, $datos['password'])) {
            return $datos;
        }

        return false;
    }

    public function actualizar($id, $nombre, $correo, $rol_id)
    {
        $sql = "UPDATE usuarios
                SET nombre = ?, correo = ?, rol_id = ?
                WHERE id = ?";

        $consulta = $this->db->prepare($sql);

        return $consulta->execute([$nombre, $correo, $rol_id, $id]);
    }

    public function consultar()
    {
        $sql = "SELECT usuarios.id, usuarios.nombre, usuarios.usuario,
                       usuarios.correo, roles.nombre AS rol, usuarios.estado
                FROM usuarios
                INNER JOIN roles ON usuarios.rol_id = roles.id";

        return $this->db->query($sql)->fetchAll();
    }
}
