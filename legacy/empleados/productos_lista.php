<?php
include 'menu.php';
require_once "funciones/conecta.php";
$con = conecta();

// Obtener los productos que no están eliminados
$sql = "SELECT * FROM productos WHERE eliminado = 0";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Productos</title>
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
            width: 90%;
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
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #333; /* Negro */
            color: white;
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
    </style>
</head>
<body>
    <header>
        <h1>Listado de Productos</h1>
    </header>
    <div class="container">
        <a href="productos_alta.php">Agregar Producto</a>
        <table id="productosTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Stock</th>
                    <th>Costo</th>
                    <th>Ver</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr id="fila-<?php echo $row['id']; ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['codigo']; ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td><?php echo $row['costo']; ?></td>
                    <td><a href="productos_detalle.php?id=<?php echo $row['id']; ?>">Ver</a></td>
                    <td><a href="productos_editar.php?id=<?php echo $row['id']; ?>">Editar</a></td>
                    <td>
                        <button onclick="eliminarProducto(<?php echo $row['id']; ?>)">Eliminar</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script>
        function eliminarProducto(id) {
            if (confirm('¿Estás seguro de eliminar este producto?')) {
                fetch('productos_eliminar.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Eliminar la fila de la tabla
                        const fila = document.getElementById(`fila-${id}`);
                        fila.remove();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>
