<?php
session_start();
include 'menu.php';
require_once "funciones/conecta.php"; 
include('validar_sesion.php');
$con = conecta();

// Verificar si el administrador está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php'); // Redirigir al login si no está logueado
    exit;
}

// Obtener todos los pedidos (incluyendo los finalizados)
$sql = "SELECT p.id, p.cliente_id, p.status, p.fecha, c.nombre AS cliente_nombre
        FROM pedidos p
        JOIN clientes c ON p.cliente_id = c.id
        ORDER BY p.fecha DESC";
$result = $con->query($sql);

echo "<h2>Listado de Pedidos</h2>";
echo "<table class='pedidos-table'>
        <tr>
            <th>ID del Pedido</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>";

while ($row = $result->fetch_assoc()) {
    $estado = ($row['status'] == 0) ? "En proceso" : "Finalizado";
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['cliente_nombre']}</td>
            <td>{$row['fecha']}</td>
            <td>{$estado}</td>
            <td><a href='detalle_pedido.php?id={$row['id']}'>Ver Detalle</a></td>
          </tr>";
}

echo "</table>";
?>

<!-- Estilos CSS para la tabla -->
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    h2 {
        text-align: center;
        color: #333;
        padding: 20px;
    }

    .pedidos-table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .pedidos-table th, .pedidos-table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .pedidos-table th {
        background-color: #333;
        color: white;
    }

    .pedidos-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .pedidos-table tr:hover {
        background-color: #ddd;
    }

    .pedidos-table td {
        font-size: 16px;
        color: #555;
    }

    .pedidos-table td a {
        text-decoration: none;
        color: #007bff;
    }

    .pedidos-table td a:hover {
        color: #0056b3;
    }
</style>
