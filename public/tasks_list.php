<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
global $pdo;

$page_title = "Tâches";

$q = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';

$sql = "SELECT t.*, u.login AS user_login
        FROM tasks t
        JOIN users u ON u.id = t.user_id
        WHERE 1=1";
$params = [];

if (!is_admin()) {
  $sql .= " AND t.user_id = :uid";
  $params['uid'] = current_user_id();
}

if ($status === 'en_cours' || $status === 'terminee') {
  $sql .= " AND t.status = :status";
  $params['status'] = $status;
}

if ($q !== '') {
  $sql .= " AND (t.title LIKE :q OR t.description LIKE :q)";
  $params['q'] = '%' . $q . '%';
}

$sql .= " ORDER BY t.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll();

include __DIR__ . '/../partials/layout_top.php';
?>

<div class="page-head mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
  <div>
    <h3 class="mb-1 fw-bold"><?= is_admin() ? 'Toutes les tâches' : 'Mes tâches' ?></h3>
    <div class="small-muted">Recherche + filtre par statut.</div>
  </div>
  <a class="btn btn-primary" href="<?= e(url('public/task_add.php')) ?>">
    <i class="bi bi-plus-circle me-1"></i> Ajouter une tâche
  </a>
</div>

<div class="card p-3 mb-3">
  <form method="get" class="row g-2 align-items-center">
    <div class="col-md-8">
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input class="form-control" name="q" placeholder="Rechercher (titre, description)..." value="<?= e($q) ?>">
      </div>
    </div>
    <div class="col-md-3">
      <select class="form-select" name="status">
        <option value="">-- Statut --</option>
        <option value="en_cours" <?= $status==='en_cours'?'selected':'' ?>>En cours</option>
        <option value="terminee" <?= $status==='terminee'?'selected':'' ?>>Terminée</option>
      </select>
    </div>
    <div class="col-md-1 d-grid">
      <button class="btn btn-outline-dark">OK</button>
    </div>
    <div class="col-12">
      <a class="small-muted" href="<?= e(url('public/tasks_list.php')) ?>"><i class="bi bi-arrow-counterclockwise me-1"></i>Réinitialiser</a>
    </div>
  </form>
</div>

<div class="card p-3">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>ID</th>
          <?php if (is_admin()): ?><th>Utilisateur</th><?php endif; ?>
          <th>Titre</th>
          <th>Statut</th>
          <th>Créée</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($tasks as $t): ?>
        <tr>
          <td><?= (int)$t['id'] ?></td>
          <?php if (is_admin()): ?><td><?= e($t['user_login']) ?></td><?php endif; ?>
          <td>
            <div class="fw-semibold"><?= e($t['title']) ?></div>
            <?php if (!empty($t['description'])): ?>
              <div class="small-muted"><?= e(mb_strimwidth((string)$t['description'], 0, 90, '...')) ?></div>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($t['status'] === 'terminee'): ?>
              <span class="badge-status badge-terminee"><i class="bi bi-check2-circle me-1"></i>Terminée</span>
            <?php else: ?>
              <span class="badge-status badge-en_cours"><i class="bi bi-hourglass-split me-1"></i>En cours</span>
            <?php endif; ?>
          </td>
          <td class="small-muted"><?= e($t['created_at']) ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="<?= e(url('public/task_edit.php?id='.(int)$t['id'])) ?>">
              <i class="bi bi-pencil-square me-1"></i>Modifier
            </a>
            <a class="btn btn-sm btn-soft" href="<?= e(url('public/task_toggle.php?id='.(int)$t['id'])) ?>">
              <i class="bi bi-arrow-repeat me-1"></i>Statut
            </a>
            <a class="btn btn-sm btn-outline-danger"
               href="<?= e(url('public/task_delete.php?id='.(int)$t['id'])) ?>"
               onclick="return confirm('Supprimer cette tâche ?')">
              <i class="bi bi-trash me-1"></i>Supprimer
            </a>
          </td>
        </tr>
      <?php endforeach; ?>

      <?php if (!$tasks): ?>
        <tr>
          <td colspan="<?= is_admin()?6:5 ?>" class="text-center py-4">
            <div class="small-muted">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Aucune tâche pour le moment.
            </div>
          </td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/../partials/layout_bottom.php'; ?>
