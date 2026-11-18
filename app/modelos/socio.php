<?php
require_once __dir__ . '/../config/conexion.php';

class socio {
    private $db;

    public function __construct() {
        $this->db = conexion::conectar();
    }

    public function registrar($dni, $nombres, $apellidos, $telefono, $correo, $direccion) {
        $sql = "insert into socios (dni, nombres, apellidos, telefono, correo, direccion, fecha_registro) 
                values (:dni, :nombres, :apellidos, :telefono, :correo, :direccion, curdate())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':dni', $dni);
        $stmt->bindvalue(':nombres', $nombres);
        $stmt->bindvalue(':apellidos', $apellidos);
        $stmt->bindvalue(':telefono', $telefono);
        $stmt->bindvalue(':correo', $correo);
        $stmt->bindvalue(':direccion', $direccion);
        return $stmt->execute();
    }

        public function editar($id, $dni, $nombres, $apellidos, $telefono, $correo, $direccion) {
        $sql = "update socios set dni = :dni, nombres = :nombres, apellidos = :apellidos, 
                telefono = :telefono, correo = :correo, direccion = :direccion 
                where id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':dni', $dni);
        $stmt->bindvalue(':nombres', $nombres);
        $stmt->bindvalue(':apellidos', $apellidos);
        $stmt->bindvalue(':telefono', $telefono);
        $stmt->bindvalue(':correo', $correo);
        $stmt->bindvalue(':direccion', $direccion);
        $stmt->bindvalue(':id', $id, pdo::param_int);
        return $stmt->execute();
    }
      public function buscarporid($id) {
        $sql = "select * from socios where id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':id', $id, pdo::param_int);
        $stmt->execute();
        return $stmt->fetch(pdo::fetch_assoc);
    }

    public function buscarpordni($dni) {
        $sql = "select * from socios where dni = :dni";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':dni', $dni);
        $stmt->execute();
        return $stmt->fetch(pdo::fetch_assoc);
    }
        public function listar() {
        $this->actualizarvencimientos();
        $sql = "select s.*, m.fecha_inicio, m.fecha_fin, m.estado as estado_membresia, p.nombre as nombre_plan
                from socios s
                left join membresias m on s.id = m.socio_id 
                    and m.id = (select max(id) from membresias where socio_id = s.id)
                left join planes p on m.plan_id = p.id
                order by s.id desc";
        $stmt = $this->db->query($sql);
        return $stmt->fetchall(pdo::fetch_assoc);
    }

    public function actualizarvencimientos() {
        $this->db->query("update membresias set estado = 'vencida' where fecha_fin < curdate() and estado = 'vigente'");
    }

    public function verporvencer($dias = 5) {
        $this->actualizarvencimientos();
        $sql = "select s.id, s.dni, s.nombres, s.apellidos, s.telefono, p.nombre as plan, m.fecha_fin, m.estado,
                datediff(m.fecha_fin, curdate()) as dias_restantes
                from membresias m
                inner join socios s on m.socio_id = s.id
                inner join planes p on m.plan_id = p.id
                where m.estado = 'vencida' or (m.estado = 'vigente' and datediff(m.fecha_fin, curdate()) <= :dias)
                order by m.fecha_fin asc";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':dias', $dias, pdo::param_int);
        $stmt->execute();
        return $stmt->fetchall(pdo::fetch_assoc);
    }
        public function registrarasistencia($dni, $observacion = '') {
        $this->actualizarvencimientos();
        $sql = "select s.id, s.nombres, s.apellidos, m.estado, m.fecha_fin
                from socios s
                inner join membresias m on s.id = m.socio_id
                where s.dni = :dni
                order by m.id desc limit 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindvalue(':dni', $dni);
        $stmt->execute();
        $socio = $stmt->fetch(pdo::fetch_assoc);

        if (!$socio) {
            return "el socio no existe o no tiene membresia registrada";
        }

        if ($socio['estado'] != 'vigente') {
            return "acceso denegado: membresia " . $socio['estado'] . " (" . $socio['fecha_fin'] . ")";
        }

        $sqlinsert = "insert into asistencias (socio_id, fecha_hora, observacion) values (:socio_id, now(), :observacion)";
        $stmtinsert = $this->db->prepare($sqlinsert);
        $stmtinsert->bindvalue(':socio_id', $socio['id'], pdo::param_int);
        $stmtinsert->bindvalue(':observacion', $observacion);
        $stmtinsert->execute();

        return "asistencia registrada: " . $socio['nombres'] . " " . $socio['apellidos'];
    }
}