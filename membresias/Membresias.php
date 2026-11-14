<?php
require_once __DIR__ . '/../conexion.php';

class Membresia {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    // 1. Registrar nueva membresía
    // Si no le pasas fecha_vencimiento, la calcula automáticamente según la duración del plan
    public function registrar($id_socio, $id_plan, $fecha_inicio, $fecha_vencimiento = null) {
        if (empty($fecha_vencimiento)) {
            // Consulta la duración en días del plan elegido
            $stmtPlan = $this->db->prepare("SELECT duracion_dias FROM planes WHERE id = :id");
            $stmtPlan->execute([':id' => $id_plan]);
            $plan = $stmtPlan->fetch(PDO::FETCH_ASSOC);

            $dias = $plan ? $plan['duracion_dias'] : 30; // 30 días por defecto si no lo encuentra
            $fecha_vencimiento = date('Y-m-d', strtotime($fecha_inicio . " + {$dias} days"));
        }

        $sql = "INSERT INTO membresias (socio_id, plan_id, fecha_inicio, fecha_vencimiento, estado) 
                VALUES (:id_socio, :id_plan, :fecha_inicio, :fecha_vencimiento, 'vigente')";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id_socio'         => $id_socio,
            ':id_plan'          => $id_plan,
            ':fecha_inicio'     => $fecha_inicio,
            ':fecha_vencimiento'=> $fecha_vencimiento
        ]);
    }

    // 2. Renovar la membresía extendiendo la fecha de vencimiento
    public function renovar($id_membresia, $nueva_fecha_vencimiento) {
        $sql = "UPDATE membresias 
                SET fecha_vencimiento = :nueva_fecha, estado = 'vigente' 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nueva_fecha' => $nueva_fecha_vencimiento,
            ':id'          => $id_membresia
        ]);
    }

    // 3. Buscar todas las membresías asignadas a un socio específico
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

    // 4. Actualizar el estado manualmente ('vigente', 'vencida', 'cancelada')
    public function actualizarEstado($id_membresia, $estado) {
        $sql = "UPDATE membresias SET estado = :estado WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':estado' => $estado,
            ':id'     => $id_membresia
        ]);
    }

    // Método extra recomendado: Actualiza automáticamente las membresías cuya fecha ya expiró
    public function verificarVencimientos() {
        $sql = "UPDATE membresias 
                SET estado = 'vencida' 
                WHERE fecha_vencimiento < CURDATE() AND estado = 'vigente'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }
}
?>