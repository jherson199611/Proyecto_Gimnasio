<?php
class Conexion {
    public static function conectar() {
        $host = "localhost";
        $bd = "gimnasio";
        $user = "root";
        $pass = "";

        $con = new PDO("mysql:host=$host;dbname=$bd;charset=utf8", $user, $pass);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $con;
    }
}