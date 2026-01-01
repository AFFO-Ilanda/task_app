<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
global $pdo;

$page_title = "Ajouter utilisateur";
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = trim($_POST['login'] ?? '');
  $pass  = $_POST['password'] ?? '';
  $role  = $_POST['role'] ?? 'utilisateur';

  if ($login === '' || $pass === '') {
    $error = "Login et mot de passe obligatoires.";
  } elseif ($role !== 'admin' && $role !== 'utilisateur') {
    $error = "Rôle invalide.";
  } else {
    try {
      $stmt = $pdo->prepare("INSERT INTO users(login, password, role) VALUES (:l,:p,:r)");
      $stmt->execute([
        'l' => $login,
        'p' => sha1($pass),
        'r' => $role
      ]);
      redirect('public/users_list.php');
    } catch (Throwable $e) {
      $error = "Login déjà utilisé.";
    }
  }
}

include __DIR__ . '/../partials/layout_top.php';
?>

<div class="page-head mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
  <div>
    <h3 class="mb-1 fw-bold">Ajouter un utilisateur</h3>
    <div class="small-muted">Créer un utilisateur et définir son rôle.</div>
  </div>
  <a class="btn btn-outline-dark" href="<?= e(url('public/users_list.php')) ?>">
    <i class="bi bi-arrow-left me-1"></i> Retour
  </a>
</div>

<div class="card p-4">
  <?php if ($error): ?><div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-1"></i><?= e($error) ?></div><?php endif; ?>

  <form method="post" class="row g-3">
    <div class="col-12">
      <label class="form-label fw-semibold">Login *</label>
      <input class="form-control" name="login" value="<?= e($_POST['login'] ?? '') ?>">
    </div>
    <div class="col-12">
      <label class="form-label fw-semibold">Mot de passe *</label>
      <input class="form-control" type="password" name="password">
    </div>
    <div class="col-12">
      <label class="form-label fw-semibold">Rôle</label>
      <select class="form-select" name="role">
        <option value="utilisateur" selected>utilisateur</option>
        <option value="admin">admin</option>
      </select>
    </div>
    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Créer</button>
      <a class="btn btn-outline-secondary" href="<?= e(url('public/users_list.php')) ?>">Annuler</a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../partials/layout_bottom.php'; ?>
