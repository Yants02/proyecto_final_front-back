<?php
session_start();
$ruta_base = ''; 
include 'php/includes/header_index.php';
?>

    <main class="contenedor-principal">
        
        <?php include 'php/includes/sidebar_index.php'; ?>

        <section class="contenido">
            <article class="bienvenida">
                <h1>Bienvenido al Sistema de Inventario y Ventas</h1>
                <p>Este sistema permite gestionar productos, proveedores y clientes cumpliendo con los requisitos integradores de Back-End y Front-End.</p>
                
                <div class="info-proyecto">
                    <h3>Estado del Sistema:</h3>
                    <ul>
                        <li><strong>CRUDs:</strong> En desarrollo (Módulo Productos listo).</li>
                        <li><strong>Base de Datos:</strong> Conectada.</li>
                        <li><strong>Apariencia:</strong> CSS Puro.</li>
                    </ul>
                </div>
            </article>
        </section>

    </main>

    <footer class="footer">
        <div class="footer-content">
            <p>2026 - TP Integrador - IFTS N° 4</p>
            <p>Integrantes: Bisutti, Yotti y equipo.</p>
        </div>
    </footer>

    <script src="js/validaciones.js"></script>
</body>
</html>