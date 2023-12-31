<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/404.css">
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <title>404</title>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="wrapper__error">
                <div class="error__container">
                    <h1 class="error__title">Данной страницы не существует: 404</h1>
                    <a href="index.php" class="error__link">На главную</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>