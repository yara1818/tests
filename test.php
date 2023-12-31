<?php
session_start();
error_reporting(E_ALL);
require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/csfrtoken.php";
$quizId = $_GET['id'];

if (!isset($quizId)) {
    header("Location: user.php");
    exit;
}

$userId = $pdo->prepare("SELECT id FROM users WHERE login = ?");
$userId->execute([$_SESSION['user']['login']]);
$userId = $userId->fetchColumn();

$stml = $pdo->prepare("SELECT finaled FROM results WHERE victories_id = ? AND user_id = ?");
$stml->execute([$quizId, $userId]);
$finaled = $stml->fetchColumn();

$res = $pdo->prepare("SELECT * FROM victories WHERE id = :testId");
$res->bindParam(':testId', $quizId, PDO::PARAM_INT);
$res->execute();
$test = $res->fetch(PDO::FETCH_ASSOC);

$title = $test['title'];
$description = $test['description'];
$img = $test['img'];

$testExample = $pdo->prepare("SELECT * FROM questions WHERE victory_id = :testId");
$testExample->bindParam(':testId', $quizId, PDO::PARAM_INT);
$testExample->execute();
$quiz = $testExample->fetchAll(PDO::FETCH_ASSOC);


if ($finaled == 1) {
    header("Location: user.php");
    exit;
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
    <link rel="stylesheet" href="./css/test.css">
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">

    <title>Прохождение теста</title>
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
            <header id="main" class="header">
                <div class="header__container">
                    <nav class="menu">
                        <ul class="menu__list">
                            <li class="menu__item">
                                <a href="index.php#main" class="menu__link">На главную</a>
                            </li>
                            <li class="menu__item">
                                <a href="user.php" class="menu__link">В личный кабинет</a>
                            </li>
                        </ul>
                    </nav>
                    <div class="header__content">
                        <h1 class="header__title">Прохождение теста</h1>
                    </div>
                </div>
            </header>
            <section class="section section__test">
                <div class="container__test">
                    <div class="test__info">
                        <div class="info__container">
                            <p class="test__title">Информация о тесте: </p>
                            <img class="test__img" src="<?= $img ?>">
                            <p class="test__theme">Название:
                                <?= $title ?>
                            </p>
                            <p class="test__description">Описание:
                                <?= $description ?>
                            </p>
                        </div>
                    </div>
                    <form action="send_test.php?id=<?= $quizId ?>" class="form" method="post">
                        <input type="hidden" name="csrf_token" value="<?= $csfrTokenTest; ?>">
                        <div class="form__container">
                            <div class="form__list">
                                <?php
                                foreach ($quiz as $key => $value) {
                                    $answers = array();

                                    $answers[] = $value['option1'];
                                    $answers[] = $value['option2'];
                                    $answers[] = $value['option3'];

                                    echo
                                        "
                                    <div class='form__item'>
                                        <div class='item__container'>
                                            <p class='form__question'>Вопрос №{$key} - {$value['question_text']}</p>
                                            <div class='form__answers'>
                                                <div class='form__answer'>
                                                    <input class='answer__input' name='answer[$key][answer_$key]' type='radio' value='1'> 
                                                    <p class='answer__text'>{$value['option1']}</p>
                                                </div>
                                                <div class='form__answer'>
                                                    <input class='answer__input' name='answer[$key][answer_$key]' type='radio' value='2'> 
                                                    <p class='answer__text'>{$value['option2']}</p>
                                                </div>
                                                <div class='form__answer'>
                                                    <input class='answer__input' name='answer[$key][answer_$key]' type='radio' value='3'> 
                                                    <p class='answer__text'>{$value['option3']}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    ";
                                }
                                ?>
                            </div>
                            <button type="submit" class="button form__button form__button_submit">Отправить</button>
                        </div>
                    </form>
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
</body>

</html>