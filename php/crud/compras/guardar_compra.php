<?php
session_start();
include '../../config/conexion.php';
$ruta_base = '../../../';
include '../../includes/header_index.php';
include __DIR__ . '/../../config/verificar_administrador.php';

// Cancelar o eliminar orden
if (isset($_GET['accion']) && $_GET['accion'] == 'limpiar') {
    unset($_SESSION['carrito_orden']);
    unset($_SESSION['prov_temp']);
    header("Location: compras.php");
    exit();
}

// Carrito 
if (!isset($_SESSION['carrito_orden'])) {
    $_SESSION['carrito_orden'] = [];
}

// Guardar proveedor
if (isset($_GET['id_prov'])) {
    $_SESSION['prov_temp'] = $_GET['id_prov'];
}
$id_proveedor = $_SESSION['prov_temp'] ?? null;
$nombre_proveedor = 'Proveedor desconocido';

if ($id_proveedor) {
    $stmt = $conn->prepare("SELECT nombre FROM proveedores WHERE id = :id");
    $stmt->execute(['id' => $id_proveedor]);
    $prov = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($prov) $nombre_proveedor = $prov['nombre'];
}

// Productos 
$stmt_prod = $conn->query("SELECT id, nombre FROM productos ORDER BY nombre ASC");
$productos = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

// Agregar producto - confirmar orden
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['accion']) && $_POST['accion'] == 'agregar_producto') {
        $id_p = $_POST['producto'];
        $cant = $_POST['cantidad'];
        $prec = $_POST['precio'];

        if (!empty($id_p) && $cant > 0 && $prec > 0) {
            $stmt_nom = $conn->prepare("SELECT nombre FROM productos WHERE id = ?");
            $stmt_nom->execute([$id_p]);
            $nombre_p = $stmt_nom->fetchColumn();

            $_SESSION['carrito_orden'][] = [
                'id' => $id_p,
                'nombre' => $nombre_p,
                'cantidad' => $cant,
                'precio' => $prec,
                'subtotal' => $cant * $prec
            ];
        }
        header("Location: guardar_compra.php");
        exit();
    }

    // Confirmar orden
    if (isset($_POST['accion']) && $_POST['accion'] == 'crear_orden') {
        if (!empty($_SESSION['carrito_orden'])) {
            $conn->beginTransaction();
            try {
                $total_final = $_POST['total_final'];
                $sql = "INSERT INTO compras (id_proveedor, nombre_proveedor, fecha, total) VALUES (?, ?, NOW(), ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$id_proveedor, $nombre_proveedor, $total_final]);

                $nuevo_id = $conn->lastInsertId();

                $sql_det = "INSERT INTO detalle_compras (id_compra, id_producto, nombre_producto, cantidad, precio_costo) VALUES (?, ?, ?, ?, ?)";
                $stmt_det = $conn->prepare($sql_det);

                $sql_stock = "UPDATE productos SET stock = stock + ? WHERE id = ?";
                $stmt_stock = $conn->prepare($sql_stock);

                foreach ($_SESSION['carrito_orden'] as $item) {
                    $stmt_det->execute([
                        $nuevo_id,
                        $item['id'],
                        $item['nombre'],
                        $item['cantidad'],
                        $item['precio']
                    ]);

                    $stmt_stock->execute([
                        $item['cantidad'],
                        $item['id']
                    ]);
                }

                $conn->commit();

                unset($_SESSION['carrito_orden']);
                unset($_SESSION['prov_temp']);
                header("Location: detail_compra.php?id=" . $nuevo_id);
                exit();
            } catch (Exception $e) {
                $conn->rollBack();
                echo "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<main class="contenedor-principal" style="justify-content: center;">
    <section class="contenido" style="max-width: 850px; margin: 0 auto;">
        <article>
            <h2 class="texto-primario">Nueva Orden: <?php echo htmlspecialchars($nombre_proveedor); ?></h2>

            <form action="guardar_compra.php" method="POST" style="margin-bottom: 30px;">
                <input type="hidden" name="accion" value="agregar_producto">

                <div class="caja-oscura flex-row-wrap" style="align-items: flex-end;">
                    <div class="campo" style="flex: 2;">
                        <label>Producto:</label>
                        <select name="producto" class="campo-input" required>
                            <option value="">-- Elegir producto --</option>
                            <?php foreach ($productos as $prod): ?>
                                <option value="<?php echo $prod['id']; ?>"><?php echo $prod['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo" style="flex: 1;">
                        <label>Cantidad:</label>
                        <input type="number" name="cantidad" min="1" class="campo-input" placeholder="0" required>
                    </div>

                    <div class="campo" style="flex: 1;">
                        <label>Precio Unit.:</label>
                        <input type="number" name="precio" min="0" step="0.01" class="campo-input" placeholder="0.00" required>
                    </div>

                    <div class="campo">
                        <button type="submit" class="btn-guardar">+ Agregar</button>
                    </div>
                </div>
            </form>

            <div>
                <h3 style="border-bottom: 1px solid #2A3B5C; padding-bottom: 10px; margin-bottom: 15px;">Productos en la Orden</h3>

                <ul style="list-style: none;">
                    <?php
                    $total_acumulado = 0;
                    if (!empty($_SESSION['carrito_orden'])) {
                        foreach ($_SESSION['carrito_orden'] as $item) {
                            $total_acumulado += $item['subtotal'];
                            echo '<li class="item-lista">';
                            echo '<span><strong>' . $item['cantidad'] . 'x</strong> ' . $item['nombre'] . '</span>';
                            echo '<span>$' . number_format($item['precio'], 2) . ' c/u <strong class="texto-primario" style="margin-left: 15px;">$' . number_format($item['subtotal'], 2) . '</strong></span>';
                            echo '</li>';
                        }
                    } else {
                        echo '<li style="color: #90A1B9; text-align: center; padding: 20px;">No hay productos agregados aún.</li>';
                    }
                    ?>
                </ul>

                <div style="text-align: right; margin-top: 20px;">
                    <h2>Total Orden: <span class="texto-primario">$</span><span class="texto-total"><?php echo number_format($total_acumulado, 2, '.', ''); ?></span></h2>
                </div>
            </div>

            <form action="guardar_compra.php" method="POST">
                <input type="hidden" name="accion" value="crear_orden">
                <input type="hidden" name="total_final" value="<?php echo $total_acumulado; ?>">

                <div class="flex-gap-10" style="justify-content: center; margin-top: 30px;">
                    <button type="submit" class="btn-violeta" style="padding: 12px 25px; font-size: 16px;" <?php echo empty($_SESSION['carrito_orden']) ? 'disabled' : ''; ?>>
                        Confirmar y Crear Orden
                    </button>
                    <a href="guardar_compra.php?accion=limpiar" class="btn-cancelar" style="padding: 12px 25px; font-size: 16px;">
                        Cancelar
                    </a>
                    <a href="guardar_compra.php?accion=limpiar" class="btn-borrar" style="padding: 12px 25px; font-size: 16px;" onclick="return confirm('¿Seguro que querés descartar esta orden? Todo lo agregado se perderá.');">
                        Eliminar Orden de Compra
                    </a>
                </div>
            </form>

        </article>
    </section>
</main>

<footer class="footer">