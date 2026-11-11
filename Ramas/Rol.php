<?php

require_once "../config/conexion.php";

class Rol {

    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }

    // REGISTRAR ROL
    public function registrar($nombre) {

        $sql = "INSERT INTO roles (nombre) VALUES (?)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([$nombre]);
    }

    // ACTUALIZAR ROL
    public function actualizar($id, $nombre) {

        $sql = "UPDATE roles SET nombre = ? WHERE id_rol = ?";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([$nombre, $id]);
    }

    // CONSULTAR ROLES
    public function consultar() {

        $sql = "SELECT * FROM roles";

        $stmt = $this->conexion->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
