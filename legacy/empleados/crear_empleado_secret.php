<?php
// 🚨 SOLO PARA USO ADMINISTRATIVO. ELIMINA ESTE ARCHIVO DESPUÉS DE USARLO 🚨

include 'Funciones/conecta.php';
$con = conecta();

// Verifica si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];
    $rol = intval($_POST['rol']);

    // Generar hash seguro de la contraseña
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar en base de datos
    $sql = "INSERT INTO empleados (nombre, apellidos, correo, pass, rol, archivo_n, archivo, eliminado)
            VALUES (?, ?, ?, ?, ?, 'default.jpg', 'default.jpg', 0)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssssi', $nombre, $apellidos, $correo, $hash, $rol);

    if ($stmt->execute()) {
        $mensaje = "✅ Usuario creado correctamente.";
    } else {
        $mensaje = "❌ Error al crear usuario: " . $con->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario Secreto</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #fffaf0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .formulario {
            background: #fff;
            border: 2px dashed #ffa07a;
            border-radius: 15px;
            padding: 25px 30px;
            width: 350px;
            box-shadow: 0 0 15px rgba(255,160,122,0.3);
        }
        h2 {
            color: #ff6f61;
            text-align: center;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            background-color: #ffb6b9;
            border: none;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            color: white;
            margin-top: 15px;
            cursor: pointer;
        }
        button:hover {
            background-color: #ff6f61;
        }
        .mensaje {
            text-align: center;
            margin-top: 10px;
            color: #333;
        }
        .alerta {
            color: #ff6f61;
            font-weight: bold;
            text-align: center;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="formulario">
        <h2>Crear Empleado 👩‍💻</h2>
        <div class="alerta">⚠️ Archivo interno. Elimínalo tras usarlo.</div>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellidos" placeholder="Apellidos" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <select name="rol" required>
                <option value="1">Administrador</option>
                <option value="2">Empleado</option>
            </select>
            <button type="submit">Crear Usuario</button>
        </form>
        <?php if (isset($mensaje)): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
