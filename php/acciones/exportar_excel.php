<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    die("Acceso denegado. Por favor, iniciá sesión.");
}

include '../config/conexion.php';
$usuario_id = $_SESSION['usuario_id'];

// Captura el parámetro que viene en la URL. Si no viene nada, queda vacío.
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

// Variables vacías que vamos a llenar dependiendo del tipo
$nombre_archivo = "";
$titulos = [];
$sql = "";
// Casi todos filtran por usuario_id, excepto la tabla de usuarios generales
$parametros = [':usuario_id' => $usuario_id]; 

// Evaluamos qué nos pidieron exportar
switch ($tipo) {
    case 'productos':
        $nombre_archivo = 'Inventario_Productos';
        $titulos = ['ID Producto', 'Nombre', 'Precio Venta', 'Stock', 'Total Valorizado'];
        // Multiplicamos en SQL directamente para ahorrar código en PHP
        $sql = "SELECT id, nombre, precio_venta, stock, (precio_venta * stock) AS total_valorizado FROM productos WHERE usuario_id = :usuario_id ORDER BY id DESC";
        break;

    case 'proveedores':
        $nombre_archivo = 'Listado_Proveedores';
        $titulos = ['ID Proveedor', 'Nombre', 'Teléfono', 'Email', 'Dirección'];
        $sql = "SELECT id, nombre, telefono, email, direccion FROM proveedores WHERE usuario_id = :usuario_id ORDER BY id DESC";
        break;

    case 'clientes':
        $nombre_archivo = 'Base_Clientes';
        $titulos = ['ID Cliente', 'DNI', 'Nombre', 'Email'];
        $sql = "SELECT id, dni, nombre, email FROM clientes WHERE usuario_id = :usuario_id ORDER BY id DESC";
        break;

    case 'compras':
        $nombre_archivo = 'Historial_Compras';
        $titulos = ['ID Compra', 'Proveedor', 'Fecha', 'Total Gasto'];
        $sql = "SELECT id, nombre_proveedor, fecha, total FROM compras WHERE usuario_id = :usuario_id ORDER BY id DESC";
        break;

    case 'usuarios':
        $nombre_archivo = 'Usuarios_Sistema';
        $titulos = ['ID Usuario', 'Nombre', 'Email', 'Rol', 'Fecha Creación'];
        // Los usuarios no tienen "dueño", así que traemos todos (útil para el admin)
        $sql = "SELECT id, nombre, email, rol, created_at FROM usuarios ORDER BY id ASC";
        $parametros = []; // Vaciamos los parámetros porque no filtramos por usuario_id
        break;

    default:
        die("Tipo de exportación no válido o no especificado.");
}

// Generar el archivo
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $nombre_archivo . '_' . date('Y-m-d') . '.csv');

$salida = fopen('php://output', 'w');
fprintf($salida, chr(0xEF).chr(0xBB).chr(0xBF));


fputcsv($salida, $titulos, ';');

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($parametros);

    // Bucle genérico: Sin importar qué tabla sea, vuelca todos sus datos línea por línea
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // array_values saca solo los datos crudos para el CSV
        fputcsv($salida, array_values($row), ';');
    }

} catch (PDOException $e) {
    fputcsv($salida, ['Error en la consulta:', $e->getMessage()], ';');
}

fclose($salida);
exit();
?>