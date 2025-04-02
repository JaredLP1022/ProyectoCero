<div style="text-align: center; margin-top: 20px;">
    <!-- Contenedor de los botones -->
    <div style="display: inline-block; margin-right: 10px;">
        <button onclick="window.location.href='pages/Panel-Administrador.php'" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Volver
        </button>
    </div>
    
    <div style="display: inline-block; margin-right: 10px;">
        <button onclick="window.location.href='pages/Panel-Administrador.php'" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Inicio
        </button>
    </div>

    <!-- Puedes agregar más botones aquí siguiendo el mismo formato -->
    <div style="display: inline-block;">
        <button class="btn btn-danger" onclick="window.location.href='pages/AcDesCuentas.php'" style="padding: 10px 20px; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Activar o Desactivar cuenta
        </button>
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
