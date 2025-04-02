<!-- Estilo para el ícono de ojo y el campo de contraseña -->
<style>
    .password-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-container input {
        padding-right: 40px; /* Espacio para el icono */
    }

    .eye-icon {
        position: absolute;
        right: 10px;
        cursor: pointer;
    }

    .eye-icon img {
        width: 24px;
        height: 24px;
    }
</style>

<div class="container position-relative">
    <form class="container formLog " action="verificarLogin.php" method="POST">
        <h2 class="inicioLog">Inicio de sesion</h2>
        <label>Usuario</label>
        <input type="text" name="username" placeholder="Usuario" required>
        <br>
        <label> Contrase&#241;a</label>
        <div class="password-container">
            <input type="password" name="password" id="password" placeholder="**************" required>
            <!-- Icono de ojo para mostrar/ocultar contraseña -->
            <i class="eye-icon" onclick="mostrarContraseña()">
                <img src="https://img.icons8.com/ios-filled/50/000000/visible.png" alt="Mostrar contraseña" />
            </i>
        </div>
        
        <!-- reCAPTCHA v3 token -->
  <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
  
        <button class="btn btn-primary btn-lg" id="boton" type="submit" name="btningresar">Iniciar
            sesion</button>
    </form>
</div>
<br>

<!-- Script de Google reCAPTCHA v3 -->
<script src="https://www.google.com/recaptcha/api.js?render=6LfW4_4qAAAAADgSAa33CTomqarX6xW6w8yk1H5L"></script>
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('6LfW4_4qAAAAADgSAa33CTomqarX6xW6w8yk1H5L', {action: 'login'}).then(function(token) {
            document.getElementById('recaptchaResponse').value = token;
        });
    });

    function mostrarContraseña() {
        var passwordField = document.getElementById("password");
        var eyeIcon = document.querySelector('.eye-icon img');
        
        if (passwordField.type === "password") {
            passwordField.type = "text";  // Mostrar contraseña
            eyeIcon.src = "https://img.icons8.com/ios-filled/50/000000/invisible.png";  // Cambiar el ícono a "ocultar"
        } else {
            passwordField.type = "password";  // Ocultar contraseña
            eyeIcon.src = "https://img.icons8.com/ios-filled/50/000000/visible.png";  // Cambiar el ícono a "mostrar"
        }
    }
</script>