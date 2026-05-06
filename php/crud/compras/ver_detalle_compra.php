<?php
include '../../conexion.php';
$ruta_base = '../../../'; 
include '../../includes/header_index.php';

$id_compra = $_GET['id']; 

try {
    $stmt = $conn->prepare("SELECT * FROM compras WHERE id = :id");
    $stmt->execute(['id' => $id_compra]);
    $compra = $stmt->fetch(PDO::FETCH_ASSOC);

    $productos = $conn->query("SELECT id, nombre, precio_venta FROM productos ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

    $stmt_det = $conn->prepare("SELECT * FROM detalle_compras WHERE id_compra = :id");
    $stmt_det->execute(['id' => $id_compra]);
    $detalles = $stmt_det->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) { echo "Error: " . $e->getMessage(); }
?>

<main class="contenedor-principal">
    <section class="contenido">
        <h2>Cargar Productos - Orden #<?php echo $id_compra; ?></h2>
        <p>Proveedor: <strong><?php echo $compra['nombre_proveedor']; ?></strong></p>

        <article id="formulario-alta">
            <form action="guardar_detalle_compra.php" method="POST" class="formulario">
                <input type="hidden" name="id_compra" value="<?php echo $id_compra; ?>">
                
                <div class="campo">
                    <label>Producto:</label>
                    <select name="id_producto" class="campo-input" required>
                        <option value="">-- Seleccione Producto --</option>
                        <?php foreach ($productos as $p): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo $p['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo">
                    <label>Cantidad:</label>
                    <input type="number" name="cantidad" class="campo-input" min="1" required>
                </div>

                <div class="campo">
                    <label>Precio Costo (Unitario):</label>
                    <input type="number" name="precio_costo" class="campo-input" step="0.01" required>
                </div>

                <button type="submit" class="btn-guardar">Agregar al Detalle</button>
            </form>
        </article>

        <article id="tabla-listado">
            <h3>Productos en esta orden</h3>
            <table class="tabla-datos">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cant.</th>
                        <th>Costo Unit.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_orden = 0;
                    foreach ($detalles as $d): 
                        $subtotal = $d['cantidad'] * $d['precio_costo'];
                        $total_orden += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo $d['nombre_producto']; ?></td>
                        <td><?php echo $d['cantidad']; ?></td>
                        <td>$<?php echo number_format($d['precio_costo'], 2); ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3 style="text-align: right;">Total Orden: $<?php echo number_format($total_orden, 2); ?></h3>
            <a href="compras.php" class="btn-guardar" style="background: #2ecc71;">Finalizar Orden</a>
        </article>
    </section>
</main>