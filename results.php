<!DOCTYPE html>
<html lang="ru" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="Results" />
    <meta property="og:url" content="http://breakout/index.php" />
    <meta property="og:description" content="Тест" />
    <title>Results</title>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Germania+One">
    <link rel="stylesheet" href="css/styleForResults.css" type="text/css"/>
</head>
<body>
    <?php
    // Подключение к БД 
    $mysql = new mysqli('127.0.0.1', 'root', 'root', 'breakoutdb');
    ?>

    <canvas id="breakout" width="400" height="500" id="game"></canvas>

    <div class="results"> 
        <div class="results__title">5 ваших последних результатов</div>
        <div class="results__content">        
        <?php 
            $result = $mysql->query("SELECT `login`, `result`.`score` FROM `result` 
            LEFT JOIN `user` ON `user`.`id` = `result`.`idUser` 
            WHERE `idUser` = {$_COOKIE['userId']} 
            ORDER BY `result`.`id` DESC 
            LIMIT 5");

            if ($result) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "Login: " . $row['login'] . ", Score: " . $row['score'] . "<br>";
                    }
                } else {
                    echo "Вы не сыграли ещё ни одной игры";
                }
            } else {
                echo "Ошибка выполнения запроса: " . $mysql->error;
            }
        ?>
        </div>
        <div class="share">Поделиться результатом:</div>
        <script defer src="https://yastatic.net/share2/share.js"></script>
        <br>
        <script src="https://yastatic.net/share2/share.js"></script>
        <div class="ya-share2" data-curtain data-size="l" data-services="vkontakte,odnoklassniki,telegram" data-title="Breakout" data-url="http://breakout/index.php"></div>
        <div class="exit">
            <a href="game.php"><img src="img/exit.png" alt="" id="exit"></a>
        </div>
    </div>

    <?php
    // Закрытие подключения к БД
    $mysql->close();
    ?>
</body>
</html>