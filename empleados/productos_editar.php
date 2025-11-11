<?php
require "funciones/conecta.php";
$con = conecta();
include 'menu.php';
include('validar_sesion.php');
$con = conecta();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = ""; // Variable para almacenar mensajes de error

// Consultar los datos actuales del producto
$sql = "SELECT * FROM productos WHERE id = ? AND eliminado = 0";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $producto = $result->fetch_assoc();
} else {
    echo "Producto no encontrado.";
    exit;
}

// Variables para repoblar los campos
$nombre = $producto['nombre'];
$codigo = $producto['codigo'];
$descripcion = $producto['descripcion'];
$costo = $producto['costo'];
$stock = $producto['stock'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $codigo = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];
    $costo = $_POST['costo'];
    $stock = $_POST['stock'];
    $imagen = $_FILES['foto'];

    // Validar código duplicado
    $sql = "SELECT id FROM productos WHERE codigo = ? AND id != ? AND eliminado = 0";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $codigo, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "El código ya está en uso por otro producto.";
    } else {
        $imagenNombre = $imagen['name'];
        if (!empty($imagenNombre)) {
            // Procesar nueva imagen si se subió
            $imagenTmp = $imagen['tmp_name'];
            $imagenEncriptada = uniqid('img_') . '.' . pathinfo($imagenNombre, PATHINFO_EXTENSION);
            $uploadDir = 'uploads_productos/'; // Carpeta de imágenes
            $uploadFile = $uploadDir . $imagenEncriptada;

            // Borrar imagen anterior
            $rutaAnterior = $uploadDir . $producto['archivo'];
            if (file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }

            // Subir nueva imagen
            if (move_uploaded_file($imagenTmp, $uploadFile)) {
                $sql = "UPDATE productos SET nombre = ?, codigo = ?, descripcion = ?, costo = ?, stock = ?, archivo_n = ?, archivo = ? WHERE id = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("ssssissi", $nombre, $codigo, $descripcion, $costo, $stock, $imagenNombre, $imagenEncriptada, $id);
            } else {
                $error = "Error al subir la imagen.";
            }
        } else {
            // Actualizar sin cambiar la imagen
            $sql = "UPDATE productos SET nombre = ?, codigo = ?, descripcion = ?, costo = ?, stock = ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssisi", $nombre, $codigo, $descripcion, $costo, $stock, $id);
        }

        if (empty($error) && $stmt->execute()) {
            header('Location: productos_lista.php');
            exit;
        } else {
            $error = $error ?: "Error al actualizar el producto.";
        }
    }
}

$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
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
        .error {
            color: red;
            margin-bottom: 10px;
        }
        input, textarea, button {
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
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Editar Producto</h1>
    </header>
    <div class="container">
        <form action="productos_editar.php?id=<?= $producto['id'] ?>" method="POST" enctype="multipart/form-data">
            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
            <label>Código:</label>
            <input type="text" name="codigo" value="<?= htmlspecialchars($codigo) ?>" required>
            <label>Descripción:</label>
            <textarea name="descripcion" rows="4" required><?= htmlspecialchars($descripcion) ?></textarea>
            <label>Costo:</label>
            <input type="number" name="costo" step="0.01" value="<?= htmlspecialchars($costo) ?>" required>
            <label>Stock:</label>
            <input type="number" name="stock" value="<?= htmlspecialchars($stock) ?>" required>
            <label>Imagen:</label>
            <input type="file" name="foto" accept="image/*">
            <?php if ($producto['archivo']): ?>
                <p><strong>Imagen actual:</strong></p>
                <img src="uploads_productos/<?= htmlspecialchars($producto['archivo']) ?>" alt="Imagen del producto">
            <?php endif; ?>
            <button type="submit">Actualizar Producto</button>
        </form>
    </div>
</body>
</html>
