<?php
// Incluye la conexión a la base de datos
include("C:/xampp/htdocs/ProyectoCero/config/db.php");

$pdo = new db();
$PDO = $pdo->conexion();

// Consultar los nombres de usuario de la base de datos
$stmt = $PDO->prepare("SELECT username FROM usuario");
$stmt->execute();
$usuarios = $stmt->fetchAll();
?>

<h3 style="text-align: center;">Cambiar estado de cuenta de usuario</h3>
<div style="text-align: center; margin-top: 20px;">
    <!-- Contenedor de los botones -->
    <div style="display: inline-block; margin-right: 10px;">
        <button onclick="window.location.href='../registroAdmin.php'" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Volver
        </button>
    </div>
    
    <div style="display: inline-block; margin-right: 10px;">
        <button onclick="window.location.href='Panel-Administrador.php'" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Inicio
        </button>
    </div>
</div>
<br>
<form id="form-cambiar-estado" action="partials/activarC.php" method="POST" style="max-width: 400px; margin: 0 auto; padding: 20px; border: 2px solid #ccc; border-radius: 10px; background-color: #f9f9f9;" onsubmit="return confirmarCambioEstado()">
    <label for="user" style="display: block; margin-bottom: 8px; font-weight: bold;">Nombre de usuario:</label>
    <select id="user" name="user" required style="width: 100%; padding: 8px; margin-bottom: 15px; border: 2px solid #ccc; border-radius: 5px; box-sizing: border-box;">
        <option value="" disabled selected>Seleccionar usuario</option>
        <?php foreach ($usuarios as $usuario): ?>
            <option value="<?php echo htmlspecialchars($usuario['username']); ?>"><?php echo htmlspecialchars($usuario['username']); ?></option>
        <?php endforeach; ?>
    </select>
    
    <label for="estado" style="display: block; margin-bottom: 8px; font-weight: bold;">Acción a realizar:</label>
    <select name="estado" id="estado" required style="width: 100%; padding: 8px; margin-bottom: 15px; border: 2px solid #ccc; border-radius: 5px; box-sizing: border-box;">
        <option value="inactivo">Desactivar</option>
        <option value="activo">Activar</option>
    </select>
    
    <button type="submit" class="btn btn-danger" style="width: 100%; padding: 10px; background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Cambiar Estado</button>
</form>

<!-- Incluir SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    function confirmarCambioEstado() {
        // Obtener los valores de los campos
        var user = document.getElementById("user").value;
        var estado = document.getElementById("estado").value;

        // Verificar si el campo "usuario" está vacío
        if (user.trim() === "") {
            // Usar SweetAlert para mostrar un mensaje
            Swal.fire({
                icon: 'error',
                title: 'Campo vacío',
                text: 'El campo "Nombre de usuario" no puede estar vacío.',
                confirmButtonText: 'Aceptar'
            });
            return false;
        }

        // Confirmación de cambio de estado
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¡Estás a punto de ${estado === 'activo' ? 'activar' : 'desactivar'} la cuenta!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#28a745',
            confirmButtonText: 'Sí, cambiar estado',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, enviar el formulario
                document.getElementById("form-cambiar-estado").submit();
            } else {
                // Si el usuario cancela, no hacer nada
                return false;
            }
        });
        // Retornar false para prevenir que el formulario se envíe antes de la confirmación
        return false;
    }
</script>
