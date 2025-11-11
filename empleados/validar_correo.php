<?php
require "funciones/conecta.php"; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];

    $con = conecta();
    $sql = "SELECT COUNT(*) as count FROM empleados WHERE correo = ? AND eliminado = 0";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    echo json_encode(['exists' => $data['count'] > 0]);

    $stmt->close();
    $con->close();
}
?>
