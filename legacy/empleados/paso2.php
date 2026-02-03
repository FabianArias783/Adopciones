<?php
session_start();
include 'funciones/conecta.php';
$con = conecta();
$cliente_id = $_SESSION['cliente_id'];

// Verificar si el cliente tiene un pedido abierto
$sql = "SELECT id FROM pedidos WHERE cliente_id = '$cliente_id' AND status = 0 LIMIT 1";
$result = $con->query($sql);

if ($result->num_rows == 0) {
    echo "<p>No tienes productos en tu carrito.</p>";
} else {
    $pedido_id = $result->fetch_assoc()['id'];

    // Mostrar productos del pedido en el Paso 2
    $sql = "SELECT pp.id, p.nombre, pp.cantidad 
            FROM pedidos_productos pp
            JOIN productos p ON pp.producto_id = p.id
            WHERE pp.pedido_id = '$pedido_id'";
    $result = $con->query($sql);

    echo "<h2>Confirmación de tu pedido</h2>";
    echo "<table class='carrito-table'>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['nombre']}</td>
                <td>{$row['cantidad']}</td>
              </tr>";
    }
    echo "</table>";

    // Botón para regresar al Paso 1
    echo "<div class='botones'>
            <button class='paso-btn' onclick='window.location.href=\"carrito_interfaz.php\"'>Regresar</button>
            <button class='finalizar-btn' onclick='finalizarPedido()'>Finalizar</button>
          </div>";
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // Función para finalizar el pedido
    function finalizarPedido() {
        $.ajax({
            url: 'finalizarPedido.php',
            method: 'POST',
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    window.location.href = 'pedido_finalizado.php'; // Redirigir a página de confirmación
                } else {
                    alert("Hubo un error al finalizar el pedido. Inténtalo de nuevo.");
                }
            },
            error: function() {
                alert("Error en la conexión con el servidor.");
            }
        });
    }
</script>

<!-- Estilos CSS para el diseño pastel -->
<style>
    body {
        font-family: 'Quicksand', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f9f3f3;
    }

    h2 {
        text-align: center;
        color: #ff6f61;
        padding: 20px;
        background-color: #ffdfdf;
        color: #333;
        margin: 0;
        border-radius: 10px;
    }

    .carrito-table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .carrito-table th, .carrito-table td {
        padding: 14px;
        text-align: center;
        border: 1px solid #fcd5ce;
    }

    .carrito-table th {
        background-color: #ffb6b9;
        color: white;
    }

    .carrito-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .carrito-table tr:hover {
        background-color: #f1f1f1;
    }

    .carrito-table td {
        font-size: 16px;
        color: #555;
    }

    .botones {
        text-align: center;
        margin-top: 20px;
    }

    .paso-btn, .finalizar-btn {
        background-color: #ff6f61;
        color: white;
        border: none;
        padding: 12px 25px;
        text-align: center;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
        margin: 10px;
        display: inline-block;
        transition: background-color 0.3s;
    }

    .paso-btn:hover, .finalizar-btn:hover {
        background-color: #ff3b33;
    }

    .finalizar-btn {
        background-color: #ff6f61;
    }

    .finalizar-btn:hover {
        background-color: #e63946;
    }

    p {
        text-align: center;
        font-size: 18px;
        color: #333;
    }

    .total {
        font-size: 20px;
        font-weight: bold;
        color: #333;
        text-align: center;
        margin-top: 20px;
    }
</style>
