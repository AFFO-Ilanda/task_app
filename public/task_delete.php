<?php
require_once __DIR__ . '/../includes/auth.php';
global $pdo;

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) redirect('public/tasks_list.php');

require_task_owner_or_admin($pdo, $id);

$del = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
$del->execute(['id' => $id]);

redirect('public/tasks_list.php');
