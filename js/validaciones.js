document.addEventListener("DOMContentLoaded", () => {

    // Expresión regular estándar para validar el formato de un email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // ==========================================
    // 1. VALIDACIÓN PANTALLA DE LOGIN
    // ==========================================
    const formLogin = document.getElementById("formLogin")

    if (formLogin) {
        formLogin.addEventListener("submit", (evento) => {
            const email = document.getElementById("inputEmail").value.trim()
            const password = document.getElementById("inputPassword").value.trim()

            // Control de campos vacíos
            if (email === "" || password === "") {
                evento.preventDefault()
                alert("⚠️ ¡Atención! Por favor completa tanto el correo electrónico como la contraseña.")
                return;
            }

            // NUEVO: Control de formato de email
            if (!emailRegex.test(email)) {
                evento.preventDefault()
                alert("❌ El formato del correo electrónico no es válido. Ejemplo: usuario@correo.com")
                return;
            }
        })
    }

    // ==========================================
    // 2. VALIDACIÓN PANTALLA DE REGISTRO
    // ==========================================
    const formRegister = document.getElementById("formRegister")
    
    if (formRegister) {
        formRegister.addEventListener("submit", (evento) => {
            const nombre = document.getElementById("nombre").value.trim()
            const email = document.getElementById("email").value.trim()
            const password = document.getElementById("password").value.trim()
            const password2 = document.getElementById("password2").value.trim()
            const rol = document.getElementById("rol").value

            // Control de campos vacíos
            if (nombre === "" || email === "" || password === "" || password2 === "") {
                evento.preventDefault()
                alert("⚠️ ¡Atención! Todos los campos son obligatorios para registrarse.")
                return; 
            }

            // NUEVO: Control de formato de email
            if (!emailRegex.test(email)) {
                evento.preventDefault()
                alert("❌ El formato del correo electrónico no es válido. Ejemplo: usuario@correo.com")
                return;
            }

            // Control de contraseñas idénticas
            if (password !== password2) {
                evento.preventDefault()
                alert("❌ Las contraseñas no coinciden. Por favor, verificalas.")
                return;
            }

            // Control de selección de Rol
            if (rol === "" || rol === "elige un rol") {
                evento.preventDefault()
                alert("⚠️ Por favor, selecciona un rol válido para el usuario.")
                return;
            }
        })
    }

    // ==========================================
    // 3. CAPTURA ÚNICA DE PARÁMETROS URL (PHP -> JS)
    // ==========================================
    const parametrosURL = new URLSearchParams(window.location.search)

    if (parametrosURL.get("error") === "1") {
        alert("❌ Email de usuario o contraseña incorrectos.")
        window.history.replaceState({}, document.title, window.location.pathname)
    }

    if (parametrosURL.get("error") === "campos_vacios") {
        alert("⚠️ El servidor detectó campos vacíos. Completá todo el formulario.")
        window.history.replaceState({}, document.title, window.location.pathname)
    }

    if (parametrosURL.get("error") === "email_duplicado") {
        alert("❌ Este correo electrónico ya está registrado en el sistema.")
        window.history.replaceState({}, document.title, window.location.pathname)
    }

    if (parametrosURL.get("success") === "registrado") {
        alert("🎉 ¡Usuario registrado con éxito! Ya podés iniciar sesión.")
        window.history.replaceState({}, document.title, window.location.pathname)
    }

    // usuario sin permisos quiso ingresar la url manualmente
    if (parametrosURL.get("error") === "no_autorizado") {
        alert("⛔ Acceso denegado: No tenés permisos de Administrador para ingresar a ese módulo.");
        window.history.replaceState({}, document.title, window.location.pathname);
    }
})