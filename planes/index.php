<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Planes - Gimnasio J</title>
</head>
<body>
    <h2>Formulario de Registro de Planes</h2>
    <form action="" method="POST">
        <label>Nombre del Plan:</label>
        <input type="text" name="nombre" placeholder="Ej. Premium"><br><br>
        
        <label>Precio:</label>
        <input type="number" name="precio" placeholder="0.00"><br><br>
        
        <label>Duración (días):</label>
        <input type="number" name="duracion" placeholder="30"><br><br>
        
        <button type="submit">Guardar Plan</button>
    </form>

    <hr>

    <h2>Lista de Planes</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Duración</th>
        </tr>
    </table>
</body>
</html>