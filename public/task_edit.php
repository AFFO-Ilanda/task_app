<?php
require_once __DIR__ . '/../includes/auth.php';
global $pdo;

require_login();
$page_title = "Modifier tâche";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) redirect('public/tasks_list.php');

require_task_owner_or_admin($pdo, $id);

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :id");
$stmt->execute(['id' => $id]);
$task = $stmt->fetch();
if (!$task) redirect('public/tasks_list.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $desc  = trim($_POST['description'] ?? '');
  $status = $_POST['status'] ?? 'en_cours';

  if ($title === '') $error = "Le titre est obligatoire.";
  if ($status !== 'en_cours' && $status !== 'terminee') $status = 'en_cours';

  if ($error === '') {
    $upd = $pdo->prepare("UPDATE tasks SET title=:t, description=:d, status=:s WHERE id=:id");
    $upd->execute([
      't' => $title,
      'd' => $desc !== '' ? $desc : null,
      's' => $status,
      'id'=> $id
    ]);
    redirect('public/tasks_list.php');
  }
}

include __DIR__ . '/../partials/layout_top.php';
?>

<div class="page-head mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
  <div>
    <h3 class="mb-1 fw-bold">Modifier la tâche #<?= (int)$id ?></h3>
    <div class="small-muted">Change titre, description ou statut.</div>
  </div>
  <a class="btn btn-outline-dark" href="<?= e(url('public/tasks_list.php')) ?>">
    <i class="bi bi-arrow-left me-1"></i> Retour
  </a>
</div>

<div class="card p-4">
  <?php if ($error): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-1"></i><?= e($error) ?></div>
  <?php endif; ?>

  <form method="post" class="row g-3">
    <div class="col-12">
      <label class="form-label fw-semibold">Titre *</label>
      <input class="form-control" name="title" value="<?= e($_POST['title'] ?? $task['title']) ?>">
    </div>

    <div class="col-12">
      <label class="form-label fw-semibold">Description</label>
      <textarea class="form-control" name="description" rows="5"><?= e($_POST['description'] ?? ($task['description'] ?? '')) ?></textarea>
    </div>

    <div class="col-12">
      <label class="form-label fw-semibold">Statut</label>
      <?php $cur = $_POST['status'] ?? $task['status']; ?>
      <select class="form-select" name="status">
        <option value="en_cours" <?= $cur==='en_cours'?'selected':'' ?>>En cours</option>
        <option value="terminee" <?= $cur==='terminee'?'selected':'' ?>>Terminée</option>
      </select>
    </div>

    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary">
        <i class="bi bi-save me-1"></i> Mettre à jour
      </button>
      <a class="btn btn-outline-secondary" href="<?= e(url('public/tasks_list.php')) ?>">Annuler</a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../partials/layout_bottom.php'; ?>
