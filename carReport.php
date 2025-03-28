<?php
session_start();
include 'php/auth.php';
checkAuth(); 
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчет по автомобилю</title>
    <link rel="stylesheet" href="styles.css"> <!-- Подключение стилей -->
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
    include 'template/header.php';
    include 'php/dbconnect.php'; 
include 'template/nav_employees.php'; // Подключение к базе данных


    // Извлечение идентификатора автомобиля из URL
    $car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Запрос для получения информации о выбранном автомобиле
    $carQuery = "SELECT car.*, model.model_name, brand.brand_name FROM car 
                 JOIN model ON car.model_id = model.model_id
                 JOIN brand ON model.brand_id = brand.brand_id
                 WHERE car.car_id = $car_id";
    $carResult = $conn->query($carQuery);
    $carData = $carResult->fetch_assoc();

    // Запрос для получения данных из таблицы car_buyback с именами клиентов и сотрудников
    $buybackQuery = "SELECT cb.*, cbc.client_name, e.employee_name FROM car_buyback cb
                     JOIN client cbc ON cb.car_buyback_client_id = cbc.client_id
                     JOIN employee e ON cb.car_buyback_employee = e.employee_id
                     WHERE cb.car_buyback_car_id = $car_id";
    $buybackResult = $conn->query($buybackQuery);

    // Запрос для получения данных из таблицы car_sales с именами клиентов и сотрудников
    $salesQuery = "SELECT cs.*, csc.client_name, e.employee_name FROM car_sales cs
                   JOIN client csc ON cs.car_sales_client_id = csc.client_id
                   JOIN employee e ON cs.car_sales_employee_id = e.employee_id
                   WHERE cs.car_sales_car_id = $car_id";
    $salesResult = $conn->query($salesQuery);
    ?>

    <div class="max-w-full w-full mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Отчет по автомобилю: <?php echo $carData['brand_name'] . ' ' . $carData['model_name']; ?></h1>
        <p>Код автомобиля: <?php echo $carData['car_win_code']; ?></p>

        <h2 class="text-xl font-bold mt-4">Записи о выкупе</h2>
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Клиент</th>
                    <th class="border border-gray-300 p-2">Дата</th>
                    <th class="border border-gray-300 p-2">Цена</th>
                    <th class="border border-gray-300 p-2">Сотрудник</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $buybackResult->fetch_assoc()): ?>
                <tr>
                    <td class="border border-gray-300 p-2"><?php echo $row['car_buyback_id']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['client_name']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['car_buyback_datetime']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['car_buyback_price']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['employee_name']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2 class="text-xl font-bold mt-4">Записи о продаже</h2>
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Клиент</th>
                    <th class="border border-gray-300 p-2">Дата</th>
                    <th class="border border-gray-300 p-2">Цена</th>
                    <th class="border border-gray-300 p-2">Сотрудник</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $salesResult->fetch_assoc()): ?>
                <tr>
                    <td class="border border-gray-300 p-2"><?php echo $row['car_sales_id']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['client_name']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['car_sales_datatime']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['car_sales_price']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['employee_name']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <button onclick="downloadPDF()" class="bg-blue-500 text-white p-2 mt-4">Скачать отчёт PDF</button>
    </div>

    <script>
    function downloadPDF() {
        window.location.href = 'php/downloadPDF.php?id=<?php echo $car_id; ?>';
    }
    </script>

    <?php include 'template/footer.php'; ?>
</body>
</html>
