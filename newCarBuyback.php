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
    <title>Добавить запись о выкупе автомобиля</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
        include 'php/dbconnect.php'; // Подключение к базе данных
    ?>
    <div class="max-w-7xl w-2/4 mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Добавить запись о выкупе автомобиля</h1>
        <form action="php/carBuybackSubmit.php?action=add" method="POST">
            <div class="mb-6">
                <label for="car_buyback_car_id" class="block text-lg font-semibold mb-2">Автомобиль:</label>
                <select id="car_buyback_car_id" name="car_buyback_car_id" class="w-full p-2 border border-gray-300 rounded" required>
                    <?php
                    $carsQuery = "SELECT car_id, car_win_code FROM car";
                    $carsResult = $conn->query($carsQuery);
                    while ($car = $carsResult->fetch_assoc()) {
                        echo "<option value='" . $car['car_id'] . "'>" . htmlspecialchars($car['car_win_code']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-6">
                <label for="car_buyback_redemption_request" class="block text-lg font-semibold mb-2">Номер заявки:</label>
                <select id="car_buyback_redemption_request" name="car_buyback_redemption_request" class="w-full p-2 border border-gray-300 rounded" required>
                    <?php
                    $reauestQuery = "SELECT * FROM `redemption_request` WHERE redemption_request_closed = 0";
                    $recuestResult = $conn->query($reauestQuery);
                    while ($request = $recuestResult->fetch_assoc()) {
                        echo "<option value='" . $request['redemption_request_id'] . "'>" . htmlspecialchars($request['redemption_request_id']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-6">
                <label for="car_buyback_client_id" class="block text-lg font-semibold mb-2">Клиент:</label>
                <select id="car_buyback_client_id" name="car_buyback_client_id" class="w-full p-2 border border-gray-300 rounded" required>
                    <?php
                    $clientsQuery = "SELECT client_id, client_name FROM client";
                    $clientsResult = $conn->query($clientsQuery);
                    while ($client = $clientsResult->fetch_assoc()) {
                        echo "<option value='" . $client['client_id'] . "'>" . htmlspecialchars($client['client_name']) . "</option>";
                    }
                    ?>
                </select>
            </div> 
            <div class="mb-6">
                <label for="car_buyback_price" class="block text-lg font-semibold mb-2">Цена:</label>
                <input type="number" step="0.01" id="car_buyback_price" name="car_buyback_price" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <button type="submit" class="bg-green-500 text-white p-2 rounded">Добавить запись</button>
        </form>
    </div>
    <?php include 'template/footer.php'; ?>
</body>
</html>
