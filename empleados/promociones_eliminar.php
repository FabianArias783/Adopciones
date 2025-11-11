<?php
require "funciones/conecta.php";
$con = conecta();

$id = $_GET['id'] ?? 0;

// Actualizar el estado de eliminado
$sql = "UPDATE promociones SET eliminado = 1 WHERE id = $id";
if ($con->query($sql)) {
    header("Location: promociones_lista.php");
    exit;
} else {
    echo "Error al eliminar la promoción.";
}
?>
