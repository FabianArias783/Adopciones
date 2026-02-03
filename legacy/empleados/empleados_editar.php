<?php
include 'menu.php';
require "funciones/conecta.php"; 
include('validar_sesion.php');
$con = conecta();

$id = $_GET['id'];

// Obtener los datos del empleado
$sql = "SELECT * FROM empleados WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$empleado = $result->fetch_assoc();

// Variable para verificar si el correo ya está registrado
$correoExiste = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $pass = empty($_POST['pass']) ? $empleado['pass'] : password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];
    
    // Verificar si el correo ya está registrado (excluyendo el correo del empleado actual)
    if ($correo !== $empleado['correo']) {
        $sql = "SELECT id FROM empleados WHERE correo = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $correoExiste = true;  // El correo ya existe
            $stmt->close();
        }
    }

    if (!$correoExiste) {
        // Procesar la nueva imagen si es que se selecciona una
        $imagen = $_FILES['foto'];
        if (!empty($imagen['name'])) {
            // Borrar la imagen anterior
            $rutaAnterior = 'uploads/' . $empleado['archivo'];
            if (file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }

            // Subir la nueva imagen
            $imagenNombre = $imagen['name'];
            $imagenTmp = $imagen['tmp_name'];
            $imagenEncriptada = uniqid('img_') . '.' . pathinfo($imagenNombre, PATHINFO_EXTENSION);
            $uploadFile = 'uploads/' . $imagenEncriptada;

            if (move_uploaded_file($imagenTmp, $uploadFile)) {
                $sql = "UPDATE empleados SET nombre = ?, apellidos = ?, correo = ?, pass = ?, rol = ?, archivo_n = ?, archivo = ? WHERE id = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("sssssssi", $nombre, $apellidos, $correo, $pass, $rol, $imagenNombre, $imagenEncriptada, $id);
            } else {
                echo "Error al subir la imagen.";
                exit;
            }
        } else {
            // Si no se selecciona imagen, solo actualizamos los demás campos
            $sql = "UPDATE empleados SET nombre = ?, apellidos = ?, correo = ?, pass = ?, rol = ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssssi", $nombre, $apellidos, $correo, $pass, $rol, $id);
        }

        if ($stmt->execute()) {
            echo "Empleado actualizado correctamente.";
            header('Location: empleados_lista.php'); // Redirigir al listado
            exit;
        } else {
            echo "Error al actualizar el empleado.";
        }

        $stmt->close();
    }
    $con->close();
}
?>

<!-- Formulario HTML para editar el empleado -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333; /* Negro */
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
            background-color: #333;
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
        .back-btn {
            background-color: #333; /* Negro */
            color: white;
            margin-top: 10px;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-btn:hover {
            background-color: #555; /* Gris oscuro */
        }
        .error {
            color: red;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Editar Empleado</h1>
    </header>

    <div class="container">
        <form action="empleados_editar.php?id=<?php echo $empleado['id']; ?>" method="POST" enctype="multipart/form-data">
            <label>Nombre:</label><br>
            <input type="text" name="nombre" value="<?php echo $empleado['nombre']; ?>" required><br>
            <label>Apellidos:</label><br>
            <input type="text" name="apellidos" value="<?php echo $empleado['apellidos']; ?>" required><br>
            <label>Correo:</label><br>
            <input type="email" name="correo" value="<?php echo $empleado['correo']; ?>" required><br>

            <!-- Mostrar el error solo si el correo está repetido -->
            <?php if ($correoExiste): ?>
                <div class="error">El correo ya está registrado. Por favor, use otro.</div>
            <?php endif; ?>

            <label>Contraseña:</label><br>
            <input type="password" name="pass"><br>
            <label>Rol:</label><br>
            <select name="rol" required>
                <option value="1" <?php echo ($empleado['rol'] == 1) ? 'selected' : ''; ?>>Gerente</option>
                <option value="2" <?php echo ($empleado['rol'] == 2) ? 'selected' : ''; ?>>Ejecutivo</option>
            </select><br>
            <label>Foto (si deseas cambiarla):</label><br>
            <input type="file" name="foto" accept="image/*"><br><br>
            <?php if ($empleado['archivo']) : ?>
                <p><strong>Foto actual:</strong><br>
                <img src="uploads/<?php echo $empleado['archivo']; ?>" alt="Foto del empleado" width="150px"></p>
            <?php endif; ?>
            <button type="submit">Actualizar empleado</button>
        </form>

        <!-- Botón para regresar al listado -->
        <button class="back-btn" onclick="window.location.href='empleados_lista.php'">Regresar al listado</button>
    </div>
</body>
</html>
