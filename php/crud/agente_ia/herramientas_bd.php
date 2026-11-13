<?php
/*
 * Herramientas que el agente de IA puede usar para consultar la base de datos.
 *
 * Gemini NO escribe SQL: solo elige una de estas funciones y le pasa
 * parámetros. Cada función ejecuta una consulta fija, de SOLO LECTURA y
 * con parámetros preparados (PDO). La tabla "usuarios" no se expone nunca.
 *
 * Permisos (igual que el menú del sistema):
 *   - administrador: todas las herramientas
 *   - ventas:        solo las de ventas
 *
 * Productos, clientes y proveedores se filtran por usuario_id, igual que
 * sus módulos. Ventas y compras no, igual que sus módulos.
 */

// ---------------------------------------------------------------------
// Utilidades
// ---------------------------------------------------------------------

function limite_seguro($valor, int $defecto = 20, int $max = 50): int
{
    $n = is_numeric($valor) ? (int)$valor : $defecto;
    return max(1, min($max, $n));
}

/** Agrega al WHERE el rango desde/hasta (formato AAAA-MM-DD, ambos inclusive). */
function agregar_rango_fechas(array $args, string $columna, array &$where, array &$params): void
{
    $sufijos = ['desde' => ' 00:00:00', 'hasta' => ' 23:59:59'];
    $operador = ['desde' => '>=', 'hasta' => '<='];

    foreach ($sufijos as $campo => $sufijo) {
        if (empty($args[$campo])) {
            continue;
        }
        $texto = (string)$args[$campo];
        $fecha = DateTime::createFromFormat('!Y-m-d', $texto);
        if (!$fecha || $fecha->format('Y-m-d') !== $texto) {
            throw new InvalidArgumentException("El campo '$campo' debe tener formato AAAA-MM-DD (por ejemplo 2026-06-30).");
        }
        $where[] = "$columna {$operador[$campo]} :f_$campo";
        $params[":f_$campo"] = $texto . $sufijo;
    }
}

function agregar_texto_like(array $args, string $campo_arg, string $columna, array &$where, array &$params): void
{
    $texto = trim((string)($args[$campo_arg] ?? ''));
    if ($texto !== '') {
        $where[] = "$columna LIKE :t_$campo_arg";
        $params[":t_$campo_arg"] = '%' . mb_substr($texto, 0, 100) . '%';
    }
}

