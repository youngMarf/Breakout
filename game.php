<?php
    // Подключение к БД 
    $mysql = new mysqli('127.0.0.1', 'root', 'root', 'breakoutdb');                
    // Запрос для получения значения score из таблицы user
    $userId = $_COOKIE['userId'];
    $query = "SELECT `score` FROM `user` WHERE `id` = '{$userId}'";
    $result = $mysql->query($query);

    // Запрос для получения idItem, купленных пользователем
    $query = "SELECT `idItem` FROM `shop` WHERE `idUser` = '{$userId}'";
    $resultItems = $mysql->query($query);

    // Массив для хранения idItem, купленных пользователем
    $purchasedItems = array();

    if ($resultItems && $resultItems->num_rows > 0) {
        while ($row = $resultItems->fetch_assoc()) {
            $purchasedItems[] = $row['idItem'];
        }
    }

    if (isset($_POST['background'])) {
        $selectedBackground = $_POST['background'];

        if (in_array($selectedBackground, $purchasedItems)) {
            // Фон уже куплен, изменяем задний фон canvas
            if ($selectedBackground == '1') {
                $backgroundStyle = 'img/bg.jpg';
            } elseif ($selectedBackground == '2') {
                $backgroundStyle = 'img/bg1.jpg';
            } elseif ($selectedBackground == '3') {
                $backgroundStyle = 'img/bg2.jpg';
            }
        } else {
            // Фон не был куплен
            $item1Price = 0;  // Цена фона 1
            $item2Price = 1000;  // Цена фона 2
            $item3Price = 2000;  // Цена фона 3

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $scoreValue = $row['score'];

                if ($selectedBackground == '1') {
                    if ($scoreValue >= $item1Price) {
                        // Достаточно средств, покупаем фон
                        $backgroundStyle = 'img/bg.jpg';
                        $purchasedItems[] = $selectedBackground;

                        // Обновление значения score в таблице user
                        $query = "UPDATE `user` SET `score` = '{$scoreValue}' WHERE `id` = '{$userId}'";
                        $mysql->query($query);

                        // Добавление записи о покупке в таблицу shop
                        $query = "INSERT INTO `shop` (`idUser`, `idItem`) VALUES ('{$userId}', '{$selectedBackground}')";
                        $mysql->query($query);
                    } else {
                        // Недостаточно средств, перенаправляем на страницу с сообщением об ошибке
                        header("Location: noMoney.html");
                        exit;
                    }
                } elseif ($selectedBackground == '2') {
                    // Аналогичная проверка для фона 2
                    if ($scoreValue >= $item2Price) {
                        // Достаточно средств, покупаем фон
                        $backgroundStyle = 'img/bg1.jpg';
                        $purchasedItems[] = $selectedBackground;

                        $scoreValue = $scoreValue - $item2Price;

                        // Обновление значения score в таблице user
                        $query = "UPDATE `user` SET `score` = '{$scoreValue}' WHERE `id` = '{$userId}'";
                        $mysql->query($query);

                        // Добавление записи о покупке в таблицу shop
                        $query = "INSERT INTO `shop` (`idUser`, `idItem`) VALUES ('{$userId}', '{$selectedBackground}')";
                        $mysql->query($query);
                    } else {
                        // Недостаточно средств, перенаправляем на страницу с сообщением об ошибке
                        header("Location: noMoney.html");
                        exit;
                    }
                } elseif ($selectedBackground == '3') {
                    // Аналогичная проверка для фона 3
                    if ($scoreValue >= $item3Price) {
                        // Достаточно средств, покупаем фон
                        $backgroundStyle = 'img/bg2.jpg';
                        $purchasedItems[] = $selectedBackground;

                        $scoreValue = $scoreValue - $item3Price;

                        // Обновление значения score в таблице user
                        $query = "UPDATE `user` SET `score` = '{$scoreValue}' WHERE `id` = '{$userId}'";
                        $mysql->query($query);

                        // Добавление записи о покупке в таблицу shop
                        $query = "INSERT INTO `shop` (`idUser`, `idItem`) VALUES ('{$userId}', '{$selectedBackground}')";
                        $mysql->query($query);
                    } else {
                        // Недостаточно средств, перенаправляем на страницу с сообщением об ошибке
                        header("Location: noMoney.html");
                        exit;
                    }
                } else {
                    $backgroundStyle = 'img/bg.jpg';
                }
            }
        }
    } else {
        // Если не был выбран фон, установите фон по умолчанию или выполните другие действия
        $backgroundStyle = 'img/bg.jpg';
    }

    $mysql->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Breakout</title>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Germania+One">
    <link rel="stylesheet" href="css/style.css" type="text/css"/>
