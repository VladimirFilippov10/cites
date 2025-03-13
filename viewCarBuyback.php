<?php
session_start();
include 'php/auth.php';
checkAuth(); // Проверка аутентификации
include 'php/dbconnect.php'; // Подключение к базе данных

// Запрос для получения всех записей о выкупе автомобилей
$query = "SELECT cb.*, c.client_name, car.car_win_code, e.employee_name FROM car_buyback cb
          JOIN client c ON cb.car_buyback_client_id = c.client_id
          JOIN employee e ON cb.car_buyback_employee = e.employee_id

          JOIN car ON cb.car_buyback_car_id = car.car_id";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список записей о выкупе автомобилей</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
    ?>
    <div class="max-w-full w-full mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Список записей о выкупе автомобилей</h1>
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">Автомобиль</th>
                    <th class="border border-gray-300 p-2">Клиент</th>
                    <th class="border border-gray-300 p-2">Сотрудник</th>
                    <th class="border border-gray-300 p-2">Дата и время</th>
                    <th class="border border-gray-300 p-2">Цена</th>
                    <th class="border border-gray-300 p-2">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($car_buyback = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($car_buyback['car_win_code']); ?></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($car_buyback['client_name']); ?></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($car_buyback['employee_name']); ?></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($car_buyback['car_buyback_datetime']); ?></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($car_buyback['car_buyback_price']); ?></td>
                        <td class="border border-gray-300 p-2">
                            <a href="edit_car_buyback.php?id=<?php echo $car_buyback['car_buyback_id']; ?>" class="bg-green-500 text-white p-1 rounded">Редактировать</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include 'template/footer.php'; ?>
</body>
</html>
