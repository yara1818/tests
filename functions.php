<?php
require_once __DIR__ . "/pdo.php";

function debag($array)
{
    echo "<pre>" . print_r($array) . "</pre>";
}

function createTable($table)
{
    global $pdo;
    $sql = $table;
    $pdo->exec($sql);

    $_SESSION["success"] = "Таблица создана";
}

function createCsfrToken($token)
{
    if (!isset($_SESSION[$token])) {
        $_SESSION[$token] = bin2hex(random_bytes(32));
    }
    return $_SESSION[$token];
}

function logout()
{
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

function register()
{
    global $pdo;
    $table = "users"; // Change the value of a variable to the name of your table

    $login = !empty($_POST["login"]) ? trim($_POST["login"]) : "";
    $password = !empty($_POST["password"]) ? trim($_POST["password"]) : "";
    // You can add new data. Example from above.

    if (empty($login) || empty($password)) {
        $_SESSION["errors"] = "Поле логин и пароль обязательны!";
        return false;
    }

    $res = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE login = ?"); // Change login
    $res->execute([$login]);
    if ($user = $res->fetchColumn()) {
        $_SESSION["errors"] = "Данный логин уже используется";
        return false;
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $res = $pdo->prepare("INSERT INTO $table (login, password) VALUES (?,?)"); // Change login and password
        if ($res->execute([$login, $password])) {
            $_SESSION["success"] = "Успешная регистрация";
            $_SESSION["user"]["login"] = $login;
            return true;
        } else {
            $_SESSION["errors"] = "Ошибка регистрации";
            return false;
        }
    }
}

function login()
{
    global $pdo;
    $table = "users"; // Change the value of a variable to the name of your table

    $login = !empty($_POST["login"]) ? trim($_POST["login"]) : "";
    $password = !empty($_POST["password"]) ? trim($_POST["password"]) : "";
    // You can add new data. Example from above.

    if (empty($login) || empty($password)) {
        $_SESSION["errors"] = "Поле логин и пароль обязательны!";
        return false;
    }
    $res = $pdo->prepare("SELECT * FROM $table WHERE login = ?"); // // Change login // change
    $res->execute([$login]);
    $user = $res->fetch();
    if (!$user) {
        $_SESSION["errors"] = "Логин/пароль введены неверно";
        return false;
    } else {
        if (!password_verify($password, $user["password"])) {
            $_SESSION["errors"] = "Логин/пароль введены неверно";
            return false;
        } else {
            $_SESSION["success"] = "Вы успешно авторизовались";
            $_SESSION["user"]["login"] = $user["login"];
            return true;
        }
    }
}

function create_quiz(): bool
{
    global $pdo;

    $quizName = !empty($_POST["quiz_name"]) ? trim($_POST["quiz_name"]) : "";
    $quizDescr = !empty($_POST["quiz_description"]) ? trim($_POST["quiz_description"]) : "";
    $quizImg = !empty($_FILES['quiz_img']) ? $_FILES['quiz_img'] : "";
    $questionCount = !empty($_POST['question']) ? count($_POST['question']) : 0;
    $questions = !empty($_POST['question']) ? $_POST['question'] : [];
    $answerCount = !empty($_POST['answer']) ? count($_POST['answer']) : 0;
    $answers = !empty($_POST['answer']) ? $_POST['answer'] : [];
    $correctOptions = !empty($_POST['correct_option']) ? $_POST['correct_option'] : [];

    if (empty($quizName) || empty($quizDescr)) {
        $_SESSION['errors'] = 'Поле название/описание викторины обязательно';
        return false;
    }

    if ($questionCount < 1) {
        $_SESSION['errors'] = "Недостаточно вопросов!";
        return false;
    }

    if ($questionCount < 1 || $answerCount < 1 || $correctOptions < 1) {
        $_SESSION['errors'] = "Недостаточно вопросов, ответов или правильных вариантов!";
        return false;
    }

    if ($quizImg["name"] !== '') {
        if ($quizImg["error"] === UPLOAD_ERR_OK) {
            $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
            $maxFileSize = 5 * 1024 * 1024;

            if (in_array($quizImg["type"], $allowedTypes) && $quizImg["size"] <= $maxFileSize) {
                $targetDir = "./img/";
                $targetFile = $targetDir . basename($quizImg["name"]);
                $targetFilePathInDb = $targetDir . $quizImg["name"];

                if (!move_uploaded_file($quizImg["tmp_name"], $targetFile)) {
                    $_SESSION['errors'] = "Ошибка при перемещении файла.";
                    return false;
                }
            } else {
                $_SESSION['errors'] = "Ошибка: Недопустимый тип файла или превышен допустимый размер.";
                return false;
            }
        } else {
            $_SESSION['errors'] = "Ошибка при загрузке файла: " . $quizImg["error"];
            return false;
        }
    } else {
        $targetFilePathInDb = './img/default.png';
    }


    $res = $pdo->prepare("INSERT INTO victories (title, description, img) VALUES (?, ?, ?)");
    $res->execute([$quizName, $quizDescr, $targetFilePathInDb]);

    if (!$res) {
        $_SESSION['errors'] = "Ошибка при добавлении данных в таблицу victories";
        return false;
    }

    $victoryId = $pdo->lastInsertId();

    $res = $pdo->prepare("INSERT INTO questions (victory_id, question_text, option1, option2, option3, correct_option) VALUES (?, ?, ?, ?, ?, ?)");

    for ($i = 0; $i < $questionCount; $i++) {
        $question = $questions[$i] ?? null;
        $answer1 = $answers[$i][0] ?? null;
        $answer2 = $answers[$i][1] ?? null;
        $answer3 = $answers[$i][2] ?? null;
        $correctOption = $correctOptions[$i][0] ?? null;

        $res->execute([$victoryId, $question, $answer1, $answer2, $answer3, $correctOption]);

        if (!$res) {
            $_SESSION['errors'] = "Ошибка при добавлении данных в таблицу questions";
            return false;
        }
    }

    $_SESSION['success'] = "Таблицы и данные созданы!";
    return true;
}

function edit_test($quizId)
{
    global $pdo;

    $quizName = $_POST['quiz_name'];
    $quizDescr = $_POST['quiz_description'];
    $quizImg = $_FILES['quiz_img'];
    $questionCount = count($_POST['question']);
    $questions = $_POST['question'];
    $answers = $_POST['answer'];
    $correctOptions = $_POST['correct_option'];

    if ($quizImg["name"] !== '' && isset($quizImg)) {
        if ($quizImg["error"] === UPLOAD_ERR_OK) { // Проверяем отсутствие ошибок при загрузке файла
            $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
            $maxFileSize = 5 * 1024 * 1024; // Максимальный размер файла (в байтах)

            // Проверяем тип файла и его размер
            if (in_array($quizImg["type"], $allowedTypes) && $quizImg["size"] <= $maxFileSize) {
                $targetDir = "./img/";
                $targetFile = $targetDir . basename($quizImg["name"]);
                $targetFilePathInDb = $targetDir . $quizImg["name"];

                // Перемещаем загруженный файл в целевую директорию
                if (!move_uploaded_file($quizImg["tmp_name"], $targetFile)) {
                    $_SESSION['errors'] = "Ошибка при перемещении файла.";
                    return false;
                }
            } else {
                $_SESSION['errors'] = "Ошибка: Недопустимый тип файла или превышен допустимый размер.";
                return false;
            }
        } else {
            $_SESSION['errors'] = "Ошибка при загрузке файла: " . $quizImg["error"];
            return false;
        }
    } else {
        $targetFilePathInDb = './img/default.png';
    }

    if (empty($quizName)) {
        $_SESSION['errors'] = 'Поле название викторины обязательно';
        return false;
    }

    if (count($questions) !== count($answers) || count($questions) !== count($correctOptions)) {
        $_SESSION['errors'] = "Неправильное количество вопросов, ответов или правильных вариантов!";
        return false;
    }

    $res = $pdo->prepare("UPDATE victories SET title = ?, description = ?, img = ? WHERE id = ?");
    $res->execute([$quizName, $quizDescr, $targetFilePathInDb, $quizId]);

    if (!$res) {
        $_SESSION['errors'] = "Ошибка при добавлении данных в таблицу victories";
        return false;
    }

    $stml = $pdo->prepare("SELECT id FROM questions WHERE victory_id = ?");
    $stml->execute([$quizId]);

    $questionsIds = $stml->fetchAll(PDO::FETCH_COLUMN);

    for ($i = 0; $i < $questionCount; $i++) {
        $questionId = $questionsIds[$i] ?? null;
        $question = $questions[$i] ?? null;
        $answer1 = $answers[$i][1] ?? null;
        $answer2 = $answers[$i][2] ?? null;
        $answer3 = $answers[$i][3] ?? null;
        $correctOption = $correctOptions[$i][0] ?? null;

        $res = $pdo->prepare("UPDATE questions SET question_text = ?, option1 = ?, option2 = ?, option3 = ?, correct_option = ? WHERE victory_id = ? AND id = ?");
        $res->execute([$question, $answer1, $answer2, $answer3, $correctOption, $quizId, $questionId]);

        if (!$res) {
            $_SESSION['errors'] = "Ошибка при добавлении данных в таблицу questions";
            return false;
        }
    }

    if (isset($_POST['question[]']) && isset($_POST['answer[]']) && isset($_POST['correct_option_new'])) {
        $newQuestion = $_POST['question[]'];
        $newAnswer = $_POST['answer[]'];
        $newCorrectOption = $_POST['correct_option_new'];

        for ($i = 0; $i < $newQuestion; $i++) {
            $newQuestion = $questions[$i] ?? null;
            $newAnswer1 = $answers[$i][0] ?? null;
            $newAnswer2 = $answers[$i][1] ?? null;
            $newAnswer3 = $answers[$i][2] ?? null;
            $newCorrectOption = $correctOptions[$i][0] ?? null;
        }
    }


    $_SESSION['success'] = "Таблицы и данные обновленны!";
    return true;
}

function send_test()
{
    global $pdo;
    $testId = $_GET['id'];

    $countQuestions = $pdo->prepare("SELECT COUNT(id) FROM questions WHERE victory_id = :testId");
    $countQuestions->execute([':testId' => $testId]);
    $totalQuestions = $countQuestions->fetchColumn();

    if (!isset($_POST['answer']) || !is_array($_POST['answer']) || count($_POST['answer']) !== $totalQuestions) {
        $_SESSION['errors'] = "Вы ответили не на все вопросы!";
        header("Location: test.php?id=$testId");
        exit;
    }

    $stmt = $pdo->prepare("SELECT correct_option FROM questions WHERE victory_id = :testId");
    $stmt->bindParam(':testId', $testId, PDO::PARAM_INT);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalCorrectAnswers = 0;

    foreach ($_POST['answer'] as $questionIndex => $answers) {
        $questionId = $questionIndex + 1;

        if (isset($results[$questionIndex]['correct_option']) && isset($answers["answer_$questionIndex"]) && $answers["answer_$questionIndex"] == $results[$questionIndex]['correct_option']) {
            $totalCorrectAnswers++;
        }
    }

    $login = $_SESSION['user']['login'];

    $userId = $pdo->prepare("SELECT id FROM users WHERE login = :login");
    $userId->execute([':login' => $login]);
    $userId = $userId->fetchColumn();

    $victoriesId = $pdo->prepare("SELECT id FROM victories WHERE id = :testId");
    $victoriesId->execute([':testId' => $testId]);
    $victoriesId = $victoriesId->fetchColumn();

    $datePassage = date('Y-m-d H:i:s');
    $finaled = 1;


    $pdo->prepare("INSERT INTO results (user_id, victories_id, date_passage, points, finaled) VALUES (:userId, :victoriesId, :datePassage, :points, :finaled)")
        ->execute([
            ':userId' => $userId,
            ':victoriesId' => $victoriesId,
            ':datePassage' => $datePassage,
            ':points' => $totalCorrectAnswers,
            ':finaled' => $finaled
        ]);

    $_SESSION['success'] = "Тест успешно пройден!";
}