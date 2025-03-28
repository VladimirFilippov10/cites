<?php
session_start();
include 'php/auth.php';
checkAuth(); // Проверка аутентификации
include 'php/dbconnect.php'; // Подключение к базе данных

// Обработка параметров сортировки
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'car_buyback_datetime';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'asc' : 'desc'; // Изменение порядка сортировки
$valid_columns = ['car_win_code', 'client_name', 'employee_name', 'car_buyback_datetime', 'car_buyback_price'];

if (!in_array($sort, $valid_columns)) {
    $sort = 'car_buyback_datetime'; // Default sorting column
}

// Запрос для получения всех записей о выкупе автомобилей
$query = "SELECT cb.*, c.client_name, car.car_win_code, e.employee_name FROM car_buyback cb
          JOIN client c ON cb.car_buyback_client_id = c.client_id
          JOIN employee e ON cb.car_buyback_employee = e.employee_id
          JOIN car ON cb.car_buyback_car_id = car.car_id
          ORDER BY $sort $order";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выкуп автомобилей</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
    ?>
    <div class="max-w-full w-full mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Выкуп автомобилей</h1>
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">
                        <a href="?sort=win_code&order=<?php echo $sort === 'car_win_code' ? $order : 'asc'; ?>">Код автомобиля 
                            <?php echo $sort === 'win_code' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th class="border border-gray-300 p-2">
                        <a href="?sort=client_name&order=<?php echo $sort === 'client_name' ? $order : 'asc'; ?>">Клиент 
                            <?php echo $sort === 'client_name' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th class="border border-gray-300 p-2">
                        <a href="?sort=employee_name&order=<?php echo $sort === 'employee_name' ? $order : 'asc'; ?>">Сотрудник 
                            <?php echo $sort === 'employee_name' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th class="border border-gray-300 p-2">
                        <a href="?sort=car_buyback_datetime&order=<?php echo $sort === 'car_buyback_datetime' ? $order : 'asc'; ?>">Дата и время 
                            <?php echo $sort === 'car_buyback_datetime' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th class="border border-gray-300 p-2">
                        <a href="?sort=car_buyback_price&order=<?php echo $sort === 'car_buyback_price' ? $order : 'asc'; ?>">Цена 
                            <?php echo $sort === 'car_buyback_price' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($car_buyback = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="border border-gray-300 p-2"><a href="editCar.php?id=<?php echo $car_buyback['car_buyback_car_id']?>"><?php echo htmlspecialchars($car_buyback['car_win_code']); ?></a></td>
                        <td class="border border-gray-300 p-2"><a class="link-button" href="editClient.php?id=<?php echo $car_buyback['car_buyback_client_id']?>"><?php echo htmlspecialchars($car_buyback['client_name']); ?></a></td>
                        <td class="border border-gray-300 p-2"><a class="link-button" href="editEmployee.php?id=<?php echo $car_buyback['car_buyback_employee']?>"><?php echo htmlspecialchars($car_buyback['employee_name']); ?></a></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($car_buyback['car_buyback_datetime']); ?></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($car_buyback['car_buyback_price']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include 'template/footer.php'; ?>
</body>
</html>
