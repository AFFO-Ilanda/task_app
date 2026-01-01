<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
global $pdo;

$page_title = "Ajouter tâche";
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $desc  = trim($_POST['description'] ?? '');

  if ($title === '') {
    $error = "Le titre est obligatoire.";
  } else {
    $stmt = $pdo->prepare("INSERT INTO tasks(user_id, title, description) VALUES (:uid, :t, :d)");
    $stmt->execute([
      'uid' => current_user_id(),
      't'   => $title,
      'd'   => $desc !== '' ? $desc : null
    ]);
    redirect('public/tasks_list.php');
  }
}

include __DIR__ . '/../partials/layout_top.php';
?>

<div class="page-head mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
  <div>
    <h3 class="mb-1 fw-bold">Ajouter une tâche</h3>
    <div class="small-muted">Crée une tâche proprement.</div>
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
      <input class="form-control" name="title" value="<?= e($_POST['title'] ?? '') ?>" placeholder="Ex: Préparer le rapport du jour">
    </div>

    <div class="col-12">
      <label class="form-label fw-semibold">Description</label>
      <textarea class="form-control" name="description" rows="5" placeholder="Détails..."><?= e($_POST['description'] ?? '') ?></textarea>
    </div>

    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary" type="submit">
        <i class="bi bi-save me-1"></i> Enregistrer
      </button>
      <a class="btn btn-outline-secondary" href="<?= e(url('public/tasks_list.php')) ?>">Annuler</a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../partials/layout_bottom.php'; ?>
