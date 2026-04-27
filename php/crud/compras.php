<?php
include '../conexion.php';

try {
    $query = "SELECT * FROM compras ORDER BY id DESC";
    $resultado = $conn->query($query);
    // Obtener los proveedores para el <select>
    $sql_prov = "SELECT id, nombre FROM proveedores ORDER BY nombre ASC";
    $stmt_prov = $conn->query($sql_prov);
    $proveedores = $stmt_prov->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al consultar clientes: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Compras - Sistema IFTS 4</title>
    <link rel="stylesheet" href="../../css/estilos.css">
</head>

<body>
    <main class="contenedor-principal">
        <section class="contenido">

            <article id="formulario-alta">
                <h2>Cargar Nueva compra</h2>
                <form action="guardar_compra.php" method="POST">

                    <label for="id_proveedor">Seleccionar Proveedor:</label>
                    <select name="id_proveedor" id="id_proveedor" required>
                        <option value="">-- Seleccione un proveedor --</option>
                        <?php foreach ($proveedores as $prov): ?>
                            <option value="<?php echo $prov['id']; ?>">
                                <?php echo $prov['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <br><br>

                    <button type="submit">Crear orden</button>
                    <a href="compras.php">Cancelar</a>
                </form>
            </article>

            <article id="tabla-listado">
                <h2>Oredenes de compra</h2>
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID proveedor</th>
                            <th>Nombre proveedor</th>
                            <th>Fecha</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado->fetch()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['id_proveedor'];
                                    if (is_null($row['id_proveedor'])) {
                                        echo " <small>(ELIMINADO)</small>";
                                    } ?></td>
                                <td><?php echo $row['nombre_proveedor']; ?></td>
                                <td><?php echo $row['fecha']; ?></td>
                                <td><?php echo $row['total']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </article>
        </section>
    </main>

    <footer class="footer">
        <p> 2026 - Panel de Control de compras</p>
    </footer>

    <script src="../../js/validaciones.js"></script>
</body>

</html>