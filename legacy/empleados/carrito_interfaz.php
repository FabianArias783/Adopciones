<?php
session_start();

// Verificar si el cliente está autenticado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login_cliente.php");
    exit();
}

include 'menu_cliente.php';
include 'funciones/conecta.php';
$con = conecta();
$cliente_id = $_SESSION['cliente_id'];

// Consultar todas las solicitudes hechas por el cliente
$sql = "SELECT s.*, p.nombre AS mascota, p.archivo AS imagen
        FROM solicitudes s
        INNER JOIN productos p ON s.producto_id = p.id
        WHERE s.cliente_id = '$cliente_id'
        ORDER BY s.fecha DESC";
$res = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Solicitudes de Adopción 🐾</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #fffaf0;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #ffb6b9;
            color: white;
            text-align: center;
            padding: 20px 0;
            font-family: 'Pacifico', cursive;
        }
        header h1 {
            margin: 0;
            font-size: 2.2rem;
        }
        .container {
            width: 90%;
            margin: 30px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border: 2px dashed #ffa07a;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(255, 182, 193, 0.3);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ffe5d9;
        }
        th {
            background-color: #ffb6b9;
            color: white;
            font-size: 1.1rem;
        }
        tr:nth-child(even) {
            background-color: #fff0e1;
        }
        img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
        }
        .estado {
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
        }
        .pendiente {
            background-color: #ffe066;
            color: #555;
        }
        .aprobada {
            background-color: #4CAF50;
            color: white;
        }
        .rechazada {
            background-color: #f44336;
            color: white;
        }
        .no-solicitudes {
            text-align: center;
            font-size: 1.3rem;
            color: #777;
            margin-top: 40px;
        }
    </style>
</head>
<body>
<header>
    <h1>🐶 Mis Solicitudes de Adopción 🐱</h1>
</header>

<div class="container">
<?php if ($res->num_rows > 0): ?>
    <table>
        <tr>
            <th>Foto</th>
            <th>Mascota</th>
            <th>Motivo</th>
            <th>Estado</th>
            <th>Razón de Rechazo</th>
            <th>Fecha</th>
        </tr>
        <?php while ($row = $res->fetch_assoc()): ?>
            <tr>
                <td><img src="uploads_productos/<?php echo $row['imagen']; ?>" alt="Mascota"></td>
                <td><?php echo htmlspecialchars($row['mascota']); ?></td>
                <td><?php echo htmlspecialchars($row['motivo']); ?></td>
                <td>
                    <span class="estado <?php echo strtolower($row['estado']); ?>">
                        <?php echo ucfirst($row['estado']); ?>
                    </span>
                </td>
                <td><?php echo $row['razon_rechazo'] ? htmlspecialchars($row['razon_rechazo']) : '<em>-</em>'; ?></td>
                <td><?php echo date("d/m/Y", strtotime($row['fecha'])); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p class="no-solicitudes">Aún no has realizado ninguna solicitud de adopción 🐾</p>
<?php endif; ?>
</div>
</body>
</html>
