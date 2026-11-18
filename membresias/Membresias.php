<?php
require_once __DIR__ . '/../conexion.php';

class Membresia {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    // Registrar nueva membresía calculando la fecha_fin automáticamente si no se provee
    public function registrar($id_socio, $id_plan, $fecha_inicio, $fecha_fin = null) {
        if (empty($fecha_fin)) {
            $stmtPlan = $this->db->prepare("SELECT duracion_dias FROM planes WHERE id = :id");
            $stmtPlan->execute([':id' => $id_plan]);
            $plan = $stmtPlan->fetch(PDO::FETCH_ASSOC);

            $dias = $plan ? (int)$plan['duracion_dias'] : 30; 
            $fecha_fin = date('Y-m-d', strtotime($fecha_inicio . " + {$dias} days"));
        }

        $sql = "INSERT INTO membresias (socio_id, plan_id, fecha_inicio, fecha_fin, estado) 
                VALUES (:id_socio, :id_plan, :fecha_inicio, :fecha_fin, 'Vigente')";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id_socio'     => $id_socio,
            ':id_plan'      => $id_plan,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin'    => $fecha_fin
        ]);
    }

    // Renovar membresía
    public function renovar($id_membresia, $nueva_fecha_fin) {
        $sql = "UPDATE membresias 
                SET fecha_fin = :nueva_fecha, estado = 'Vigente' 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nueva_fecha' => $nueva_fecha_fin,
            ':id'          => $id_membresia
        ]);
    }

    // Buscar membresías por socio
    public function buscarPorSocio($id_socio) {
        $sql = "SELECT m.*, p.nombre AS plan_nombre, p.precio 
                FROM membresias m
                INNER JOIN planes p ON m.plan_id = p.id
                WHERE m.socio_id = :id_socio
                ORDER BY m.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_socio' => $id_socio]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar estado manualmente
    public function actualizarEstado($id_membresia, $estado) {
        $sql = "UPDATE membresias SET estado = :estado WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':estado' => $estado,
            ':id'     => $id_membresia
        ]);
    }

    // Verificar vencimientos automáticos
    public function verificarVencimientos() {
        $sql = "UPDATE membresias 
                SET estado = 'Vencida' 
                WHERE fecha_fin < CURDATE() AND estado = 'Vigente'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }
}
?>