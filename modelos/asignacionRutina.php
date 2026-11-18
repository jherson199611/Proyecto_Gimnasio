
<?php

require_once "../conexion/conn.php";

class AsignacionRutina
{
    public function asignar($socio_id, $rutina_id, $fecha_inicio, $fecha_fin)
    {
        $connect = Conexion::getInstancia()->getPDO();

        $sql = "INSERT INTO asignaciones_rutina
                (socio_id, rutina_id, fecha_inicio, fecha_fin, estado)
                VALUES (:socio_id, :rutina_id, :fecha_inicio, :fecha_fin, :estado)";

        $stmt = $connect->prepare($sql);

        $stmt->bindValue(":socio_id", $socio_id, PDO::PARAM_INT);
        $stmt->bindValue(":rutina_id", $rutina_id, PDO::PARAM_INT);
        $stmt->bindValue(":fecha_inicio", $fecha_inicio, PDO::PARAM_STR);
        $stmt->bindValue(":fecha_fin", $fecha_fin, PDO::PARAM_STR);
        $stmt->bindValue(":estado", "Activa", PDO::PARAM_STR);

        $stmt->execute();

        $filas = $stmt->rowCount();

        Conexion::getInstancia()->cerrarConexion();

        return $filas;
    }
   
public function finalizar($id)
{
    $connect = Conexion::getInstancia()->getPDO();

    $sql = "UPDATE asignaciones_rutina
            SET estado = :estado, fecha_fin = CURDATE()
            WHERE id = :id";

    $stmt = $connect->prepare($sql);

    $stmt->bindValue(":estado", "Finalizada", PDO::PARAM_STR);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    $stmt->execute();

    $filas = $stmt->rowCount();

    Conexion::getInstancia()->cerrarConexion();

    return $filas;
}

public function consultar()
{
    $connect = Conexion::getInstancia()->getPDO();

    $sql = "SELECT * FROM asignaciones_rutina";

    $stmt = $connect->prepare($sql);

    $stmt->execute();

    $asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $asignaciones;
} 
}
?>