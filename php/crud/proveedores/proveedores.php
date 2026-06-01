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
$resultado = false; 
$error_bd = null;
$proveedores = [];

try {
    $usuario_id = $_SESSION['usuario_id'];
    $sql = "SELECT id, nombre, telefono, email, direccion FROM proveedores WHERE usuario_id = :usuario_id";
    $stmt = $conn->prepare($sql);
    $stmt -> execute([':usuario_id' => $usuario_id]);
    $proveedores = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    $resultado = true;
} catch (PDOException $e) {
    $resultado = false;
    $error_bd = "Fallo en BD: " . $e->getMessage();
}
?>

    <main class="contenedor-principal">

        <aside class="sidebar">
            <h3>Acciones</h3>
            <ul>
                <li><a href="#formulario-alta">Nuevo Proveedor</a></li>
                <li><a href="#tabla-listado">Ver Proveedores</a></li>
                <li><a href="../../../index.php">Volver al Inicio</a></li>
            </ul>
        </aside>

        <section class="contenido">
            
            <?php if(isset($error_bd)): ?>
                <div style="background-color: #ffb3b3; color: #990000; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                    <strong>¡Atención!</strong> La consulta falló. Revisá si la tabla 'proveedores' existe en phpMyAdmin. <br>
                    Detalle técnico: <?php echo $error_bd; ?>
                </div>
            <?php endif; ?>

            <article id="formulario-alta">
                <h2>Cargar Nuevo Proveedor</h2>
                <form action="guardar_proveedores.php" method="POST" class="formulario">
                    
                    <div class="campo">
                        <label>Nombre del Proveedor:</label>
                        <input type="text" name="nombre" placeholder="Ej: Empresa S.A." required>
                    </div>

                    <div class="campo">
                        <label>Teléfono del Proveedor:</label>
                        <input type="number" name="telefono" placeholder="Número de contacto">
                    </div>

                    <div class="campo">
                        <label>Email del Proveedor:</label>
                        <input type="email" name="email" placeholder="mail@proveedor.com" required>
                    </div>

                    <div class="campo">
                        <label>Dirección del Proveedor:</label>
                        <input type="text" name="direccion" placeholder="Calle 1234" required>
                    </div>

                    <div style="margin-top: 10px;">
                        <button type="submit" class="btn-guardar">Guardar Proveedor</button>
                    </div>
                </form>
            </article>

            <hr>
            <article id="tabla-listado">
                <h2>Proveedores Actuales</h2>
                <a href="../../acciones/exportar_excel.php?tipo=proveedores" class="btn-guardar" style="background-color: #27ae60; display: flex; align-items: center; gap: 8px; text-decoration: none;">
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
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <?php
                    $id_a_editar = isset($_GET['editar_id']) ? (int)$_GET['editar_id'] : null;
                    ?>

                    <tbody>
                        <?php if ($resultado): ?>
                            <?php foreach ($proveedores as $row): ?>
                                <tr>
                                    <?php if ($id_a_editar !== null && $id_a_editar === (int)$row['id']): ?>
                                        <form action="actualizar_proveedores.php" method="POST">
                                            <td><strong>#<?php echo $row['id']; ?></strong> <input type="hidden" name="id" value="<?php echo $row['id']; ?>"></td>
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
                                        <td>#<?php echo $row['id']; ?></td>
                                        <td><?php echo $row['nombre']; ?></td> 
                                        <td><?php echo $row['telefono'] ? $row['telefono'] : 'N/A'; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['direccion']; ?></td>
                                        <td>
                                            <a href="proveedores.php?editar_id=<?php echo $row['id']; ?>" class="btn-editar">Editar</a>
                                            
                                            <form action="eliminar_proveedores.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn-borrar">X</button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">No hay datos disponibles o error de conexión.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </article>

        </section>
    </main>

    <?php include '../../includes/footer.php'; ?>
</body>
</html>