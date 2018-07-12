<div class="card-body">
    <div class="form-group">
        <label for="nombre_usuario">Username:</label>
        <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" placeholder="Username" autofocus required <?php $validador-> mostrar_nombre_usuario(); ?>>
        <?php $validador-> mostrar_error_nombre_usuario(); ?>
    </div>
    <div class="form-group">
        <label for="password1">Password:</label>
        <input type="password" class="form-control" id="password1" name="password1" placeholder="Password" required>
        <?php $validador-> mostrar_error_password1(); ?>
    </div>
    <div class="form-group">
        <label for="password2">Confirm password:</label>
        <input type="password" class="form-control" id="password2" name="password2" placeholder="Confirm password" required>
        <?php $validador-> mostrar_error_password2(); ?>
    </div>
    <div class="form-group">
        <label for="nombres">First names:</label>
        <input type="text" class="form-control" id="nombres" name="nombres" placeholder="First names" required <?php $validador-> mostrar_nombres(); ?>>
        <?php $validador-> mostrar_error_nombres(); ?>
    </div>
    <div class="form-group">
        <label for="apellidos">Last names:</label>
        <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Last names" required <?php $validador-> mostrar_apellidos(); ?>>
        <?php $validador-> mostrar_error_apellidos(); ?>
    </div>
    <div class="form-group">
        <label for="email">E-mail:</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
    </div>
    <div class="form-group">
        <label for="cargo">Position:</label>
        <select class="form-control" name="cargo" id="cargo">
            <option>Jefe</option>
            <option>Jefe de área</option>
            <option>Usuario comun</option>
        </select>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-success" name="registrar_usuario"><i class="fa fa-save"></i> Sign in</button>
</div>
