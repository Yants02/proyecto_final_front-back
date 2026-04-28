<?php
include '../conexion.php';

try {
    $query = "SELECT * FROM productos ORDER BY id DESC";
    $resultado = $conn->query($query);
} catch (PDOException $e) {
    echo "Error al consultar productos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - Sistema IFTS 4</title>
    <link rel="stylesheet" href="../../css/estilos.css">
</head>

<body>

    <nav class="navbar">
        <div class="logo">Sistema Gestión 2026</div>
        <ul class="nav-links">
            <li><a href="../../index.php">Inicio</a></li>
            <li><a href="productos.php">Productos</a></li>
            <li><a href="#">Proveedores</a></li>
            <li><a href="#">Clientes</a></li>
        </ul>
    </nav>

    <main class="contenedor-principal">

        <aside class="sidebar">
            <h3>Acciones</h3>
            <ul>
                <li><a href="#">Nuevo Producto</a></li>
                <li><a href="#">Ver Inventario</a></li>
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

                    <div class="campo">
                        <label>Stock Inicial:</label>
                        <input type="number" name="stock" placeholder="Cantidad disponible" required>
                    </div>

                    <button type="submit" class="btn-guardar">Guardar Producto</button>
                </form>
            </article>

            <hr>

            <article id="tabla-listado">
                <h2>Inventario Actual</h2>
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
                        <?php while ($row = $resultado->fetch()): ?>
                            <tr>
                                <?php if ($id_a_editar == $row['id']): ?>
                                    <form action="actualizar_producto.php" method="POST">
                                        <td><?php echo $row['id']; ?> <input type="hidden" name="id" value="<?php echo $row['id']; ?>"></td>
                                        <td><input type="text" name="nuevo-nombre" value="<?php echo $row['nombre']; ?>"></td>
                                        <td><input type="number" step="0.01" name="nuevo-precio" value="<?php echo $row['precio_venta']; ?>"></td>
                                        <td><?php echo $row['stock']; ?> <input type="hidden" name="stock" value="<?php echo $row['stock']; ?>"></td>
                                        <td>
                                            <button type="submit" class="btn-guardar">Guardar</button>
                                            <a href="productos.php" class="btn-cancelar">Cancelar</a>
                                        </td>
                                    </form>
                                <?php else: ?>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td><?php echo $row['precio_venta']; ?></td>
                                    <td><?php echo $row['stock']; ?></td>
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

    <footer class="footer">
        <p> 2026 - Panel de Control de Inventario</p>
    </footer>

    <script src="../../js/validaciones.js"></script>
</body>

</html>