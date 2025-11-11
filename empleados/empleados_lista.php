<?php
include 'menu.php';
require_once "funciones/conecta.php"; 
$con = conecta();
include('validar_sesion.php');

// Consulta para mostrar todos los empleados activos (no eliminados)
$sql = "SELECT * FROM empleados WHERE eliminado = 0";
$res = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Empleados</title> <!-- Título de la pestaña -->
    <link rel="stylesheet" href="styles.css"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header-bar {
            background-color: #333; /* Barra negra */
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
        }

        th {
            background-color: #333;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #333;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 5px;
        }

        a:hover {
            text-decoration: underline;
            background-color: #f4f4f4;
        }

        .crear-nuevo {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
            text-align: center;
        }

        .crear-nuevo:hover {
            background-color: #555;
        }

        .actions a {
            margin: 0 5px;
        }

        .actions a:first-child {
            background-color: #4CAF50;
            color: white;
        }

        .actions a:nth-child(2) {
            background-color: #2196F3;
            color: white;
        }

        .actions a:nth-child(3) {
            background-color: #f44336;
            color: white;
        }

        .actions a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="header-bar">
        Listado de empleados
    </div>
    <a class="crear-nuevo" href="empleados_alta.php">Dar de alta</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre completo</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Ver detalle</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody id="empleadosTable">
            <?php
            // Verificamos si hay resultados
            if ($res->num_rows > 0) {
                // Iterar sobre los empleados y generar filas
                while ($row = $res->fetch_array()) {
                    $id = $row['id'];
                    $nombre = $row['nombre'];
                    $apellidos = $row['apellidos'];
                    $correo = $row['correo'];
                    $rol = $row['rol'] == 1 ? 'Gerente' : 'Ejecutivo';
                    
                    echo "<tr id='fila-$id'>";
                    echo "<td>$id</td>";
                    echo "<td>$nombre $apellidos</td>";
                    echo "<td>$correo</td>";
                    echo "<td>$rol</td>";
                    echo "<td><a href='empleado_detalle.php?id=$id'>Ver detalle</a></td>";
                    echo "<td><a href='empleados_editar.php?id=$id'>Editar</a></td>";
                    echo "<td><a href='#' onclick='eliminarEmpleado($id)'>Eliminar</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No se encontraron empleados activos.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        function eliminarEmpleado(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este empleado?')) {
                $.ajax({
                    url: 'empleados_elimina_ajax.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            $('#fila-' + id).fadeOut(); // Elimina la fila de la tabla
                        } else {
                            alert('Error al eliminar el empleado.');
                        }
                    },
                    error: function() {
                        alert('Hubo un error con la solicitud AJAX.');
                    }
                });
            }
        }
    </script>
</body>
</html>