<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Страница не найдена</title>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php
        include 'template/header.php';
    ?>
    <div class="container mx-auto py-12 px-6 text-center">
        <h1 class="text-4xl font-bold mb-4">404 - Страница не найдена</h1>
        <p class="text-xl mb-4">К сожалению, запрашиваемая вами страница не существует.</p>
    <a href="index.php" class="text-blue-500 font-bold" onclick="window.location.href='index.php'; return false;">Вернуться на главную страницу</a>

    </div>
    <?php
        include 'template/footer.php';
    ?>
</body>
</html>
