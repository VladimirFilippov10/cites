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
        checkAuth(); 
        include 'php/dbconnect.php';
        if ($_SESSION['employee_role'] == 3)
        {// Проверка авторизации
    $query = "SELECT *,e.employee_name, DATE_FORMAT(redemption_request_date, '%d.%m.%Y %H:%i') as formatted_created_at FROM redemption_request r JOIN employee e ON r.redemption_request_employee = e.employee_id WHERE redemption_request_closed=0 ";
    $result = $conn->query($query);
?>
<div class="max-w-9xl w-5/6 mx-auto p-4 bg-white shadow-md mt-10">
<h1 class="text-2xl font-bold mb-6">Список заявок на выкуп</h1>
<!-- Таблица с заявками -->
<table class="min-w-full border-collapse border border-gray-300">
    <thead>
        <tr>
        <th class="border border-gray-300 p-2">Код заявки</th>
            <th class="border border-gray-300 p-2">Дата создания</th>
            <th class="border border-gray-300 p-2">Имя клиента</th>
            <th class="border border-gray-300 p-2">Модель авто</th>
            <th class="border border-gray-300 p-2">Телефон</th>
            <th class="border border-gray-300 p-2">Сотрудник</th>
            <th class="border border-gray-300 p-2">Статус</th>
            <th class="border border-gray-300 p-2">Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $a = 0; 
        while ($row = $result->fetch_assoc()): ?>
            <tr class="<?php echo $row['redemption_request_closed'] ? 'bg-gray-200' : ''; ?>">
            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($row['redemption_request_id']); ?></td>
                <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($row['formatted_created_at']); ?></td>

                <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($row['redemption_request_name_client']); ?></td>

                <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($row['redemption_request_model_car']); ?></td>
                <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($row['redemption_request_number_phone']); ?></td>
                <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($row['employee_name']); ?></td>
                <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($row['redemption_request_status']); ?></td>
                <td class="border border-gray-300 p-2">
                    <a href="editRedemptionRequest.php?id=<?php echo $row['redemption_request_id']; ?>" class="bg-green-500 text-white p-1 rounded">Редактировать</a>
                </td>
            </tr>
        <?php $a++; endwhile; ?>
   
    </tbody>
</table>
<?php
 if ($a == 0) { 
    echo '<p>Открытых заявок нет<p>';} }
if ($_SESSION['employee_role'] == 4)
{
    $query = "SELECT car.*, model.model_name, brand.brand_name FROM car 
    JOIN model ON car.model_id = model.model_id
    JOIN brand ON model.brand_id = brand.brand_id
    WHERE car.car_in_price = true";
    echo "<div class=\"flex justify-center\"><h1 class=\"text-2xl font-bold mb-6 text-center\">Список авто в продаже</h1></div>";
    echo "<div class=\"container mx-auto py-20 px-20\">";
    echo "<div class=\"flex flex-col w-full p-5 space-y-5\">";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $car_id = $row['car_id'] ?? null; // Проверка на существование ключа
            if ($car_id) {
                $query_photo = "SELECT * FROM car_photo WHERE car_id = " . $car_id . " LIMIT 1;";
                $res_photo= $conn->query($query_photo);
                $photo = $res_photo->fetch_assoc();
                echo '<a href="editCar.php?id=' . $car_id . '" class="flex w-full bg-gray-200 h-350 rounded-lg overflow-hidden shadow-lg">';
                echo '<div class="w-1/5">';
                echo '<img alt="' . $car_id . '1" class="h-full w-full object-cover" wight="250px" height="150px" src="img/cars/' . ($photo["car_photo_image_patch"] ?? '') . '" />'; // Проверка на существование ключа
                echo '</div>';
                echo '<div class="w-2/3 pl-4 flex flex-col justify-between">';
                echo '<div>';
                echo '<h2 class="text-xl font-bold">' . $row['brand_name'] . ' ' . $row['model_name'] . '</h2>'; // Объединение марки и модели
                echo '<p class="text-gray-600 text-sm">' . ($row['car_volume'] ?? 'Неизвестно') . ' л/' . ($row['car_power'] ?? 'Неизвестно') . ' л.с./' . ($row['car_type_oil'] ?? 'Неизвестно') . '</p>';
                echo '<p class="text-gray-600 text-sm">' . ($row['car_onwers'] ?? 'Неизвестно') . ' владельцев</p>';
                echo '<p class="text-gray-600 text-sm">' . ($row['car_bodywork'] ?? 'Неизвестно') . '</p>';
                echo '<div class="flex items-center mt-2">';
                echo '<span class="text-green-600 text-lg font-bold">' . ($row['car_price'] ?? 'Неизвестно') . ' ₽</span>';
                echo '</div>';
                echo '<div class="flex items-center mt-2">';
                echo '<span class="text-gray-600 text-sm">' . ($row['car_year_made'] ?? 'Неизвестно') . '</span>';
                echo '<span class="ml-4 text-gray-600 text-sm">' . ($row['car_mileage'] ?? 'Неизвестно') . ' км</span>';
                echo '</div>';
                echo '<div class="flex items-center mt-2">';
                echo '<span class="text-gray-600 text-sm">' . ($row['car_drive'] ?? 'Неизвестно') . '</span>';
                echo '<span class="ml-4 text-gray-600 text-sm">' . ($row['car_color'] ?? 'Неизвестно') . '</span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</a>';
            } else {
                echo '<p class="text-center">Ошибка: ID автомобиля не найден.</p>';
            }
        }
    } else {
        echo '<p class="text-center">Нет доступных автомобилей.</p>';
    }
    echo "</div></div>";
}
?>
</div>
<?php     
   include 'template/footer.php'; ?>
    </body>
    </html>