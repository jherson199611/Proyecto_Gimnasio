<?php
require_once __DIR__ . '/../conexion.php';

class Plan {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    // Registrar un nuevo plan (ej: Básico, Estándar, Premium)
    public function registrar($nombre, $precio, $duracion) {
        $sql = "INSERT INTO planes (nombre, precio, duracion_dias) VALUES (:nombre, :precio, :duracion)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':nombre'   => $nombre,
            ':precio'   => $precio,
            ':duracion' => $duracion
        ]);
    }

    // Actualizar precio y duración de un plan existente
    public function actualizar($id_plan, $precio, $duracion) {
        $sql = "UPDATE planes SET precio = :precio, duracion_dias = :duracion WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':precio'   => $precio,
            ':duracion' => $duracion,
            ':id'       => $id_plan
        ]);
    }

    // Consultar todos los planes registrados
    public function consultar() {
        $sql = "SELECT * FROM planes ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>