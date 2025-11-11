<?php
require "funciones/conecta.php";
$con = conecta();

$id = $_GET['id'] ?? 0;

// Obtener los datos de la promoción
$sql = "SELECT * FROM promociones WHERE id = $id AND eliminado = 0";
$res = $con->query($sql);

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $nombreActual = $row['nombre'];
    $archivoActual = $row['archivo'];
} else {
    echo "Promoción no encontrada.";
    exit;
}

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];

    // Manejo de la imagen (si se sube una nueva)
    $imagen = $_FILES['archivo'];
    $imagenNombre = $imagen['name'];
    $imagenTmp = $imagen['tmp_name'];
    $archivoEncriptado = $archivoActual; // Mantener el archivo actual por defecto

    if (!empty($imagenNombre)) {
        $archivoEncriptado = md5_file($imagenTmp) . '.' . pathinfo($imagenNombre, PATHINFO_EXTENSION);
        $rutaDestino = "uploads_promociones/$archivoEncriptado";

        if (!move_uploaded_file($imagenTmp, $rutaDestino)) {
            echo "Error al subir la nueva imagen.";
            exit;
        }
    }

    // Actualizar la promoción en la base de datos
    $sqlUpdate = "UPDATE promociones SET nombre = ?, archivo = ? WHERE id = ?";
    $stmt = $con->prepare($sqlUpdate);
    $stmt->bind_param("ssi", $nombre, $archivoEncriptado, $id);

    if ($stmt->execute()) {
        header("Location: promociones_lista.php");
        exit;
    } else {
        echo "Error al actualizar la promoción.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Promoción</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 50%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="file"], button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #555;
        }
        a {
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Promoción</h1>
        <form action="promociones_editar.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <label>Nombre:</label><br>
            <input type="text" name="nombre" value="<?php echo $nombreActual; ?>" required><br>
            <label>Imagen actual:</label><br>
            <img src="uploads_promociones/<?php echo $archivoActual; ?>" alt="Imagen Actual" style="max-width: 100%; height: auto;"><br><br>
            <label>Nueva Imagen (opcional):</label><br>
            <input type="file" name="archivo" accept="image/*"><br>
            <button type="submit">Guardar Cambios</button>
        </form>
        <br>
        <a href="promociones_lista.php">Regresar al listado</a>
    </div>
</body>
</html>
