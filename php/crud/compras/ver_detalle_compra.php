<?php
session_start();
include '../../conexion.php';
$ruta_base = '../../../';
include '../../includes/header_index.php';

// Si venimos de compras.php, guardamos el proveedor en la sesión
if (isset($_GET['id_prov'])) {
    $_SESSION['compra_temp'] = [
        'id_prov' => $_GET['id_prov'],
        'productos' => [] // Aquí guardaremos los items
    ];
}

// Si no hay sesión iniciada, volvemos al listado
if (!isset($_SESSION['compra_temp'])) {
    header("Location: compras.php");
    exit();
}

// Puede venir el ID de una compra existente o el ID de un proveedor para una nueva
// $id_compra = $_GET['id'] ?? 0;
// $id_prov = $_GET['id_prov'] ?? 0;

$id_prov = $_SESSION['compra_temp']['id_prov'];

// Buscamos el nombre del proveedor para mostrarlo
$stmt = $conn->prepare("SELECT nombre FROM proveedores WHERE id = :id");
$stmt->execute(['id' => $id_prov]);
$nombre_p = $stmt->fetchColumn();

$productos = $conn->query("SELECT id, nombre FROM productos ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="contenedor-principal">
    <section class="contenido">
        <h2>Nueva Orden</h2>
        <p>Proveedor: <strong><?php echo $nombre_p; ?></strong></p>

        <article id="formulario-alta">
            <form action="guardar_detalle_compra.php" method="POST" class="formulario">
                <div class="campo">
                    <label>Producto:</label>
                    <select name="id_producto" class="campo-input" required>
                        <option value="">-- Seleccione Producto --</option>
                        <?php foreach ($productos as $p): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo $p['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="campo">
                    <label>Cantidad:</label>
                    <input type="number" name="cantidad" class="campo-input" min="1" required>
                </div>

                <div class="campo">
                    <label>Precio Costo (Unitario):</label>
                    <input type="number" name="precio_costo" class="campo-input" step="0.01" required>
                </div>
                <button type="submit" class="btn-guardar">Agregar al Detalle</button>
            </form>
        </article>
        <article id="tabla-listado">
            <h3>Productos en esta orden</h3>
            <table class="tabla-datos">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cant.</th>
                        <th>Costo Unit.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_orden = 0;
                    $items_carrito = $_SESSION['compra_temp']['productos'];
                    // Recorrer session
                    foreach ($items_carrito as $d):
                        $subtotal = $d['cantidad'] * $d['precio_costo'];
                        $total_orden += $subtotal;
                    ?>
                        <tr>
                            <td><?php echo $d['nombre_producto']; ?></td>
                            <td><?php echo $d['cantidad']; ?></td>
                            <td>$<?php echo number_format($d['precio_costo'], 2, ',', '.'); ?></td>
                            <td>$<?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($items_carrito)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No hay productos cargados aún.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0;">Total de la Orden: $<?php echo number_format($total_orden, 2, ',', '.'); ?></h3>

                <div style="display: flex; gap: 10px;">
                    <a href="finalizar_transaccion.php" class="btn-guardar" style="background: #2ecc71; text-decoration: none;">
                        Confirmar compra
                    </a>
                    <a href="compras.php" class="btn-cancelar" style="text-decoration: none;">
                        Cancelar y salir
                    </a>
                </div>
            </div>
        </article>
    </section>
</main>