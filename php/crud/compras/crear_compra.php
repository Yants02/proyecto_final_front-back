<?php
session_start();
include '../../config/conexion.php';
$ruta_base = '../../../';
include '../../includes/header_index.php';

$id_proveedor = $_GET['id_prov'] ?? null;
$nombre_proveedor = 'Proveedor Desconocido';

if ($id_proveedor) {
    $stmt = $conn->prepare("SELECT nombre FROM proveedores WHERE id = :id");
    $stmt->execute(['id' => $id_proveedor]);
    $prov = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($prov) $nombre_proveedor = $prov['nombre'];
}

$stmt_prod = $conn->query("SELECT id, nombre FROM productos ORDER BY nombre ASC");
$productos = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn->beginTransaction();
    try {
        $sql = "INSERT INTO compras (id_proveedor, nombre_proveedor, fecha, total) VALUES (?, ?, NOW(), ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_POST['id_proveedor'], $_POST['nombre_proveedor'], $_POST['total_orden']]);
        
        $nuevo_id = $conn->lastInsertId();
        
        if (!empty($_POST['prod_ids'])) {
            $sql_det = "INSERT INTO detalle_compras (id_compra, id_producto, nombre_producto, cantidad, precio_costo) VALUES (?, ?, ?, ?, ?)";
            $stmt_det = $conn->prepare($sql_det);
            
            for ($i = 0; $i < count($_POST['prod_ids']); $i++) {
                $stmt_det->execute([
                    $nuevo_id,
                    $_POST['prod_ids'][$i],
                    $_POST['prod_nombres'][$i],
                    $_POST['prod_cants'][$i],
                    $_POST['prod_precios'][$i]
                ]);
            }
        }
        $conn->commit();
        header("Location: detail_compra.php?id=" . $nuevo_id);
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>

<main class="contenedor-principal">
    <section class="contenido">
        <article class="formulario-container">
            <h2 style="color: #50A2FF;">Nueva Orden: <?php echo htmlspecialchars($nombre_proveedor); ?></h2>
            
            <form action="crear_compra.php" method="POST" class="formulario">
                <input type="hidden" name="id_proveedor" value="<?php echo $id_proveedor; ?>">
                <input type="hidden" name="nombre_proveedor" value="<?php echo $nombre_proveedor; ?>">
                <input type="hidden" name="total_orden" id="input_total" value="0">

                <div style="display: flex; gap: 20px; flex-wrap: wrap; background: #050C24; padding: 20px; border-radius: 8px; border: 1px solid #2A3B5C;">
                    <div class="campo" style="flex: 2;">
                        <label>Producto:</label>
                        <select id="producto" class="campo-input">
                            <option value="">-- Elegir producto --</option>
                            <?php foreach ($productos as $prod): ?>
                                <option value="<?php echo $prod['id']; ?>"><?php echo $prod['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo" style="flex: 1;">
                        <label>Cantidad:</label>
                        <input type="number" id="cantidad" min="1" class="campo-input" placeholder="0">
                    </div>

                    <div class="campo" style="flex: 1;">
                        <label>Precio Unit.:</label>
                        <input type="number" id="precio" min="0" step="0.01" class="campo-input" placeholder="0.00">
                    </div>

                    <div class="campo" style="display: flex; align-items: flex-end;">
                        <button type="button" id="btn_agregar" class="btn-guardar" style="background: #155DFC;">+ Agregar</button>
                    </div>
                </div>

                <div style="margin-top: 30px;">
                    <h3 style="border-bottom: 1px solid #2A3B5C; padding-bottom: 10px;">Productos en la Orden</h3>
                    <ul id="lista_productos" style="list-style: none; padding: 0;">
                        </ul>
                    
                    <div style="text-align: right; margin-top: 20px;">
                        <h2 style="margin: 0;">Total Orden: <span style="color: #50A2FF;">$</span><span id="texto_total" style="color: #50A2FF;">0.00</span></h2>
                    </div>
                </div>

                <div id="datos_ocultos"></div>

                <div style="margin-top: 40px; display: flex; gap: 15px;">
                    <button type="submit" class="btn-guardar" style="background: #9810FA; padding: 15px 30px; font-size: 1.1rem;">Confirmar y Crear Orden</button>
                    <a href="compras.php" class="btn-cancelar" style="padding: 15px 30px; text-decoration: none; display: inline-block; text-align: center;">Cancelar</a>
                </div>
            </form>
        </article>
    </section>
</main>

<script>
let total = 0;

document.getElementById('btn_agregar').addEventListener('click', function() {
    let select = document.getElementById('producto');
    let id = select.value;
    let nombre = select.options[select.selectedIndex].text;
    let cant = parseFloat(document.getElementById('cantidad').value);
    let precio = parseFloat(document.getElementById('precio').value);

    if (!id || isNaN(cant) || cant <= 0 || isNaN(precio) || precio <= 0) {
        alert("Por favor, completa producto, cantidad y precio correctamente.");
        return;
    }

    let subtotal = cant * precio;
    total += subtotal;

    // Agregar a la lista visual con estilo
    let li = document.createElement('li');
    li.style = "background: #03081A; margin-bottom: 10px; padding: 15px; border-radius: 6px; border-left: 4px solid #155DFC; display: flex; justify-content: space-between;";
    li.innerHTML = `
        <span><strong>${cant}x</strong> ${nombre}</span>
        <span>$${precio.toFixed(2)} c/u <strong style="color: #50A2FF; margin-left: 15px;">$${subtotal.toFixed(2)}</strong></span>
    `;
    document.getElementById('lista_productos').appendChild(li);

    // Actualizar totales
    document.getElementById('texto_total').innerText = total.toFixed(2);
    document.getElementById('input_total').value = total.toFixed(2);

    // Inyectar inputs ocultos para el POST
    document.getElementById('datos_ocultos').innerHTML += `
        <input type="hidden" name="prod_ids[]" value="${id}">
        <input type="hidden" name="prod_nombres[]" value="${nombre}">
        <input type="hidden" name="prod_cants[]" value="${cant}">
        <input type="hidden" name="prod_precios[]" value="${precio}">
    `;

    // Limpiar campos y foco
    select.value = "";
    document.getElementById('cantidad').value = "";
    document.getElementById('precio').value = "";
    select.focus();
});
</script>