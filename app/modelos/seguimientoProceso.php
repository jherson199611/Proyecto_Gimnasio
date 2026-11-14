<?php
require_once __dir__ . '/../config/conexion.php';

class seguimientoprogreso {
    private $db;

    public function __construct() {
        $this->db = conexion::conectar();
    }

    public function registrarmedida($socio_id, $peso, $altura, $cintura, $pecho, $observaciones) {
        $sql = "insert into seguimiento_progreso (socio_id, fecha, peso, altura, cintura, pecho, observaciones) 
                values (:socio_id, curdate(), :peso, :altura, :cintura, :pecho, :observaciones)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':socio_id', $socio_id, pdo::param_int);
        $stmt->bindvalue(':peso', $peso);
        $stmt->bindvalue(':altura', $altura);
        $stmt->bindvalue(':cintura', $cintura);
        $stmt->bindvalue(':pecho', $pecho);
        $stmt->bindvalue(':observaciones', $observaciones);
        return $stmt->execute();
    }

    public function listarmedidasporsocio($socio_id) {
        $sql = "select * from seguimiento_progreso where socio_id = :socio_id order by fecha desc";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':socio_id', $socio_id, pdo::param_int);
        $stmt->execute();
        return $stmt->fetchall(pdo::fetch_assoc);
    }

    public function editarmedida($id, $peso, $altura, $cintura, $pecho, $observaciones) {
        $sql = "update seguimiento_progreso 
                set peso = :peso, altura = :altura, cintura = :cintura, pecho = :pecho, observaciones = :observaciones 
                where id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':peso', $peso);
        $stmt->bindvalue(':altura', $altura);
        $stmt->bindvalue(':cintura', $cintura);
        $stmt->bindvalue(':pecho', $pecho);
        $stmt->bindvalue(':observaciones', $observaciones);
        $stmt->bindvalue(':id', $id, pdo::param_int);
        return $stmt->execute();
    }

    public function asignarrutina($socio_id, $rutina_id, $fecha_inicio, $fecha_fin = null) {
        $sqlcerrar = "update asignaciones_rutina set estado = 'finalizada' where socio_id = :socio_id and estado = 'activa'";
        $stmtcerrar = $this->db->prepare($sqlcerrar);
        $stmtcerrar->bindvalue(':socio_id', $socio_id, pdo::param_int);
        $stmtcerrar->execute();

        $sql = "insert into asignaciones_rutina (socio_id, rutina_id, fecha_inicio, fecha_fin, estado) 
                values (:socio_id, :rutina_id, :fecha_inicio, :fecha_fin, 'activa')";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':socio_id', $socio_id, pdo::param_int);
        $stmt->bindvalue(':rutina_id', $rutina_id, pdo::param_int);
        $stmt->bindvalue(':fecha_inicio', $fecha_inicio);
        $stmt->bindvalue(':fecha_fin', $fecha_fin);
        return $stmt->execute();
    }

    public function verrutinasporplansocio($socio_id) {
        $sqlplan = "select p.nombre 
                    from membresias m
                    inner join planes p on m.plan_id = p.id
                    where m.socio_id = :socio_id and m.estado = 'vigente'
                    order by m.id desc limit 1";
        $stmtplan = $this->db->prepare($sqlplan);
        $stmtplan->bindvalue(':socio_id', $socio_id, pdo::param_int);
        $stmtplan->execute();
        $plan = $stmtplan->fetch(pdo::fetch_assoc);

        if (!$plan) {
            return [];
        }

        if ($plan['nombre'] == 'básico') {
            $sql = "select * from rutinas where nivel = 'básico'";
        } elseif ($plan['nombre'] == 'estándar') {
            $sql = "select * from rutinas where nivel in ('básico', 'intermedio')";
        } else {
            $sql = "select * from rutinas";
        }

        $stmt = $this->db->query($sql);
        return $stmt->fetchall(pdo::fetch_assoc);
    }

    public function verrutinaactivasocio($socio_id) {
        $sql = "select ar.id as asignacion_id, ar.fecha_inicio, ar.fecha_fin, r.nombre as rutina, r.nivel, r.descripcion
                from asignaciones_rutina ar
                inner join rutinas r on ar.rutina_id = r.id
                where ar.socio_id = :socio_id and ar.estado = 'activa'
                order by ar.id desc limit 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':socio_id', $socio_id, pdo::param_int);
        $stmt->execute();
        return $stmt->fetch(pdo::fetch_assoc);
    }

    public function verejerciciosrutina($rutina_id) {
        $sql = "select e.nombre as ejercicio, e.musculo, dr.series, dr.repeticiones, dr.descanso_seg
                from detalle_rutina dr
                inner join ejercicios e on dr.ejercicio_id = e.id
                where dr.rutina_id = :rutina_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':rutina_id', $rutina_id, pdo::param_int);
        $stmt->execute();
        return $stmt->fetchall(pdo::fetch_assoc);
    }
}