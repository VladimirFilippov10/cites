<?php
session_start(); // Инициализация сессии

// Проверка аутентификации
if (isset($_SESSION['user_id'])) {
    // Пользователь аутентифицирован, перенаправляем на dashboard
    header("Location: dashboard.php");
    exit();
}

include 'template/header.php';

// Проверка и вывод сообщения
if (isset($_SESSION['outputMessage'])) {
    echo '<div class="bg-red-500 text-white p-4 rounded mb-4">' . $_SESSION['outputMessage'] . '</div>';
    unset($_SESSION['outputMessage']); // Удаляем сообщение после отображения
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <script src="js/messageRansomCars.js"></script>
</head>
<body class="bg-gray-100 font-roboto">
    <main class="flex-grow flex items-center justify-center py-12">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Авторизация</h2>
            <form action="php/login.php" method="POST">
                <div class="mb-4">
                    <label for="login" class="block text-gray-700 text-sm font-bold mb-2">Логин</label>
                    <input type="text" id="login" name="login" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Введите логин">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Пароль</label>
                    <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" placeholder="Введите пароль">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Войти
                    </button>
                    <a href="#" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Забыли пароль?
                    </a>
                </div>
            </form>
        </div>
    </main>

<?php
include 'template/footer.php';
?>
</body>
</html>
