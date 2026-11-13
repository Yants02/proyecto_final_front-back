<?php
/*
 * Diagnóstico del Agente de IA (solo administradores).
 *
 * Revisa el entorno, la configuración y le pregunta a Google qué modelos
 * puede usar la API key. Nunca muestra la clave completa.
 * Se puede borrar este archivo cuando todo funcione.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../../login.php");
    exit();
}
if (($_SESSION['rol'] ?? '') !== 'administrador') {
    header("Location: ../../../index.php?error=no_autorizado");
    exit();
}

require_once __DIR__ . '/gemini_cliente.php';

$ruta_base = '../../../';
$css_extra = 'css/estilos_agente_ia.css';
include '../../includes/header_index.php';

/** GET o POST simple a Gemini. Devuelve [codigo_http, datos, error_curl]. */
function diag_llamar(array $config, string $metodo, string $ruta, ?array $cuerpo = null): array
{
    $ch = curl_init(rtrim($config['url_base'], '/') . $ruta);
    $opc = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false, // Desactivar temporalmente verificación SSL
        CURLOPT_SSL_VERIFYHOST => 0,   // Desactivar verificación de host
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'x-goog-api-key: ' . $config['api_key']],
    ];
    if ($metodo === 'POST') {
        $opc[CURLOPT_POST] = true;
        $opc[CURLOPT_POSTFIELDS] = json_encode($cuerpo, JSON_UNESCAPED_UNICODE);
    }
    if (!empty($config['cacert'])) {
        $opc[CURLOPT_CAINFO] = $config['cacert'];
    }
    curl_setopt_array($ch, $opc);
    $resp = curl_exec($ch);
    $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    return [$http, is_string($resp) ? json_decode($resp, true) : null, $err];
}

$pasos = []; // cada paso: [ok(bool|null), titulo, detalle]

// 1. Entorno
$pasos[] = [version_compare(PHP_VERSION, '7.4.0', '>='), 'Versión de PHP', PHP_VERSION . ' (se necesita 7.4 o superior)'];
$hay_curl = function_exists('curl_init');
$pasos[] = [$hay_curl, 'Extensión cURL', $hay_curl ? 'activada' : 'NO está activada. En XAMPP: php.ini → quitar el ";" de extension=curl y reiniciar Apache.'];
$pasos[] = [function_exists('mb_substr'), 'Extensión mbstring', function_exists('mb_substr') ? 'activada' : 'NO está activada (extension=mbstring en php.ini).'];

// 2. Configuración
$ruta_config = __DIR__ . '/../../config/gemini.php';
$config = gemini_cargar_config();
if (!is_file($ruta_config)) {
    $pasos[] = [false, 'Archivo php/config/gemini.php', 'No existe. Copiá gemini.example.php como gemini.php y pegá tu API key.'];
} elseif ($config === null) {
    $pasos[] = [false, 'Archivo php/config/gemini.php', 'Existe, pero la API key está vacía o sigue siendo el texto de ejemplo.'];
} else {
    $clave = (string)$config['api_key'];
    $pasos[] = [true, 'Archivo php/config/gemini.php', 'Cargado. Clave: ' . substr($clave, 0, 4) . '… (' . strlen($clave) . ' caracteres). Modelo configurado: ' . $config['modelo']];
}

$modelos_ok = [];
if ($config !== null && $hay_curl) {
    // 3. Modelos que ve esta clave
    [$http, $datos, $err] = diag_llamar($config, 'GET', '/models?pageSize=200');
    if ($err !== '' && $http === 0) {
        $extra = stripos($err, 'SSL certificate') !== false
            ? ' → Falta el certificado: descargá https://curl.se/ca/cacert.pem y poné su ruta en "cacert" de gemini.php.'
            : '';
        $pasos[] = [false, 'Conexión con Google', 'No se pudo conectar: ' . $err . $extra];
    } elseif ($http >= 400) {
        $msg = $datos['error']['message'] ?? 'sin detalle';
        $pasos[] = [false, 'Consulta de modelos', "Google respondió HTTP $http: $msg"];
    } else {
        $pasos[] = [true, 'Conexión con Google', 'Correcta (la API key fue aceptada)'];
        foreach (($datos['models'] ?? []) as $m) {
            if (in_array('generateContent', $m['supportedGenerationMethods'] ?? [], true)) {
                $modelos_ok[] = preg_replace('#^models/#', '', $m['name']);
            }
        }
        $existe = in_array($config['modelo'], $modelos_ok, true);
        $pasos[] = [
            $existe,
            'Modelo configurado',
            $existe
                ? $config['modelo'] . ' está disponible para tu clave.'
                : '"' . $config['modelo'] . '" NO está en la lista de tu clave. Probá con uno de los modelos de más abajo y cambialo en gemini.php.'
        ];
    }

    // 4. Prueba real de generación (sin herramientas)
    [$http, $datos, $err] = diag_llamar($config, 'POST', '/models/' . rawurlencode($config['modelo']) . ':generateContent', [
        'contents' => [['role' => 'user', 'parts' => [['text' => 'Respondé solamente con la palabra: OK']]]],
        'generationConfig' => ['maxOutputTokens' => 20],
    ]);
    if ($http >= 400 || $http === 0) {
        $msg = $datos['error']['message'] ?? ($err ?: 'sin detalle');
        $pasos[] = [false, 'Prueba de generación', "HTTP $http: $msg"];
    } else {
        $texto = '';
        foreach (($datos['candidates'][0]['content']['parts'] ?? []) as $p) {
            $texto .= $p['text'] ?? '';
        }
        $pasos[] = [true, 'Prueba de generación', 'Gemini respondió: «' . trim($texto) . '»'];
    }
}
?>

<main class="contenedor-principal">
    <aside class="sidebar">
        <h3>Acciones</h3>
        <ul>
            <li><a href="agente_ia.php">Volver al Agente</a></li>
            <li><a href="diagnostico.php">Repetir diagnóstico</a></li>
        </ul>
    </aside>

    <section class="contenido">
        <article>
            <h2>Diagnóstico del Agente de IA</h2>

            <?php foreach ($pasos as [$ok, $titulo, $detalle]): ?>
                <div class="chat-aviso" style="border-left-color: <?php echo $ok ? '#00b894' : '#d63031'; ?>;">
                    <strong><?php echo $ok ? '✔' : '✘'; ?> <?php echo htmlspecialchars($titulo); ?>:</strong>
                    <?php echo htmlspecialchars($detalle); ?>
                </div>
            <?php endforeach; ?>

            <?php if ($modelos_ok): ?>
                <h2 style="margin-top: 30px;">Modelos disponibles para tu clave</h2>
                <p class="chat-ayuda" style="margin-bottom: 10px;">Copiá uno en <code>'modelo'</code> de <code>php/config/gemini.php</code>. Los que dicen "flash" suelen ser los más rápidos y baratos.</p>
                <ul style="columns: 2; padding-left: 20px; line-height: 1.8;">
                    <?php foreach ($modelos_ok as $m): ?>
                        <li><code><?php echo htmlspecialchars($m); ?></code></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </article>
    </section>
</main>

<?php include '../../includes/footer.php'; ?>

</body>

</html>