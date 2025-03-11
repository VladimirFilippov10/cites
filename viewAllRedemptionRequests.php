<?php
session_start();
include 'php/auth.php';
checkAuth(); // Проверка аутентификации

// Остальной код остается без изменений
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список заявок на выкуп</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
        include 'php/dbconnect.php';

        // Обработка данных формы поиска
        $searchName = isset($_POST['searchName']) ? $_POST['searchName'] : '';
        $searchPhone = isset($_POST['searchPhone']) ? $_POST['searchPhone'] : '';
        $searchModel = isset($_POST['searchModel']) ? $_POST['searchModel'] : '';
        $filterStatus = isset($_POST['filterStatus']) ? $_POST['filterStatus'] : '';

        // Основной запрос
        $query = "SELECT *, DATE_FORMAT(redemption_request_date, '%d.%m.%Y %H:%i') as formatted_created_at FROM redemption_request WHERE 1=1";



        if ($searchName) {
            $query .= " AND redemption_request_name_client LIKE '%" . $conn->real_escape_string($searchName) . "%'";
        }
        if ($searchPhone) {
            $query .= " AND redemption_request_number_phone LIKE '%" . $conn->real_escape_string($searchPhone) . "%'";
        }
        if ($searchModel) {
            $query .= " AND redemption_request_model_car LIKE '%" . $conn->real_escape_string($searchModel) . "%'";
        }
        if ($filterStatus) {
            $query .= " AND redemption_request_status = '" . $conn->real_escape_string($filterStatus) . "'";
        }

        $result = $conn->query($query);
    ?>
    <div class="max-w-7xl w-2/4 mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Список заявок на выкуп</h1>
        
        <!-- Форма поиска -->
        <form method="POST" class="mb-4">
            <input type="text" name="searchName" placeholder="Поиск по имени" value="<?php echo htmlspecialchars($searchName); ?>" class="border p-2 mr-2">
            <input type="text" name="searchPhone" placeholder="Поиск по телефону" value="<?php echo htmlspecialchars($searchPhone); ?>" class="border p-2 mr-2">
            <input type="text" name="searchModel" placeholder="Поиск по модели" value="<?php echo htmlspecialchars($searchModel); ?>" class="border p-2 mr-2">
            <select name="filterStatus" class="border p-2 mr-2">
                <option value="">Все статусы</option>
                <option value="Открыта" <?php echo $filterStatus == 'Открыта' ? 'selected' : ''; ?>>Открыта</option>
                <option value="В обработке" <?php echo $filterStatus == 'В обработке' ? 'selected' : ''; ?>>В обработке</option>
                <option value="Завершена" <?php echo $filterStatus == 'Завершена' ? 'selected' : ''; ?>>Завершена</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white p-2">Поиск</button>
        </form>

        <!-- Таблица с заявками -->
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                <th class="border border-gray-300 p-2">Код заявки</th>
                    <th class="border border-gray-300 p-2">Дата создания</th>
                    <th class="border border-gray-300 p-2">Имя клиента</th>

                    <th class="border border-gray-300 p-2">Модель авто</th>
                    <th class="border border-gray-300 p-2">Телефон</th>
                    <th class="border border-gray-300 p-2">Статус</th>
                    <th class="border border-gray-300 p-2">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="<?php echo $row['redemption_request_closed'] ? 'bg-gray-200' : ''; ?>">
                    <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($row['redemption_request_id']); ?></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($row['formatted_created_at']); ?></td>

                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($row['redemption_request_name_client']); ?></td>

                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($row['redemption_request_model_car']); ?></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($row['redemption_request_number_phone']); ?></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($row['redemption_request_status']); ?></td>
                        <td class="border border-gray-300 p-2">
                            <a href="editRedemptionRequest.php?id=<?php echo $row['redemption_request_id']; ?>" class="bg-green-500 text-white p-1 rounded">Редактировать</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php
        include 'template/footer.php';
        $conn->close();
    ?>
</body>
</html>
