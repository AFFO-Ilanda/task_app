<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
global $pdo;

$page_title = "Modifier utilisateur";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) redirect('public/users_list.php');

$stmt = $pdo->prepare("SELECT id, login, role FROM users WHERE id=:id");
$stmt->execute(['id'=>$id]);
$user = $stmt->fetch();
if (!$user) redirect('public/users_list.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = trim($_POST['login'] ?? '');
  $role  = $_POST['role'] ?? 'utilisateur';
  $pass  = $_POST['password'] ?? '';

  if ($login === '') $error = "Login obligatoire.";
  if ($role !== 'admin' && $role !== 'utilisateur') $error = "Rôle invalide.";

  if ($error === '') {
    try {
      if ($pass !== '') {
        $upd = $pdo->prepare("UPDATE users SET login=:l, role=:r, password=:p WHERE id=:id");
        $upd->execute(['l'=>$login,'r'=>$role,'p'=>sha1($pass),'id'=>$id]);
      } else {
        $upd = $pdo->prepare("UPDATE users SET login=:l, role=:r WHERE id=:id");
        $upd->execute(['l'=>$login,'r'=>$role,'id'=>$id]);
      }
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
    <h3 class="mb-1 fw-bold">Modifier utilisateur #<?= (int)$id ?></h3>
    <div class="small-muted">Changer login / rôle / mot de passe.</div>
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
      <input class="form-control" name="login" value="<?= e($_POST['login'] ?? $user['login']) ?>">
    </div>
    <div class="col-12">
      <label class="form-label fw-semibold">Nouveau mot de passe (optionnel)</label>
      <input class="form-control" type="password" name="password">
    </div>
    <div class="col-12">
      <label class="form-label fw-semibold">Rôle</label>
      <?php $cur = $_POST['role'] ?? $user['role']; ?>
      <select class="form-select" name="role">
        <option value="utilisateur" <?= $cur==='utilisateur'?'selected':'' ?>>utilisateur</option>
        <option value="admin" <?= $cur==='admin'?'selected':'' ?>>admin</option>
      </select>
    </div>
    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Mettre à jour</button>
      <a class="btn btn-outline-secondary" href="<?= e(url('public/users_list.php')) ?>">Annuler</a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../partials/layout_bottom.php'; ?>
