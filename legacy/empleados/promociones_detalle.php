<?php
require "funciones/conecta.php";
$con = conecta();

$id = $_GET['id'] ?? 0;

// Obtener los datos de la promoción
$sql = "SELECT * FROM promociones WHERE id = $id AND eliminado = 0";
$res = $con->query($sql);

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $nombre = $row['nombre'];
    $archivo = $row['archivo'];
    $archivoRuta = "uploads_promociones/$archivo";
} else {
    echo "Promoción no encontrada.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Promoción</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #333;
        }
        .container {
            width: 50%;
            margin: 20px auto;
            background-color: #333;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        img {
            max-width: 100%;
            height: auto;
        }
        a {
            text-decoration: none;
            color: #333;
            margin-top: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalle de Promoción</h1>
        <p><strong>Nombre:</strong> <?php echo $nombre; ?></p>
        <p><strong>Imagen:</strong></p>
        <img src="<?php echo $archivoRuta; ?>" alt="<?php echo $nombre; ?>">
        <br><br>
        <a href="promociones_lista.php">Regresar al listado</a>
    </div>
</body>
</html>
