<?php
include 'menu.php';
require_once "funciones/conecta.php";
$con = conecta();

// Obtener las promociones que no están eliminadas
$sql = "SELECT * FROM promociones WHERE eliminado = 0";
$res = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Promociones</title>
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
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            text-align: center;
            padding: 8px;
        }
        th {
            background-color: #333; /* Negro */
            color: white;
        }
        img {
            max-width: 150px;
            border-radius: 5px;
        }
        a, button {
            display: inline-block;
            background-color: #333; /* Negro */
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }
        a:hover, button:hover {
            background-color: #555; /* Gris oscuro al pasar el mouse */
        }
        .add-btn {
            background-color: #333; /* Negro */
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 10px;
        }
        .add-btn:hover {
            background-color: #555; /* Gris oscuro al pasar el mouse */
        }
    </style>
</head>
<body>
    <header>
        <h1>Listado de Promociones</h1>
    </header>

    <div class="container">
        <a href="promociones_alta.php" class="add-btn">Agregar Promoción</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Imagen</th>
                    <th>Ver</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        $id = $row['id'];
                        $nombre = $row['nombre'];
                        $archivo = $row['archivo'];
                        $archivoRuta = "uploads_promociones/$archivo";

                        echo "<tr>
                                <td>$id</td>
                                <td>$nombre</td>
                                <td><img src='$archivoRuta' alt='$nombre'></td>
                                <td><a href='promociones_detalle.php?id=$id'>Ver</a></td>
                                <td><a href='promociones_editar.php?id=$id'>Editar</a></td>
                                <td><button onclick='eliminarPromocion($id)'>Eliminar</button></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay promociones disponibles.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function eliminarPromocion(id) {
            if (confirm("¿Estás seguro de que deseas eliminar esta promoción?")) {
                window.location.href = `promociones_eliminar.php?id=${id}`;
            }
        }
    </script>
</body>
</html>
