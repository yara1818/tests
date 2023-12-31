<?php
session_start();
require_once __DIR__ . "/functions.php";

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_register']) {
  $_SESSION['errors'] = 'Ошибка csrf токена';
  header("Location: index.php");
  exit;
} else {
  register();
  header("Location: user.php");
  exit;
}
