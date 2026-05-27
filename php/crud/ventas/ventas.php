<?php
session_start();
include '../../config/conexion.php';
$ruta_base = '../../../';
include '../../includes/header_index.php';

try {
    $query = "SELECT * FROM ventas ORDER BY id DESC";
    $resultado = $conn->query($query);

    $sql_prov = "SELECT id, nombre FROM clientes ORDER BY nombre ASC";
    $stmt_prov = $conn->query($sql_prov);
    $clientes = $stmt_prov->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error en el sistema: " . $e->getMessage();
}
?>

<main class="contenedor-principal">
    <aside class="sidebar">
        <h3>Acciones</h3>
        <ul>
            <li><a href="#formulario-alta">Nueva Venta</a></li>
            <li><a href="#tabla-listado">Ver Ventas</a></li>
            <li><a href="../../../index.php">Volver al Inicio</a></li>
        </ul>
    </aside>

    <section class="contenido">
        <article id="formulario-alta">
            <h2>Cargar Nueva Venta</h2>
            <form action="guardar_venta.php" method="GET" class="formulario">
                <div class="campo">
                    <label for="id_cliente">Seleccionar Cliente:</label>
                    <select name="id_cli" id="id_cliente" class="campo-input" required>
                        <option value="">-- Seleccione un cliente --</option>
                        <?php foreach ($clientes as $cli): ?>
                            <option value="<?php echo $cli['id']; ?>">
                                <?php echo $cli['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="flex-gap-10">
                    <button type="submit" class="btn-guardar">Crear Venta</button>
                    <a href="ventas.php" class="btn-cancelar">Cancelar</a>
                </div>
            </form>
        </article>
                                <!-- CONTINUAR... -->
        <hr>
        
        <article id="tabla-listado">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">Órdenes de Compra</h2>
                <a href="../../acciones/exportar_excel.php?tipo=compras" class="btn-guardar" style="background-color: #27ae60; display: flex; align-items: center; gap: 8px; text-decoration: none;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
        <polyline points="7 10 12 15 17 10"></polyline>
        <line x1="12" y1="15" x2="12" y2="3"></line>
    </svg>
    Exportar a Excel (CSV)
</a>
                <a href="../../acciones/generar_registro_compras.php" target="_blank" class="btn-guardar" style="background-color: #9810FA; text-decoration: none;">Imprimir Listado General (PDF)</a>
            </div>
            
            <table class="tabla-datos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID Proveedor</th>
                        <th>Nombre Proveedor</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultado->fetch()): ?>
                        <tr>
                            <td><strong>#<?php echo $row['id']; ?></strong></td>
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
                            <td>
                                <a href="detail_compra.php?id=<?php echo $row['id']; ?>" class="btn-guardar" style="padding: 6px 12px; font-size: 13px; text-decoration: none;">Ver Detalle</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </article>
    </section>
</main>

<?php include '../../includes/footer.php'; ?>
</body>
</html>