<?php
require_once __DIR__ . '/../config/Conexion.php';

class Rol
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->conectar();
    }

    public function registrar($nombre, $descripcion)
    {
        $sql = "INSERT INTO roles (nombre, descripcion) VALUES (?, ?)";
        $consulta = $this->db->prepare($sql);

        return $consulta->execute([$nombre, $descripcion]);
    }

    public function actualizar($id, $nombre, $descripcion)
    {
        $sql = "UPDATE roles SET nombre = ?, descripcion = ? WHERE id = ?";
        $consulta = $this->db->prepare($sql);

        return $consulta->execute([$nombre, $descripcion, $id]);
    }

    public function consultar()
    {
        return $this->db->query("SELECT * FROM roles")->fetchAll();
    }
}
