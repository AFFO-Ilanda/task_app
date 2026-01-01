<?php
require_once __DIR__ . '/../includes/auth.php';
global $pdo;

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) redirect('public/tasks_list.php');

require_task_owner_or_admin($pdo, $id);

$stmt = $pdo->prepare("SELECT status FROM tasks WHERE id = :id");
$stmt->execute(['id' => $id]);
$row = $stmt->fetch();
if (!$row) redirect('public/tasks_list.php');

$new = ($row['status'] === 'terminee') ? 'en_cours' : 'terminee';

$upd = $pdo->prepare("UPDATE tasks SET status = :s WHERE id = :id");
$upd->execute(['s' => $new, 'id' => $id]);

redirect('public/tasks_list.php');
