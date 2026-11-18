<?php
require_once __DIR__ . '/../conexion.php';

class Plan {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db =$conexion->getConexion();
    }

    // Registrar un nuevo plan
    public function registrar($nombre, $precio,$duracion_dias, $descripcion = null) {$sql = "INSERT INTO planes (nombre, precio, duracion_dias, descripcion) 
                VALUES (:nombre, :precio, :duracion_dias, :descripcion)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':nombre'        => $nombre,
            ':precio'        => $precio,
            ':duracion_dias' => $duracion_dias,
            ':descripcion'   => $descripcion
        ]);
    }

    // Actualizar un plan existente
    public function actualizar($id_plan, $precio,$duracion_dias, $descripcion = null) {$sql = "UPDATE planes 
                SET precio = :precio, duracion_dias = :duracion_dias, descripcion = :descripcion 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':precio'        => $precio,
            ':duracion_dias' => $duracion_dias,
            ':descripcion'   => $descripcion,
            ':id'            => $id_plan
        ]);
    }

    // Consultar todos los planes registrados
    public function consultar() {
        $sql = "SELECT id, nombre, precio, duracion_dias, descripcion FROM planes ORDER BY id ASC";
        $stmt =$this->db->prepare($sql);$stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>