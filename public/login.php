<?php
require_once __DIR__ . '/../includes/init.php';

$page_title = "Connexion";
if (is_logged_in()) redirect('public/dashboard.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = trim($_POST['login'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($login === '' || $password === '') {
    $error = "Remplis le login et le mot de passe.";
  } else {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, login, role FROM users WHERE login = :l AND password = :p");
    $stmt->execute([
      'l' => $login,
      'p' => sha1($password)
    ]);
    $user = $stmt->fetch();

    if ($user) {
      $_SESSION['user_id'] = (int)$user['id'];
      $_SESSION['login']   = $user['login'];
      $_SESSION['role']    = $user['role'];
      redirect('public/dashboard.php');
    } else {
      $error = "Identifiants incorrects.";
    }
  }
}

include __DIR__ . '/../partials/layout_top.php';
?>

<div class="row justify-content-center">
  <div class="col-lg-10">
    <div class="card p-0 overflow-hidden">
      <div class="row g-0">
        <div class="col-md-6 p-4" style="background:linear-gradient(135deg, rgba(29,78,216,.10), rgba(96,165,250,.12));">
          <h3 class="fw-bold mb-2"><i class="bi bi-shield-lock me-1"></i> TaskApp</h3>
          <div class="small-muted mb-4">Gestion des tâches avec rôles (Admin / Utilisateur).</div>

          <div class="d-flex gap-3 align-items-start mb-3">
            <i class="bi bi-check2-circle fs-4 text-primary"></i>
            <div>
              <div class="fw-semibold">Sécurisé</div>
              <div class="small-muted">Accès protégé par session et rôles.</div>
            </div>
          </div>

          <div class="d-flex gap-3 align-items-start mb-3">
            <i class="bi bi-list-task fs-4 text-primary"></i>
            <div>
              <div class="fw-semibold">Simple & rapide</div>
              <div class="small-muted">Ajout, modification, suppression, statut.</div>
            </div>
          </div>

          <div class="d-flex gap-3 align-items-start">
            <i class="bi bi-search fs-4 text-primary"></i>
            <div>
              <div class="fw-semibold">Recherche & filtre</div>
              <div class="small-muted">Filtrer par statut et chercher par texte.</div>
            </div>
          </div>
        </div>

        <div class="col-md-6 p-4">
          <h4 class="fw-bold mb-1">Connexion</h4>
          <div class="small-muted mb-3">Entre tes identifiants pour continuer.</div>

          <?php if ($error): ?>
            <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-1"></i><?= e($error) ?></div>
          <?php endif; ?>

          <form method="post" class="row g-3">
            <div class="col-12">
              <label class="form-label">Login</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input class="form-control" name="login" value="<?= e($_POST['login'] ?? '') ?>" placeholder="admin">
              </div>
            </div>

            <div class="col-12">
              <label class="form-label">Mot de passe</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-key"></i></span>
                <input class="form-control" type="password" name="password" placeholder="admin123">
              </div>
            </div>

            <div class="col-12 d-grid">
              <button class="btn btn-primary">
                <i class="bi bi-box-arrow-in-right me-1"></i> Se connecter
              </button>
            </div>

            <div class="col-12">
              <div class="small-muted">
                Compte admin par défaut : <b>admin / admin123</b>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../partials/layout_bottom.php'; ?>
