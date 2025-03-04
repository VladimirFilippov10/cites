<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <script>
        function validatePasswords() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm-password").value;
            if (password !== confirmPassword) {
                alert("Пароли не совпадают!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body class="bg-gray-100 font-roboto">
    <?php
        include 'template\header.php';
    ?>
    <main class="container mx-auto p-12">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <h2 class="text-2xl font-bold mb-6 text-center">Регистрация</h2>
            <form method="POST" action="php/registerEmployee.php">

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Адрес электронной почты</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="fullname" class="block text-gray-700 font-medium mb-2">ФИО</label>
                    <input type="text" id="fullname" name="fullname" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-medium mb-2">Логин</label>
                    <input type="text" id="username" name="username" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Пароль</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="confirm-password" class="block text-gray-700 font-medium mb-2">Повторите пароль</label>
                    <input type="password" id="confirm-password" name="confirm-password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="registration-code" class="block text-gray-700 font-medium mb-2">Код для регистрации</label>
                    <input type="text" id="registration-code" name="registration-code" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-6">
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="return validatePasswords()">Зарегистрироваться</button>
                </div>
            </form>
        </div>
    </main>
<?php
include 'template/footer.php'
?>
</body>
</html>
