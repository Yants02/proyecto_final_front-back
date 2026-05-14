<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos_onepage.css">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
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
                            <span class="material-symbols-outlined">
                                box
                            </span>
                            <h2>Control de stock</h2>
                        </header>
                        <p>Monitoreá tu inventario en tiempo real y recibí alertas de productos con bajo stock</p>
                    </article>
                </li>
                <li>
                    <article>
                        <header>
                            <span class="material-symbols-outlined">
                                finance_mode
                            </span>
                            <h2>Análisis de ventas</h2>
                        </header>
                        <p>Visualizá tus ventas diarias, productos más vendidos y maximizá tus ganancias</p>
                    </article>
                </li>
                <li>
                    <article>
                        <header>
                            <span class="material-symbols-outlined">
                                local_shipping
                            </span>
                            <h2>Gestión de proveedores</h2>
                        </header>
                        <p>Registrá ingresos de mercadería, mantené un historial claro de que compraste y a qué precio</p>
                    </article>
                </li>
                <li>
                    <article>
                        <header>
                            <span class="material-symbols-outlined">
                                shopping_cart_checkout
                            </span>
                            <h2>Registro de ventas</h2>
                        </header>
                        <p>Registrá cada venta en segundos con una interfaz fácil de usar evitando errores en el cobro.</p>
                    </article>
                </li>
                <li>
                    <article>
                        <header>
                            <span class="material-symbols-outlined">
                                inventory
                            </span>
                            <h2>Carga de productos</h2>
                        </header>
                        <p>Sumá nuevos productos en segundos, definiendo precios de costo y venta.</p>
                    </article>
                </li>
                <li>
                    <article>
                        <header>
                            <span class="material-symbols-outlined">
                                request_quote
                            </span>
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
                            <span class="material-symbols-outlined">
                                diamond
                            </span>
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
                            <span>Más Popular</span><br>
                            <span class="material-symbols-outlined">
                                stars
                            </span>
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
                            <span class="material-symbols-outlined">
                                crown
                            </span>
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
            <header>
                <h2>Casos de éxito</h2>
                <p>Lo que dicen los dueños de negocios que ya confían en nosotros</p>
            </header>
            <ul class="carrousel">
                <li class="carrousel-item">
                    <article class="caso-exito">
                        <img src="./img/caso-exito1.jpg" alt="Maria Garcia" class="card-foto">
                        <div class="card">
                            <header>
                                <h3>Maria Garcia</h3>
                                <p>Kiosco "La Esquinita"</p>
                            </header>
                            <p>"Aumenté mis ventas un 20%"</p>
                            <div class="beneficio">
                                <p>Gracias a Kiosko Manager, el control de stock dejó de ser una pesadilla. Ya no pierdo ventas por falta de mercadería. ¡Súper recomendable!</p>
                            </div>
                            <footer>
                                <span class="stars">★★★★★</span>
                            </footer>
                        </div>
                    </article>
                </li>
                <li class="carrousel-item">
                    <article class="caso-exito">
                        <img src="./img/caso-exito2.jpg" alt="Carlos Perez" class="card-foto">
                        <div class="card">
                            <header>
                                <h3>Carlos Perez</h3>
                                <p>Minimercado Carlitos</p>
                            </header>
                            <p>"Me organizó el negocio"</p>
                            <div class="beneficio">
                                <p>La gestión de proveedores me ahorra horas cada semana. Antes era un caos, ahora sé exactamente qué pedir y a qué precio. ¡Muy buena app!</p>
                            </div>
                            <footer>
                                <span class="stars">★★★★</span>
                            </footer>
                        </div>
                    </article>
                </li>
                <li class="carrousel-item">
                    <article class="caso-exito">
                        <img src="./img/caso-exito3.jpg" alt="Carlos Perez" class="card-foto">
                        <div class="card">
                            <header>
                                <h3>Julio Rodriguez</h3>
                                <p>Almacén Don Julio</p>
                            </header>
                            <p>"Facturación rápida y fácil"</p>
                            <div class="beneficio">
                                <p>Emitir comprobantes al instante es genial. Mis clientes lo agradecen y mi cierre de caja es perfecto. Una gran ayuda para el día a día.</p>
                            </div>
                            <footer>
                                <span class="stars">★★★★</span>
                            </footer>
                        </div>
                    </article>
                </li>
                <li class="carrousel-item">
                    <article class="caso-exito">
                        <img src="./img/caso-exito4.jpg" alt="Ricardo Sosa" class="card-foto">
                        <div class="card">
                            <header>
                                <h3>Ricardo Sosa</h3>
                                <p>Despensa "El Sol"</p>
                            </header>
                            <p>"Control total desde mi casa"</p>
                            <div class="beneficio">
                                <p>Lo que más me gusta es poder revisar las ventas del día desde mi casa sin tener que estar físicamente en el local. ¡Es libertad pura!</p>
                            </div>
                            <footer>
                                <span class="stars">★★★★★</span>
                            </footer>
                        </div>
                    </article>
                </li>
                <li class="carrousel-item">
                    <article class="caso-exito">
                        <img src="./img/caso-exito5.jpg" alt="Elena Martinez" class="card-foto">
                        <div class="card">
                            <header>
                                <h3>Elena Martinez</h3>
                                <p>Fiambrería "3 Salames"</p>
                            </header>
                            <p>"Reduje mis pérdidas al mínimo"</p>
                            <div class="beneficio">
                                <p>Gracias a las alertas automáticas de stock, dejé de tirar mercadería vencida. El sistema me avisa con tiempo para rotar los productos y cuidar mi rentabilidad.</p>
                            </div>
                            <footer>
                                <span class="stars">★★★★</span>
                            </footer>
                        </div>
                    </article>
                </li>
                <li class="carrousel-item">
                    <article class="caso-exito">
                        <img src="./img/caso-exito6.jpg" alt="Javier Ortega" class="card-foto">
                        <div class="card">
                            <header>
                                <h3>Javier Ortega</h3>
                                <p>Mayorista "Distribuidora Sur"</p>
                            </header>
                            <p>"Precision total en grandes volumenes"</p>
                            <div class="beneficio">
                                <p>En un mayorista el margen de error debe ser cero. Kiosko Manager nos permite procesar pedidos grandes con una precisión increíble, manteniendo el stock sincronizado en tiempo real.</p>
                            </div>
                            <footer>
                                <span class="stars">★★★★★</span>
                            </footer>
                        </div>
                    </article>
                </li>
            </ul>
                
        </section>
    <section id="contacto">
    <h2>Contactanos</h2>
    
    <?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'enviado'): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            ¡Tu mensaje fue enviado correctamente!
        </div>
    <?php endif; ?>

    <form action="php/acciones/enviar_contacto.php" method="POST" class="formulario">
        <div class="campo">
            <label>Nombre:</label>
            <input type="text" name="nombre" placeholder="Tu nombre" required>
        </div>
        
        <div class="campo">
            <label>Email:</label>
            <input type="email" name="email" placeholder="tu@email.com" required>
        </div>
        
        <div class="campo">
            <label>Consulta:</label>
            <textarea name="consulta" rows="5" placeholder="Escribí tu mensaje acá..." required></textarea>
        </div>
        
        <button type="submit" class="btn-guardar">Enviar Mensaje</button>
    </form>
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