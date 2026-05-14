<?php
session_start();
include '../../config/conexion.php';
$ruta_base = '../../../';
include '../../includes/header_index.php';

try {
    // Solo traemos la lista general
    $query = "SELECT * FROM compras ORDER BY id DESC";
    $resultado = $conn->query($query);

    $sql_prov = "SELECT id, nombre FROM proveedores ORDER BY nombre ASC";
    $stmt_prov = $conn->query($sql_prov);
    $proveedores = $stmt_prov->fetchAll(PDO::FETCH_ASSOC);

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
            <form action="crear_compra.php" method="GET" class="formulario">
                <div class="campo">
                    <label for="id_proveedor">Seleccionar Proveedor:</label>
                    <select name="id_prov" id="id_proveedor" class="campo-input" required
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">Órdenes de Compra</h2>
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

<footer class="footer">
    <p>2026 - Panel de Control de Compras - IFTS N° 4</p>
</footer>
</body>
</html>