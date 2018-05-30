<div class="card-body">
    <div class="form-group">
        <label for="email_code">Email-code:</label>
        <input type="text" class="form-control" id="email_code" name="email_code" placeholder="Email-code" autofocus required>
    </div>
    <div class="form-group">
        <label for="type_of_bid">Type of bid:</label>
        <select class="form-control" name="type_of_bid" id="type_of_bid">
            <option>Audio Visual</option>
            <option>Back up Batteries</option>
            <option>Cameras</option>
            <option>Computer Peripherals</option>
            <option>Computers</option>
            <option>Medical</option>
            <option>Miscellaneous</option>
            <option>Monitors & Televisions</option>
            <option>Office Supplies</option>
            <option>Peripherals</option>
            <option>Portable Radios</option>
            <option>Printers</option>
            <option>Servers</option>
            <option>Software</option>
            <option>Tactical</option>
            <option>Tools</option>
            <option>Scanners</option>
            <option>Projectors</option>
            <option>Video Cameras</option>
            <option>Phones</option>
        </select>
    </div>
    <div class="form-group">
        <label for="issue_date">Issue date:</label>
        <input type="date" class="form-control" id="issue_date" name="issue_date" placeholder="Issue date" required>
    </div>
    <div class="form-group">
        <label for="end_date">End date:</label>
        <input type="datetime-local" class="form-control" id="end_date" name="end_date" placeholder="End date" required>
    </div>
    <div class="form-group">
        <?php
        Conexion::abrir_conexion();
        $usuarios = RepositorioUsuario::obtener_usuarios_rfq(Conexion::obtener_conexion());
        Conexion::cerrar_conexion();
        ?>
        <?php
        if (count($usuarios)) {
            ?>
            <label for="usuario_designado">Usuario designado:</label>
            <select id="usuario_designado" class="form-control" name="usuario_designado">
                <?php
                foreach ($usuarios as $usuario) {
                    ?>
                    <option><?php echo $usuario->obtener_nombre_usuario(); ?></option>
                    <?php
                }
                ?>
            </select>   
            <?php
        }
        ?>
    </div>
    <div class="form-group">
        <label for="canal">Canal:</label>
        <select class="form-control" name="canal" id="canal">
            <option>GSA-Buy</option>
            <option>FedBid</option>
            <option>E-mails</option>
            <option>FindFRP</option>
            <option>Embassies</option>
            <option>FBO</option>
        </select>
    </div>
    <div class="form-group">
        <input type="file" name="documentos[]" multiple class="btn btn-primary btn-block">
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary" name="registrar_cotizacion">Registrar</button>
</div>
