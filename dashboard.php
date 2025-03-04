<?php
session_start();
include 'php/auth.php';
checkAuth(); // Проверка аутентификации

// Остальной код остается без изменений
?>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <script src="js/messageRansomCars.js"></script>
    <style>
        th {
            position: relative;
        }
        th:hover {
            cursor: col-resize;
        }
    </style>
</head>
<body class="bg-gray-100 font-roboto">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
        include 'php/auth.php'; // Включение проверки авторизации
        checkAuth(); // Проверка авторизации
        include 'template/footer.php'; // Подключение к базе данных
     ?>

        