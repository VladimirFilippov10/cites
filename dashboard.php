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
if (true)
{
    
}
?>
</div>
<?php     
   include 'template/footer.php'; ?>
    </body>
    </html>