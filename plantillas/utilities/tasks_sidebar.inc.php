<?php
$isTasksActive = ($partes_ruta[2] ?? null) == 'tasks';
$subRoute = $partes_ruta[3] ?? null;
?>

<li class="nav-item <?= $isTasksActive ? 'menu-open' : ''; ?>">
  <a href="#" class="nav-link <?= $isTasksActive ? 'active' : ''; ?>">
    <i class="nav-icon fas fa-thumbtack"></i>
    <p>
      Tasks
      <i class="right fa fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?= ALL_TASKS; ?>" class="nav-link <?= $subRoute == 'all_tasks' ? 'active' : ''; ?>">
        <i class="far fa-circle nav-icon"></i>
        <p>All Tasks</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= MY_TASKS; ?>" class="nav-link <?= $subRoute == 'my_tasks' ? 'active' : ''; ?>">
        <i class="far fa-circle nav-icon"></i>
        <p>My Tasks</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= TASKS_DONE; ?>" class="nav-link <?= $subRoute == 'tasks_done' ? 'active' : ''; ?>">
        <i class="far fa-circle nav-icon"></i>
        <p>Tasks Done</p>
      </a>
    </li>
  </ul>
</li>