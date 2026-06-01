<?php
session_start();
include '../../config/conexion.php';
$ruta_base = '../../../';
include '../../includes/header_index.php';
include __DIR__ . '/../../config/verificar_administrador.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: compras.php");
    exit();
}

$id_compra = $_GET['id'];

try {
    $stmt_compra = $conn->prepare("SELECT * FROM compras WHERE id = :id");
    $stmt_compra->execute(['id' => $id_compra]);
    $compra = $stmt_compra->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        die("La orden de compra no existe.");
    }

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
            <div class="flex-row-wrap" style="justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <h2>Detalles de la Orden #<?php echo $compra['id']; ?></h2>
                    <p style="color: #90A1B9;">
                        Proveedor: <strong class="texto-primario"><?php echo $compra['nombre_proveedor']; ?></strong> | 
                        Fecha: <?php echo date("d/m/Y H:i", strtotime($compra['fecha'])); ?>
                    </p>
                </div>
                
                <div class="flex-gap-10" style="margin-top: 0;">
                    <a href="../../acciones/generar_orden_compra.php?id=<?php echo $compra['id']; ?>" target="_blank" class="btn-guardar">
                        Imprimir Orden (PDF)
                    </a>
                    <a href="eliminar_compra.php?id=<?php echo $compra['id']; ?>" class="btn-borrar" onclick="return confirm('¿Estás seguro de que querés eliminar toda esta orden? Esta acción no se puede deshacer.');">
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
                                <td class="texto-primario">$<?php echo number_format($det['cantidad'] * $det['precio_costo'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; font-size: 16px;"><strong>TOTAL DE LA ORDEN:</strong></td>
                            <td class="texto-total">
                                $<?php echo number_format($compra['total'], 2, ',', '.'); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </article>
    </section>
</main>

<footer class="footer">
    <p>2026 - Panel de Control de Compras - IFTS N° 4</p>
</footer>
</body>
</html>