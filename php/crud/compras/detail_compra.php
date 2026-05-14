<?php
session_start();
include '../../config/conexion.php';
$ruta_base = '../../../';
include '../../includes/header_index.php';

// Si alguien entra sin un ID válido, lo pateamos de vuelta a compras
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: compras.php");
    exit();
}

$id_compra = $_GET['id'];

try {
    // 1. Buscamos los datos generales de esta compra específica
    $stmt_compra = $conn->prepare("SELECT * FROM compras WHERE id = :id");
    $stmt_compra->execute(['id' => $id_compra]);
    $compra = $stmt_compra->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        die("La orden de compra no existe.");
    }

    // 2. Buscamos los productos (ítems) de esta compra
    $stmt_det = $conn->prepare("SELECT * FROM detalle_compras WHERE id_compra = :id");
    $stmt_det->execute(['id' => $id_compra]);
    $detalles = $stmt_det->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error en el sistema: " . $e->getMessage());
}
?>

<main class="contenedor-principal">
    
    <aside class="sidebar">
        <h3>Navegación</h3>
        <ul>
            <li><a href="compras.php">← Volver a Compras</a></li>
        </ul>
    </aside>

    <section class="contenido">
        <article>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <h2 style="margin: 0; margin-bottom: 5px;">Detalles de la Orden #<?php echo $compra['id']; ?></h2>
                    <p style="color: #90A1B9; margin: 0;">
                        Proveedor: <strong><?php echo $compra['nombre_proveedor']; ?></strong> | 
                        Fecha: <?php echo date("d/m/Y H:i", strtotime($compra['fecha'])); ?>
                    </p>
                </div>
                
                <a href="../../acciones/generar_orden_compra.php?id=<?php echo $compra['id']; ?>" target="_blank" class="btn-guardar" style="background-color: #155DFC; text-decoration: none;">
                     Imprimir Orden (PDF)
                </a>
            </div>

            <table class="tabla-datos" style="background: #050C24;">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Costo</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $det): ?>
                        <tr>
                            <td><?php echo $det['nombre_producto']; ?></td>
                            <td><?php echo $det['cantidad']; ?></td>
                            <td>$<?php echo number_format($det['precio_costo'], 2, ',', '.'); ?></td>
                            <td style="color: #50A2FF;">$<?php echo number_format($det['cantidad'] * $det['precio_costo'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; font-size: 18px;"><strong>TOTAL DE LA ORDEN:</strong></td>
                        <td style="color: #9810FA; font-size: 18px; font-weight: bold;">
                            $<?php echo number_format($compra['total'], 2, ',', '.'); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
            
        </article>
    </section>
</main>

<footer class="footer">
    <p>2026 - Panel de Control de Compras - IFTS N° 4</p>
</footer>
</body>
</html>