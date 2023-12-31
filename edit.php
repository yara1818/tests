<?php
session_start();
error_reporting(E_ALL);
require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/csfrtoken.php";
$quizId = $_GET['id'];

$res = $pdo->query("SELECT * FROM victories WHERE id = $quizId");
$tests = $res->fetchAll(PDO::FETCH_ASSOC);
foreach ($tests as $key => $value) {
    $question = $value['title'];
    $descr = $value['description'];
    $img = $value['img'];
    $id = $value['id'];
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
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="./css/edit.css">
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">

    <title>Редактирование викторины</title>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <main class="main">
                <div class="main__container">
                    <div class="main__quiz-list">
                        <div class="quiz-list__container">
                            <div class="quiz-list__quizzes">
                                <div class="quizzes__container">
                                    <h1 class="main__title">Редактирование теста</h1>
                                    <form class="form__edit-quiz" method="post" enctype="multipart/form-data"
                                        action="edit_test.php?id=<?= $quizId ?>">
                                        <input type="hidden" name="csrf_token" value="<?= $csfrTokenEditQuiz ?>">
                                        <?php
                                        foreach ($tests as $key => $value) {
                                            $question = $value['title'];
                                            $descr = $value['description'];
                                            $img = $value['img'];
                                            $id = $value['id'];

                                            echo "<div class ='form__item'><span>Название викторины:</span> <input class ='form__input' type='text' name='quiz_name' value='$question'></div>";
                                            echo "<div class ='form__item'><span>Описание викторины:</span> <input class ='form__input' type='text' name='quiz_description' value='$descr'></div>";
                                            echo "<div class ='form__item'><span class ='form__item-text'>Текущая картинка викторины:</span> <img src='$img'></div>";
                                            echo "<div class ='form__item'><span>Обновить картинку викторины:</span> <input  class ='form__input' type='file' name='quiz_img'></div>";

                                            $res = $pdo->query("SELECT * FROM questions WHERE victory_id = $quizId");
                                            $questions = $res->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($questions as $key => $value) {
                                                $question = $value['question_text'];
                                                $option1 = $value['option1'];
                                                $option2 = $value['option2'];
                                                $option3 = $value['option3'];
                                                $correct_option = $value['correct_option'];

                                                echo "<div class ='form__item'><span>Вопрос {$key}:</span> <input class ='form__input' type='text' name='question[$key]' value='$question'></div>";
                                                echo "<div class ='form__item'><span>Ответ 1:</span> <input class ='form__input' type='text' name='answer[$key][1]' value='$option1'></div>";
                                                echo "<div class ='form__item'><span>Ответ 2:</span> <input class ='form__input' type='text' name='answer[$key][2]' value='$option2'></div>";
                                                echo "<div class ='form__item'><span>Ответ 3:</span> <input class ='form__input' type='text' name='answer[$key][3]' value='$option3'></div>";
                                                echo "<div class ='form__item'><span>Правильный вариант:</span> 
                                            <select class ='form__input form__input_select' name='correct_option[$key]' required>
                                                <option class ='form__input ' value='1' " . ($correct_option == '1' ? 'selected' : '') . ">Вариант 1</option>
                                                <option class ='form__input' value='2' " . ($correct_option == '2' ? 'selected' : '') . ">Вариант 2</option>
                                                <option class ='form__input' value='3' " . ($correct_option == '3' ? 'selected' : '') . ">Вариант 3</option>
                                            </select>
                                        </div>
                                        <br>";
                                            }
                                        }
                                        ?>
                                        <button class="button button__add-test" type="button">Добавить ещё один вопрос с
                                            ответами</button>
                                        <button class="button" type="submit"
                                            value="Сохранить викторину">Отправить</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </main>
        </div>
    </div>
</body>

</html>