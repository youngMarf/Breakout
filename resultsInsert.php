<?php
// Подключение к БД 
$mysql = new mysqli('127.0.0.1', 'root', 'root', 'breakoutdb');


$score = $_POST['score'];

// Запрос к БД
$mysql->query("INSERT INTO `result` (`idUser`, `score`) VALUES('{$_COOKIE['userId']}', '$score')");

// Получение суммарного score для данного пользователя из таблицы result
$query = "SELECT SUM(score) AS totalScore FROM result WHERE idUser = '{$_COOKIE['userId']}'";
$result = $mysql->query($query);
$row = $result->fetch_assoc();
$totalScore = $row['totalScore'];

// Обновление score в таблице user
$mysql->query("UPDATE `user` SET `score` = '$totalScore' WHERE id = '{$_COOKIE['userId']}'");



// ЗАкрытие подлючения
$mysql->close();
?>