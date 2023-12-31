<?php
session_start();
error_reporting(E_ALL);
require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/csfrtoken.php";
$quizId = $_GET['id'];


if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $csfrTokenEditQuiz) {
    die('Ошибка CSRF при отправке теста');
} else {
    edit_test($quizId);
    header("Location: user.php");
    exit;
}
