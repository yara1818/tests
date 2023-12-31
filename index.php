<?php
session_start();
error_reporting(E_ALL);
require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/csfrtoken.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/index.css">
  <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
  <title>QuizYar</title>
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
                <a href="#main" class="menu__link">Главная</a>
              </li>
              <li class="menu__item">
                <a href="#about_us" class="menu__link">О нас</a>
              </li>
              <li class="menu__item">
                <a href="#tests" class="menu__link">Тесты</a>
              </li>
            </ul>
          </nav>
          <div class="header__content">
            <h1 class="header__title">QuizYar</h1>
            <p class="header__description">Проходите тесты вместе с нами</p>
            <?php if (!isset($_SESSION['user'])): ?>
              <button class="header__button header__button_test">Перейти к тестам</button>
            <?php else: ?>
              <button class="header__button header__button_user">Перейти к тестам</button>
            <?php endif; ?>
          </div>
        </div>
      </header>
      <section id="about_us" class="section section__about">
        <div class="container__about">
          <h2 class="section__title">О нас</h2>
          <p class="section__text"><span>QuizYar</span> - Добро пожаловать на наш сайт с тестами! Развлекайтесь,
            проверяйте свои знания и узнавайте что-то новое. Присоединяйтесь к нашему сообществу и начните свой
            увлекательный путь к успеху и саморазвитию!</p>
        </div>
      </section>
      <section id="tests" class="section section__tests">
        <div class="tests__container">
          <?php if (!isset($_SESSION['user'])): ?>
            <p class="section__text section__text_warring">Чтобы пройти тесты необходимо зарегистрироваться или войти в
              аккаунт!</p>
            <div class="tests__forms">
              <form method="post" action="registration.php" class="tests__form tests__form_reg">
                <div class="form__container">
                  <p class="form__title">Регистрация</p>
                  <input type="hidden" name="csrf_token" value="<?php echo $csrfTokenRegister; ?>">
                  <div class="form__item">
                    <span>
                      *Придумайте логин:
                    </span>
                    <input class="form__input" name="login" required="" type="text" placeholder="Придумайте ваш логин">
                  </div>
                  <div class="form__item">
                    <span>
                      *Придумайте пароль:
                    </span>
                    <input class="form__input" name="password" required="" type="password"
                      placeholder="Придумайте ваш пароль">
                  </div>
                  <div class="form__item">
                    <button class="form__button form__button_submit form__button_reg-submit" name="reg"
                      type="submit">Отправить</button>
                  </div>
                </div>
              </form>
              <form method="post" action="login.php" class="tests__form tests__form_auth">
                <div class="form__container">
                  <p class="form__title">Вход</p>
                  <input type="hidden" name="csrf_token" value="<?php echo $csrfTokenLogin; ?>">
                  <div class="form__item">
                    <span>
                      *Ваш логин:
                    </span>
                    <input class="form__input" name="login" required="" type="text" placeholder="Введите ваш логин">
                  </div>
                  <div class="form__item">
                    <span>
                      *Ваш пароль:
                    </span>
                    <input class="form__input" name="password" required="" type="password"
                      placeholder="Введите ваш пароль">
                  </div>
                  <div class="form__item">
                    <button class="form__button form__button_submit form__button_auth-submit" name="auth"
                      type="submit">Отправить</button>
                  </div>
                </div>
              </form>
            </div>
          <?php else: ?>
            <div class="tests__user-content">
              <p class="section__text section__text_warring">Перейдите в свой профиль для прохождения тестов</p>
              <a href="user.php" class="tests__link">Перейти</a>
            </div>
          <?php endif; ?>
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