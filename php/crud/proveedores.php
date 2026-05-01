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
                <li><a href="../../index.php"> Volver al Inicio</a></li>
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
                    <?php
                    $id_a_editar = isset($_GET['editar_id']) ? $_GET['editar_id'] : null;
                    ?>
                    <tbody>
                        <?php while ($row = $resultado->fetch()): ?>
                            <tr>
                                <?php if ($id_a_editar == $row['id']): ?>
                                    <form action="actualizar_prov.php" method="POST">
                                        <td><?php echo $row['id']; ?> <input type="hidden" name="id" value="<?php echo $row['id']; ?>"></td>
                                        <td><input type="text" name="nuevo-nombre" value="<?php echo $row['nombre']; ?>"></td>
                                        <td><input type="number" name="nuevo-telefono" value="<?php echo $row['telefono']; ?>"></td>
                                        <td><input type="email" name="nuevo-email" value="<?php echo $row['email']; ?>"></td>
                                        <td><input type="text" name="nuevo-direccion" value="<?php echo $row['direccion']; ?>"></td>
                                        <td>
                                            <button type="submit" class="btn-guardar">Guardar</button>
                                            <a href="proveedores.php" class="btn-cancelar">Cancelar</a>
                                        </td>
                                    </form>
                                <?php else: ?>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['nombre']; ?></td> 
                                    <td><?php echo $row['telefono']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['direccion']; ?></td>

                                    <td>
                                        <a href="proveedores.php?editar_id=<?php echo $row['id']; ?>" class="btn-editar">Editar</a>
                                        <form action="eliminar_prov.php" method="POST" style="display:inline;">
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
        <p> 2026 - Panel de Control de proveedores</p>
    </footer>

    <script src="../../js/validaciones.js"></script>
</body>

</html>