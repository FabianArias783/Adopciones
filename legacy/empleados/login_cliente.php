<?php
session_start();
include_once 'funciones/conecta.php';
$con = conecta();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $pass = $_POST['pass'];
    $hash = md5($pass); // 🔒 Convertir la contraseña a md5

    // Verifica con contraseña encriptada o en texto plano (por compatibilidad)
    $sql = "SELECT * FROM clientes 
            WHERE correo = '$correo' 
            AND (pass = '$pass' OR pass = '$hash') 
            AND eliminado = 0";
    $result = $con->query($sql);


    if ($result->num_rows > 0) {
        $cliente = $result->fetch_assoc();
        $_SESSION['cliente_id'] = $cliente['id'];
        $_SESSION['cliente_nombre'] = $cliente['nombre'];
        $_SESSION['cliente_apellidos'] = $cliente['apellidos'];
        $_SESSION['cliente_correo'] = $cliente['correo'];

        header("Location: home.php");
        exit;
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #fffaf0;
            margin: 0;
            padding: 0;
        }

        /* 🔸 Navbar */
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

        /* 🔸 Login box */
        .container {
            width: 90%;
            max-width: 400px;
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
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🐾 Huellitas Unidas</h1>
        <div class="navbar-buttons">
            <a href="home.php">🏠 Inicio</a>
            <a href="sign_up.php">📝 Registrarse</a>
        </div>
    </div>

    <div class="container">
        <div class="gif-container">
            <img src="https://media.giphy.com/media/3oriO0OEd9QIDdllqo/giphy.gif" alt="Gatito pastel">
        </div>
        <h2>Inicio de Sesión</h2>
        <form action="" method="POST">
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="pass" placeholder="Contraseña" required>
            <input type="submit" value="Ingresar">
        </form>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
