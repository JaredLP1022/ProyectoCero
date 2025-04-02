<?php
include_once dirname(__DIR__) . '../config/db.php';
$pdo = new db();
$PDO = $pdo->conexion();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clave = $_POST['clave_maestra'];

    // Validación de la clave en PHP (refuerzo por seguridad)
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $clave)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La clave debe tener al menos 8 caracteres, incluir una mayúscula, un número y un símbolo.',
                confirmButtonColor: '#d33'
            });
        </script>";
        exit;
    }

    $hash = password_hash($clave, PASSWORD_BCRYPT);

    // Insertar o actualizar la clave maestra con clave fija "AdministradorTech"
    $query = "INSERT INTO configuracion (clave, valor) VALUES ('AdministradorTech', :valor) 
              ON DUPLICATE KEY UPDATE valor = VALUES(valor)";
    $stmt = $PDO->prepare($query);
    $stmt->bindParam(':valor', $hash);

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Clave guardada',
                text: 'Clave maestra guardada correctamente',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'TablaUsuarios.php';
                }
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al guardar la clave',
                confirmButtonColor: '#d33'
            });
        </script>";
    }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.25/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.25/dist/sweetalert2.all.min.js"></script>

<style>
    .password-container {
        position: relative;
        display: flex;
        align-items: center;
    }
    .password-container input {
        padding-right: 40px;
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

<div class="container mt-5 col-md-4">
    <h2>Configurar Clave Maestra</h2>
    <p>El administrador puede generar una clave maestra, esta solo es para confirmación de los cambios en los usuarios o para confirmar que se quieren eliminar usuarios de la base de datos.</p>
    <form action="RegistrarClave.php" method="POST" onsubmit="return validarClave()">
        <label>Nueva Clave Maestra</label>
        <div class="password-container">
            <input type="password" name="clave_maestra" id="clave_maestra" class="form-control" >
            <i class="eye-icon" onclick="togglePassword()">
                <img src="https://img.icons8.com/ios-filled/50/000000/visible.png" alt="Mostrar contraseña" />
            </i>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Guardar Clave</button>
    </form>
</div>

<script>
    function togglePassword() {
        var passwordField = document.getElementById("clave_maestra");
        var eyeIcon = document.querySelector('.eye-icon img');

        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.src = "https://img.icons8.com/ios-filled/50/000000/invisible.png";
        } else {
            passwordField.type = "password";
            eyeIcon.src = "https://img.icons8.com/ios-filled/50/000000/visible.png";
        }
    }

    function validarClave() {
        var clave = document.getElementById("clave_maestra").value;
        var regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

        if (!regex.test(clave)) {
            Swal.fire({
                icon: 'error',
                title: 'Clave inválida',
                text: 'Debe tener al menos 8 caracteres, una mayúscula, un número y un símbolo.',
                confirmButtonColor: '#d33'
            });
            return false;
        }
        return true;
    }
</script>
