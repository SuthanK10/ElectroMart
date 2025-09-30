<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function current_user() {
  return $_SESSION['user'] ?? null;
}
function is_logged_in(): bool {
  return !empty($_SESSION['user']);
}
function is_admin(): bool {
  return !empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}
function require_login() {
  if (!is_logged_in()) {
    header('Location: ../pages/login.php');
    exit;
  }
}
function require_admin() {
  if (!is_admin()) {
    header('Location: ../pages/index.php');
    exit;
  }
}
