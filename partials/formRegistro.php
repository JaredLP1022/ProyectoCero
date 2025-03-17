<?php if(!empty($_GET["error"])):?>
    <div class="alert alert-danger">
        <?= !empty($_GET["error"]) ? $_GET["error"] : ""?>
    </div>
<?php endif;?>

<div class="contenedorPanel">
    <div class="botonCss">
        <button title="Volver" class=" border-white botonCerrar ColorLetra" type="submit"
            onclick="location.href='pages/Panel-administrador.php'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path
                    d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
            </svg>
        </button>
    </div>
    <div class="tituloCss">
        <h4 class="text-center ColorLetra">Formulario para registrar nuevos usuarios</h4>
    </div>
    <div class="botonCss">
        <button title="Home" class=" border-white botonCerrar ColorLetra" type="submit"
            onclick="location.href='pages/Panel-Administrador.php'"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                <path
                    d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
            </svg></button>
    </div>

</div>

<div class="container position-relative h-auto">
    
    <form class="container registroJ" action="store.php" id="registroForm" method="POST" onsubmit="return validarFormulario()">
        <h2 class="inicioLog">Registro de Usuario</h2>

        <div class="form-group">
            <label>Nombre del usuario</label>
            <input type="text" name="name" class="form-control" placeholder="Nombre" required>
        </div>

        <div class="row">
            <div class="form-group col-md-8">
                <label>Correo Electrónico</label>
                <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com" required>
            </div>

            <div class="form-group col-md-4">
                <label>Usuario</label>
                <input type="text" name="username" class="form-control" placeholder="Usuario" minlength="4" required>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label>Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="**************" minlength="6" required>
                <button type="button" onclick="togglePassword('password', 'togglePass1')">
            👁️
        </button>
            </div>

            <div class="form-group col-md-6">
                <label>Repetir Contraseña</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="**************" required>
                <button type="button" onclick="togglePassword('confirm_password', 'togglePass2')">
            👁️
        </button>
                <small id="error-password" class="text-danger"></small>
            </div>
        </div>

        <!-- Fila con dos columnas: Rol y Estado -->
        <div class="row">
            <div class="form-group col-md-6">
                <label>Rol</label>
                <select name="rol" class="form-control" required>
                    <option value="">Seleccione un rol</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Ventas">Ventas</option>
                    <option value="Soporte">Soporte</option>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label>Estado</label>
                <select name="estado" class="form-control" required>
                    <option value="">Seleccione un estado</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
        </div>

        <br>
        <button class="btn btn-primary btn-lg" id="boton" type="submit" name="btningresar">Registrar</button>
    </form>
</div>

<script>
    function togglePassword(inputId, buttonId) {
    let passwordInput = document.getElementById(inputId);
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}

function validarFormulario() {
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm_password").value;
    let passwordError = document.getElementById("passwordError");
    let confirmPasswordError = document.getElementById("confirmPasswordError");

    let regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    // Validar contraseña
    if (!regex.test(password)) {
        passwordError.textContent = "La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial.";
        return false;
    } else {
        passwordError.textContent = "";
    }

    // Validar que las contraseñas coincidan
    if (password !== confirm_password) {
        confirm_password.textContent = "Las contraseñas no coinciden.";
        return false;
    } else {
        confirm_password.textContent = "";
    }

    return true;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById("registroForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Detiene el envío hasta que verifiquemos

        let name = document.querySelector("[name='name']").value.trim();
        let username = document.querySelector("[name='username']").value.trim();
        let email = document.querySelector("[name='email']").value.trim();
        let password = document.getElementById("password").value.trim();
        let confirm_password = document.getElementById("confirm_password").value.trim();
        let errors = [];

        // Validar campos vacíos
        if (!name || !username || !email || !password || !confirm_password) {
            errors.push("Todos los campos son obligatorios.");
        }

        // Validar correo electrónico
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.push("El correo electrónico no es válido.");
        }

        // Validar contraseña (mínimo 8 caracteres, una mayúscula, un número y un carácter especial)
        let passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        if (!passwordRegex.test(password)) {
            errors.push("La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial.");
        }

        // Verificar que las contraseñas coincidan
        if (password !== confirm_password) {
            errors.push("Las contraseñas no coinciden.");
        }

        // Si hay errores, mostrar SweetAlert y no enviar el formulario
        if (errors.length > 0) {
            Swal.fire({
                icon: "error",
                title: "Error en el registro",
                html: errors.join("<br>"),
            });
            return;
        }

        // Si todo está bien, enviar el formulario
        this.submit();
    });
</script>
