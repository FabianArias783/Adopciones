<?php
session_start(); // Asegúrate de que la sesión esté iniciada

include 'funciones/conecta.php';
$con = conecta();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $id = intval($_POST['id']); // Sanitizar el ID
    $response = ['success' => false];

    if ($action == 'eliminar') {
        // Eliminar el producto del carrito
        $stmt = $con->prepare("DELETE FROM pedidos_productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $response['success'] = $stmt->execute();
    } elseif ($action == 'actualizar') {
        // Actualizar la cantidad de un producto
        $cantidad = intval($_POST['cantidad']);
        $stmt = $con->prepare("UPDATE pedidos_productos SET cantidad = ?, subtotal = precio * ? WHERE id = ?");
        $stmt->bind_param("iii", $cantidad, $cantidad, $id);
        if ($stmt->execute()) {
            // Obtener el nuevo subtotal
            $stmt = $con->prepare("SELECT subtotal FROM pedidos_productos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($nuevoSubtotal);
            $stmt->fetch();
            $response['success'] = true;
            $response['nuevoSubtotal'] = $nuevoSubtotal;
        }
    }

    echo json_encode($response);
    exit();
}
?>
