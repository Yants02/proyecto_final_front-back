<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// si el usuario no esta logueado, lo manda al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../../login.php");
    exit();
}

$ruta_base = '../../../';
$css_extra = 'css/estilos_agente_ia.css';
include '../../includes/header_index.php';

require_once __DIR__ . '/respuesta_ia.php';

$nombre = $_SESSION['nombre'] ?? 'Usuario';
$inicial = mb_strtoupper(mb_substr($nombre, 0, 1));

// conversacion guardada en la sesion
$mensajes = $_SESSION['chat_ia'] ?? [];

// las sugerencias dependen de lo que el rol puede consultar
$sugerencias = sugerencias_para_rol((string)($_SESSION['rol'] ?? 'ventas'));
?>

<main class="contenedor-principal">
    <aside class="sidebar">
        <h3>Acciones</h3>

        <ul>
            <li>
                <form action="limpiar_chat.php" method="POST">
                    <button type="submit" class="sidebar-sugerencia">Nueva conversación</button>
                </form>
            </li>
            <li><a href="../../../index.php">Volver al Inicio</a></li>
        </ul>

        <h3 class="sidebar-subtitulo">Sugerencias</h3>

        <ul>
            <?php foreach ($sugerencias as $sugerencia): ?>
                <li>
                    <form action="enviar_mensaje.php" method="POST">
                        <input type="hidden" name="mensaje" value="<?php echo htmlspecialchars($sugerencia); ?>">
                        <button type="submit" class="sidebar-sugerencia"><?php echo htmlspecialchars($sugerencia); ?></button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <section class="contenido">

        <article id="chat-agente">
            <h2>Agente de IA</h2>

            <?php if (!ia_configurada()): ?>
                <div class="chat-aviso">
                    <strong>Modo demostración:</strong> falta configurar la API key de Gemini
                    (<code>php/config/gemini.php</code>). Por ahora el agente responde con un texto fijo.
                </div>
            <?php endif; ?>

            <div class="chat-mensajes">
                <div class="chat-hilo">

                    <!-- Mensaje de bienvenida (siempre visible) -->
                    <div class="mensaje mensaje-ia">
                        <div class="avatar avatar-ia">IA</div>
                        <div class="burbuja">
                            ¡Hola, <?php echo htmlspecialchars($nombre); ?>! Soy tu asistente de inventario y ventas.
                            Preguntame lo que necesites sobre productos, clientes, proveedores o ventas.
                        </div>
                    </div>

                    <?php foreach ($mensajes as $m): ?>
                        <?php if ($m['rol'] === 'usuario'): ?>
                            <div class="mensaje mensaje-usuario">
                                <div class="burbuja">
                                    <?php echo nl2br(htmlspecialchars($m['texto'])); ?>
                                    <span class="hora"><?php echo htmlspecialchars($m['hora']); ?></span>
                                </div>
                                <div class="avatar avatar-usuario"><?php echo htmlspecialchars($inicial); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="mensaje mensaje-ia">
                                <div class="avatar avatar-ia">IA</div>
                                <div class="burbuja<?php echo !empty($m['error']) ? ' burbuja-error' : ''; ?>">
                                    <?php echo nl2br(htmlspecialchars($m['texto'])); ?>
                                    <span class="hora"><?php echo htmlspecialchars($m['hora']); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </div>
            </div>

            <form action="enviar_mensaje.php" method="POST" class="chat-form">
                <textarea name="mensaje" placeholder="Escribí tu consulta..." maxlength="1000" required></textarea>
                <button type="submit" class="btn-guardar">Enviar</button>
            </form>
            <p class="chat-ayuda">Máximo 1000 caracteres por mensaje. Las respuestas de la IA pueden tener errores: verificá los datos importantes.</p>
        </article>

    </section>
</main>

<?php include '../../includes/footer.php'; ?>

</body>

</html>
