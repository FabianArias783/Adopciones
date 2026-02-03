<?php
session_start();
include 'funciones/conecta.php';
$con = conecta();

if (!isset($_SESSION['cliente_id'])) {
    echo json_encode(['success' => false, 'message' => 'No estás logueado']);
    exit;
}

$cliente_id = $_SESSION['cliente_id'];

// Buscar el ID del pedido abierto
$sql = "SELECT id FROM pedidos WHERE cliente_id = '$cliente_id' AND status = 0 LIMIT 1";
$result = $con->query($sql);

if ($result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'No tienes un pedido abierto']);
    exit;
}

$pedido_id = $result->fetch_assoc()['id'];

// Cambiar el estado del pedido a "finalizado" (asumimos que "1" es el estado finalizado)
$sql = "UPDATE pedidos SET status = 1 WHERE id = '$pedido_id' AND status = 0";
if ($con->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Pedido finalizado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Hubo un error al finalizar el pedido']);
}
?>