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
    <div class="grid grid-rows-[auto_auto_1fr_auto] min-h-screen">
        <div>
            <?php include 'template/header.php'; ?>
        </div>
        <div>
            <?php include 'template/nav_employees.php'; ?>
        </div>
        <div>
            <?php
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
    ?>
    <?php
    if ($_SESSION['employee_role'] == 1) {
        $query = "SELECT employee_name, employye_last_activity FROM employee WHERE employye_last_activity > DATE_SUB(NOW(), INTERVAL 10 MINUTE)";
        $result = $conn->query($query);
        echo '<div class="max-w-9xl w-5/6 mx-auto p-4 bg-white shadow-md mt-10">';
        echo '<h1 class="text-2xl font-bold mb-6">Пользователи онлайн</h1>';
        if ($result->num_rows > 0) {
            echo '<table class="min-w-full border-collapse border border-gray-300">';
            echo '<thead><tr><th class="border border-gray-300 p-2">Имя пользователя</th><th class="border border-gray-300 p-2">Последняя активность</th></tr></thead>';
            echo '<tbody>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td class="border border-gray-300 p-2">' . htmlspecialchars($row['employee_name']) . '</td>';
                echo '<td class="border border-gray-300 p-2">' . htmlspecialchars($row['employye_last_activity']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>Пользователей онлайн нет</p>';
        }
        echo '</div>';
    }
    ?>
    <!-- Analytics charts for admin -->
    <div class="max-w-9xl w-5/6 mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Аналитика по выкупам и продажам</h1>
        <div>
            <label for="periodSelect">Период:</label>
            <select id="periodSelect" onchange="updateCharts()">
                <option value="today" selected>Сегодня</option>
                <option value="week">Неделя</option>
                <option value="month">Месяц</option>
                <option value="halfyear">Полгода</option>
                <option value="all">Все время</option>
            </select>
            <button id="generatePdfBtn" class="ml-4 bg-blue-500 text-white px-4 py-2 rounded" onclick="generatePDF()">Сгенерировать PDF</button>
        </div>
        <canvas id="redemptionChart" width="800" height="400"></canvas>
        <canvas id="salesChart" width="800" height="400" class="mt-10"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@2.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script>
        const redemptionCtx = document.getElementById('redemptionChart').getContext('2d');
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        let redemptionChart;
        let salesChart;

        async function fetchData(period) {
            try {
                const response = await fetch('php/dashboard_analytics_data.php?period=' + period);
                if (!response.ok) {
                    console.error('Ошибка загрузки данных:', response.statusText);
                    return null;
                }
                const data = await response.json();
                console.log('Получены данные:', data);

                // Преобразование строк дат в объекты Date для Chart.js
                data.redemptions.dates = data.redemptions.dates.map(dateStr => new Date(dateStr));
                data.sales.dates = data.sales.dates.map(dateStr => new Date(dateStr));

                return data;
            } catch (error) {
                console.error('Ошибка при запросе данных:', error);
                return null;
            }
        }

        function createChart(ctx, label, data, borderColor) {
            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.dates,
                    datasets: [{
                        label: label,
                        data: data.counts,
                        borderColor: borderColor,
                        backgroundColor: borderColor,
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                tooltipFormat: 'dd.MM.yyyy'
                            },
                            title: {
                                display: true,
                                text: 'Дата'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            precision: 0,
                            title: {
                                display: true,
                                text: 'Сумма (руб.)'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' ₽';
                                }
                            }
                        }
                    }
                }
            });
        }

        async function updateCharts() {
            const period = document.getElementById('periodSelect').value;
            console.log('Выбран период:', period);
            const data = await fetchData(period);
            console.log('Данные для графиков:', data);
            if (!data) {
                console.error('Нет данных для отображения графиков');
                return;
            }

            if (redemptionChart) redemptionChart.destroy();
            if (salesChart) salesChart.destroy();

            redemptionChart = createChart(redemptionCtx, 'Выкупы', data.redemptions, 'rgba(75, 192, 192, 1)');
            salesChart = createChart(salesCtx, 'Продажи', data.sales, 'rgba(255, 99, 132, 1)');
        }

        async function generatePDF() {
            const period = document.getElementById('periodSelect').value;
            // Get chart images as base64
            const redemptionImage = redemptionChart.toBase64Image();
            const salesImage = salesChart.toBase64Image();

            // Create form data
            const formData = new FormData();
            formData.append('period', period);
            formData.append('redemptionImage', redemptionImage);
            formData.append('salesImage', salesImage);

            try {
                const response = await fetch('php/sales_buyback_report_fpdf.php', {
                    method: 'POST',
                    body: formData
                });
                if (!response.ok) {
                    console.error('Ошибка при генерации PDF:', response.statusText);
                    alert('Ошибка при генерации PDF');
                    return;
                }
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `sales_buyback_report_${period}.pdf`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            } catch (error) {
                console.error('Ошибка при запросе генерации PDF:', error);
                alert('Ошибка при запросе генерации PDF');
            }
        }

        updateCharts();
    </script>
    <?php
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
  /*  if ($_SESSION['employee_role'] == 1) {
        $query = "SELECT employee_name, employye_last_activity FROM employee WHERE employye_last_activity > DATE_SUB(NOW(), INTERVAL 10 MINUTE)";
        $result = $conn->query($query);
        echo '<div class="max-w-9xl w-5/6 mx-auto p-4 bg-white shadow-md mt-10">';
        echo '<h1 class="text-2xl font-bold mb-6">Пользователи онлайн</h1>';
        if ($result->num_rows > 0) {
            echo '<table class="min-w-full border-collapse border border-gray-300">';
            echo '<thead><tr><th class="border border-gray-300 p-2">Имя пользователя</th><th class="border border-gray-300 p-2">Последняя активность</th></tr></thead>';
            echo '<tbody>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td class="border border-gray-300 p-2">' . htmlspecialchars($row['employee_name']) . '</td>';
                echo '<td class="border border-gray-300 p-2">' . htmlspecialchars($row['employye_last_activity']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>Пользователей онлайн нет</p>';
        }
        echo '</div>';
    }*/
    ?>
    </div>
    <?php     
       include 'template/footer.php'; ?>
    </div>
    </body>
    </html>
