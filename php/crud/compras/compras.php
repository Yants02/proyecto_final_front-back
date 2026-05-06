<?php
include '../../conexion.php';
$ruta_base = '../../../';
include '../../includes/header_index.php';

try {
    $query = "SELECT * FROM compras ORDER BY id DESC";
    $resultado = $conn->query($query);

    $sql_prov = "SELECT id, nombre FROM proveedores ORDER BY nombre ASC";
    $stmt_prov = $conn->query($sql_prov);
    $proveedores = $stmt_prov->fetchAll(PDO::FETCH_ASSOC);

    // Clic en una orden
    $detalles_seleccionados = null;
    if (isset($_GET['ver_detalle'])) {
        $id_c = $_GET['ver_detalle'];
        $stmt_det = $conn->prepare("SELECT * FROM detalle_compras WHERE id_compra = :id");
        $stmt_det->execute(['id' => $id_c]);
        $detalles_seleccionados = $stmt_det->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Error en el sistema: " . $e->getMessage();
}
?>

<main class="contenedor-principal">

    <aside class="sidebar">
        <h3>Acciones</h3>
        <ul>
            <li><a href="#formulario-alta">Nueva Compra</a></li>
            <li><a href="#tabla-listado">Ver Órdenes</a></li>
            <li><a href="../../../index.php">Volver al Inicio</a></li>
        </ul>
    </aside>

    <section class="contenido">

        <article id="formulario-alta">
            <h2>Cargar Nueva Compra</h2>
            <form action="guardar_compra.php" method="POST" class="formulario">

                <div class="campo">
                    <label for="id_proveedor">Seleccionar Proveedor:</label>
                    <select name="id_proveedor" id="id_proveedor" class="campo-input" required
                        style="width: 100%; padding: 12px; border-radius: 8px; background: #03081A; color: #ededed; border: 1px solid #2A3B5C;">
                        <option value="">-- Seleccione un proveedor --</option>
                        <?php foreach ($proveedores as $prov): ?>
                            <option value="<?php echo $prov['id']; ?>">
                                <?php echo $prov['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="submit" class="btn-guardar">Crear orden</button>
                    <a href="compras.php" class="btn-cancelar">Cancelar</a>
                </div>
            </form>
        </article>

        <hr>
        <article id="tabla-listado">
            <h2>Órdenes de Compra</h2>
            <table class="tabla-datos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID Proveedor</th>
                        <th>Nombre Proveedor</th>
                        <th>Fecha</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultado->fetch()): ?>
                        <tr>
                            <td><!-- ver detalles debajo -->
                                <a href="compras.php?ver_detalle=<?php echo $row['id']; ?>#tabla-detalles" style="color: #50A2FF; text-decoration: none;">
                                    <strong>#<?php echo $row['id']; ?></strong>
                                </a>
                            </td>
                            <td>
                                <?php
                                echo $row['id_proveedor'];
                                if (is_null($row['id_proveedor'])) {
                                    echo " <small style='color: #ff7675;'>(ELIMINADO)</small>";
                                }
                                ?>
                            </td>
                            <td><?php echo $row['nombre_proveedor']; ?></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($row['fecha'])); ?></td>
                            <td style="color: #50A2FF; font-weight: bold;">
                                $<?php echo number_format($row['total'], 2, ',', '.'); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php if ($detalles_seleccionados): ?>
                <hr id="tabla-detalles" style="margin: 40px 0; border-color: #2A3B5C;">
                <article>
                    <h2>Detalles de la Orden #<?php echo $_GET['ver_detalle']; ?></h2>
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
                            <?php foreach ($detalles_seleccionados as $det): ?>
                                <tr>
                                    <td><?php echo $det['nombre_producto']; ?></td>
                                    <td><?php echo $det['cantidad']; ?></td>
                                    <td>$<?php echo number_format($det['precio_costo'], 2, ',', '.'); ?></td>
                                    <td>$<?php echo number_format($det['cantidad'] * $det['precio_costo'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="margin-top: 10px;">
                        <a href="compras.php" class="btn-cancelar">Cerrar detalles</a>
                    </div>
                </article>
            <?php endif; ?>
        </article>

    </section>
</main>

<footer class="footer">
    <p>2026 - Panel de Control de Compras - IFTS N° 4</p>
</footer>
</body>

</html>