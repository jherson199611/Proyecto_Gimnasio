
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

    }

    public function generarComprobante($idPago)
    {


    }
}
?>