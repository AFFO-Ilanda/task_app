<?php
declare(strict_types=1);

require_once __DIR__ . '/init.php';

function require_login(): void {
  if (!is_logged_in()) redirect('public/login.php');
}

function require_admin(): void {
  require_login();
  if (!is_admin()) redirect('public/dashboard.php');
}

function fetch_task_owner_id(PDO $pdo, int $taskId): ?int {
  $stmt = $pdo->prepare("SELECT user_id FROM tasks WHERE id = :id");
  $stmt->execute(['id' => $taskId]);
  $row = $stmt->fetch();
  return $row ? (int)$row['user_id'] : null;
}

function require_task_owner_or_admin(PDO $pdo, int $taskId): void {
  require_login();
  if (is_admin()) return;

  $ownerId = fetch_task_owner_id($pdo, $taskId);
  if ($ownerId === null) redirect('public/tasks_list.php');
  if ($ownerId !== current_user_id()) redirect('public/tasks_list.php');
}
