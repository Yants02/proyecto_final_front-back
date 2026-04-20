<?php
include '../conexion.php';
$query = "SELECT * FROM productos ORDER BY id DESC";
$resultado = mysqli_query($conn, $query);
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
                    <tbody>
                        <?php 
                        while($row = mysqli_fetch_array($resultado)): 
                        ?>
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