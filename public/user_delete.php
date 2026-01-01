<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
global $pdo;

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) redirect('public/users_list.php');

if ($id === current_user_id()) {
  redirect('public/users_list.php'); // éviter auto-suppression
}

$del = $pdo->prepare("DELETE FROM users WHERE id = :id");
$del->execute(['id'=>$id]);

redirect('public/users_list.php');
