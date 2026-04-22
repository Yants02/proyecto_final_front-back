<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Inventario - IFTS 4</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">Sistema Gestión de Inventario</div>
        <ul class="nav-links">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="php/crud/productos.php">Productos</a></li>
            <li><a href="php/crud/proveedores.php">Proveedores</a></li>
            <li><a href="#">Clientes</a></li>
            <?php if(isset($_SESSION['usuario'])): ?>
                <li><a href="php/logout.php">Cerrar Sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main class="contenedor-principal">
        
        <aside class="sidebar">
            <h3>Menú Rápido</h3>
            <ul>
                <li><a href="php/crud/productos.php">Ver Inventario</a></li>
                <li><a href="php/reportes/generar_pdf.php">Exportar PDF</a></li>
                <li><a href="php/reportes/generar_excel.php">Exportar Excel</a></li>
            </ul>
        </aside>

        <section class="contenido">
            <article class="bienvenida">
                <h1>Bienvenido al Sistema de Inventario y Ventas</h1>
                <p>Este sistema permite gestionar productos, proveedores y clientes cumpliendo con los requisitos integradores de Back-End y Front-End.</p>
                
                <div class="info-proyecto">
                    <h3>Estado del Sistema:</h3>
                    <ul>
                        <li><strong>CRUDs:</strong> En desarrollo (Módulo Productos listo).</li>
                        <li><strong>Base de Datos:</strong> Conectada.</li>
                        <li><strong>Apariencia:</strong> CSS Puro (Punto 7).</li>
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