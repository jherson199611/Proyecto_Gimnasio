
<?php

require_once "../conexion/conn.php";

class Pago
{
    public function registrar($fechaPago, $monto, $idMembresia)
    {
    $connect = Conexion::getInstancia()->getPDO();

    $sql = "INSERT INTO pagos (fecha_pago, monto, estado, id_membresia)
            VALUES (:fecha_pago, :monto, :estado, :id_membresia)";

    $stmt = $connect->prepare($sql);

    $stmt->bindValue(":fecha_pago", $fechaPago, PDO::PARAM_STR);
    $stmt->bindValue(":monto", $monto);
    $stmt->bindValue(":estado", "Registrado", PDO::PARAM_STR);
    $stmt->bindValue(":id_membresia", $idMembresia, PDO::PARAM_INT);

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

    $sql = "SELECT * FROM pagos WHERE id_pago = :id_pago";

    $stmt = $connect->prepare($sql);

    $stmt->bindValue(":id_pago", $idPago, PDO::PARAM_INT);

    $stmt->execute();

    $pago = $stmt->fetch(PDO::FETCH_ASSOC);

    return $pago;
    }
}
?>