<li class="nav-item <?php echo $gestor_actual == '' || $gestor_actual == 'my_tasks' || $gestor_actual == 'tasks_done' ? 'menu-open' : ''; ?>">
  <a href="#" class="nav-link <?php echo $gestor_actual == '' || $gestor_actual == 'my_tasks' || $gestor_actual == 'tasks_done' ? 'active' : ''; ?>">
    <i class="nav-icon fas fa-thumbtack"></i>
    <p>
      Tasks
      <i class="right fa fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo PERFIL; ?>" class="nav-link <?php echo $gestor_actual == '' ? 'active' : ''; ?>">
        <i class="far fa-circle nav-icon"></i>
        <p>All Tasks</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo MY_TASKS; ?>" class="nav-link <?php echo $gestor_actual == 'my_tasks' ? 'active' : ''; ?>">
        <i class="far fa-circle nav-icon"></i>
        <p>My Tasks</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo TASKS_DONE; ?>" class="nav-link <?php echo $gestor_actual == 'tasks_done' ? 'active' : ''; ?>">
        <i class="far fa-circle nav-icon"></i>
        <p>Tasks Done</p>
      </a>
    </li>
  </ul>
</li>