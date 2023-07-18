<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Germania+One">
    <link rel="stylesheet" href="css/styleForShop.css" type="text/css"/>
</head>
<body>
    <canvas id="breakout" width="400" height="500" id="game"></canvas>

    <div class="shop"> 
        <?php
        // Подключение к БД 
        $mysql = new mysqli('127.0.0.1', 'root', 'root', 'breakoutdb');
        ?>

        <div class="score">
            <?php
                // Запрос для получения значения score из таблицы user
                $userId = $_COOKIE['userId'];
                $query = "SELECT `score` FROM `user` WHERE `id` = '{$userId}'";
                $result = $mysql->query($query);

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $score = $row['score'];
                    echo "Score: " . $score;
                } else {
                    echo "Score не найден";
                }
            ?>
        </div>

        <?php
            // Запрос для получения idItem, купленных пользователем
            $userId = $_COOKIE['userId'];
            $query = "SELECT idItem FROM shop WHERE idUser = '{$userId}'";
            $result = $mysql->query($query);

            // Массив для хранения idItem, купленных пользователем
            $purchasedItems = array();

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $purchasedItems[] = $row['idItem'];
                }
            }

            // Вывод информации о покупках
            $item1Price = "Purchased";
            $item2Price = 1000;
            $item3Price = 2000;

        ?>

        <div class="shop__items">
            <div class="item">
                <img class="shop__item" src="img/bg.jpg" alt="">
                <div class="price">
                    <?php
                    // Проверка, является ли item1 купленным товаром
                    if (in_array(1, $purchasedItems)) {
                        echo "Purchased";
                    } else {
                        echo $item1Price;
                    }
                    ?>
                </div>
            </div>
            <div class="item">
                <img class="shop__item" src="img/bg1.jpg" alt="">
                <div class="price">
                    <?php
                    // Проверка, является ли item2 купленным товаром
                    if (in_array(2, $purchasedItems)) {
                        echo "Purchased";
                    } else {
                        echo $item2Price;
                    }
                    ?>
                </div>
            </div>
            <div class="item">
                <img class="shop__item" src="img/bg2.jpg" alt="">
                <div class="price">
                    <?php
                    // Проверка, является ли item3 купленным товаром
                    if (in_array(3, $purchasedItems)) {
                        echo "Purchased";
                    } else {
                        echo $item3Price;
                    }
                    ?>
                </div>
            </div>
        </div>

        <form action="game.php"  method="post">
            <input type="radio" id="Choice1" name="choise" value="1">
            <input type="radio" id="Choice2" name="choise" value="2">
            <input type="radio" id="Choice3" name="choise" value="3">
            <input type="hidden" name="background" id="background" value="">

            <div class="exit">
                <button type="submit" class="round-button">
                    <img src="img/saveAndExit.png" alt="Save and Exit">
                </button>
            </div>
        </form>
    </div>

    <?php
    // Закрытие подключения к БД
    $mysql->close();
    ?>

    <script>
        document.querySelector('.round-button').addEventListener('click', function() {
            var selectedBackground = document.querySelector('input[name="choise"]:checked').value;
            document.getElementById('background').value = selectedBackground;
        });
    </script>
</body>
</html>
