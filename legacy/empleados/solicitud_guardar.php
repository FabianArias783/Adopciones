<?php
session_start();
include_once 'funciones/conecta.php';
$con = conecta();

$cliente_id = $_SESSION['cliente_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $cliente_id > 0) {
    $producto_id = intval($_POST['producto_id']);
    $telefono = $con->real_escape_string($_POST['telefono']);
    $motivo = $con->real_escape_string($_POST['motivo']);
    $mascotas_previas = $_POST['mascotas_previas'];
    $tipo_vivienda = $_POST['tipo_vivienda'];
    $ninos_mayores = $_POST['ninos_mayores'];
    $espacio_exterior = $_POST['espacio_exterior'];
    $horas_solo = intval($_POST['horas_solo']);
    $respuesta_responsabilidad = $con->real_escape_string($_POST['respuesta_responsabilidad']);
    $fecha = date('Y-m-d H:i:s');

    $sql = "INSERT INTO solicitudes 
            (cliente_id, producto_id, motivo, mascotas_previas, tipo_vivienda, ninos_mayores, 
            espacio_exterior, horas_solo, respuesta_responsabilidad, estado, fecha, telefono)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente', ?, ?)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param('iisssssisss', $cliente_id, $producto_id, $motivo, $mascotas_previas, $tipo_vivienda, 
                    $ninos_mayores, $espacio_exterior, $horas_solo, $respuesta_responsabilidad, $fecha, $telefono);
    if ($stmt->execute()) {
        echo "Solicitud enviada correctamente. Un empleado revisará tu solicitud pronto.";
    } else {
        echo "Error al guardar la solicitud. Inténtalo más tarde.";
    }
} else {
    echo "No se pudo procesar la solicitud.";
}
?>
