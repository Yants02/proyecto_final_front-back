<?php
// 1. Llamamos a la librería que acabás de mover
require_once('../includes/tcpdf/tcpdf.php');

// 2. Creamos un PDF nuevo (Vertical, en milímetros, tamaño A4)
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// 3. Configuraciones básicas
$pdf->SetCreator('Sistema de Gestion');
$pdf->SetTitle('Reporte de Prueba');
$pdf->setPrintHeader(false); // Sacamos la cabecera por defecto
$pdf->setPrintFooter(false); // Sacamos el pie de página
$pdf->SetMargins(15, 15, 15); // Márgenes de 15mm

// 4. Agregamos una página en blanco
$pdf->AddPage();

// 5. Armamos el diseño con HTML puro
$html = '
    <h1 style="color: #155DFC; text-align: center;">¡TCPDF Funciona!</h1>
    <hr>
    <br>
    <p style="font-size: 14px;">Si estás viendo esto significa que la librería está bien conectada y lista para recibir los datos de tu base de datos.</p>
';

// 6. Escribimos el HTML adentro del PDF
$pdf->writeHTML($html, true, false, true, false, '');

// 7. Lo mostramos en el navegador
// La letra "I" hace que se abra en el navegador. Si ponés "D", se descarga automáticamente.
$pdf->Output('reporte_prueba.pdf', 'I');
?>