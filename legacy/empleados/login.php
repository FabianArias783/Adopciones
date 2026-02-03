<?php 
// 🔑 Iniciamos la sesión
session_start();
require "funciones/conecta.php";
$con = conecta();

// 🚀 Lógica de backend para AJAX (no se verá en la página)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    // Verificar si el correo existe
    $sql = "SELECT * FROM empleados WHERE correo = ? AND eliminado = 0";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        // El correo existe, ahora verificar la contraseña
        $empleado = $res->fetch_assoc();

        // 🛡️ Usamos password_verify (¡mucho más seguro que md5!)
        if (password_verify($password, $empleado['pass'])) {
            // Iniciar sesión
            $_SESSION['usuario'] = $empleado['nombre'];
            $_SESSION['id_empleado'] = $empleado['id']; // También guardamos el ID
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Contraseña incorrecta.']);
        }
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'El correo ingresado no está registrado.']);
    }
    exit(); // Detenemos la ejecución para no enviar el HTML
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Empleados</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #fffaf0;
            margin: 0;
            padding: 0;
        }

        /* 🔸 Navbar (Modificada para Empleados) */
        .navbar {
            background-color: #ffb6b9;
            display: flex;
            justify-content: center; /* Centramos el título */
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
            box-sizing: border-box; /* Añadido para que el padding no rompa el ancho */
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        /* 🎨 Estilo para el botón de submit (antes input) */
        form button {
            background-color: #ffb6b9;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            font-family: 'Quicksand', sans-serif; /* Heredar la fuente */
            transition: background-color 0.3s;
        }
        form button:hover {
            background-color: #ff6f61;
        }
        
        /* 🎨 Estilo para el mensaje de error (usado por AJAX) */
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
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
        <h1>🐾 Huellitas Unidas - Empleados</h1>
    </div>

    <div class="container">
        <div class="gif-container">
            <img src="https://media.giphy.com/media/3oriO0OEd9QIDdllqo/giphy.gif" alt="Gatito pastel">
        </div>
        <h2>Portal de Empleados</h2>
        
        <form id="loginForm">
            <input type="email" id="correo" name="correo" placeholder="Correo electrónico" required>
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>

        <div id="mensaje" class="error" style="display:none;"></div>
    </div>

    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                $('#mensaje').hide();

                // Validar que ambos campos estén completos
                if (!$('#correo').val() || !$('#password').val()) {
                    $('#mensaje').text('Por favor, completa todos los campos.').show();
                    return;
                }

                // Enviar los datos por AJAX
                $.ajax({
                    url: 'login.php', // Envía al mismo archivo
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log("Respuesta recibida:", response);
                        try {
                            var data = JSON.parse(response);
                            if (data.success) {
                                window.location.href = 'bienvenido.php'; // Redirige al panel de empleado
                            } else {
                                $('#mensaje').text(data.mensaje).show();
                            }
                        } catch (e) {
                            console.error("Error al procesar JSON:", e);
                            $('#mensaje').text('Error al procesar la respuesta.').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en AJAX:", error);
                        $('#mensaje').text('Error en la conexión.').show();
                    }
                });
            });
        });
    </script>
</body>
</html>