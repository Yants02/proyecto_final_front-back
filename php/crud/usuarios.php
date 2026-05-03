<?php
include '../conexion.php';

$ruta_base = '../../'; 
include '../includes/auth_check.php';
include '../includes/header_index.php';

try {
    $query = "SELECT * FROM usuarios ORDER BY id DESC";
    $resultado = $conn->query($query);
} catch (PDOException $e) {
    echo "Error en el sistema: " . $e->getMessage();
}
?>

    <main class="contenedor-principal">
        <aside class="sidebar">
            <h3>Gestión de Accesos</h3>
            <ul>
                <li><a href="#formulario-alta">Nuevo Usuario</a></li>
                <li><a href="#tabla-listado">Lista de Usuarios</a></li>
                <li><a href="../../index.php">Volver al Inicio</a></li>
            </ul>
        </aside>

        <section class="contenido">
            <article id="formulario-alta">
                <h2>Registrar Nuevo Usuario</h2>
                <form action="guardar_usuario.php" method="POST" class="formulario">
                    
                    <div class="campo">
                        <label>Nombre Completo:</label>
                        <input type="text" name="nombre" placeholder="Ej: Juan Pérez" required>
                    </div>

                    <div class="campo">
                        <label>Correo Electrónico (será su Login):</label>
                        <input type="email" name="email" placeholder="usuario@correo.com" required>
                    </div>

                    <div class="campo">
                        <label>Contraseña:</label>
                        <input type="password" name="password" placeholder="Mínimo 6 caracteres" required>
                    </div>

                    <div class="campo">
                        <label>Rol del Usuario:</label>
                        <select name="rol" class="campo-input" required 
                                style="width: 100%; padding: 12px; border-radius: 8px; background: #03081A; color: #ededed; border: 1px solid #2A3B5C;">
                            <option value="ventas">Ventas (Acceso limitado)</option>
                            <option value="administrador">Administrador (Acceso total)</option>
                        </select>
                    </div>

                    <div style="margin-top: 15px;">
                        <button type="submit" class="btn-guardar">Crear Cuenta</button>
                    </div>
                </form>
            </article>

            <hr>

            <article id="tabla-listado">
                <h2>Cuentas de Usuario</h2>
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Fecha Registro</th>
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
                                    <form action="actualizar_usuario.php" method="POST">
                                        <td>#<?php echo $row['id']; ?> <input type="hidden" name="id" value="<?php echo $row['id']; ?>"></td>
                                        <td><input type="text" name="nuevo-nombre" value="<?php echo $row['nombre']; ?>"></td>
                                        <td><input type="email" name="nuevo-email" value="<?php echo $row['email']; ?>"></td>
                                        <td>
                                            <select name="nuevo-rol">
                                                <option value="ventas" <?php echo $row['rol'] == 'ventas' ? 'selected' : ''; ?>>Ventas</option>
                                                <option value="administrador" <?php echo $row['rol'] == 'administrador' ? 'selected' : ''; ?>>Admin</option>
                                            </select>
                                        </td>
                                        <td>---</td>
                                        <td>
                                            <button type="submit" class="btn-guardar">OK</button>
                                            <a href="usuarios.php" class="btn-cancelar">X</a>
                                        </td>
                                    </form>
                                <?php else: ?>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td>
                                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: <?php echo $row['rol'] == 'administrador' ? '#9b59b6' : '#2ecc71'; ?>;">
                                            <?php echo strtoupper($row['rol']); ?>
                                        </span>
                                    </td>
                                    <td style="font-size: 13px; color: #90A1B9;">
                                        <?php echo date("d/m/Y", strtotime($row['created_at'])); ?>
                                    </td>
                                    <td>
                                        <a href="usuarios.php?editar_id=<?php echo $row['id']; ?>" class="btn-editar">Editar</a>
                                        <?php if($row['id'] != 1):?>
                                            <form action="eliminar_usuario.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn-borrar">X</button>
                                            </form>
                                        <?php endif; ?>
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
        <p>2026 - Control de Accesos - Sistema de Gestión IFTS 4</p>
    </footer>

    <script src="../../js/validaciones.js"></script>
</body>
</html>