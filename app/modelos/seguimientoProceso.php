<?php
require_once __DIR__ . '/../config/Conexion.php';

class SeguimientoProgreso {
    private $con;

    public function __construct() {
        $this->con = Conexion::conectar();
    }

    public function registrar($id_socio, $peso, $pecho, $cintura, $cadera, $brazo, $pierna) {

    }

    public function consultarPorSocio($id_socio) {

    }
}