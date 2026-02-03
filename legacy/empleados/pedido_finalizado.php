<?php
  session_start();
  include 'funciones/conecta.php';
  $con = conecta();
  $cliente_id = $_SESSION['cliente_id'];
  
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        h1 {
            color: #4CAF50;
            font-size: 2.5em;
        }
        p {
            color: #555;
            font-size: 1.2em;
            margin-top: 10px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.2em;
            margin-top: 20px;
            display: inline-block;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>¡Pedido Confirmado!</h1>
    <p>Tu pedido ha sido confirmado con éxito. En breve recibirás un correo con los detalles.</p>
    <a href="home.php" class="button">Regresar al inicio</a>
</div>

</body>
</html>