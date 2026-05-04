<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos_onepage.css">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=local_convenience_store" />
    <title>Onepage</title>
</head>

<body>
    <?php include 'php/includes/header_onepage.php'; ?>
    <main>
        <section id="inicio">
            <article>
                <h1>GESTIONÁ TU NEGOCIO <br><span class="h1-color">SIN VUELTAS</span></h1>
                <p class="title-desc">Control de stock, precios, proveedores y mucho más <span>desde tu celular</span></p>
                <a href="index.php" class="btn-ir-a-dashboard">Ingresar al panel</a>
            </article>
            <figure>
                <img src="img/foto_portada.png" alt="Local">
            </figure>
        </section>

        <section id="funciones">
            <ul>
                <li>
                    <article>
                        <header>
                            <img src="img/stock.svg">
                            <h2>Control de stock</h2>
                        </header>
                        <p>Monitoreá tu inventario en tiempo real y recibí alertas de productos con bajo stock</p>
                    </article>
                </li>
                <li>
                    <article>
                        <header>
                            <img src="img/stock.svg">
                            <h2>Análisis de ventas</h2>
                        </header>
                        <p>Visualizá tus ventas diarias, productos más vendidos y maximizá tus ganancias</p>
                    </article>
                </li>
                <li>
                    <article>
                        <header>
                            <img src="img/stock.svg">
                            <h2>Gestión de proveedores</h2>
                        </header>
                        <p>Registrá ingresos de mercadería, mantené un historial claro de que compraste y a qué precio</p>
                    </article>
                </li>
                <li>
                    <article>
                        <header>
                            <img src="img/stock.svg">
                            <h2>Registro de ventas</h2>
                        </header>
                        <p>Registrá cada venta en segundos con una interfaz fácil de usar evitando errores en el cobro.</p>
                    </article>
                </li>
                <li>
                    <article>
                        <header>
                            <img src="img/stock.svg">
                            <h2>Carga de productos</h2>
                        </header>
                        <p>Sumá nuevos productos en segundos, definiendo precios de costo y venta.</p>
                    </article>
                </li>
                <li>
                    <article>
                        <header>
                            <img src="img/stock.svg">
                            <h2>Facturación simplificada</h2>
                        </header>
                        <p>Emití comprobantes de venta al instante, manteniendo así un registro detallado de cada transacción para un cierre de caja transparente y sin diferencias.</p>
                    </article>
                </li>
            </ul>
        </section>

        <section id="precios">
            <header>
                <h2>Elegí el plan Perfecto para tu negocio</h2>
                <p>Planes flexibles que se adaptan a tus necesidades</p>
            </header>
            <ul>
                <li>
                    <article class="plan">
                        <header>
                            <img src="img/stock.svg">
                            <h3>Pro</h3>
                            <p class="price">Próximamente</p>
                        </header>
                        <p class="target">Para negocios en crecimiento</p>
                        <ul class="benefits">
                            <li>Productos ilimitados</li>
                            <li>Control avanzado de stock</li>
                            <li>Hasta 3 usuarios</li>
                            <li>Alertas de vencimiento</li>
                            <li>Análisis de ventas</li>
                            <li>Soporte prioritario</li>
                        </ul>
                        <footer>
                            <a href="#" class="btn-comenzar-plan">Próximamente</a>
                        </footer>
                    </article>
                </li>
                <li>
                    <article>
                        <header>
                            <span>Más Popular</span>
                            <img src="img/stock.svg">
                            <h3>Lite</h3>
                            <p class="price">GRATIS</p>
                        </header>
                        <p class="target">Para empezar</p>
                        <ul class="benefits">
                            <li>Hasta 100 productos</li>
                            <li>Control básico de stock</li>
                            <li>1 usuario</li>
                            <li>Reportes básicos</li>
                        </ul>
                        <footer>
                            <a href="#" class="btn-comenzar-plan">Comenzar Gratis</a>
                        </footer>
                    </article>
                </li>
                <li>
                    <article>
                        <header>
                            <img src="img/crown.svg">
                            <h3>Elite</h3>
                            <p class="price">Próximamente</p>
                        </header>
                        <p class="target">Para máximo rendimiento</p>
                        <ul class="benefits">
                            <li>Todo de Pro +</li>
                            <li>Usuarios ilimitados</li>
                            <li>Múltiples sucursales</li>
                            <li>API avanzada</li>
                            <li>Soporte 24/7</li>
                            <li>Capacitación personalizada</li>
                        </ul>
                        <footer>
                            <a href="" class="btn-comenzar-plan plan-elite">Próximamente</a>
                        </footer>
                    </article>
                </li>
            </ul>
        </section>

        <section id="casos_exito">
            <h2>Casos de éxito</h2>
        </section>

        <footer class="footer">
            <section id="empresa">
                <h3>Empresa</h3>
                <nav>
                    <ul>
                        <li><a href="#">Sobre nosotros</a></li>
                        <li><a href="#">Casos de éxito</a></li>
                        <li><a href="#">Carreras</a></li>
                    </ul>
                </nav>
            </section>
            <section id="contacto">
                <h3>Contacto</h3>
                <nav>
                    <ul>
                        <li><a href="#">Contacto@empresa.com</a></li>
                        <li><a href="#">Argentina, Buenos Aires</a></li>
                    </ul>
                </nav>
                <nav>
                    <a href="#">FB</a>
                    <a href="#">LI</a>
                    <a href="#">IG</a>
                    <a href="#">X</a>
                    <a href="#">YT</a>
                </nav>
            </section>
            <hr>
            <section id="final">
                <p>© 2026 Todos los derechos reservados.</p>
                <nav>
                    <ul>
                        <li><a href="#">Privacidad</a></li>
                        <li><a href="#">Términos</a></li>
                        <li><a href="#">Cookies</a></li>
                    </ul>
                </nav>
            </section>
        </footer>
    </main>
</body>

</html>