</head>
<body>

    <img src="img/bg.jpg" alt="" id="start__bg">

    <div class="start">
        <img src="img/start.png" alt="" id="start">
    </div>

    <div class="shop">
        <a href="shop.php"><img src="img/shop.png" alt="" id="settings"></a>
    </div>

    <div class="results">
        <a href="results.php"><img src="img/results.png" alt="" id="results"></a>
    </div>

    <div class="exit">
        <a href="php/exit.php"><img src="img/exit.png" alt="" id="exit"></a>
    </div>

    <div class="pauseMenu" id="pauseMenu">
        <div class="gamePause">
        </div>
        <div class="restartFromPause">
            <img src="img/restart.png" alt="" id="restartFromPause">
        </div>

        <div class="menu">
            <a href="game.php"><img src="img/menu.png" alt="" id="menu"></a>
        </div>

        <div class="back">
            <img src="img/back.png" alt="" id="back">
        </div>
    </div>

    <div id="game">
        <div class="sound">
            <img src="img/SOUND_ON.png" alt="" id="sound">
        </div>
        
        <div id="gameover">
            <img src="img/youwon.png" alt="" id="youwon">
            <img src="img/gameover.png" alt="" id="youlose">
            <div id="restart">Play Again!</div>
        </div>

        <div class="pause">
            <img src="img/PAUSE_OFF.png" alt="" id="pause">
        </div>
    
        <canvas id="breakout" width="400" height="500" id="game"></canvas>
    </div>
    <script>
        /////// LOAD IMAGES ////////

        // LOAD BG IMAGE
        const BG_IMG = new Image();
        BG_IMG.src = "<?php echo $backgroundStyle; ?>";

        const LEVEL_IMG = new Image();
        LEVEL_IMG.src = "img/level.png";

        const LIFE_IMG = new Image();
        LIFE_IMG.src = "img/life.png";

        const SCORE_IMG = new Image();
        SCORE_IMG.src = "img/score.png";


        /////// END LOAD IMAGES ////////

        // ************************ //

        /////// LOAD SOUNDS ////////

        const WALL_HIT = new Audio();
        WALL_HIT.src = "sounds/wall.mp3";

        const LIFE_LOST = new Audio();
        LIFE_LOST.src = "sounds/life_lost.mp3";

        const PADDLE_HIT = new Audio();
        PADDLE_HIT.src = "sounds/paddle_hit.mp3";

        const WIN = new Audio();
        WIN.src = "sounds/win.mp3";

        const BRICK_HIT = new Audio();
        BRICK_HIT.src = "sounds/brick_hit.mp3";

        const BONUS = new Audio();
        BONUS.src = "sounds/BONUS.mp3";


        /////// END LOAD SOUNDS ////////

        // SELECT CANVAS ELEMENT
        const cvs = document.getElementById("breakout");
        const ctx = cvs.getContext("2d");

        // ADD BORDER TO CANVAS
        cvs.style.border = "1px solid #000";
        cvs.style.borderRadius = "15px";

        // MAKE LINE THIK WHEN DRAWING TO CANVAS
        ctx.lineWidth = 3;

        // GAME VARIABLES AND CONSTANTS
        const PADDLE_WIDTH = 100;
        const PADDLE_MARGIN_BOTTOM = 55;
        const PADDLE_HEIGHT = 20;
        const BALL_RADIUS = 8;
        const BONUS_LIFE_COLOR = "red";
        const BONUS_PADDLE_SIZE_COLOR = "blue";
        const BONUS_EXTRA_BALL_COLOR = "green";
        let LIFE = 3; // PLAYER HAS 3 LIVES
        let SCORE = 0;
        const SCORE_UNIT = 10;
        let LEVEL = 1;
        const MAX_LEVEL = 3;
        let GAME_OVER = true;
        let leftArrow = false;
        let rightArrow = false;

        // CREATE BONUS
        bonus = {
            x: 0,
            y: 0,
            radius: 10,
            speed: 2,
            type: "",
            active: false
        };

        // MOVE BONUS
        function moveBonus() {
            if (bonus.active) { 
                bonus.y += bonus.speed;

                // PADDLE COLLISION DETECTION
                if (bonus.y + bonus.radius > paddle.y && bonus.x > paddle.x && bonus.x < paddle.x + paddle.width) {
                    activateBonus(bonus.type);
                    bonus.active = false;
                }

                // BOTTOM SIDE OF CANVAS COLLISION DETECTION
                if (bonus.y + bonus.radius > cvs.height) {
                    bonus.active = false;
                }
            }
        }

        // DRAW BONUS
        function drawBonus() {
            ctx.beginPath();
            ctx.arc(bonus.x, bonus.y, bonus.radius, 0, Math.PI * 2);

            let bonusColor = "";
            switch (bonus.type) {
                case "red":
                    bonusColor = BONUS_LIFE_COLOR;
                    break;
                case "blue":
                    bonusColor = BONUS_PADDLE_SIZE_COLOR;
                    break;
                case "green":
                    bonusColor = BONUS_EXTRA_BALL_COLOR;
                    break;
            }

            ctx.fillStyle = bonusColor;
            ctx.fill();

            ctx.strokeStyle = "#2e3548";
            ctx.stroke();

            ctx.closePath();
        }

        // ACTIVATE BONUS
        function activateBonus(type) {
            BONUS.play();
            switch (type) {
                case "red":
                    LIFE++;
                    break;
                case "blue":
                    paddle.width += 20;
                    // SIZE LIMITATION
                    if (paddle.width > 150) {
                        paddle.width = 150;
                    }
                    break;
                case "green":
                    SCORE += 100;
                    break;
            }
        }

        // CREATE THE PADDLE
        const paddle = {
            x: cvs.width / 2 - PADDLE_WIDTH / 2,
            y: cvs.height - PADDLE_MARGIN_BOTTOM - PADDLE_HEIGHT,
            width: PADDLE_WIDTH,
            height: PADDLE_HEIGHT,
            dx: 5
        }

        // DRAW PADDLE
        function drawPaddle() {
            ctx.fillStyle = "#2e3548";
            ctx.fillRect(paddle.x, paddle.y, paddle.width, paddle.height);

            ctx.strokeStyle = "#ffcd05"; 
            ctx.strokeRect(paddle.x, paddle.y, paddle.width, paddle.height);
        }

        // CONTROL THE PADDLE
        document.addEventListener("keydown", function (event) {
            if (event.keyCode == 37) {
                leftArrow = true;
            } else if (event.keyCode == 39) {
                rightArrow = true;
            }
        });
        document.addEventListener("keyup", function (event) {
            if (event.keyCode == 37) {
                leftArrow = false;
            } else if (event.keyCode == 39) {
                rightArrow = false;
            }
        });

        // MOVE PADDLE
        function movePaddle() {
            if (rightArrow && paddle.x + paddle.width < cvs.width) {
                paddle.x += paddle.dx;
            } else if (leftArrow && paddle.x > 0) {
                paddle.x -= paddle.dx;
            }
        }

        // CREATE THE BALL
        const ball = {
            x: cvs.width / 2,
            y: paddle.y - BALL_RADIUS,
            radius: BALL_RADIUS,
            speed: 8,
            dx: 6 * (Math.random() * 2 - 1),
            dy: -6
        }

        // DRAW THE BALL
        function drawBall() {
            ctx.beginPath();

            ctx.arc(ball.x, ball.y, ball.radius, 0, Math.PI * 2);
            ctx.fillStyle = "#ffcd05";
            ctx.fill();

            ctx.strokeStyle = "#2e3548";
            ctx.stroke();

            ctx.closePath();
        }

        // MOVE THE BALL
        function moveBall() {
            ball.x += ball.dx;
            ball.y += ball.dy;
        }

        // BALL AND WALL COLLISION DETECTION
        function ballWallCollision() {
            if (ball.x + ball.radius > cvs.width || ball.x - ball.radius < 0) {
                ball.dx = - ball.dx;
                WALL_HIT.play(); // SOUND
            }

            if (ball.y - ball.radius < 0) {
                ball.dy = -ball.dy;
                WALL_HIT.play();
            }

            if (ball.y + ball.radius > cvs.height) {
                LIFE--; // LOSE LIFE
                LIFE_LOST.play();
                resetBall();
            }
        }

        // RESET THE BALL
        function resetBall() {
            ball.x = cvs.width / 2;
            ball.y = paddle.y - BALL_RADIUS;
            ball.dx = 6 * (Math.random() * 2 - 1);
            ball.dy = -6;

            bonus.active = false;
        }

        // BALL AND PADDLE COLLISION
        function ballPaddleCollision() {
            if (ball.x < paddle.x + paddle.width && ball.x > paddle.x && paddle.y < paddle.y + paddle.height && ball.y > paddle.y) {

                // PLAY SOUND
                PADDLE_HIT.play();

                // CHECK WHERE THE BALL HIT THE PADDLE
                let collidePoint = ball.x - (paddle.x + paddle.width / 2);

                // NORMALIZE THE VALUES
                collidePoint = collidePoint / (paddle.width / 2);

                // CALCULATE THE ANGLE OF THE BALL
                let angle = collidePoint * Math.PI / 3;


                ball.dx = ball.speed * Math.sin(angle);
                ball.dy = - ball.speed * Math.cos(angle);
            }
        }

        // CREATE THE BRICKS
        const brick = {
            row: 1,
            column: 5,
            width: 55,
            height: 20,
            offSetLeft: 20,
            offSetTop: 20,
            marginTop: 40,
            fillColor: "#2e3548",
            strokeColor: "#FFF"
        }

        let bricks = [];

        function createBricks() {
            for (let r = 0; r < brick.row; r++) {
                bricks[r] = [];
                for (let c = 0; c < brick.column; c++) {
                    bricks[r][c] = {
                        x: c * (brick.offSetLeft + brick.width) + brick.offSetLeft,
                        y: r * (brick.offSetTop + brick.height) + brick.offSetTop + brick.marginTop,
                        status: true
                    }
                }
            }
        }

        createBricks();

        // DRAW THE BRICKS
        function drawBricks() {
            for (let r = 0; r < brick.row; r++) {
                for (let c = 0; c < brick.column; c++) {
                    let b = bricks[r][c];
                    // if the brick isn't broken
                    if (b.status) {
                        ctx.fillStyle = brick.fillColor;
                        ctx.fillRect(b.x, b.y, brick.width, brick.height);

                        ctx.strokeStyle = brick.strokeColor;
                        ctx.strokeRect(b.x, b.y, brick.width, brick.height);
                    }
                }
            }
        }

        // BALL AND BRICK COLLISION 
        function ballBrickCollision() {
            for (let r = 0; r < brick.row; r++) {
                for (let c = 0; c < brick.column; c++) {
                    let b = bricks[r][c];
                    // if the brick isn't broken
                    if (b.status) {
                        if (ball.x + ball.radius > b.x && ball.x - ball.radius < b.x + brick.width && ball.y + ball.radius > b.y && ball.y - ball.radius < b.y + brick.height) {
                            BRICK_HIT.play();
                            ball.dy = - ball.dy;
                            b.status = false; // the brick is broken
                            SCORE += SCORE_UNIT;

                            if (Math.random() < 0.1 && !bonus.active) {
                                bonus.x = ball.x;
                                bonus.y = ball.y;
                                bonus.active = true;

                                const bonusTypes = ["red", "blue", "green"];
                                bonus.type = bonusTypes[Math.floor(Math.random() * bonusTypes.length)];
                            }
                        }
                    }
                }
            }
        }

        // SHOW GAME STATS
        function showGameStats(text, textX, textY, img, imgX, imgY) {
            // DRAW TEXT
            ctx.fillStyle = "#FFF";
            ctx.font = "25px Germania One";
            ctx.fillText(text, textX, textY);

            // draw image
            ctx.drawImage(img, imgX, imgY, width = 25, height = 25);
        }

        // DRAW FUNCTION
        function draw() {
            drawPaddle();

            drawBall();

            drawBricks();

            if (bonus.active) {
                drawBonus();
            }

            // SHOW SCORE
            showGameStats(SCORE, 35, 25, SCORE_IMG, 5, 5);
            // SHOW LIVES
            showGameStats(LIFE, cvs.width - 25, 25, LIFE_IMG, cvs.width - 55, 5);
            // SHOW LEVEL
            showGameStats(LEVEL, cvs.width / 2, 25, LEVEL_IMG, cvs.width / 2 - 30, 5);
        }

        // GAME OVER
        function gameOver() {
            if (LIFE <= 0) {
                showYouLose();
                GAME_OVER = true;
            }
        }

        // LEVEL UP
        function levelUp() {
            let isLevelDone = true;

            // CHECK IF ALL THE BRICKS ARE BROKEN 
            for (let r = 0; r < brick.row; r++) {
                for (let c = 0; c < brick.column; c++) {
                    isLevelDone = isLevelDone && !bricks[r][c].status;
                }
            }

            if (isLevelDone) {
                WIN.play();

                if (LEVEL >= MAX_LEVEL) {
                    showYouWin();
                    GAME_OVER = true;
                    return;
                }
                brick.row++;
                createBricks();
                ball.speed += 0.5;
                resetBall();
                LEVEL++;
            }
        }

        // UPDATE GAME FUNCTION
        function update() {
            movePaddle();

            moveBall();

            if (bonus.active) {
                moveBonus();
            }

            ballWallCollision();

            ballPaddleCollision();

            ballBrickCollision();

            gameOver();

            levelUp();
        }

        // GAME LOOP
        function loop() {
            // CLEAR THE CANVAS
            ctx.drawImage(BG_IMG, 0, 0);

            draw();

            update();

            if (!GAME_OVER) {
                requestAnimationFrame(loop);
            }
        }
        loop();


        // SELECT SOUND ELEMENT
        const soundElement = document.getElementById("sound");

        soundElement.addEventListener("click", audioManager);

        function audioManager() {
            // CHANGE IMAGE SOUND_ON/OFF
            let imgSrc = soundElement.getAttribute("src");
            let SOUND_IMG = imgSrc == "img/SOUND_ON.png" ? "img/SOUND_OFF.png" : "img/SOUND_ON.png";

            soundElement.setAttribute("src", SOUND_IMG);

            // MUTE AND UNMUTE SOUNDS
            WALL_HIT.muted = WALL_HIT.muted ? false : true;
            PADDLE_HIT.muted = PADDLE_HIT.muted ? false : true;
            BRICK_HIT.muted = BRICK_HIT.muted ? false : true;
            WIN.muted = WIN.muted ? false : true;
            LIFE_LOST.muted = LIFE_LOST.muted ? false : true;
            BONUS.muted = BONUS.muted ? false : true;
        }

        // SELECT PAUSE ELEMENT
        const pauseElement = document.getElementById("pause");
        const pauseMenuBlock = document.getElementById("pauseMenu");

        // MENU PAUSE 
        const menuPauseRestart = document.getElementById("restartFromPause");
        const menuPauseBack = document.getElementById("back");

        menuPauseBack.addEventListener("click", startGameFromPause);

        menuPauseRestart.addEventListener("click", function () {
            //CHANGE IMAGE PAUSE_ON/OFF
            let imgSrc = pauseElement.getAttribute("src");
            let PAUSE_IMG = imgSrc == "img/PAUSE_OFF.png" ? "img/PAUSE_ON.png" : "img/PAUSE_OFF.png";
            pauseElement.setAttribute("src", PAUSE_IMG);
            // Сброс переменных и состояний игры
            paddle.x = cvs.width / 2 - PADDLE_WIDTH / 2;
            paddle.y = cvs.height - PADDLE_MARGIN_BOTTOM - PADDLE_HEIGHT;
            paddle.width = PADDLE_WIDTH;

            ball.x = cvs.width / 2;
            ball.y = paddle.y - BALL_RADIUS;
            ball.dx = 6 * (Math.random() * 2 - 1);
            ball.dy = -6;

            LIFE = 3;
            SCORE = 0;
            LEVEL = 1;
            GAME_OVER = false;

            brick.row = 1;
            createBricks();

            bonus.active = false;

            // Скрытие меню паузы
            pauseMenuBlock.style.display = "none";

            // Запуск игрового цикла
            loop();
        })

        pauseElement.addEventListener("click", pauseManager);

        function pauseManager() {
            //CHANGE IMAGE PAUSE_ON/OFF
            let imgSrc = pauseElement.getAttribute("src");
            let PAUSE_IMG = imgSrc == "img/PAUSE_OFF.png" ? "img/PAUSE_ON.png" : "img/PAUSE_OFF.png";

            pauseElement.setAttribute("src", PAUSE_IMG);

            if (!GAME_OVER) {
                GAME_OVER = true;
                pauseMenuBlock.style.display = "block";
            } else {
                GAME_OVER = false;
                loop();
            }

        }

        function startGameFromPause() {
            //CHANGE IMAGE PAUSE_ON/OFF
            let imgSrc = pauseElement.getAttribute("src");
            let PAUSE_IMG = imgSrc == "img/PAUSE_OFF.png" ? "img/PAUSE_ON.png" : "img/PAUSE_OFF.png";

            pauseElement.setAttribute("src", PAUSE_IMG);
            pauseMenuBlock.style.display = "none";
            GAME_OVER = false;
            loop();
        }

        // SHOW GAME OVER MESSAGE
        /* SELECT ELEMENTS */
        const gameover = document.getElementById("gameover");
        const youwin = document.getElementById("youwin");
        const youlose = document.getElementById("youlose");
        const restart = document.getElementById("restart");


        // CLICK ON PLAY AGAIN BUTTON
        restart.addEventListener("click", function () {
            location.reload(); // reload the page
        })

        // SHOW YOU WIN
        function showYouWin() {
            gameover.style.display = "block";
            youwon.style.display = "block";

            // Создаем XMLHttpRequest-объект
            var xhr = new XMLHttpRequest();
            var url = "resultsInsert.php"; // Путь к вашему PHP-скрипту

            // Открываем соединение с помощью метода POST
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            // Отправляем количество очков на сервер
            xhr.send("score=" + SCORE);
        }

        // SHOW YOU LOSE
        function showYouLose() {
            gameover.style.display = "block";
            youlose.style.display = "block";

            // Создаем XMLHttpRequest-объект
            var xhr = new XMLHttpRequest();
            var url = "resultsInsert.php"; // Путь к вашему PHP-скрипту

            // Открываем соединение с помощью метода POST
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            // Отправляем количество очков на сервер
            xhr.send("score=" + SCORE);
        }



        // START THE GAME
        const buttonForStat = document.getElementById("start");
        const startBg = document.getElementById("start__bg");
        const game = document.getElementById("game");

        // SETTINGS 
        const buttonSettings = document.getElementById("settings");

        // Results 
        const buttonResults = document.getElementById("results");

        // EXIT
        const buttonExit = document.getElementById("exit");

        buttonForStat.addEventListener("click", function () {
            buttonForStat.style.display = "none";
            buttonResults.style.display = "none";
            buttonSettings.style.display = "none";
            buttonExit.style.display = "none";
            startBg.style.display = "none";
            game.style.display = "block";
            GAME_OVER = false;
            loop();
        })

    </script>
</body>
</html>