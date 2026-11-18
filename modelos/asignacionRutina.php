
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
}
?>