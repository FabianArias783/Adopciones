<?php
session_start();
include 'menu.php';
include 'funciones/conecta.php';
$con = conecta();

// Verificar si el administrador está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php'); // Redirigir al login si no está logueado
    exit;
}

// Obtener el ID del pedido
if (isset($_GET['id'])) {
    $pedido_id = $_GET['id'];
} else {
    echo "No se especificó un ID de pedido.";
    exit;
}

// Obtener los detalles del pedido
$sql = "SELECT pp.id, p.nombre, pp.cantidad, pp.precio, pp.subtotal
        FROM pedidos_productos pp
        JOIN productos p ON pp.producto_id = p.id
        WHERE pp.pedido_id = '$pedido_id'";
$result = $con->query($sql);

// Verificar si se encontró el pedido
if ($result->num_rows == 0) {
    echo "Este pedido no tiene productos.";
    exit;
}

echo "<h2>Detalles del Pedido #$pedido_id</h2>";
echo "<table class='table table-bordered table-striped table-hover'>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>";

$total = 0;
while ($row = $result->fetch_assoc()) {
    $total += $row['subtotal'];
    echo "<tr>
            <td>{$row['nombre']}</td>
            <td>{$row['cantidad']}</td>
            <td>\${$row['precio']}</td>
            <td>\${$row['subtotal']}</td>
          </tr>";
}

echo "</tbody>
      </table>";
echo "<div class='total'>
        <p><strong>Total: \$$total</strong></p>
      </div>";

// Opcional: Agregar un botón para volver al listado de pedidos
echo "<button class='btn btn-primary' onclick='window.location.href=\"pedidos_admin.php\"'>Volver al listado de pedidos</button>";
?>

<!-- Estilos CSS adicionales -->
<style>
    h2 {
        text-align: center;
        font-family: 'Arial', sans-serif;
        color: #333;
        margin-bottom: 30px;
    }

    .table {
        width: 80%;
        margin: 0 auto;
        font-family: 'Arial', sans-serif;
        background-color: #f9f9f9;
    }

    .table th, .table td {
        text-align: center;
        padding: 12px;
        font-size: 16px;
    }

    .table thead {
        background-color: #333;
        color: white;
    }

    .table-striped tbody tr:nth-child(odd) {
        background-color: #f2f2f2;
    }

    .table-hover tbody tr:hover {
        background-color: #ddd;
    }

    .total {
        text-align: center;
        font-size: 18px;
        margin-top: 20px;
    }

    .btn-primary {
        display: block;
        margin: 30px auto;
        padding: 10px 20px;
        font-size: 16px;
        background-color: #333;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #555;
    }
</style>
