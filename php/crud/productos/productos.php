<?php
include __DIR__ . '/../../config/verificar_administrador.php';

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si no está logueado, al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

include '../../config/conexion.php';
$ruta_base = '../../../'; 
include '../../includes/header_index.php';

try {
    $usuario_id = $_SESSION['usuario_id'];

    $sql = "SELECT id, nombre, precio_venta, stock FROM productos WHERE usuario_id = :usuario_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':usuario_id' => $usuario_id]);
    $productos = $stmt;
} catch (PDOException $e) {
    echo "Error en el sistema: " . $e->getMessage();
}
?>

    <main class="contenedor-principal">

        <aside class="sidebar">
            <h3>Acciones</h3>
            <ul>
                <li><a href="#formulario-alta">Nuevo Producto</a></li>
                <li><a href="#tabla-listado">Ver Inventario</a></li>
                <li><a href="../../../index.php">Volver al Inicio</a></li>
            </ul>
        </aside>

        <section class="contenido">

            <article id="formulario-alta">
                <h2>Cargar Nuevo Producto</h2>
                <form action="guardar_producto.php" method="POST" class="formulario">
                    
                    <div class="campo">
                        <label>Nombre del Producto:</label>
                        <input type="text" name="nombre" placeholder="Ej: Chocolate" required>
                    </div>

                    <div class="campo">
                        <label>Precio de Venta:</label>
                        <input type="number" step="0.01" name="precio_venta" placeholder="0.00" required>
                    </div>

                    <!-- <div class="campo">
                        <label>Stock Inicial:</label>
                        <input type="number" name="stock" placeholder="Cantidad disponible" required>
                    </div> -->

                    <div style="margin-top: 10px;">
                        <button type="submit" class="btn-guardar">Guardar Producto</button>
                    </div>
                </form>
            </article>

            <hr>

            <article id="tabla-listado">
                <h2>Inventario Actual</h2>
                <a href="../../acciones/exportar_excel.php?tipo=productos" class="btn-guardar" style="background-color: #27ae60; display: flex; align-items: center; gap: 8px; text-decoration: none;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
        <polyline points="7 10 12 15 17 10"></polyline>
        <line x1="12" y1="15" x2="12" y2="3"></line>
    </svg>
    Exportar a Excel (CSV)
</a>
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <?php
                    $id_a_editar = isset($_GET['editar_id']) ? $_GET['editar_id'] : null;
                    ?>

                    <tbody>
                        <?php while ($row = $productos->fetch()): ?>
                            <tr>
                                <?php if ($id_a_editar == $row['id']): ?>>
                                    <form action="actualizar_producto.php" method="POST">
                                        <td><strong>#<?php echo $row['id']; ?></strong> <input type="hidden" name="id" value="<?php echo $row['id']; ?>"></td>
                                        <td><input type="text" name="nuevo-nombre" value="<?php echo $row['nombre']; ?>" class="input-edit"></td>
                                        <td><input type="number" step="0.01" name="nuevo-precio" value="<?php echo $row['precio_venta']; ?>" class="input-edit"></td>
                                        <td><strong><?php echo $row['stock']; ?></strong> <input type="hidden" name="stock" value="<?php echo $row['stock']; ?>" class="input-edit"></td>
                                        <td>
                                            <button type="submit" class="btn-guardar">Guardar</button>
                                            <a href="productos.php" class="btn-cancelar">Cancelar</a>
                                        </td>
                                    </form>
                                <?php else: ?>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td style="color: #50A2FF; font-weight: bold;">$<?php echo number_format($row['precio_venta'], 2, ',', '.'); ?></td>
                                    <td>
                                        <span style="padding: 4px 8px; border-radius: 4px; background: <?php echo $row['stock'] <= 5 ? '#d63031' : 'transparent'; ?>;">
                                            <?php echo $row['stock']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="productos.php?editar_id=<?php echo $row['id']; ?>" class="btn-editar">Editar</a>
                                        <form action="eliminar_producto.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn-borrar">X</button>
                                        </form>
                                    </td>
                                <?php endif; ?>
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