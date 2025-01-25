<?php
// Настройки подключения к базе данных
$host = 'localhost'; // Хост, обычно 'localhost'
$username = 'root'; // Имя пользователя, по умолчанию 'root'
$password = ''; // Пароль, по умолчанию пустой для XAMPP
$dbname = 'autoshop'; // Имя вашей базы данных

// Создание подключения
$conn = new mysqli($host, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>
