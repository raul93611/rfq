<input type="hidden" name="id_cuestionario" value="<?php echo $cuestionario->obtener_id(); ?>">
<div class="card-body">
    <h3 class="card-title">PROJECT OBJECTIVES</h3>
    <br>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="">Reach&nbsp;</span>
        </div>
        <input type="text" class="form-control" name="reach_objectives" placeholder="Objectives" autofocus <?php echo 'value="' . $cuestionario->obtener_reach_objectives() . '"'; ?>>
        <input type="text" class="form-control" name="reach_goals" placeholder="Goals" <?php echo 'value="' . $cuestionario->obtener_reach_goals() . '"'; ?>>
    </div>
    <br>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="">Cost&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </div>
        <input type="text" class="form-control" name="cost_objectives" placeholder="Objectives" <?php echo 'value="' . $cuestionario->obtener_cost_objectives() . '"'; ?>>
        <input type="text" class="form-control" name="cost_goals" placeholder="Goals" <?php echo 'value="' . $cuestionario->obtener_cost_goals() . '"'; ?>>
    </div>
    <br>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="">Time&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </div>
        <input type="text" class="form-control" name="time_objectives" placeholder="Objectives" <?php echo 'value="' . $cuestionario->obtener_time_objectives() . '"'; ?>>
        <input type="text" class="form-control" name="time_goals" placeholder="Goals" <?php echo 'value="' . $cuestionario->obtener_time_goals() . '"'; ?>>
    </div>
    <br>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="">Quality</span>
        </div>
        <input type="text" class="form-control" name="quality_objectives" placeholder="Objectives" <?php echo 'value="' . $cuestionario->obtener_quality_objectives() . '"'; ?>>
        <input type="text" class="form-control" name="quality_goals" placeholder="Goals" <?php echo 'value="' . $cuestionario->obtener_quality_goals() . '"'; ?>>
    </div>
    <hr>
    <h3 class="card-title">LOCATIONS</h3>
    <br>
    <div class="form-group">
        <div class="form-check-inline">
            <input class="form-check-input" type="radio" value="multiple" name="locations" <?php
            if ($cuestionario->obtener_locations() == 'multiple') {
                echo 'checked';
            }
            ?>>
            <label class="form-check-label">Multiple</label>
        </div>
        <div class="form-check-inline">
            <input class="form-check-input" type="radio" value="one_location" name="locations" <?php
            if ($cuestionario->obtener_locations() == 'one_location') {
                echo 'checked';
            }
            ?>>
            <label class="form-check-label">One location</label>
        </div>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary" name="registrar_cuestionario">Save</button>
    <a class="btn btn-primary" href="<?php echo EDITAR_COTIZACION . '/' . $cuestionario->obtener_id_rfq(); ?>">Go Back</a>
</div>
<div class="card-body">
    <h3 class="card-title">HIGH LEVEL REQUIREMENTS</h3>
    <a class="btn btn-info btn-sm float-right" href="<?php echo ADD_HIGH_LEVEL_REQUIREMENT . '/' . $cuestionario->obtener_id(); ?>">Add</a>
    <br>
    <br>
    <?php
    RepositorioHighLevelRequirement::escribir_high_level_requirements($cuestionario->obtener_id());
    ?>
    <hr>
    <h3 class="card-title">OUT OF SCOPE</h3>
    <a class="btn btn-info btn-sm float-right" href="<?php echo ADD_OUT_OF_SCOPE . '/' . $cuestionario->obtener_id(); ?>">Add</a>
    <br>
    <br>
    <?php
    RepositorioOutOfScope::escribir_out_of_scopes($cuestionario->obtener_id());
    ?>
    <hr>
    <h3 class="card-title">PROJECT RISKS</h3>
    <a class="btn btn-info btn-sm float-right" href="<?php echo ADD_PROJECT_RISK . '/' . $cuestionario->obtener_id(); ?>">Add</a>
    <br>
    <br>
    <?php
    RepositorioProjectRisk::escribir_project_risks($cuestionario->obtener_id());
    ?>
    <hr>
    <h3 class="card-title">PROJECT MILESTONES</h3>
    <a class="btn btn-info btn-sm float-right" href="<?php echo ADD_PROJECT_MILESTONE . '/' . $cuestionario->obtener_id(); ?>">Add</a>
    <br>
    <br>
    <?php
    RepositorioProjectMilestone::escribir_project_milestones($cuestionario->obtener_id());
    ?>
</div>
