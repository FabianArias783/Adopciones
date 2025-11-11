<?php
require "funciones/conecta.php";
$con = conecta();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

// Verificar si el producto existe y no está ya eliminado
$sql = "SELECT id FROM productos WHERE id = ? AND eliminado = 0";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$response = ["success" => false, "message" => "Producto no encontrado o ya eliminado."];

if ($result->num_rows > 0) {
    // Marcar el producto como eliminado
    $sql = "UPDATE productos SET eliminado = 1 WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $response = ["success" => true, "message" => "Producto eliminado correctamente."];
    } else {
        $response["message"] = "Error al eliminar el producto.";
    }
}

$stmt->close();
$con->close();

echo json_encode($response);
?>
