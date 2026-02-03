<?php
require "funciones/conecta.php"; 
$con = conecta();

$id = $_GET['id'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$correo = $_POST['correo'];
$rol = $_POST['rol'];
$password = $_POST['password'];

// Validar si hay una nueva contraseña y preparar la consulta
if (!empty($password)) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE empleados SET nombre=?, apellidos=?, correo=?, password=?, rol=? WHERE id=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssii", $nombre, $apellidos, $correo, $password, $rol, $id);
} else {
    $sql = "UPDATE empleados SET nombre=?, apellidos=?, correo=?, rol=? WHERE id=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssii", $nombre, $apellidos, $correo, $rol, $id);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$con->close();
