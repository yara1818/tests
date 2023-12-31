<?php
session_start();
error_reporting(E_ALL);
require_once __DIR__ . "/functions.php";

if (empty($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if ($_SESSION['user']['login'] == 'Admin') {
    header("Location: admin.php");
    exit;
}

if (isset($_GET) && isset($_GET['logout'])) {
    logout();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/user.css">
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <title>Личный кабинет</title>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <? if (!empty($_SESSION['errors'])): ?>
                <div class="modal-window modal-window_error">
                    <div class="modal-window__container">
                        <button
                            class="modal-window__button modal-window__button_error modal-window__button_error-exit">Закрыть</button>
                        <p class="modal-window__title modal-window__title_error">Error:
                            <?php echo $_SESSION['errors'];
                            unset($_SESSION['errors']); ?>
                        </p>
                    </div>
                </div>
            <? endif; ?>
            <? if (!empty($_SESSION['success'])): ?>
                <div class="modal-window modal-window_success">
                    <div class="modal-window__container">
                        <button
                            class="modal-window__button modal-window__button_success modal-window__button_success-exit">Закрыть</button>
                        <p class="modal-window__title modal-window__title_success">Succes:
                            <?php echo $_SESSION['success'];
                            unset($_SESSION['success']); ?>
                        </p>
                    </div>
                </div>
            <? endif; ?>
            <div class="modal-window modal-window_user-exit">
                <div class="modal-window__container">
                    <p class="modal-window__title">Вы уверенны, что хотите выйти из аккаунта?</p>
                    <div class="modal-window__buttons">
                        <button class="button modal-window__button modal-window__button_yes">Да</button>
                        <button class="button modal-window__button modal-window__button_no">Нет</button>
                    </div>
                </div>
            </div>
            <header class="header">
                <div class="header__container">
                    <nav class="menu">
                        <ul class="menu__list">
                            <li class="menu__item">
                                <a href="index.php#main" class="menu__link">На главную</a>
                            </li>
                        </ul>
                    </nav>
                    <div class="header__content">
                        <h1 class="header__title">Личный кабинет</h1>
                        <p class="header__description">Здравствуйте,
                            <?= htmlspecialchars($_SESSION['user']['login']) ?>
                        </p>
                        <button class="header__button button_exit button">Выйти из аккаунта</button>
                    </div>
                </div>
            </header>
            <section class="section section__quizzes">
                <div class="quizzes__container">
                    <?php
                    $res = $pdo->prepare("SELECT COUNT(*) FROM victories");
                    $res->execute();
                    $count = $res->fetchColumn();
                    $res = $pdo->prepare("SELECT * FROM victories");
                    $res->execute();
                    $tests = $res->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($tests as $key => $value) {
                        $name = $value['title'];
                        $description = $value['description'];
                        $img = $value['img'];
                    }
                    ?>
                    <?php
                    $countFalse = $pdo->prepare("SELECT COUNT(*) FROM victories");
                    $countFalse->execute();
                    $countFalse = $countFalse->fetchColumn();

                    if ($countFalse > 0) {
                        echo "
                        <h2 class='section__title quizzes__title quizzes__title_progress'>Тесты, доступные вам для прохождения:</h2>
                        <div class='quizzes__quiz-cards quizzes__quiz-cards_progress'>
                        ";

                        $noTestsAvailable = true;

                        foreach ($tests as $test) {
                            $testId = $test['id'];

                            $stml = $pdo->prepare("SELECT finaled FROM results WHERE victories_id = ?");
                            $stml->execute([$testId]);
                            $finaled = $stml->fetchColumn();

                            if ($finaled != 1) {
                                echo "
                                <div class='quiz-card__card'>
                                    <div class='card__container'>
                                        <p class='card__title'>Title: {$test['title']}</p>
                                        <img class='card__img' src='{$test['img']}'>
                                        <p class='card__description'>Description: {$test['description']}</p>
                                        <a href='test.php?id={$test['id']}' class='card__link link'>Перейти к тесту</a>
                                    </div>
                                </div>
                                ";
                                $noTestsAvailable = false;
                            }
                        }

                        if ($noTestsAvailable) {
                            echo "<p class ='section__text section__text_warring'>Тестов доступных для вас ещё нет</p>";
                        }
                    }
                    ?>
                </div>
                <h2 class="section__title quizzes__title quizzes__title_no-progress">Тесты, которые вы уже прошли: </h2>
                <div class="quizzes__quiz-cards quizzes__quiz-cards_no-progress">
                    <?php
                    $countTrue = $pdo->prepare("SELECT COUNT(*) FROM results WHERE finaled = 1");
                    $countTrue->execute();
                    $countTrue = $countTrue->fetchColumn();

                    if ($countTrue > 0) {

                        for ($i = 0; $i < $count; $i++) {
                            $testId = $tests[$i]['id'];

                            $stml = $pdo->prepare("SELECT finaled FROM results WHERE finaled = 1 AND victories_id = ?");
                            $stml->execute([$testId]);
                            $finaled = $stml->fetchColumn();

                            $balls = $pdo->prepare("SELECT points FROM results WHERE victories_id = ?");
                            $balls->execute([$testId]);
                            $points = $balls->fetchColumn();

                            if ($finaled == 1) {
                                echo "
                                        <div>
                                            <div class='quiz-card__card'>
                                                <div class='card__container'>
                                                    <p class='card__title'>Title: {$tests[$i]['title']}</p>
                                                    <img class='card__img' src='{$tests[$i]['img']}'>
                                                    <p class='card__description'>Description: {$tests[$i]['description']}</p>
                                                    <p class='card__points'>Количество набранных очков: {$points}</p>
                                                </div>
                                            </div>
                                        </div>
                                        ";
                            }
                        }
                    } else {
                        echo "<p class ='section__text section__text_warring'>Тесты которые вы прошли ещё нет</p>";
                    }
                    ?>
                </div>
            </section>
            <footer class="footer">
                <div class="footer__container">
                    <p class="footer__text">&copy; 2022 QuizYar</p>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="./js/index.js"></script>
    <script src="./js/user.js"></script>
</body>

</html>