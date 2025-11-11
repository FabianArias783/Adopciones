<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'Funciones/conecta.php';
$con = conecta();

// Verifica que haya sesión de usuario
if (!isset($_SESSION['usuario']) && !isset($_SESSION['id_empleado'])) {
    header("Location: login.php");
    exit();
}

// Contador de solicitudes pendientes
$sqlPendientes = "SELECT COUNT(*) AS total FROM solicitudes WHERE estado = 'pendiente'";
$resPendientes = $con->query($sqlPendientes);
$dataPendientes = $resPendientes->fetch_assoc();
$pendientes = $dataPendientes['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        nav {
            background-color: #ffb6b9;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 10px 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        nav a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            font-family: 'Quicksand', sans-serif;
            padding: 8px 16px;
            border-radius: 10px;
            transition: background-color 0.3s;
        }
        nav a:hover {
            background-color: #ff6f61;
        }
        .badge {
            background-color: #fff;
            color: #ff6f61;
            font-weight: bold;
            border-radius: 50%;
            padding: 3px 8px;
            margin-left: 5px;
        }
        .usuario {
            color: #fff;
            font-family: 'Quicksand', sans-serif;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <nav>
        <a href="bienvenido.php">Inicio</a>
        <a href="empleados_lista.php">Empleados</a>
        <a href="productos_lista.php">Mascotas</a>
        <a href="promociones_lista.php">Promociones</a>
        <a href="pedidos_admin.php">Pedidos</a>
        <a href="solicitudes.php">
            Solicitudes
            <?php if ($pendientes > 0): ?>
                <span class="badge"><?php echo $pendientes; ?></span>
            <?php endif; ?>
        </a>
        <span class="usuario">
            👋 <?php echo htmlspecialchars($_SESSION['usuario']); ?>
        </span>
        <a href="logout.php">Cerrar sesión</a>
    </nav>
</body>
</html>
