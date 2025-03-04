<?php
session_start();
include 'php/auth.php';
checkAuth(); // Проверка аутентификации
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить клиента</title>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
        include 'php/dbconnect.php'; // Подключение к базе данных
    ?>
    <div class="max-w-7xl w-2/4 mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Добавить нового клиента</h1>
        <form action="php/client_update.php?action=add" method="POST">
            <div class="mb-6">
                <label for="client_name" class="block text-lg font-semibold mb-2">Имя клиента:</label>
                <input type="text" id="client_name" name="client_name" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-6">
                <label for="client_phone_number" class="block text-lg font-semibold mb-2">Номер телефона:</label>
                <input type="text" id="client_phone_number" name="client_phone_number" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-6">
                <label for="client_type_client" class="block text-lg font-semibold mb-2">Тип клиента:</label>
                <select id="client_type_client" name="client_type_client" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="физическое лицо">Физическое лицо</option>
                    <option value="юридическое лицо">Юридическое лицо</option>
                </select>
            </div>
            <button type="submit" class="bg-green-500 text-white p-2 rounded">Добавить клиента</button>
        </form>
    </div>
    <?php include 'template/footer.php'; ?>
</body>
</html>
