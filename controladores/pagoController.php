<?php

require_once "../modelos/Pago.php";

class PagoController
{
    public function registrar($fechaPago, $monto, $idMembresia)
    {
        $pago = new Pago();
        return $pago->registrar(
            $fechaPago,
            $monto,
            $idMembresia
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