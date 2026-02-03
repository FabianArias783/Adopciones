<?php
session_start();
include 'funciones/conecta.php';
$con = conecta();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $correo = trim($_POST['correo']);
    $pass = trim($_POST['pass']);
    $confirmar = trim($_POST['confirmar']);

    if ($pass !== $confirmar) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $sql_check = "SELECT id FROM clientes WHERE correo = '$correo' AND eliminado = 0";
        $res = $con->query($sql_check);

        if ($res->num_rows > 0) {
            $error = "El correo ya está registrado.";
        } else {
            $hash = md5($pass);
            $sql = "INSERT INTO clientes (nombre, apellidos, correo, pass, eliminado)
                    VALUES ('$nombre', '$apellidos', '$correo', '$hash', 0)";
            
            if ($con->query($sql)) {
                header("Location: login_cliente.php");
                exit;
            } else {
                $error = "Error al registrar usuario. Inténtalo más tarde.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #fffaf0;
            margin: 0;
            padding: 0;
        }

        /* 🔸 Navbar igual que login */
        .navbar {
            background-color: #ffb6b9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 30px;
            box-shadow: 0 2px 6px rgba(255, 160, 122, 0.4);
        }
        .navbar h1 {
            color: white;
            font-family: 'Pacifico', cursive;
            font-size: 1.8rem;
            margin: 0;
        }
        .navbar-buttons {
            display: flex;
            gap: 10px;
        }
        .navbar a {
            text-decoration: none;
            background-color: white;
            color: #ff6f61;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .navbar a:hover {
            background-color: #ff6f61;
            color: white;
            border: 2px solid white;
        }

        /* 🔸 Formulario */
        .container {
            width: 90%;
            max-width: 450px;
            margin: 80px auto;
            background-color: #fff;
            border: 2px dashed #ffa07a;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(255, 160, 122, 0.3);
        }
        h2 {
            text-align: center;
            color: #ff6f61;
            margin-bottom: 20px;
        }
        form input[type="text"],
        form input[type="email"],
        form input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }
        form input[type="submit"] {
            background-color: #ffb6b9;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        form input[type="submit"]:hover {
            background-color: #ff6f61;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .gif-container {
            text-align: center;
            margin-bottom: 15px;
        }
        .gif-container img {
            width: 150px;
            border-radius: 12px;
        }
        .link {
            text-align: center;
            margin-top: 15px;
        }
        .link a {
            color: #ff6f61;
            text-decoration: none;
            font-weight: bold;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>🐾 Huellitas Unidas</h1>
        <div class="navbar-buttons">
            <a href="home.php">🏠 Inicio</a>
            <a href="login_cliente.php">🔐 Iniciar sesión</a>
        </div>
    </div>

    <!--  Formulario de registro -->
    <div class="container">
        <div class="gif-container">
            <img src="https://media.giphy.com/media/3oriO0OEd9QIDdllqo/giphy.gif" alt="Gatito pastel">
        </div>
        <h2>Crear Cuenta</h2>
        <form action="" method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellidos" placeholder="Apellidos" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="pass" placeholder="Contraseña" required>
            <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
            <input type="submit" value="Registrarse">
        </form>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="link">
            <p>¿Ya tienes cuenta? <a href="login_cliente.php">Inicia sesión</a></p>
        </div>
    </div>
</body>
</html>
