<?php
ob_clean(); // Limpia cualquier salida previa del buffer
include_once 'funciones/conecta.php';
$con = conecta();

// Incluir FPDF
require_once('../fpdf/fpdf.php');

// Obtener ID de solicitud
$id = intval($_GET['id']);
$sql = "SELECT s.*, 
               c.nombre AS cliente_nombre, c.apellidos AS cliente_apellidos, c.correo,
               p.nombre AS producto_nombre, p.archivo AS producto_imagen
        FROM solicitudes s
        INNER JOIN clientes c ON s.cliente_id = c.id
        INNER JOIN productos p ON s.producto_id = p.id
        WHERE s.id = $id";
$res = $con->query($sql);
$sol = $res->fetch_assoc();

if (!$sol) {
    die("Solicitud no encontrada");
}

// Clase personalizada para el PDF
class PDF extends FPDF {
    function Header() {
        // Logo (opcional)
        if (file_exists('../img/logo.png')) {
            $this->Image('../img/logo.png', 10, 8, 25);
        }
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(255, 105, 97); // rosita pastel
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT','Huellitas Unidas'), 0, 1, 'C');
        $this->Ln(3);
        $this->SetFont('Arial', 'I', 12);
        $this->SetTextColor(80, 80, 80);
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT','Solicitud de Adopción'), 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT','Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// --- Datos del adoptante ---
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Datos del Adoptante"), 0, 1, 'L');
$pdf->Ln(2);
$pdf->Cell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Nombre: {$sol['cliente_nombre']} {$sol['cliente_apellidos']}"), 0, 1);
$pdf->Cell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Correo: {$sol['correo']}"), 0, 1);
$pdf->Cell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Teléfono: {$sol['telefono']}"), 0, 1);
$pdf->Ln(5);

// --- Datos de la mascota ---
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(255, 105, 97);
$pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Mascota"), 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(2);
$pdf->Cell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Nombre: {$sol['producto_nombre']}"), 0, 1);
$pdf->Ln(3);
if (file_exists("../uploads_productos/{$sol['producto_imagen']}")) {
    $pdf->Image("../uploads_productos/{$sol['producto_imagen']}", 150, 50, 40);
}
$pdf->Ln(10);

// --- Respuestas del formulario ---
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(255, 105, 97);
$pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Respuestas del Formulario"), 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(2);
$pdf->MultiCell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Motivo de adopción: {$sol['motivo']}"));

if (!empty($sol['tipo_vivienda'])) $pdf->MultiCell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Tipo de vivienda: {$sol['tipo_vivienda']}"));
if (!empty($sol['mascotas_previas'])) $pdf->MultiCell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Mascotas previas: {$sol['mascotas_previas']}"));
if (!empty($sol['ninos_mayores'])) $pdf->MultiCell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Niños o adultos mayores en casa: {$sol['ninos_mayores']}"));
if (!empty($sol['espacio_exterior'])) $pdf->MultiCell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Espacio exterior: {$sol['espacio_exterior']}"));
if (!empty($sol['horas_solo'])) $pdf->MultiCell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Horas que el perro pasará solo: {$sol['horas_solo']}"));
if (!empty($sol['respuesta_responsabilidad'])) $pdf->MultiCell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Responsabilidad: {$sol['respuesta_responsabilidad']}"));

$pdf->Ln(10);
$pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Estado: " . ucfirst($sol['estado'])), 0, 1);
if (!empty($sol['razon_rechazo'])) {
    $pdf->SetTextColor(200, 0, 0);
    $pdf->MultiCell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Razón de rechazo: {$sol['razon_rechazo']}"));
}

$pdf->Ln(15);
$pdf->SetFont('Arial', 'I', 10);
$pdf->SetTextColor(120, 120, 120);
$pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',"Gracias por apoyar la adopción responsable 💕"), 0, 1, 'C');

// ✅ Nombre del archivo dinámico
$cliente = preg_replace('/\s+/', '_', $sol['cliente_nombre']);
$mascota = preg_replace('/\s+/', '_', $sol['producto_nombre']);
$nombreArchivo = "Solicitud_{$cliente}_{$mascota}.pdf";
ob_end_clean(); // Limpia otra vez por si acaso
$pdf->Output("I", $nombreArchivo);
?>
