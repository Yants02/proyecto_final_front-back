<?php
// Conexión y la librería TCPDF

require_once('../config/conexion.php');
require_once('../includes/tcpdf/tcpdf.php');

// Valida que llegue un ID por la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No se proporcionó un ID de orden válido.");
}

$id_compra = $_GET['id'];

try {
    // Busca los datos generales de la compra
    $stmt_compra = $conn->prepare("SELECT * FROM compras WHERE id = :id");
    $stmt_compra->execute(['id' => $id_compra]);
    $compra = $stmt_compra->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        die("Error: La orden de compra no existe en el sistema.");
    }

    // Busca los ítems detallados de esa compra
    $stmt_det = $conn->prepare("SELECT * FROM detalle_compras WHERE id_compra = :id");
    $stmt_det->execute(['id' => $id_compra]);
    $detalles = $stmt_det->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de base de datos: " . $e->getMessage());
}

// --- CONFIGURACIÓN DEL PDF ---

// Crear instancia de TCPDF: Orientación Vertical (P), milímetros (mm), tamaño A4
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Metadatos del documento
$pdf->SetCreator('Kiosko Manager');
$pdf->SetAuthor('Germán Bisutti');
$pdf->SetTitle('Orden de Compra #' . $id_compra);

// Eliminamos cabecera y pie de página automáticos para diseño personalizado
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Márgenes: Izquierda(15), Arriba(20), Derecha(15)
$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(TRUE, 15);

// Añadimos la página
$pdf->AddPage();

// Formateamos la fecha para el PDF
$fecha_pdf = date("d/m/Y H:i", strtotime($compra['fecha']));

$html = '
    <table cellpadding="5">
        <tr>
            <td width="50%">
                <h1 style="color: #155DFC; font-size: 24pt; margin-bottom: 0;">KIOSKO MANAGER</h1>
                <p style="font-size: 10pt; color: #555;">
                    Gestión Profesional de Inventario<br>
                    Buenos Aires, Argentina
                </p>
            </td>
            <td width="50%" style="text-align: right;">
                <h2 style="margin-bottom: 0; color: #333;">ORDEN DE COMPRA</h2>
                <span style="font-size: 14pt; font-weight: bold; color: #155DFC;">N° ' . str_pad($compra['id'], 5, "0", STR_PAD_LEFT) . '</span><br>
                <span style="font-size: 10pt; color: #555;">Emitida el: ' . $fecha_pdf . '</span>
            </td>
        </tr>
    </table>

    <br><br>

    <table cellpadding="8" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
        <tr>
            <td>
                <span style="font-size: 10pt; color: #555; font-weight: bold;">DATOS DEL PROVEEDOR:</span><br>
                <span style="font-size: 12pt; color: #333;"><strong>' . $compra['nombre_proveedor'] . '</strong></span><br>
                <span style="font-size: 9pt; color: #777;">ID Proveedor: ' . ($compra['id_proveedor'] ?? "N/A") . '</span>
            </td>
        </tr>
    </table>

    <br><br>

    <table border="0" cellpadding="10" cellspacing="0" style="width: 100%;">
        <thead>
            <tr style="background-color: #155DFC; color: #ffffff; font-weight: bold; text-align: center;">
                <th width="45%" style="border-bottom: 2px solid #0B1327;">Descripción del Producto</th>
                <th width="15%" style="border-bottom: 2px solid #0B1327;">Cant.</th>
                <th width="20%" style="border-bottom: 2px solid #0B1327;">Precio Unit.</th>
                <th width="20%" style="border-bottom: 2px solid #0B1327;">Subtotal</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($detalles as $det) {
            $subtotal_item = $det['cantidad'] * $det['precio_costo'];
            $html .= '
            <tr style="border-bottom: 1px solid #eee;">
                <td style="color: #333;">' . $det['nombre_producto'] . '</td>
                <td style="text-align: center; color: #333;">' . $det['cantidad'] . '</td>
                <td style="text-align: right; color: #333;">$ ' . number_format($det['precio_costo'], 2, ',', '.') . '</td>
                <td style="text-align: right; font-weight: bold; color: #155DFC;">$ ' . number_format($subtotal_item, 2, ',', '.') . '</td>
            </tr>';
        }

$html .= '
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="border-top: 1px solid #155DFC;"></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td style="text-align: right; font-size: 12pt; font-weight: bold; background-color: #f2f2f2; padding: 10px;">TOTAL:</td>
                <td style="text-align: right; font-size: 14pt; font-weight: bold; color: #ffffff; background-color: #155DFC; padding: 10px;">$ ' . number_format($compra['total'], 2, ',', '.') . '</td>
            </tr>
        </tfoot>
    </table>

    <br><br><br>
    <div style="text-align: center; border-top: 1px dashed #ccc; padding-top: 10px;">
        <p style="font-size: 9pt; color: #888;">
            Este documento es un comprobante de control interno generado automáticamente por el sistema <strong>Kiosko Manager</strong>.<br>
            Proyecto Académico - Germán Bisutti - IFTS N° 4
        </p>
    </div>
';

// Escribe el HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Salida del PDF: "I" para mostrarlo en el navegador
$pdf->Output('Orden_Compra_' . $compra['id'] . '.pdf', 'I');