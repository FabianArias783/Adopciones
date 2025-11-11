<?php
require "funciones/conecta.php"; 
$con = conecta();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];

    // Procesar la imagen
    $imagen = $_FILES['archivo'];
    $imagenNombre = $imagen['name'];
    $imagenTmp = $imagen['tmp_name'];

    if (empty($imagenNombre)) {
        echo "La imagen es obligatoria.";
        exit;
    }

    // Generar nombre encriptado para la imagen
    $imagenEncriptada = md5_file($imagenTmp) . '.' . pathinfo($imagenNombre, PATHINFO_EXTENSION);

    // Validar dimensiones de la imagen
    list($ancho, $alto) = getimagesize($imagenTmp);
    if ($ancho / $alto != 600 / 200) {
        echo "La imagen debe tener una proporción de 600x200.";
        exit;
    }

    // Subir la imagen a la carpeta 'uploads_promociones'
    $uploadDir = 'uploads_promociones/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $uploadFile = $uploadDir . $imagenEncriptada;

    if (move_uploaded_file($imagenTmp, $uploadFile)) {
        // Insertar en la base de datos
        $sql = "INSERT INTO promociones (nombre, archivo, eliminado) 
                VALUES (?, ?, 0)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $nombre, $imagenEncriptada);
        
        if ($stmt->execute()) {
            echo "Promoción agregada correctamente.";
            header('Location: promociones_lista.php'); // Redirigir al listado
            exit;
        } else {
            echo "Error al agregar la promoción.";
        }
        $stmt->close();
    } else {
        echo "Error al subir la imagen.";
    }

    $con->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Promoción</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        .container {
            width: 50%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            font-size: 1rem;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        a {
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <h1>Alta de Promoción</h1>
    </header>

    <div class="container">
        <form action="promociones_alta.php" method="POST" enctype="multipart/form-data">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>
            
            <label>Imagen (600x200px):</label>
            <input type="file" name="archivo" accept="image/*" required>
            
            <button type="submit">Agregar Promoción</button>
        </form>
    </div>
</body>
</html>
