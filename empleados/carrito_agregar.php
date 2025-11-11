<?php
session_start();
include 'funciones/conecta.php';
$con = conecta();

// Verificar si el cliente está autenticado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login_cliente.php");
    exit();
}

$cliente_id = $_SESSION['cliente_id'];
$producto_id = $_POST['producto_id'];
$cantidad = intval($_POST['cantidad']);

// Obtener el stock del producto
$sql = "SELECT stock FROM productos WHERE id = '$producto_id' AND eliminado = 0";
$res = $con->query($sql);
if ($res->num_rows > 0) {
    $producto = $res->fetch_assoc();
    $stock = $producto['stock'];

    if ($cantidad > $stock) {
        $cantidad = $stock; // Ajustar la cantidad al stock disponible
    }

    // Verificar si el cliente ya tiene un pedido abierto
    $sql = "SELECT id FROM pedidos WHERE cliente_id = '$cliente_id' AND status = 0 LIMIT 1";
    $pedido_res = $con->query($sql);

    if ($pedido_res->num_rows == 0) {
        // Crear un nuevo pedido
        $sql = "INSERT INTO pedidos (cliente_id, status, fecha) VALUES ('$cliente_id', 0, NOW())";
        $con->query($sql);
        $pedido_id = $con->insert_id;
    } else {
        $pedido_id = $pedido_res->fetch_assoc()['id'];
    }

    // Agregar o actualizar el producto en el carrito
    $sql = "SELECT id, cantidad FROM pedidos_productos WHERE pedido_id = '$pedido_id' AND producto_id = '$producto_id'";
    $pedido_producto_res = $con->query($sql);

    if ($pedido_producto_res->num_rows > 0) {
        $pedido_producto = $pedido_producto_res->fetch_assoc();
        $nueva_cantidad = $pedido_producto['cantidad'] + $cantidad;
        if ($nueva_cantidad > $stock) {
            $nueva_cantidad = $stock; // Limitar la cantidad al stock disponible
        }
        $sql = "UPDATE pedidos_productos SET cantidad = '$nueva_cantidad', subtotal = precio * '$nueva_cantidad' WHERE id = '{$pedido_producto['id']}'";
    } else {
        $sql = "INSERT INTO pedidos_productos (pedido_id, producto_id, cantidad, precio, subtotal) 
                SELECT '$pedido_id', id, '$cantidad', costo, costo * '$cantidad' 
                FROM productos WHERE id = '$producto_id'";
    }
    $con->query($sql);

    // Redirigir al carrito
    header("Location: carrito_interfaz.php");
    exit();
} else {
    echo "Producto no encontrado.";
}
?>
