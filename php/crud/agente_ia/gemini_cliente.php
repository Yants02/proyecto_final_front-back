<?php
/*
 * Cliente mínimo de la API de Gemini (REST + cURL, sin librerías).
 *
 * Flujo con "function calling":
 *   1. Le mandamos a Gemini la pregunta + la lista de herramientas.
 *   2. Si responde con un "functionCall", ejecutamos esa consulta en la base
 *      y le devolvemos el resultado como "functionResponse".
 *   3. Repetimos hasta que conteste con texto (máximo 6 vueltas).
 */

/** Error con un mensaje apto para mostrarle al usuario. El detalle técnico va al log. */
class GeminiError extends RuntimeException {}

/** Devuelve la configuración, o null si falta el archivo o la API key. */
function gemini_cargar_config(): ?array
{
    $ruta = __DIR__ . '/../../config/gemini.php';
    if (!is_file($ruta)) {
        return null;
    }

    $config = include $ruta;
    if (!is_array($config) || empty($config['api_key']) || $config['api_key'] === 'PEGA_TU_API_KEY_AQUI') {
        return null;
    }

    return $config + [
        'modelo' => 'gemini-2.5-flash',
        'url_base' => 'https://generativelanguage.googleapis.com/v1beta',
        'cacert' => '',
    ];
}

/** Una llamada HTTP a generateContent. Devuelve la respuesta decodificada. */
function gemini_post(array $config, array $cuerpo): array
{
    $url = rtrim($config['url_base'], '/') . '/models/' . rawurlencode($config['modelo']) . ':generateContent';

    $json = json_encode($cuerpo, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);

    $ch = curl_init($url);
    $opciones = [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 40,
        CURLOPT_SSL_VERIFYPEER => false, // Desactivar temporalmente verificación SSL
        CURLOPT_SSL_VERIFYHOST => 0,   // Desactivar verificación de host
        // La key va en un header (no en la URL) para que no quede en logs.
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'x-goog-api-key: ' . $config['api_key'],
        ],
    ];
    if (!empty($config['cacert'])) {
        $opciones[CURLOPT_CAINFO] = $config['cacert'];
    }
    curl_setopt_array($ch, $opciones);

    $respuesta = curl_exec($ch);
    $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error_curl = curl_error($ch);

    if ($respuesta === false) {
        error_log('[agente_ia] Error de red con Gemini: ' . $error_curl);
        if (stripos($error_curl, 'SSL certificate') !== false) {
            throw new GeminiError('Falló la verificación SSL. Configurá la ruta "cacert" en php/config/gemini.php.');
        }
        throw new GeminiError('No se pudo conectar con Gemini. Revisá la conexión a internet e intentá de nuevo.');
    }

    $datos = json_decode($respuesta, true);

    if ($http >= 400) {
        $detalle = $datos['error']['message'] ?? substr($respuesta, 0, 300);
        error_log("[agente_ia] Gemini respondió HTTP $http: $detalle");

        if ($http === 400 && stripos($detalle, 'API key') !== false) {
            throw new GeminiError('La API key de Gemini no es válida. Revisá php/config/gemini.php.');
        }
        // (sin "match" para que funcione también en PHP 7.4)
        if ($http === 400) {
            $mensaje = 'Gemini rechazó la consulta. Probá reformular la pregunta.';
        } elseif ($http === 403) {
            $mensaje = 'La API key no tiene permiso para usar este modelo.';
        } elseif ($http === 404) {
            $mensaje = 'El modelo configurado no existe. Revisá "modelo" en php/config/gemini.php (o abrí diagnostico.php para ver los modelos disponibles).';
        } elseif ($http === 429) {
            $mensaje = 'Se alcanzó el límite de uso de Gemini. Probá de nuevo en un minuto.';
        } else {
            $mensaje = 'Gemini no está disponible en este momento. Probá de nuevo más tarde.';
        }
        throw new GeminiError($mensaje);
    }

    if (!is_array($datos)) {
        error_log('[agente_ia] Respuesta de Gemini que no es JSON: ' . substr($respuesta, 0, 300));
        throw new GeminiError('Gemini devolvió una respuesta inesperada.');
    }

    return $datos;
}

/**
 * Hace la consulta completa, resolviendo las herramientas que Gemini pida.
 *
 * @param callable $ejecutar function(string $nombre, array $args): array
 */
function gemini_responder(array $config, string $sistema, array $contenidos, array $declaraciones, callable $ejecutar): string
{
    for ($vuelta = 0; $vuelta < 6; $vuelta++) {
        $cuerpo = [
            'systemInstruction' => ['parts' => [['text' => $sistema]]],
            'contents' => $contenidos,
            'generationConfig' => ['temperature' => 0.3, 'maxOutputTokens' => 1024],
        ];
        if ($declaraciones) {
            $cuerpo['tools'] = [['functionDeclarations' => $declaraciones]];
        }

        $r = gemini_post($config, $cuerpo);

        if (!empty($r['promptFeedback']['blockReason'])) {
            error_log('[agente_ia] Consulta bloqueada: ' . $r['promptFeedback']['blockReason']);
            throw new GeminiError('Gemini no pudo procesar esa consulta. Probá con otra pregunta.');
        }

        $partes = $r['candidates'][0]['content']['parts'] ?? [];
        if (!$partes) {
            error_log('[agente_ia] Sin contenido. finishReason=' . ($r['candidates'][0]['finishReason'] ?? '?'));
            throw new GeminiError('Gemini no devolvió una respuesta. Probá reformular la pregunta.');
        }

        $llamadas = [];
        $texto = '';
        foreach ($partes as $i => $parte) {
            if (isset($parte['functionCall'])) {
                $llamadas[] = $parte['functionCall'];
                // Una función sin parámetros llega con args vacío: debe volver como {} y no como [].
                if (empty($parte['functionCall']['args'])) {
                    $partes[$i]['functionCall']['args'] = new stdClass();
                }
            } elseif (isset($parte['text']) && empty($parte['thought'])) {
                $texto .= $parte['text'];
            }
        }

        if (!$llamadas) {
            $texto = trim($texto);
            if ($texto === '') {
                throw new GeminiError('Gemini no devolvió una respuesta. Probá reformular la pregunta.');
            }
            return $texto;
        }

        // El turno del modelo se reenvía tal cual vino (incluye firmas internas que Gemini necesita).
        $contenidos[] = ['role' => 'model', 'parts' => $partes];

        $resultados = [];
        foreach ($llamadas as $llamada) {
            $nombre = (string)($llamada['name'] ?? '');
            $args = is_array($llamada['args'] ?? null) ? $llamada['args'] : [];
            $resultados[] = [
                'functionResponse' => [
                    'name' => $nombre,
                    'response' => ['result' => $ejecutar($nombre, $args)],
                ],
            ];
        }
        $contenidos[] = ['role' => 'user', 'parts' => $resultados];
    }

    throw new GeminiError('La consulta requirió demasiados pasos. Probá con una pregunta más puntual.');
}
