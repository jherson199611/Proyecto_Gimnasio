
<?php

require_once "../modelos/Pago.php";

class PagoController
{
    public function registrar($membresia_id, $fecha, $monto, $metodo, $referencia)
    {
        $pago = new Pago();

        return $pago->registrar(
            $membresia_id,
            $fecha,
            $monto,
            $metodo,
            $referencia
        );
    }

    public function consultar()
    {
        $pago = new Pago();

        return $pago->consultar();
    }

    public function generarComprobante($idPago)
    {
        $pago = new Pago();

        return $pago->generarComprobante($idPago);
    }
}

?>
