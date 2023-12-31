<?php
session_start();
error_reporting(E_ALL);
require_once __DIR__ . "/functions.php";

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_login']) {
    $_SESSION['errors'] = 'Ошибка csrf токена';
    header("Location: index.php");
} else {
    login();
    header("Location: user.php");
    exit;
}
