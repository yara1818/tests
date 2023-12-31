<?php
session_start();
error_reporting(E_ALL);
require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/csfrtoken.php";

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $csfrTokenTest) {
    die('Ошибка CSRF при отправке теста');
} else {
    send_test();
    header("Location: user.php");
    exit;
}
