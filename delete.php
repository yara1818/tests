<?php

session_start();
error_reporting(E_ALL);
require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/csfrtoken.php";
$quizId = $_GET['id'];

$res = $pdo->prepare("DELETE FROM results WHERE victories_id = ?");
$res->bindValue(1, $quizId, PDO::PARAM_INT);
$res->execute();

$res2 = $pdo->prepare("DELETE FROM questions WHERE victory_id = ?");
$res2->bindValue(1, $quizId, PDO::PARAM_INT);
$res2->execute();

$res2 = $pdo->prepare("DELETE FROM victories WHERE id = ?");
$res2->bindValue(1, $quizId, PDO::PARAM_INT);
$res2->execute();

$_SESSION['success'] = "Викторина и все данные о ней удалены";
header("Location: admin.php");
exit;