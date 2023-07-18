<?php
// Получение данных из формы 
$login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);

$password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

$repass = filter_var(trim($_POST['repass']), FILTER_SANITIZE_STRING);

// Подключение к БД 
$mysql = new mysqli('127.0.0.1', 'root', 'root', 'breakoutdb');

// Проверка, что не существует пользователя с таким же логином
$result = $mysql->query("SELECT * FROM `user` WHERE `login` = '$login'");
if ($result->num_rows > 0) {
    header('Location: ../errorUser.html');
    exit();
}

// Проверки логина (на количество символов), пароля (на соответвии правилам), соответвие пароля и потверждение пароля
if (mb_strlen($login) == 0 || mb_strlen($password) == 0 || $password != $repass  ) {
    header('Location: ../error.html');
    exit();
}

// Хеширование пароля
$password = md5($password."sol123");

// Выполнение запроса 
$mysql->query("INSERT INTO `user` (`login`, `password`, `score`) VALUES('$login', '$password', '0')");
// Закрытие подключения к БД
$mysql->close();

// Переадресация на нужную страницу
header('Location: ../index.php');

?>
