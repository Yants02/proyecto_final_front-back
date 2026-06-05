<?php
include __DIR__ . '/../../config/verificar_administrador.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}


include '../../config/conexion.php';
$ruta_base = '../../../';
include '../../includes/header_index.php';

try {
    $usuario_id = $_SESSION['usuario_id'];
    $sql = "SELECT id, dni, nombre, email FROM clientes WHERE usuario_id = :usuario_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':usuario_id' => $usuario_id]);
    $clientes = $stmt;
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
            <a href="../../acciones/exportar_excel.php?tipo=clientes" class="btn-guardar" style="background-color: #27ae60; display: flex; align-items: center; gap: 8px; text-decoration: none;">
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
                        <th>DNI</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <?php
                $id_a_editar = isset($_GET['editar_id']) ? (int)$_GET['editar_id'] : null;
                ?>
                <tbody>
                    <?php while ($row = $clientes->fetch()): ?>
                        <tr>
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