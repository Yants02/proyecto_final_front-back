<?php
session_start();
require_once('../config/conexion.php');
require_once('../includes/tcpdf/tcpdf.php');

try {
    // Traemos absolutamente todas las compras para el reporte general
    $query = "SELECT * FROM compras ORDER BY id DESC";
    $stmt = $conn->query($query);
    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de base de datos: " . $e->getMessage());
}

// Configuración de TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetCreator('Kiosko Manager');
$pdf->SetAuthor('Germán Bisutti');
$pdf->SetTitle('Reporte General de Compras');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();

// Encabezado del reporte
$html = '
    <table cellpadding="5">
        <tr>
            <td width="60%">
                <h1 style="color: #155DFC; font-size: 20pt;">KIOSKO MANAGER</h1>
                <p style="font-size: 10pt; color: #555;">Reporte Histórico de Órdenes de Compra</p>
            </td>
            <td width="40%" style="text-align: right;">
                <p style="font-size: 10pt; color: #555;">
                    Fecha de reporte: ' . date("d/m/Y H:i") . '<br>
                    Total de registros: ' . count($compras) . '
                </p>
            </td>
        </tr>
    </table>

    <br><br>

    <table border="0" cellpadding="8" cellspacing="0" style="width: 100%;">
        <thead>
            <tr style="background-color: #0B1327; color: #ffffff; font-weight: bold; text-align: center;">
                <th width="15%" style="border-bottom: 1px solid #155DFC;">ID Orden</th>
                <th width="45%" style="border-bottom: 1px solid #155DFC;">Proveedor</th>
                <th width="20%" style="border-bottom: 1px solid #155DFC;">Fecha</th>
                <th width="20%" style="border-bottom: 1px solid #155DFC;">Total</th>
            </tr>
        </thead>
        <tbody>';

        $suma_total = 0;

        foreach ($compras as $c) {
            $suma_total += $c['total'];
            $html .= '
            <tr style="border-bottom: 1px solid #eee;">
                <td style="text-align: center; color: #333;">#' . $c['id'] . '</td>
                <td style="color: #333;">' . $c['nombre_proveedor'] . '</td>
                <td style="text-align: center; color: #333;">' . date("d/m/Y", strtotime($c['fecha'])) . '</td>
                <td style="text-align: right; font-weight: bold; color: #333;">$ ' . number_format($c['total'], 2, ',', '.') . '</td>
            </tr>';
        }

$html .= '
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa;">
                <td colspan="3" style="text-align: right; font-size: 12pt; font-weight: bold;">TOTAL ACUMULADO:</td>
                <td style="text-align: right; font-size: 12pt; font-weight: bold; color: #9810FA;">$ ' . number_format($suma_total, 2, ',', '.') . '</td>
            </tr>
        </tfoot>
    </table>

    <br><br>
    <div style="text-align: center;">
        <p style="font-size: 8pt; color: #bbb;">*** Fin del Reporte de Auditoría Interna ***</p>
    </div>
';

$pdf->writeHTML($html, true, false, true, false, '');

// Salida al navegador
$pdf->Output('Reporte_General_Compras.pdf', 'I');