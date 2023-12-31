<?php
session_start();
error_reporting(E_ALL);
require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/csfrtoken.php";

if (isset($_GET['logout'])) {
    logout();
}

// echo "<pre> print_r($_POST) ."</pre>"
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="./css/user.css">
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">

    <title>Админ</title>
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
            <main class="main">
                <div class="main__container">
                    <h1 class="main__title">Админ панель - Здравствуйте, Админ</h1>
                    <button class="button main__button button_exit">Выйти из аккаунта</button>
                    <div class="main__create-quiz">
                        <div class="create-quiz__container">
                            <h2 class="main__create-title">Создание нового теста</h2>
                            <button class="create-quiz__button create-quiz__button_create-quiz">Создать новый
                                тест</button>
                            <form class="create-quiz__form" action="create_quiz.php" method="POST"
                                enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?= $csfrTokenCreateQuiz ?>">
                                <div class="form__item">
                                    <span>
                                        *Придумайте название теста:
                                    </span>
                                    <input class="form__input" type="text" name="quiz_name"
                                        placeholder="Придумайте название теста" required>
                                </div>
                                <div class="form__item">
                                    <span>
                                        *Придумайте описание теста:
                                    </span>
                                    <input class="form__input" type="text" name="quiz_description"
                                        placeholder="Придумайте описание теста" required>
                                </div>
                                <div class="form__item">
                                    <span>
                                        Укажите путь к картинке теста:
                                    </span>
                                    <input class="form__input form__input_file" type="file" name="quiz_img">
                                </div>
                                <div class="form__questions"></div>
                                <button class="form__button form__button_add-question" type="button">Добавить ещё один
                                    вопрос с ответами</button>
                                <button class="form__button form__button_submit" type="submit"
                                    value="Сохранить викторину">Отправить</button>
                            </form>
                        </div>
                    </div>
                    <?php
                    $count = $pdo->prepare("SELECT COUNT(*) FROM victories");
                    $count->execute();
                    $count = $count->fetchColumn();
                    ?>
                    <div class="main__quiz-list">
                        <div class="quiz-list__container">
                            <h2 class="quiz-list__title">Список тестов:</h2>
                            <p class="quiz-list__total">Всего викторин:
                                <?= $count ?>
                            </p>
                            <div class="quiz-list__quizzes">
                                <div class="quizzes__container">
                                    <?php
                                    if ($count !== 0) {

                                        $res = $pdo->query("SELECT * FROM victories");
                                        $tests = $res->fetchAll(PDO::FETCH_ASSOC);


                                        for ($i = 0; $i < $count; $i++) {
                                            foreach ($tests as $key => $value) {
                                                $subarray[$key]['title'] = $value['title'];
                                                $subarray[$key]['description'] = $value['description'];
                                                $subarray[$key]['img'] = $value['img'];
                                                $subarray[$key]['id'] = $value['id'];
                                            }
                                            echo "
                                                <div class='quiz-list__quiz'>
                                                    <div class='quiz__container'>
                                                        <h3 class='quiz__title'>Название теста - {$subarray[$i]['title']}</h3>
                                                        <img class='quiz__img' src='{$subarray[$i]['img']}'>
                                                        <p class='quiz__description'>Описание теста - {$subarray[$i]['description']}</p>
                                                        <div class='quiz__buttons'>
                                                            <a class='quiz-list__button' href='edit.php?id={$subarray[$i]['id']}'>Редактировать тест</a>
                                                            <a class='quiz-list__button' href='delete.php?id={$subarray[$i]['id']}'>Удалить тест</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            ";
                                        }
                                    } else {
                                        echo "<p>Тестов ещё нет</p>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="footer">
                <div class="footer__container">
                    <p class="footer__text">&copy; 2022 QuizYar</p>
                </div>
            </footer>
        </div>
    </div>
    <script type="text/javascript" src="./js/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="./js/index.js"></script>
    <script type="text/javascript" src="./js/admin.js"></script>
    <script type="text/javascript" src="./js/user.js"></script>
</body>

</html>