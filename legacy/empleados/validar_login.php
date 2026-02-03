<?php
require "funciones/conecta.php"; 
$con = conecta();

$email = $_POST['email'];
$password = $_POST['password'];


$sql = "SELECT * FROM empleados WHERE correo = ? AND activo = 1 AND eliminado = 0";
$stmt = $con->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $con->error]);
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $empleado = $result->fetch_assoc();
    // Verificar la contraseña
    if (password_verify($password, $empleado['password'])) {
        // Iniciar sesión
        session_start();
        $_SESSION['usuario'] = $empleado['nombre'];
        $_SESSION['email'] = $empleado['correo'];
        $_SESSION['rol'] = $empleado['rol'];

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'El usuario no está registrado o está inactivo.']);
}

$stmt->close();
$con->close();
