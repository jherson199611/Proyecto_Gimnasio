<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">   
    <title>Registrar Pago</title>
</head>
<body>      
    <h2>Registrar Pago</h2>
    <form action="" method="POST">
    <label>Fecha de Pago</label>
    <input type="date" name="fechaPago" required>
    <br><br>


    <label>Monto</label>
    <inpunt type="number" name="monto" step="0.01" min="0"
    required>
    <br><br>

    <button type="submit" name="registrar"> registrar Pago </button>
    </form>
</body>
</html>