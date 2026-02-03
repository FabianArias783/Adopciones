<?php
include_once 'Funciones/conecta.php';
$con = conecta();

if (!isset($_POST['id']) || !isset($_POST['estado'])) {
    echo "FALTAN_DATOS";
    exit;
}

$id = intval($_POST['id']);
$estado = $_POST['estado'];
$razon = isset($_POST['razon']) ? trim($_POST['razon']) : '';

$permitidos = ['pendiente', 'aprobada', 'rechazada'];
if (!in_array($estado, $permitidos)) {
    echo "ESTADO_INVALIDO";
    exit;
}

// Actualizar la solicitud
$sql = "UPDATE solicitudes SET estado = ?, razon_rechazo = ? WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('ssi', $estado, $razon, $id);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "ERROR_SQL";
}
$stmt->close();
$con->close();
?>
