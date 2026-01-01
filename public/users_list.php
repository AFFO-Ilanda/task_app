<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
global $pdo;

$page_title = "Utilisateurs";
$users = $pdo->query("SELECT id, login, role, created_at FROM users ORDER BY id DESC")->fetchAll();

include __DIR__ . '/../partials/layout_top.php';
?>

<div class="page-head mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
  <div>
    <h3 class="mb-1 fw-bold">Utilisateurs</h3>
    <div class="small-muted">Créer, modifier, supprimer, attribuer des rôles.</div>
  </div>
  <a class="btn btn-primary" href="<?= e(url('public/user_add.php')) ?>">
    <i class="bi bi-person-plus me-1"></i> Ajouter
  </a>
</div>

<div class="card p-3">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>ID</th>
          <th>Login</th>
          <th>Rôle</th>
          <th>Créé</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
            <td><?= (int)$u['id'] ?></td>
            <td class="fw-semibold"><?= e($u['login']) ?></td>
            <td>
              <?php if ($u['role'] === 'admin'): ?>
                <span class="badge text-bg-dark"><i class="bi bi-shield-lock me-1"></i>admin</span>
              <?php else: ?>
                <span class="badge text-bg-secondary"><i class="bi bi-person me-1"></i>utilisateur</span>
              <?php endif; ?>
            </td>
            <td class="small-muted"><?= e($u['created_at']) ?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="<?= e(url('public/user_edit.php?id='.(int)$u['id'])) ?>">
                <i class="bi bi-pencil-square me-1"></i>Modifier
              </a>
              <a class="btn btn-sm btn-outline-danger"
                 href="<?= e(url('public/user_delete.php?id='.(int)$u['id'])) ?>"
                 onclick="return confirm('Supprimer cet utilisateur ?')">
                <i class="bi bi-trash me-1"></i>Supprimer
              </a>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (!$users): ?>
          <tr><td colspan="5" class="text-center small-muted py-4">
            <i class="bi bi-people fs-3 d-block mb-2"></i> Aucun utilisateur.
          </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/../partials/layout_bottom.php'; ?>
