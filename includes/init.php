<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/../config/db.php';

function e(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

/**
 * URL depuis la racine du projet /task_app
 * Ex: url('public/dashboard.php') => /task_app/public/dashboard.php
 * Empêche le bug public/public
 */
function url(string $path = ''): string {
  $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

  // si on est dans /task_app/public -> remonter à /task_app
  if (substr($dir, -7) === '/public') {
    $dir = substr($dir, 0, -7);
  }

  $base = $dir === '' ? '' : $dir;
  return $base . '/' . ltrim($path, '/');
}

function redirect(string $path): void {
  header('Location: ' . url($path));
  exit;
}

function is_logged_in(): bool { return isset($_SESSION['user_id']); }
function current_user_id(): ?int { return $_SESSION['user_id'] ?? null; }
function current_user_login(): string { return $_SESSION['login'] ?? ''; }
function current_user_role(): string { return $_SESSION['role'] ?? ''; }
function is_admin(): bool { return current_user_role() === 'admin'; }
