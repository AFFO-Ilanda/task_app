<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
global $pdo;

$page_title = "Dashboard";

if (is_admin()) {
  $total    = (int)$pdo->query("SELECT COUNT(*) c FROM tasks")->fetch()['c'];
  $enCours  = (int)$pdo->query("SELECT COUNT(*) c FROM tasks WHERE status='en_cours'")->fetch()['c'];
  $terminee = (int)$pdo->query("SELECT COUNT(*) c FROM tasks WHERE status='terminee'")->fetch()['c'];
  $users    = (int)$pdo->query("SELECT COUNT(*) c FROM users")->fetch()['c'];
} else {
  $uid = current_user_id();

  $stmt = $pdo->prepare("SELECT COUNT(*) c FROM tasks WHERE user_id=:u");
  $stmt->execute(['u'=>$uid]);
  $total = (int)$stmt->fetch()['c'];

  $stmt = $pdo->prepare("SELECT COUNT(*) c FROM tasks WHERE user_id=:u AND status='en_cours'");
  $stmt->execute(['u'=>$uid]);
  $enCours = (int)$stmt->fetch()['c'];

  $stmt = $pdo->prepare("SELECT COUNT(*) c FROM tasks WHERE user_id=:u AND status='terminee'");
  $stmt->execute(['u'=>$uid]);
  $terminee = (int)$stmt->fetch()['c'];

  $users = 0;
}

include __DIR__ . '/../partials/layout_top.php';
?>

<div class="page-head mb-3">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
      <h3 class="mb-1 fw-bold">Dashboard</h3>
      <div class="small-muted">Bienvenue, <?= e(current_user_login()) ?>. Suivi rapide de tes tâches.</div>
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-soft" href="<?= e(url('public/task_add.php')) ?>">
        <i class="bi bi-plus-circle me-1"></i> Nouvelle tâche
      </a>
      <a class="btn btn-outline-primary" href="<?= e(url('public/tasks_list.php')) ?>">
        <i class="bi bi-list-check me-1"></i> Voir la liste
      </a>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-4">
    <div class="card p-4">
      <div class="kpi">
        <i class="bi bi-collection"></i>
        <div>
          <div class="small-muted">Total tâches</div>
          <div class="value"><?= $total ?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card p-4">
      <div class="kpi">
        <i class="bi bi-hourglass-split"></i>
        <div>
          <div class="small-muted">En cours</div>
          <div class="value"><?= $enCours ?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card p-4">
      <div class="kpi">
        <i class="bi bi-check2-circle"></i>
        <div>
          <div class="small-muted">Terminées</div>
          <div class="value"><?= $terminee ?></div>
        </div>
      </div>
    </div>
  </div>

  <?php if (is_admin()): ?>
    <div class="col-12">
      <div class="card p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
          <div class="kpi">
            <i class="bi bi-people"></i>
            <div>
              <div class="small-muted">Utilisateurs</div>
              <div class="value"><?= $users ?></div>
            </div>
          </div>
          <a class="btn btn-outline-dark" href="<?= e(url('public/users_list.php')) ?>">
            <i class="bi bi-gear me-1"></i> Gérer les utilisateurs
          </a>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/layout_bottom.php'; ?>
