<?php
session_start();
error_reporting(E_ALL);
require_once __DIR__ . "/functions.php";

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_create_quiz']) {
    die('Ошибка CSRF при отправке викторины');
} else {
    create_quiz();
    header("Location: admin.php");
    exit;
}
