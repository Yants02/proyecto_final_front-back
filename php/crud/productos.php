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

            <article id="formulario-baja">
                <h2>Eliminar Producto</h2>
                <form action="eliminar_producto.php" method="POST" class="formulario">
                    <div class="campo">
                        <label for="id">Id producto:</label>
                        <input type="number" name="id" id="id" placeholder="Ej: 23" required>
                    </div>

                    <button type="submit" class="btn-eliminar">Eliminar Producto</button>
                </form>
            </article>

            <hr>

            <article id="formulario-editar">
                <h2>Editar Producto</h2>
                <form action="actualizar_producto.php" method="post" class="formulario">
                    <div class="campo">
                        <label for="id">Id producto:</label>
                        <input type="number" name="id" id="id" placeholder="Ej:23" required>
                    </div>
                    <div class="campo">
                        <label for="nuevo-nombre">Nuevo Nombre:</label>
                        <input type="text" name="nuevo-nombre" id="nuevo-nombre" placeholder="Chocolate">
                    </div>
                    <div class="campo">
                        <label for="nuevo-precio">precio producto:</label>
                        <input type="number" name="nuevo-precio" id="nuevo-precio" placeholder="0.00">
                    </div>
                    <div class="campo">
                        <label for="nuevo-stock">Stock producto:</label>
                        <input type="number" name="nuevo-stock" id="nuevo-stock" placeholder="Cantidad disponible">
                    </div>

                    <button type="submit" class="btn-guardar">Actualizar Producto</button>
                </form>
            </article>

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
                    <tbody>
                        <?php while($row = $resultado->fetch()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td>$<?php echo number_format($row['precio_venta'], 2); ?></td>
                            <td><?php echo $row['stock']; ?></td>
                            <td>
                                <button class="btn-editar">Editar</button>
                                <button class="btn-borrar">X</button>
                            </td>
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