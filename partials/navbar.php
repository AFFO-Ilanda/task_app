<?php require_once __DIR__ . '/../includes/init.php';
$current = basename($_SERVER['SCRIPT_NAME']);

function nav_active(string $file, string $current): string {
  return $file === $current ? 'active fw-semibold' : '';
}
?>
<nav class="navbar navbar-expand-lg navbar-dark app-navbar">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= e(url('public/dashboard.php')) ?>">
      <span class="brand-dot"></span>
      TaskApp
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= nav_active('dashboard.php',$current) ?>" href="<?= e(url('public/dashboard.php')) ?>">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= nav_active('tasks_list.php',$current) ?>" href="<?= e(url('public/tasks_list.php')) ?>">
            <i class="bi bi-list-check me-1"></i> Mes tâches
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= nav_active('task_add.php',$current) ?>" href="<?= e(url('public/task_add.php')) ?>">
            <i class="bi bi-plus-circle me-1"></i> Ajouter
          </a>
        </li>

        <?php if (is_admin()): ?>
          <li class="nav-item">
            <a class="nav-link <?= nav_active('users_list.php',$current) ?>" href="<?= e(url('public/users_list.php')) ?>">
              <i class="bi bi-people me-1"></i> Utilisateurs
            </a>
          </li>
        <?php endif; ?>
      </ul>

      <div class="d-flex align-items-center gap-2">
        <span class="badge rounded-pill text-bg-light border app-user-badge">
          <i class="bi bi-person-circle me-1"></i>
          <?= e(current_user_login()) ?> • <?= e(current_user_role()) ?>
        </span>
        <a class="btn btn-sm btn-outline-light" href="<?= e(url('public/logout.php')) ?>">
          <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
        </a>
      </div>
    </div>
  </div>
</nav>
