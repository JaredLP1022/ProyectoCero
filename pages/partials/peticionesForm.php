<form action="procesar_peticiones.php" method="POST" onsubmit="return validarFormulario()">
    <h2 style="text-align: center;">Bienvenido a las Peticiones</h2>

    <!-- Contenedor para el párrafo y el textarea -->
    <div style="margin: 20px auto; text-align: center; max-width: 600px;">
        <p>Un administrador revisará la petición. Si ya has realizado una petición, haz clic en el siguiente botón para
            consultar tus peticiones.</p>

        <label for="descripcion" style="display: block; font-weight: bold; margin-bottom: 8px;">Descripción de la
            petición:</label>
        <textarea id="descripcion" name="descripcion" rows="4" placeholder="Describe tu solicitud..." required
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 2px solid #ccc; border-radius: 5px; background-color: transparent; resize: vertical;"></textarea>
    </div>

    <!-- Contenedor para los campos select y fecha -->
    <div style="display: flex; justify-content: space-between; gap: 10px; margin-top: 15px;">
        <div style="flex: 1;">
            <!-- Selección de Prioridad -->
            <label for="prioridad">Prioridad:</label>
            <select name="prioridad" id="prioridad" required>
                <option value="">Seleccione una opción</option>
                <option value="Alta">Alta</option>
                <option value="Media">Media</option>
                <option value="Baja">Baja</option>
            </select>
        </div>

        <div style="flex: 1;">
            <!-- Selección de Departamento -->
            <label for="departamento">Departamento:</label>
            <select name="departamento" id="departamento" required>
                <option value="">Seleccione una opción</option>
                <option value="Soporte Tecnico">Soporte Técnico</option>
                <option value="Ventas">Ventas</option>
                <option value="Administrador">Administrador</option>
            </select>
        </div>

        <div style="flex: 1;">
            <!-- Selección de Fecha Necesaria -->
            <label for="fecha_necesaria">Fecha Necesaria:</label>
            <input type="date" name="fecha_necesaria" id="fecha_necesaria" required>
        </div>
    </div>

    <button type="submit"
        style="margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; width: 100%;">Enviar
        Petición</button>
</form>

<!-- Botón para consultar peticiones -->
<div class="consulta-btn" style="margin-top: 20px; text-align: center;">
    <a href="consultar_peticiones.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Consultar mis
        peticiones</a>
</div>