<?php
session_start();
include '../../config/conexion.php';
$ruta_base = '../../../';
include '../../includes/header_index.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ventas.php");
    exit();
}

$id_venta = $_GET['id'];

try {
    $stmt_venta = $conn->prepare("SELECT * FROM ventas WHERE id = :id");
    $stmt_venta->execute(['id' => $id_venta]);
    $venta = $stmt_venta->fetch(PDO::FETCH_ASSOC);

    if (!$venta) {
        die("La venta no existe.");
    }

    $stmt_det = $conn->prepare("SELECT * FROM detalle_ventas WHERE id_venta = :id");
    $stmt_det->execute(['id' => $id_venta]);
    $detalles = $stmt_det->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error en el sistema: " . $e->getMessage());
}
?>

<main class="contenedor-principal">
    <aside class="sidebar">
        <h3>Navegación</h3>
        <ul>
            <li><a href="ventas.php">← Volver a Ventas</a></li>
        </ul>
    </aside>

    <section class="contenido">
        <article>
            <div class="flex-row-wrap" style="justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <h2>Detalles de la Venta #<?php echo $venta['id']; ?></h2>
                    <p style="color: #90A1B9;">
                        Proveedor: <strong class="texto-primario"><?php echo $venta['nombre_cliente']; ?></strong> | 
                        Fecha: <?php echo date("d/m/Y H:i", strtotime($venta['fecha'])); ?>
                    </p>
                </div>
                
                <div class="flex-gap-10" style="margin-top: 0;">
                    <!-- <a href="../../acciones/generar_orden_compra.php?id=<?php echo $venta['id']; ?>" target="_blank" class="btn-guardar">
                        Imprimir Orden (PDF)
                    </a> -->
                    <a href="eliminar_venta.php?id=<?php echo $venta['id']; ?>" class="btn-borrar" onclick="return confirm('¿Estás seguro de que querés eliminar toda esta orden? Esta acción no se puede deshacer.');">
                        Eliminar Orden
                    </a>
                </div>
            </div>

            <div class="caja-oscura">
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio venta</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalles as $det): ?>
                            <tr>
                                <td><?php echo $det['nombre_producto']; ?></td>
                                <td><?php echo $det['cantidad']; ?></td>
                                <td>$<?php echo number_format($det['precio_venta'], 2, ',', '.'); ?></td>
                                <td class="texto-primario">$<?php echo number_format($det['cantidad'] * $det['precio_venta'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; font-size: 16px;"><strong>TOTAL DE LA VENTA:</strong></td>
                            <td class="texto-total">
                                $<?php echo number_format($venta['total'], 2, ',', '.'); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </article>
    </section>
</main>

<footer class="footer">
    <p>2026 - Panel de Control de Ventas - IFTS N° 4</p>
</footer>
</body>
</html>