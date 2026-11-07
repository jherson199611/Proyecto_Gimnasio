<?php
require_once __DIR__ . '/../config/Conexion.php';

class Socio {

    public function __construct() {
     $this->pdo = Conexion::conectar();
    }

    public function registrar($dni, $nombres, $apellidos, $telefono, $email) {
    
    }

    public function buscarPorDni($dni) {
    
    }

    public function actualizar($id_socio, $nombres, $apellidos, $telefono, $email) {
    
    }

    public function consultarMembresia($id_socio) {

    }
}
?>