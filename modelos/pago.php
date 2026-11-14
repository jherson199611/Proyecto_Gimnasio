
<?php

require_once "../conexion/conn.php";

class Pago
{
    public function registrar($membresia_id, $fecha, $monto, $metodo, $referencia)
    {
        $connect = Conexion::getInstancia()->getPDO();

        $sql = "INSERT INTO pagos (membresia_id, fecha, monto, metodo, referencia)
                VALUES (:membresia_id, :fecha, :monto, :metodo, :referencia)";

        $stmt = $connect->prepare($sql);

        $stmt->bindValue(":membresia_id", $membresia_id, PDO::PARAM_INT);
        $stmt->bindValue(":fecha", $fecha, PDO::PARAM_STR);
        $stmt->bindValue(":monto", $monto);
        $stmt->bindValue(":metodo", $metodo, PDO::PARAM_STR);
        $stmt->bindValue(":referencia", $referencia, PDO::PARAM_STR);

        $stmt->execute();

        $filas = $stmt->rowCount();

        Conexion::getInstancia()->cerrarConexion();

        return $filas;
    }

    public function consultar()
    {
        $connect = Conexion::getInstancia()->getPDO();

        $sql = "SELECT * FROM pagos";

        $stmt = $connect->prepare($sql);

        $stmt->execute();

        $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $pagos;
    }

    public function generarComprobante($idPago)
    {
        $connect = Conexion::getInstancia()->getPDO();

        $sql = "SELECT * FROM pagos WHERE id = :id";

        $stmt = $connect->prepare($sql);

        $stmt->bindValue(":id", $idPago, PDO::PARAM_INT);

        $stmt->execute();

        $pago = $stmt->fetch(PDO::FETCH_ASSOC);

        return $pago;
    }
}

?>
