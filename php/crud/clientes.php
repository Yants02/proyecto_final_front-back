<?php
include '../conexion.php';
$ruta_base = '../../'; 
include '../includes/header_index.php';

try {
    $query = "SELECT * FROM clientes ORDER BY id DESC";
    $resultado = $conn->query($query);
} catch (PDOException $e) {
    echo "Error al consultar clientes: " . $e->getMessage();
}
?>
    <main class="contenedor-principal">

        <aside class="sidebar">
            <h3>Acciones</h3>
            <ul>
                <li><a href="#">Nuevo cliente</a></li>
                <li><a href="#">Ver cliente</a></li>
                <li><a href="../../index.php"> Volver al Inicio</a></li>
            </ul>
        </aside>

        <section class="contenido">

            <article id="formulario-alta">
                <h2>Cargar Nuevo Cliente</h2>
                <form action="guardar_cli.php" method="POST" class="formulario">
                    <div class="campo">
                        <label>DNI del Cliente:</label>
                        <input type="number" name="dni" required>
                    </div>
                    <div class="campo">
                        <label>Nombre del cliente:</label>
                        <input type="text" name="nombre" placeholder="Nombre y apellido" required>
                    </div>
                    <div class="campo">
                        <label>Email del Cliente:</label>
                        <input type="email" name="email" placeholder="mail@mail.com" required>
                    </div>
                    <button type="submit" class="btn-guardar">Guardar Cliente</button>
                </form>
            </article>

            <hr>

            <article id="tabla-listado">
                <h2>Clientes Actuales</h2>
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Email</th>
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
                                    <form action="actualizar_cli.php" method="POST">
                                        <td><?php echo $row['id']; ?> <input type="hidden" name="id" value="<?php echo $row['id']; ?>"></td>
                                        <td><input type="number" name="nuevo-dni" value="<?php echo $row['dni']; ?>"></td>
                                        <td><input type="text" name="nuevo-nombre" value="<?php echo $row['nombre']; ?>"></td>
                                        <td><input type="email" name="nuevo-email" value="<?php echo $row['email']; ?>"></td>
                                        <td>
                                            <button type="submit" class="btn-guardar">Guardar</button>
                                            <a href="clientes.php" class="btn-cancelar">Cancelar</a>
                                        </td>
                                    </form>
                                <?php else: ?>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['dni']; ?></td>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td>
                                        <a href="clientes.php?editar_id=<?php echo $row['id']; ?>" class="btn-editar">Editar</a>
                                        <form action="eliminar_cli.php" method="POST" style="display:inline;">
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
        <p>2026 - Panel de Control de clientes</p>
    </footer>

    <script src="../../js/validaciones.js"></script>
</body>
</html>