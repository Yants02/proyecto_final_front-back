<?php
include '../../config/conexion.php';
$ruta_base = '../../../'; 
include '../../includes/header_index.php';

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
            <li><a href="../../../index.php">Volver al Inicio</a></li>
        </ul>
    </aside>

    <section class="contenido">

            <article id="formulario-alta">
                
                <?php if (isset($_GET['error']) && $_GET['error'] == 'dni_duplicado'): ?>
                    <div style="background-color: #d63031; color: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #ff7675;">
                        <strong>¡Atención!</strong> No se pudo guardar. Ya existe un cliente registrado con ese mismo DNI.
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'creado'): ?>
                    <div style="background-color: #00b894; color: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #00cec9;">
                        <strong>¡Éxito!</strong> Cliente guardado correctamente.
                    </div>
                <?php endif; ?>
                <h2>Cargar Nuevo Cliente</h2>
                
                <form action="guardar_cliente.php" method="POST" class="formulario">
                    
                    <div class="campo">
                        <label>DNI del Cliente:</label>
                        <input type="number" name="dni" class="campo-input" placeholder="Sin puntos ni espacios" required>
                    </div>

                    <div class="campo">
                        <label>Nombre del cliente:</label>
                        <input type="text" name="nombre" class="campo-input" placeholder="Nombre y apellido" required>
                    </div>

                    <div class="campo">
                        <label>Email del Cliente:</label>
                        <input type="email" name="email" class="campo-input" placeholder="mail@mail.com" required>
                    </div>

                    <div style="margin-top: 10px;">
                        <button type="submit" class="btn-guardar">Guardar Cliente</button>
                    </div>
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
                // Forzamos que el ID de edición sea un entero o null para evitar el error del ID 0
                $id_a_editar = isset($_GET['editar_id']) ? (int)$_GET['editar_id'] : null;
                ?>
                <tbody>
                    <?php while ($row = $resultado->fetch()): ?>
                        <tr>
                            <!-- Usamos comparación estricta (!== null y ===) para que no se confunda con el ID 0 -->
                            <?php if ($id_a_editar !== null && $id_a_editar === (int)$row['id']): ?>
                                <form action="actualizar_cliente.php" method="POST">
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
                                    <form action="eliminar_cliente.php" method="POST" style="display:inline;">
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