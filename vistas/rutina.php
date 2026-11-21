
<?php

require_once "../conexion/conn.php";

class Rutina
{
    public function registrar($nombre, $nivel, $descripcion)
    {
        $connect = Conexion::getInstancia()->getPDO();

        $sql = "INSERT INTO rutinas (nombre, nivel, descripcion)
                VALUES (:nombre, :nivel, :descripcion)";

        $stmt = $connect->prepare($sql);

        $stmt->bindValue(":nombre", $nombre, PDO::PARAM_STR);
        $stmt->bindValue(":nivel", $nivel, PDO::PARAM_STR);
        $stmt->bindValue(":descripcion", $descripcion, PDO::PARAM_STR);

        $stmt->execute();

        $filas = $stmt->rowCount();

        Conexion::getInstancia()->cerrarConexion();

        return $filas;
    }
}