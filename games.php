<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мини-игры</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 40px;
            font-size: 50px;
            letter-spacing: 2px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            border-bottom: 3px solid #e67e22;
            display: block;
            margin-left: auto;
            margin-right: auto;
            padding-bottom: 10px;
            width: fit-content;
        }
        .games-grid {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .game-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 250px;
            text-align: center;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: #2c3e50;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .game-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .game-image {
            width: 100%;
            height: 140px;
            border-radius: 8px;
            object-fit: cover;
            margin-bottom: 15px;
            background-color: #ddd;
        }
        .game-title {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php
        session_start();
        include 'template/header.php';
        
        // Проверка авторизации и подключение меню
        if (isset($_SESSION['user_id'])) {
            include 'template/nav_employees.php'; // Подключение навигации для аутентифицированных пользователей
        }
        include 'php/dbconnect.php'; // Подключение к базе данных

    ?>
    </div>
    <div class="container">
        <h2>Мини-игры</h2>
        <div class="games-grid">
            <a href="games/mudRacing.html" class="game-card">
                <img src="https://placehold.co/250x140?text=Гонки+на+выживание" alt="Гонки на выживание" class="game-image" />
                <p class="game-title">Гонки на выживание</p>
            </a>
            <a href="games/miniMechanic.html" class="game-card">
                <img src="https://placehold.co/250x140?text=Мини-механик" alt="Мини-механик" class="game-image" />
                <p class="game-title">Мини-механик</p>
            </a>
            <a href="games/fuelman.html" class="game-card">
                <img src="https://placehold.co/250x140?text=Заправщик-обманщик" alt="Заправщик" class="game-image" />
                <p class="game-title">Заправщик-обманщик</p>
            </a>
        </div>
    </div>
    <?php
        include 'template/footer.php';
    ?>
</body>
</html>