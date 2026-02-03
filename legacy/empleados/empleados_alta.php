<?php
include 'menu.php';
require "funciones/conecta.php"; 
include('validar_sesion.php');
$con = conecta();

$correoExiste = false;  // Variable para saber si el correo ya existe

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    // Verificar si el correo ya existe en la base de datos
    $sql = "SELECT id FROM empleados WHERE correo = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $correoExiste = true;  // El correo ya existe
        $stmt->close();
    } else {
        $stmt->close();

        // Procesar la imagen
        $imagen = $_FILES['foto'];
        $imagenNombre = $imagen['name'];
        $imagenTmp = $imagen['tmp_name'];

        // Validar que la imagen no esté vacía
        if (empty($imagenNombre)) {
            echo "La imagen es obligatoria.";
            exit;
        }

        // Generar nombre encriptado para la imagen
        $imagenEncriptada = uniqid('img_') . '.' . pathinfo($imagenNombre, PATHINFO_EXTENSION);

        // Subir la imagen a la carpeta 'uploads'
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . $imagenEncriptada;

        if (move_uploaded_file($imagenTmp, $uploadFile)) {
            // Insertar en la base de datos
            $sql = "INSERT INTO empleados (nombre, apellidos, correo, pass, rol, archivo_n, archivo, eliminado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssssss", $nombre, $apellidos, $correo, $pass, $rol, $imagenNombre, $imagenEncriptada);
            
            if ($stmt->execute()) {
                echo "Empleado dado de alta correctamente.";
                header('Location: empleados_lista.php'); // Redirigir al listado
                exit;
            } else {
                echo "Error al dar de alta el empleado.";
            }
            $stmt->close();
        } else {
            echo "Error al subir la imagen.";
        }
    }

    $con->close();
}
?>

<!-- Formulario HTML para alta de empleados -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Empleado</title>
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
        input[type="text"], input[type="email"], input[type="password"], select, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #000;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #555;
        }
        a {
            text-decoration: none;
            color: #333;
        }
        .btn-regresar {
            background-color: #000;
            margin-top: 10px;
        }
        .btn-regresar:hover {
            background-color: #555;
        }
        .error {
            color: red;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Alta de Empleado</h1>
    </header>

    <div class="container">
        <form action="empleados_alta.php" method="POST" enctype="multipart/form-data">
            <label>Nombre:</label><br>
            <input type="text" name="nombre" required><br>
            <label>Apellidos:</label><br>
            <input type="text" name="apellidos" required><br>
            <label>Correo:</label><br>
            <input type="email" name="correo" required><br>
            
            <!-- Mostrar el error solo si el correo está repetido -->
            <?php if ($correoExiste): ?>
                <div class="error" id="errorCorreo">El correo ya está registrado. Por favor, use otro.</div>
            <?php endif; ?>

            <label>Contraseña:</label><br>
            <input type="password" name="pass" required><br>
            <label>Rol:</label><br>
            <select name="rol" required>
                <option value="1">Gerente</option>
                <option value="2">Ejecutivo</option>
            </select><br>
            <label>Foto:</label><br>
            <input type="file" name="foto" accept="image/*" required><br><br>
            
            <button type="submit">Dar de alta</button>
        </form>
        <br>
        <a href="empleados_lista.php"><button class="btn-regresar">Regresar al listado</button></a>
    </div>

    <script>
        // Ocultar el mensaje de error después de 5 segundos
        window.onload = function () {
            const errorCorreo = document.getElementById('errorCorreo');
            if (errorCorreo) {
                setTimeout(() => {
                    errorCorreo.style.display = 'none';
                }, 5000);
            }
        }
    </script>
</body>
</html>
