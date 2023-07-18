<?php
// Получение данных из формы 
$login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);

$password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);


// Хеширование пароля
$password = md5($password."sol123");

// Подключение к БД 
$mysql = new mysqli('127.0.0.1', 'root', 'root', 'breakoutdb');

// Получение пользователя с такой почтой и паролем
$result = $mysql->query("SELECT * FROM `user` WHERE `login` = '$login' AND `password` = '$password'");

// Конвертация данных пользователя в массив (так как с полученным объектом не очень удобно работать)
$user = $result->fetch_assoc();

// Проверка, что такой пользователь есть в БД 
 if (count($user) == 0) {
    $error_message = "Ошибка ввода";
    header('Location: ../error.html');
    exit();
 }



// Установка куки для авторизации
setcookie('user', $user['login'], time() + 3600, "/");
setcookie('userId', $user['id'], time() + 3600, "/");


// Закрытие подключения к БД
$mysql->close();

// Переадресация на нужную страницу
header('Location: ../game.php');
?>