
<?php

require_once "../conexion/conn.php";

class Asistencia
{
    
public function registrar($fecha, $hora, $idSocio)
{
    $connect = Conexion::getInstancia()->getPDO();

    $sql = "INSERT INTO asistencias (fecha, hora, id_socio)
            VALUES (:fecha, :hora, :id_socio)";

    $stmt = $connect->prepare($sql);

    $stmt->bindValue(":fecha", $fecha, PDO::PARAM_STR);
    $stmt->bindValue(":hora", $hora, PDO::PARAM_STR);
    $stmt->bindValue(":id_socio", $idSocio, PDO::PARAM_INT);

    $stmt->execute();

    $filas = $stmt->rowCount();

    Conexion::getInstancia()->cerrarConexion();

    return $filas;
}

    
public function consultar()
{
    $connect = Conexion::getInstancia()->getPDO();

    $sql = "SELECT * FROM asistencias";

    $stmt = $connect->prepare($sql);

    $stmt->execute();

    $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $asistencias;
}
}
?>