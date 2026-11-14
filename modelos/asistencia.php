
<?php

require_once "../conexion/conn.php";

class Asistencia
{
    public function registrar($socio_id, $fecha_hora, $observacion)
    {
        $connect = Conexion::getInstancia()->getPDO();

        $sql = "INSERT INTO asistencias (socio_id, fecha_hora, observacion)
                VALUES (:socio_id, :fecha_hora, :observacion)";

        $stmt = $connect->prepare($sql);

        $stmt->bindValue(":socio_id", $socio_id, PDO::PARAM_INT);
        $stmt->bindValue(":fecha_hora", $fecha_hora, PDO::PARAM_STR);
        $stmt->bindValue(":observacion", $observacion, PDO::PARAM_STR);

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
