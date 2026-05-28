<?php
session_start();
include '../../config/conexion.php';
$ruta_base = '../../../';
include '../../includes/header_index.php';

// 1. Si el usuario toca "Cancelar" o "Eliminar Orden", vaciamos la memoria y volvemos a compras.
if (isset($_GET['accion']) && $_GET['accion'] == 'limpiar') {
    unset($_SESSION['venta_registro']);
    unset($_SESSION['venta_temp']);
    header("Location: ventas.php");
    exit();
}

// 2. Inicializamos el carrito en la sesión si no existe
if (!isset($_SESSION['venta_registro'])) {
    $_SESSION['venta_registro'] = [];
}

// 3. Guardamos o recuperamos el proveedor en la sesión para que no se pierda al recargar la página
if (isset($_GET['id_cli'])) {
    $_SESSION['venta_temp'] = $_GET['id_cli'];
}
$id_cliente = $_SESSION['venta_temp'] ?? null;
$nombre_cliente = 'Cliente Desconocido';

if ($id_cliente) {
    $stmt = $conn->prepare("SELECT nombre FROM clientes WHERE id = :id");
    $stmt->execute(['id' => $id_cliente]);
    $cli = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($cli) $nombre_cliente = $cli['nombre'];
}

// Traemos los productos para el select
$stmt_prod = $conn->query("SELECT id, nombre, precio_venta FROM productos ORDER BY nombre ASC");
$productos = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

// 4. Procesamos los formularios (Agregar producto o Confirmar orden)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ACCIÓN A: Agregar un producto al carrito temporal
    if (isset($_POST['accion']) && $_POST['accion'] == 'agregar_producto') {
        $id_p = $_POST['producto'];
        $cant = $_POST['cantidad'];
        // $prec = $_POST['precio'];

        if (!empty($id_p) && $cant > 0) {
            // Buscamos el nombre del producto seleccionado para guardarlo
            $stmt_prod_info = $conn->prepare("SELECT nombre, precio_venta FROM productos WHERE id = ?");
            $stmt_prod_info->execute([$id_p]);
            $producto_info = $stmt_prod_info->fetch(PDO::FETCH_ASSOC);

            if ($producto_info) {
                $nombre_p = $producto_info['nombre'];
                $prec = $producto_info['precio_venta']; // Tomamos el precio del catálogo

                // Guardamos el producto en la memoria (sesión)
                $_SESSION['venta_registro'][] = [
                    'id' => $id_p,
                    'nombre' => $nombre_p,
                    'cantidad' => $cant,
                    'precio' => $prec,
                    'subtotal' => $cant * $prec
                ];
            }
        }
        // Recargamos la página para evitar que el formulario se reenvíe si tocan F5
        header("Location: guardar_venta.php");
        exit();
    }

    // ACCIÓN B: Crear la orden final en la base de datos
    if (isset($_POST['accion']) && $_POST['accion'] == 'crear_orden') {
        if (!empty($_SESSION['venta_registro'])) {
            $conn->beginTransaction();
            try {
                // Guardamos la compra general
                $total_final = $_POST['total_final'];
                $sql = "INSERT INTO ventas (id_cliente, nombre_cliente, fecha, total) VALUES (?, ?, NOW(), ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$id_cliente, $nombre_cliente, $total_final]);

                $nuevo_id = $conn->lastInsertId();

                // Guardamos los detalles leyendo lo que hay en la sesión y RESTAMOS STOCK
                $sql_det = "INSERT INTO detalle_ventas (id_venta, id_producto, nombre_producto, cantidad, precio_venta) VALUES (?, ?, ?, ?, ?)";
                $stmt_det = $conn->prepare($sql_det);

                $sql_stock = "UPDATE productos SET stock = stock - ? WHERE id = ?";
                $stmt_stock = $conn->prepare($sql_stock);

                foreach ($_SESSION['venta_registro'] as $item) {
                    // Insertamos renglón
                    $stmt_det->execute([
                        $nuevo_id,
                        $item['id'],
                        $item['nombre'],
                        $item['cantidad'],
                        $item['precio']
                    ]);

                    // Descontamos del stock físico
                    $stmt_stock->execute([$item['cantidad'], $item['id']]);
                }

                $conn->commit();

                // Vaciamos la memoria temporal y mandamos al detalle
                unset($_SESSION['venta_registro']);
                unset($_SESSION['venta_temp']);
                header("Location: detail_venta.php?id=" . $nuevo_id);
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
            <h2 class="texto-primario">Nueva Orden: <?php echo htmlspecialchars($nombre_cliente); ?></h2>

            <form action="guardar_venta.php" method="POST" style="margin-bottom: 30px;">
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

                    <div class="campo">
                        <button type="submit" class="btn-guardar">+ Agregar</button>
                    </div>
                </div>
            </form>

            <div>
                <h3 style="border-bottom: 1px solid #2A3B5C; padding-bottom: 10px; margin-bottom: 15px;">Productos en la Venta</h3>

                <ul style="list-style: none;">
                    <?php
                    $total_acumulado = 0;
                    if (!empty($_SESSION['venta_registro'])) {
                        foreach ($_SESSION['venta_registro'] as $item) {
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
                    <h2>Total Venta: <span class="texto-primario">$</span><span class="texto-total"><?php echo number_format($total_acumulado, 2, '.', ''); ?></span></h2>
                </div>
            </div>

            <form action="guardar_venta.php" method="POST">
                <input type="hidden" name="accion" value="crear_orden">
                <input type="hidden" name="total_final" value="<?php echo $total_acumulado; ?>">

                <div class="flex-gap-10" style="justify-content: center; margin-top: 30px;">
                    <button type="submit" class="btn-violeta" style="padding: 12px 25px; font-size: 16px;" <?php echo empty($_SESSION['venta_registro']) ? 'disabled' : ''; ?>>
                        Confirmar y Crear venta
                    </button>
                    <a href="guardar_venta.php?accion=limpiar" class="btn-cancelar" style="padding: 12px 25px; font-size: 16px;">
                        Cancelar
                    </a>
                    <a href="guardar_venta.php?accion=limpiar" class="btn-borrar" style="padding: 12px 25px; font-size: 16px;" onclick="return confirm('¿Seguro que querés descartar esta orden? Todo lo agregado se perderá.');">
                        Eliminar venta
                    </a>
                </div>
            </form>

        </article>
    </section>
</main>

<footer class="footer">