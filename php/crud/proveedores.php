<?php
include '../conexion.php';

try {
    $query = "SELECT * FROM proveedores ORDER BY id DESC";
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
    <main class="contenedor-principal">

        <aside class="sidebar">
            <h3>Acciones</h3>
            <ul>
                <li><a href="#">Nuevo Proveedor</a></li>
                <li><a href="#">Ver Proveedores</a></li>
            </ul>
        </aside>

        <section class="contenido">

            <article id="formulario-alta">
                <h2>Cargar Nuevo Proveedor</h2>
                <form action="guardar_prov.php" method="POST" class="formulario">
                    <div class="campo">
                        <label>Nombre del Proveedor:</label>
                        <input type="text" name="nombre" placeholder="Ej: Empresa sa" required>
                    </div>

                    <div class="campo">
                        <label>Teléfono del Proveedor:</label>
                        <input type="number" name="telefono">
                    </div>

                    <div class="campo">
                        <label>Email del Proveedor:</label>
                        <input type="email" name="email" placeholder="mail@mail.com" required>
                    </div>

                    <div class="campo">
                        <label>Dirección del Proveedor:</label>
                        <input type="text" name="direccion" placeholder="Calle 1234" required>
                    </div>
                    <button type="submit" class="btn-guardar">Guardar Proveedor</button>
                </form>
            </article>

            <hr>

            <article id="formulario-baja">
                <h2>Eliminar Proveedor</h2>
                <form action="eliminar_prov.php" method="POST" class="formulario">
                    <div class="campo">
                        <label for="id">Id proveedor:</label>
                        <input type="number" name="id" id="id" placeholder="Ej: 23" required>
                    </div>

                    <button type="submit" class="btn-eliminar">Eliminar Proveedor</button>
                </form>
            </article>

            <hr>

            <article id="formulario-editar">
                <h2>Editar Producto</h2>
                <form action="actualizar_prov.php" method="post" class="formulario">
                    <div class="campo">
                        <label for="id">Id proveedor:</label>
                        <input type="number" name="id" id="id" placeholder="Ej:23" required>
                    </div>

                    <div class="campo">
                        <label>Nombre del Proveedor:</label>
                        <input type="text" name="nuevo-nombre" placeholder="Ej: Empresa sa" required>
                    </div>

                    <div class="campo">
                        <label>Teléfono del Proveedor:</label>
                        <input type="number" name="nuevo-telefono">
                    </div>

                    <div class="campo">
                        <label>Email del Proveedor:</label>
                        <input type="email" name="nuevo-email" placeholder="mail@mail.com" required>
                    </div>

                    <div class="campo">
                        <label>Dirección del Proveedor:</label>
                        <input type="text" name="nuevo-direccion" placeholder="Calle 1234" required>
                    </div>

                    <button type="submit" class="btn-guardar">Actualizar Proveedor</button>
                </form>
            </article>

            <article id="tabla-listado">
                <h2>Proveedores Actuales</h2>
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th>Direccion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado->fetch()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['nombre']; ?></td>
                                <td><?php echo $row['telefono']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['direccion']; ?></td>
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
        <p> 2026 - Panel de Control de proveedores</p>
    </footer>

    <script src="../../js/validaciones.js"></script>
</body>

</html>