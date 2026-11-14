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

$seg = new seguimientoprogreso();
$db = conexion::conectar();
$mensaje = "";
$socio_id = isset($_REQUEST['socio_id']) ? intval($_REQUEST['socio_id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion == 'guardar_medida' && $socio_id > 0) {
        $ok = $seg->registrarmedida($socio_id, $_POST['peso'], $_POST['altura'], $_POST['cintura'], $_POST['pecho'], $_POST['observaciones']);
        $mensaje = $ok ? "Medida guardada." : "Error al guardar medida.";
    }

    if ($accion == 'asignar_rutina' && $socio_id > 0) {
        $fecha_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
        $ok = $seg->asignarrutina($socio_id, $_POST['rutina_id'], $_POST['fecha_inicio'], $fecha_fin);
        $mensaje = $ok ? "Rutina asignada." : "Error al asignar rutina.";
    }
}

$socios = $db->query("select id, dni, nombres, apellidos from socios order by id desc")->fetchAll(PDO::FETCH_ASSOC);
$medidas = $socio_id > 0 ? $seg->listarmedidasporsocio($socio_id) : [];
$rutinasDisponibles = $socio_id > 0 ? $seg->verrutinasporplansocio($socio_id) : [];
$rutinaActiva = $socio_id > 0 ? $seg->verrutinaactivasocio($socio_id) : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba Seguimiento</title>
</head>
<body>
    <h2>Seguimiento y Rutinas</h2>

    <?php if ($mensaje != ""): ?>
        <p><b><?php echo $mensaje; ?></b></p>
    <?php endif; ?>

    <form method="GET">
        <label>Socio:</label>
        <select name="socio_id" onchange="this.form.submit()">
            <option value="">-- Seleccione --</option>
            <?php foreach ($socios as $s): ?>
                <option value="<?php echo $s['id']; ?>" <?php echo ($socio_id == $s['id']) ? 'selected' : ''; ?>>
                    <?php echo $s['dni'] . " - " . $s['nombres'] . " " . $s['apellidos']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($socio_id > 0): ?>
        <hr>
        <h3>Registrar Medida</h3>
        <form method="POST">
            <input type="hidden" name="accion" value="guardar_medida">
            <input type="hidden" name="socio_id" value="<?php echo $socio_id; ?>">
            Peso: <input type="number" step="0.01" name="peso" required><br><br>
            Altura: <input type="number" step="0.01" name="altura" required><br><br>
            Cintura: <input type="number" step="0.01" name="cintura"><br><br>
            Pecho: <input type="number" step="0.01" name="pecho"><br><br>
            Observaciones: <input type="text" name="observaciones"><br><br>
            <button type="submit">Guardar</button>
        </form>

        <h4>Medidas Registradas</h4>
        <table border="1" cellpadding="5">
            <tr>
                <th>Fecha</th>
                <th>Peso</th>
                <th>Altura</th>
                <th>Cintura</th>
                <th>Pecho</th>
                <th>Observaciones</th>
            </tr>
            <?php foreach ($medidas as $m): ?>
                <tr>
                    <td><?php echo $m['fecha']; ?></td>
                    <td><?php echo $m['peso']; ?></td>
                    <td><?php echo $m['altura']; ?></td>
                    <td><?php echo $m['cintura']; ?></td>
                    <td><?php echo $m['pecho']; ?></td>
                    <td><?php echo $m['observaciones']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <hr>
        <h3>Asignar Rutina</h3>
        <?php if (count($rutinasDisponibles) > 0): ?>
            <form method="POST">
                <input type="hidden" name="accion" value="asignar_rutina">
                <input type="hidden" name="socio_id" value="<?php echo $socio_id; ?>">
                Rutina:
                <select name="rutina_id" required>
                    <?php foreach ($rutinasDisponibles as $r): ?>
                        <option value="<?php echo $r['id']; ?>">
                            <?php echo $r['nombre'] . " (" . $r['nivel'] . ")"; ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>
                Inicio: <input type="date" name="fecha_inicio" value="<?php echo date('Y-m-d'); ?>" required><br><br>
                Fin: <input type="date" name="fecha_fin"><br><br>
                <button type="submit">Asignar</button>
            </form>
        <?php else: ?>
            <p>Sin membresia vigente o sin rutinas en la BD.</p>
        <?php endif; ?>

        <h4>Rutina Activa</h4>
        <?php if ($rutinaActiva): ?>
            <p><?php echo $rutinaActiva['rutina'] . " - Nivel: " . $rutinaActiva['nivel'] . " (Desde: " . $rutinaActiva['fecha_inicio'] . ")"; ?></p>
        <?php else: ?>
            <p>Ninguna rutina activa.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>