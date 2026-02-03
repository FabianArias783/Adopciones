<?php
session_start();
include 'menu.php';
include_once 'Funciones/conecta.php';
$con = conecta();

if (!isset($_SESSION['usuario']) && !isset($_SESSION['id_empleado'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT s.*, 
               c.nombre AS cliente_nombre, c.apellidos AS cliente_apellidos, c.correo AS cliente_correo,
               p.nombre AS producto_nombre, p.archivo AS producto_imagen
        FROM solicitudes s
        INNER JOIN clientes c ON s.cliente_id = c.id
        INNER JOIN productos p ON s.producto_id = p.id
        ORDER BY s.fecha DESC";
$res = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes de Adopción</title>
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
            color: #fff;
            padding: 15px;
            text-align: center;
            font-family: 'Pacifico', cursive;
        }
        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            text-align: center;
            border: 2px dashed #ffa07a;
            background: #fff;
            border-radius: 15px;
        }
        th, td {
            border: 1px solid #ffa07a;
            padding: 10px;
        }
        th {
            background-color: #ffb6b9;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #fff0e1;
        }
        .estado {
            font-weight: bold;
            color: #ff6f61;
        }
        .btn {
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            color: white;
            margin: 2px;
        }
        .aprobar { background-color: #4CAF50; }
        .rechazar { background-color: #f44336; }
        .ver { background-color: #ffb6b9; }
        .wpp { background-color: #25d366; }
        .pdf { background-color: #6c63ff; }
        .btn:hover { opacity: 0.85; }

        /* Popup de detalles */
        .popup {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background: #fffaf0;
            border-radius: 15px;
            border: 2px dashed #ffa07a;
            padding: 20px;
            width: 90%;
            max-width: 500px;
            overflow-y: auto;
            max-height: 90vh;
        }
        .popup-content h3 {
            color: #ff6f61;
            text-align: center;
            margin-bottom: 10px;
        }
        .popup-content p {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <header><h1>🐾 Solicitudes de Adopción 🐾</h1></header>

    <table>
        <tr>
            <th>ID</th>
            <th>Mascota</th>
            <th>Cliente</th>
            <th>Teléfono</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $res->fetch_assoc()): ?>
            <tr id="sol-<?php echo $row['id']; ?>">
                <td><?php echo $row['id']; ?></td>
                <td>
                    <img src="uploads_productos/<?php echo $row['producto_imagen']; ?>" style="width:60px; height:60px; border-radius:10px;">
                    
                    <br><?php echo $row['producto_nombre']; ?>
                </td>
                <td><?php echo $row['cliente_nombre'] . ' ' . $row['cliente_apellidos']; ?></td>
                <td>
                    <?php if (!empty($row['telefono'])): ?>
                        <a href="https://wa.me/52<?php echo preg_replace('/\D/', '', $row['telefono']); ?>" target="_blank" class="btn wpp">📞 WhatsApp</a>
                    <?php else: ?>
                        <em>Sin número</em>
                    <?php endif; ?>
                </td>
                <td class="estado"><?php echo ucfirst($row['estado']); ?></td>
                <td>
                    <button class="ver" onclick='verDetalles(<?php echo json_encode($row); ?>)'>Ver</button>
                    <button class="pdf" onclick='descargarPDF(<?php echo $row["id"]; ?>)'>PDF</button>
                    <?php if ($row['estado'] === 'pendiente'): ?>
                        <button class="aprobar" onclick="actualizarEstado(<?php echo $row['id']; ?>, 'aprobada')">Aprobar</button>
                        <button class="rechazar" onclick="actualizarEstado(<?php echo $row['id']; ?>, 'rechazada')">Rechazar</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Popup detalles -->
    <div class="popup" id="popupDetalles">
        <div class="popup-content">
            <h3>Detalles del formulario</h3>
            <div id="detallesContenido"></div>
            <div style="text-align:center; margin-top:15px;">
                <button class="btn" style="background:#aaa;" onclick="cerrarDetalles()">Cerrar</button>
            </div>
        </div>
    </div>

        <script>
            // 🔹 Mostrar detalles de la solicitud
            function verDetalles(data) {
                let html = `
                    <p><strong>Motivo:</strong> ${data.motivo || 'No especificado'}</p>
                    <p>><strng>Teléfono:</strong> ${data.telefono || 'No indicado'}</p>
                    <p><strong>¿Tuvo mascotas antes?:</strong> ${data.mascotas_previas || 'No indicado'}</p>
                    <p><strong>Tipo de vivienda:</strong> ${data.tipo_vivienda || 'No indicado'}</p>
                    <p><strong>Niños/adultos mayores:</strong> ${data.ninos_mayores || 'No indicado'}</p>
                    <p><strong>Espacio exterior:</strong> ${data.espacio_exterior || 'No indicado'}</p>
                    <p><strong>Horas solo:</strong> ${data.horas_solo || 'No indicado'}</p>
                    <p><strong>Responsabilidad:</strong> ${data.respuesta_responsabilidad || 'No indicado'}</p>
                    ${data.razon_rechazo ? `<p><strong>Razón de rechazo:</strong> ${data.razon_rechazo}</p>` : ''}
                `;
                document.getElementById('detallesContenido').innerHTML = html;
                document.getElementById('popupDetalles').style.display = 'flex';
            }

            // 🔹 Cerrar popup de detalles
            function cerrarDetalles() {
                document.getElementById('popupDetalles').style.display = 'none';
            }

            // 🔹 Descargar PDF
            function descargarPDF(id) {
                window.open('solicitud_pdf.php?id=' + id, '_blank');
            }

            // 🔹 Aprobar o rechazar con razón opcional
            function actualizarEstado(id, estado) {
                if (estado === 'rechazada') {
                    const razon = prompt('Por favor, escribe la razón del rechazo:');
                    if (!razon) return; // cancela si no escribió nada
                    enviarActualizacion(id, estado, razon);
                } else {
                    if (confirm('¿Deseas aprobar esta solicitud?')) {
                        enviarActualizacion(id, estado);
                    }
                }
            }

            // 🔹 Enviar cambio al servidor
            function enviarActualizacion(id, estado, razon = '') {
                fetch('solicitud_actualizar.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ id, estado, razon })
                })
                .then(res => res.text())
                .then(resp => {
                    if (resp.trim() === 'OK') {
                        const fila = document.getElementById(`sol-${id}`);
                        fila.querySelector('.estado').textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
                        fila.querySelector('td:last-child').innerHTML = `<em>${estado.charAt(0).toUpperCase() + estado.slice(1)}</em>`;
                        alert(`Solicitud ${estado === 'rechazada' ? 'rechazada' : 'aprobada'} correctamente.`);
                    } else {
                        alert('Error al actualizar la solicitud: ' + resp);
                    }
                })
                .catch(err => alert('Error de conexión: ' + err));
            }
        </script>


</body>
</html>