function ejecutar_consulta(PDO $conn, string $sql, array $params): array
{
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function resultado_lista(string $clave, array $filas, int $limite): array
{
    $res = ['cantidad' => count($filas), $clave => $filas];
    if (count($filas) >= $limite) {
        $res['aviso'] = "Resultado limitado a $limite filas; puede haber más.";
    }
    return $res;
}

function donde(array $where): string
{
    return $where ? 'WHERE ' . implode(' AND ', $where) : '';
}

// ---------------------------------------------------------------------
// Herramientas (cada una recibe la conexión, los argumentos y el id de usuario)
// ---------------------------------------------------------------------

function herr_buscar_productos(PDO $conn, array $args, int $uid): array
{
    $where = ['usuario_id = :uid'];
    $params = [':uid' => $uid];
    agregar_texto_like($args, 'texto', 'nombre', $where, $params);

    $bajo = !empty($args['solo_bajo_stock']);
    if ($bajo) {
        $umbral = is_numeric($args['umbral_stock'] ?? null) ? max(0, (int)$args['umbral_stock']) : 10;
        $where[] = 'stock <= :umbral';
        $params[':umbral'] = $umbral;
    }

    $limite = limite_seguro($args['limite'] ?? null, 20, 50);
    $orden = $bajo ? 'stock ASC, nombre ASC' : 'nombre ASC';
    $sql = "SELECT id, nombre, precio_venta, stock FROM productos " . donde($where) . " ORDER BY $orden LIMIT $limite";
    return resultado_lista('productos', ejecutar_consulta($conn, $sql, $params), $limite);
}

function herr_listar_ventas(PDO $conn, array $args, int $uid): array
{
    $where = [];
    $params = [];
    agregar_rango_fechas($args, 'fecha', $where, $params);
    agregar_texto_like($args, 'cliente', 'nombre_cliente', $where, $params);

    $limite = limite_seguro($args['limite'] ?? null, 20, 50);
    $sql = "SELECT id, nombre_cliente, fecha, total FROM ventas " . donde($where) . " ORDER BY fecha DESC LIMIT $limite";
    return resultado_lista('ventas', ejecutar_consulta($conn, $sql, $params), $limite);
}

function herr_resumen_ventas(PDO $conn, array $args, int $uid): array
{
    $where = [];
    $params = [];
    agregar_rango_fechas($args, 'fecha', $where, $params);

    $sql = "SELECT COUNT(*) AS cantidad_ventas, COALESCE(SUM(total), 0) AS total_facturado FROM ventas " . donde($where);
    $fila = ejecutar_consulta($conn, $sql, $params)[0] ?? ['cantidad_ventas' => 0, 'total_facturado' => 0];

    $cantidad = (int)$fila['cantidad_ventas'];
    $total = (float)$fila['total_facturado'];
    return [
        'cantidad_ventas' => $cantidad,
        'total_facturado' => $total,
        'ticket_promedio' => $cantidad > 0 ? round($total / $cantidad, 2) : 0,
    ];
}

function herr_productos_mas_vendidos(PDO $conn, array $args, int $uid): array
{
    $where = [];
    $params = [];
    agregar_rango_fechas($args, 'v.fecha', $where, $params);

    $limite = limite_seguro($args['limite'] ?? null, 5, 20);
    $sql = "SELECT d.nombre_producto, SUM(d.cantidad) AS unidades_vendidas, SUM(d.cantidad * d.precio_venta) AS ingresos
            FROM detalle_ventas d
            JOIN ventas v ON v.id = d.id_venta " . donde($where) . "
            GROUP BY d.nombre_producto
            ORDER BY unidades_vendidas DESC
            LIMIT $limite";
    return resultado_lista('productos', ejecutar_consulta($conn, $sql, $params), $limite);
}

function herr_detalle_venta(PDO $conn, array $args, int $uid): array
{
    $id = (int)($args['id_venta'] ?? 0);
    if ($id <= 0) {
        throw new InvalidArgumentException("Falta el número de venta (id_venta).");
    }
    $venta = ejecutar_consulta($conn, "SELECT id, nombre_cliente, fecha, total FROM ventas WHERE id = :id", [':id' => $id]);
    if (!$venta) {
        return ['error' => "No existe la venta $id."];
    }
    $items = ejecutar_consulta(
        $conn,
        "SELECT nombre_producto, cantidad, precio_venta FROM detalle_ventas WHERE id_venta = :id",
        [':id' => $id]
    );
    return ['venta' => $venta[0], 'items' => $items];
}

function herr_listar_compras(PDO $conn, array $args, int $uid): array
{
    $where = [];
    $params = [];
    agregar_rango_fechas($args, 'fecha', $where, $params);
    agregar_texto_like($args, 'proveedor', 'nombre_proveedor', $where, $params);

    $limite = limite_seguro($args['limite'] ?? null, 20, 50);
    $sql = "SELECT id, nombre_proveedor, fecha, total FROM compras " . donde($where) . " ORDER BY fecha DESC LIMIT $limite";
    return resultado_lista('compras', ejecutar_consulta($conn, $sql, $params), $limite);
}

function herr_buscar_clientes(PDO $conn, array $args, int $uid): array
{
    $where = ['usuario_id = :uid'];
    $params = [':uid' => $uid];
    agregar_texto_like($args, 'texto', 'nombre', $where, $params);

    // No se incluye el DNI a propósito: son datos personales que viajan a Google.
    $limite = limite_seguro($args['limite'] ?? null, 20, 50);
    $sql = "SELECT id, nombre, email FROM clientes " . donde($where) . " ORDER BY nombre ASC LIMIT $limite";
    return resultado_lista('clientes', ejecutar_consulta($conn, $sql, $params), $limite);
}

function herr_buscar_proveedores(PDO $conn, array $args, int $uid): array
{
    $where = ['usuario_id = :uid'];
    $params = [':uid' => $uid];
    agregar_texto_like($args, 'texto', 'nombre', $where, $params);

    $limite = limite_seguro($args['limite'] ?? null, 20, 50);
    $sql = "SELECT id, nombre, telefono, email, direccion FROM proveedores " . donde($where) . " ORDER BY nombre ASC LIMIT $limite";
    return resultado_lista('proveedores', ejecutar_consulta($conn, $sql, $params), $limite);
}

// ---------------------------------------------------------------------
// Catálogo: qué herramientas existen, quién las puede usar y cómo
// se las describimos a Gemini
// ---------------------------------------------------------------------

function propiedad_fecha(string $descripcion): array
{
    return ['type' => 'STRING', 'description' => $descripcion . ' Formato AAAA-MM-DD.'];
}

function catalogo_herramientas(): array
{
    $rango = [
        'desde' => propiedad_fecha('Fecha inicial, inclusive.'),
        'hasta' => propiedad_fecha('Fecha final, inclusive.'),
    ];
    $limite = ['type' => 'INTEGER', 'description' => 'Máximo de filas a devolver (hasta 50).'];

    return [
        'buscar_productos' => [
            'roles' => ['administrador'],
            'descripcion' => 'Lista productos con su precio de venta y stock. Sirve para consultar stock, precios o encontrar productos con poco stock.',
            'propiedades' => [
                'texto' => ['type' => 'STRING', 'description' => 'Parte del nombre del producto a buscar.'],
                'solo_bajo_stock' => ['type' => 'BOOLEAN', 'description' => 'true para listar solo productos con poco stock.'],
                'umbral_stock' => ['type' => 'INTEGER', 'description' => 'Con solo_bajo_stock: se consideran de bajo stock los que tienen esta cantidad o menos. Por defecto 10.'],
                'limite' => $limite,
            ],
        ],
        'listar_ventas' => [
            'roles' => ['administrador', 'ventas'],
            'descripcion' => 'Lista ventas (las más recientes primero) con cliente, fecha y total. Se puede filtrar por fechas y por cliente.',
            'propiedades' => $rango + [
                'cliente' => ['type' => 'STRING', 'description' => 'Parte del nombre del cliente.'],
                'limite' => $limite,
            ],
        ],
        'resumen_ventas' => [
            'roles' => ['administrador', 'ventas'],
            'descripcion' => 'Devuelve cantidad de ventas, total facturado y ticket promedio en un período. Sin fechas, usa todo el historial.',
            'propiedades' => $rango,
        ],
        'productos_mas_vendidos' => [
            'roles' => ['administrador', 'ventas'],
            'descripcion' => 'Ranking de productos más vendidos (unidades e ingresos) en un período.',
            'propiedades' => $rango + [
                'limite' => ['type' => 'INTEGER', 'description' => 'Cuántos productos del ranking devolver (por defecto 5, máximo 20).'],
            ],
        ],
        'detalle_venta' => [
            'roles' => ['administrador', 'ventas'],
            'descripcion' => 'Muestra los productos de una venta puntual, a partir de su número.',
            'propiedades' => [
                'id_venta' => ['type' => 'INTEGER', 'description' => 'Número de la venta.'],
            ],
            'requeridos' => ['id_venta'],
        ],
        'listar_compras' => [
            'roles' => ['administrador'],
            'descripcion' => 'Lista compras a proveedores (las más recientes primero) con proveedor, fecha y total.',
            'propiedades' => $rango + [
                'proveedor' => ['type' => 'STRING', 'description' => 'Parte del nombre del proveedor.'],
                'limite' => $limite,
            ],
        ],
        'buscar_clientes' => [
            'roles' => ['administrador'],
            'descripcion' => 'Busca clientes por nombre y devuelve su nombre y email.',
            'propiedades' => [
                'texto' => ['type' => 'STRING', 'description' => 'Parte del nombre del cliente.'],
                'limite' => $limite,
            ],
        ],
        'buscar_proveedores' => [
            'roles' => ['administrador'],
            'descripcion' => 'Busca proveedores por nombre y devuelve teléfono, email y dirección.',
            'propiedades' => [
                'texto' => ['type' => 'STRING', 'description' => 'Parte del nombre del proveedor.'],
                'limite' => $limite,
            ],
        ],
    ];
}

/** Declaraciones en el formato que espera Gemini, solo las que el rol puede usar. */
function declaraciones_para_rol(string $rol): array
{
    $declaraciones = [];
    foreach (catalogo_herramientas() as $nombre => $h) {
        if (!in_array($rol, $h['roles'], true)) {
            continue;
        }
        $parametros = ['type' => 'OBJECT', 'properties' => $h['propiedades']];
        if (!empty($h['requeridos'])) {
            $parametros['required'] = $h['requeridos'];
        }
        $declaraciones[] = [
            'name' => $nombre,
            'description' => $h['descripcion'],
            'parameters' => $parametros,
        ];
    }
    return $declaraciones;
}

/** Ejecuta la herramienta pedida por Gemini, verificando el permiso del rol. */
function ejecutar_herramienta(PDO $conn, string $nombre, $args, string $rol, int $usuario_id): array
{
    $catalogo = catalogo_herramientas();

    if (!isset($catalogo[$nombre]) || !in_array($rol, $catalogo[$nombre]['roles'], true)) {
        return ['error' => 'No tenés permiso para consultar esa información o la herramienta no existe.'];
    }

    try {
        $funcion = 'herr_' . $nombre;
        return $funcion($conn, is_array($args) ? $args : [], $usuario_id);
    } catch (InvalidArgumentException $e) {
        return ['error' => $e->getMessage()];
    } catch (PDOException $e) {
        error_log('[agente_ia] Error SQL en ' . $nombre . ': ' . $e->getMessage());
        return ['error' => 'No se pudo consultar la base de datos en este momento.'];
    }
}
