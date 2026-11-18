
<?php require_once '../includes/auth.php';
require_once '../clases/Rol.php';
$o=new Rol();
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
        $o->registrar($_POST['nombre'],$_POST['descripcion']);
        $msg='Rol creado.';
    }
    catch(Throwable $e){
        $msg='Error: '.$e->getMessage();
    }
}
$rows=$o->consultar();
$base='../';
require '../includes/header.php';
?>
<div class="panel">
<h1>Roles</h1>
<?php if($msg):?>
<div class="msg">
<?=htmlspecialchars($msg)?>
</div>
<?php endif;
?>
<form method="post">
<label>Nombre</label>
<input name="nombre" required>
<label>Descripción</label>
<input name="descripcion">
<button>Registrar rol</button>
</form>
</div>
<div class="panel">
<table>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Descripción</th>
</tr>
<?php foreach($rows as $r):?>
<tr>
<td>
<?=$r['id']?>
</td>
<td>
<?=htmlspecialchars($r['nombre'])?>
</td>
<td>
<?=htmlspecialchars($r['descripcion'])?>
</td>
</tr>
<?php endforeach;
?>
</table>
</div>
<?php require '../includes/footer.php';
?>
