<?php
require_once __dir__ . '/../config/conexion.php';

class socio {
    private $db;

    public function __construct() {
        $this->db = conexion::conectar();
    }

    public function registrar($dni, $nombres, $apellidos, $telefono, $correo, $direccion) {
        $sql = "insert into socios (dni, nombres, apellidos, telefono, correo, direccion, fecha_registro) 
                values (:dni, :nombres, :apellidos, :telefono, :correo, :direccion, curdate())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':dni', $dni);
        $stmt->bindvalue(':nombres', $nombres);
        $stmt->bindvalue(':apellidos', $apellidos);
        $stmt->bindvalue(':telefono', $telefono);
        $stmt->bindvalue(':correo', $correo);
        $stmt->bindvalue(':direccion', $direccion);
        return $stmt->execute();
    }

        public function editar($id, $dni, $nombres, $apellidos, $telefono, $correo, $direccion) {
        $sql = "update socios set dni = :dni, nombres = :nombres, apellidos = :apellidos, 
                telefono = :telefono, correo = :correo, direccion = :direccion 
                where id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':dni', $dni);
        $stmt->bindvalue(':nombres', $nombres);
        $stmt->bindvalue(':apellidos', $apellidos);
        $stmt->bindvalue(':telefono', $telefono);
        $stmt->bindvalue(':correo', $correo);
        $stmt->bindvalue(':direccion', $direccion);
        $stmt->bindvalue(':id', $id, pdo::param_int);
        return $stmt->execute();
    }
}