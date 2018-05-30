<div class="card-body">
    <div class="form-group">
        <label for="nombre_usuario">Nombre usuario:</label>
        <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" placeholder="Nombre usuario" autofocus required <?php $validador-> mostrar_nombre_usuario(); ?>>
        <?php $validador-> mostrar_error_nombre_usuario(); ?>
    </div>
    <div class="form-group">
        <label for="password1">Contraseña:</label>
        <input type="password" class="form-control" id="password1" name="password1" placeholder="Contraseña" required>
        <?php $validador-> mostrar_error_password1(); ?>
    </div>
    <div class="form-group">
        <label for="password2">Repita su contraseña:</label>
        <input type="password" class="form-control" id="password2" name="password2" placeholder="Repita su contraseña" required>
        <?php $validador-> mostrar_error_password2(); ?>
    </div>
    <div class="form-group">
        <label for="nombres">Nombres:</label>
        <input type="text" class="form-control" id="nombres" name="nombres" placeholder="Nombres" required <?php $validador-> mostrar_nombres(); ?>>
        <?php $validador-> mostrar_error_nombres(); ?>
    </div>
    <div class="form-group">
        <label for="apellidos">Apellidos:</label>
        <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Apellidos" required <?php $validador-> mostrar_apellidos(); ?>>
        <?php $validador-> mostrar_error_apellidos(); ?>
    </div>
    <div class="form-group">
        <label for="cargo">Cargo:</label>
        <select class="form-control" name="cargo" id="cargo">
            <option>Jefe</option>
            <option>Jefe de área</option>
            <option>Usuario comun</option>
        </select>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary" name="registrar_usuario">Registrar</button>
</div>

