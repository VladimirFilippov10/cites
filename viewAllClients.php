<?php
session_start();
include 'php/auth.php'; // Включение проверки авторизации
checkAuth();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список клиентов</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
        include 'php/dbconnect.php'; // Подключение к базе данных

        // Обработка данных формы поиска
        $searchName = isset($_POST['searchName']) ? $_POST['searchName'] : '';
        $searchPhone = isset($_POST['searchPhone']) ? $_POST['searchPhone'] : '';
        $searchType = isset($_POST['searchType']) ? $_POST['searchType'] : '';

        // Обработка параметров сортировки
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'client_name';
        $order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'asc' : 'desc'; // Изменение порядка сортировки
        $valid_columns = ['client_name', 'client_phone_number', 'client_type_client'];

        if (!in_array($sort, $valid_columns)) {
            $sort = 'client_name'; // Default sorting column
        }

        // Запрос для получения всех клиентов
        $clientsQuery = "SELECT * FROM client WHERE 1=1 ORDER BY $sort $order";


        if ($searchName) {
            $clientsQuery .= " AND client_name LIKE '%" . $conn->real_escape_string($searchName) . "%'";
        }
        if ($searchPhone) {
            $clientsQuery .= " AND client_phone_number LIKE '%" . $conn->real_escape_string($searchPhone) . "%'";
        }
        if ($searchType) {
            $clientsQuery .= " AND client_type_client LIKE '%" . $conn->real_escape_string($searchType) . "%'";
        }

        $clientsResult = $conn->query($clientsQuery);
    ?>
    <div class="max-w-full w-full mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Список клиентов</h1>
        <form method="POST" class="mb-4">
            <input type="text" name="searchName" placeholder="Поиск по имени" value="<?php echo htmlspecialchars($searchName); ?>" class="border p-2 mr-2">
            <input type="text" name="searchPhone" placeholder="Поиск по номеру телефона" value="<?php echo htmlspecialchars($searchPhone); ?>" class="border p-2 mr-2">
<select name="searchType" class="border p-2 mr-2">
    <option value="">Все типы</option>
    <?php
        $typeQuery = "SELECT DISTINCT client_type_client FROM client";
        $typeResult = $conn->query($typeQuery);
        while ($type = $typeResult->fetch_assoc()) {
            $selected = ($type['client_type_client'] == $searchType) ? 'selected' : '';
            echo "<option value='" . htmlspecialchars($type['client_type_client']) . "' $selected>" . htmlspecialchars($type['client_type_client']) . "</option>";
        }
    ?>
</select>

            <button type="submit" class="bg-blue-500 text-white p-2">Поиск</button>
        </form>
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">
                        <a href="?sort=client_name&order=<?php echo $sort === 'client_name' ? $order : 'asc'; ?>">Имя клиента 
                            <?php echo $sort === 'client_name' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th class="border border-gray-300 p-2">
                        <a href="?sort=client_phone_number&order=<?php echo $sort === 'client_phone_number' ? $order : 'asc'; ?>">Номер телефона 
                            <?php echo $sort === 'client_phone_number' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th class="border border-gray-300 p-2">
                        <a href="?sort=client_type_client&order=<?php echo $sort === 'client_type_client' ? $order : 'asc'; ?>">Тип клиента 
                            <?php echo $sort === 'client_type_client' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>

                    <th class="border border-gray-300 p-2">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($client = $clientsResult->fetch_assoc()): ?>
                    <tr>
                        <td class="border border-gray-300 p-2"><?php echo $client['client_name']; ?></td>
                        <td class="border border-gray-300 p-2"><?php echo $client['client_phone_number']; ?></td>
                        <td class="border border-gray-300 p-2"><?php echo $client['client_type_client']; ?></td>
                        <td class="border border-gray-300 p-2">
                            <a href="editClient.php?id=<?php echo $client['client_id']; ?>" class="bg-green-500 text-white p-1 rounded">Редактировать</a>
                           <!-- <a href="deleteClient.php?id=<?php echo $client['client_id']; ?>" class="bg-red-500 text-white p-1 rounded">Удалить</a>-->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php
    include 'template/footer.php';
    ?>
</body>
</html>
