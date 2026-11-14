<?php
class Conexion {
    private static $instancia = null;
    private $con;

    private function __construct() {
        $host = 'localhost';$db = 'gimnasio_db';
        $user = 'root';$pass = '';

        try {
            $this->con = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexion: " . $e->getMessage());
        }
    }

    public static function conectar() {
        if (self::$instancia == null) {
            self::$instancia = new Conexion();
        }
        return self::$instancia->con;
    }
}