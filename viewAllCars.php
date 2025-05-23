<?php
session_start();
include 'php/auth.php'; // Включение проверки авторизации
checkAuth();

// Проверка прав доступа
if ($_SESSION['employee_role'] == 3) { // Если роль 3, перенаправляем на dashboard
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Просмотр всех автомобилей</title>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
        include 'php/dbconnect.php'; // Подключение к базе данных

        // Обработка данных формы поиска
        $searchWin = isset($_POST['searchWin']) ? $_POST['searchWin'] : '';
        $searchModel = isset($_POST['searchModel']) ? $_POST['searchModel'] : '';
        $car_state_number = isset($_POST['car_state_number']) ? $_POST['car_state_number'] : ''; // Новое поле для гос номера
        $filterSale = isset($_POST['filterSale']) ? $_POST['filterSale'] : '';

        // Обработка параметров сортировки
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'model_name';
        $order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'asc' : 'desc'; // Изменение порядка сортировки
        $valid_columns = ['model_name', 'car_win_code', 'car_state_number', 'car_price'];

        if (!in_array($sort, $valid_columns)) {
            $sort = 'model_name'; // Default sorting column
        }

        // Запрос для получения всех автомобилей с именами моделей
        $carsQuery = "SELECT car.*, model.model_name FROM car JOIN model ON car.model_id = model.model_id";

        $conditions = [];

        if ($searchWin) {
            $conditions[] = "car.car_win_code LIKE '%" . $conn->real_escape_string($searchWin) . "%'";
        }
        if ($searchModel) {
            $conditions[] = "model.model_name LIKE '%" . $conn->real_escape_string($searchModel) . "%'";
        }
        if ($car_state_number) {
            $conditions[] = "car.car_state_number LIKE '%" . $conn->real_escape_string($car_state_number) . "%'"; // Фильтр по гос номеру
        }
        if ($filterSale) {
            $conditions[] = "car.car_in_price = 1"; // Предполагается, что 1 - это признак, что автомобиль на продаже
        }

        if (count($conditions) > 0) {
            $carsQuery .= " WHERE " . implode(" AND ", $conditions);
        }

        $carsQuery .= " ORDER BY $sort $order";

        $carsResult = $conn->query($carsQuery);
    ?>
<div class="max-w-full w-full mx-auto p-4 bg-white shadow-md mt-10">

        <h1 class="text-2xl font-bold mb-6">Список автомобилей</h1>
        <form method="POST" class="mb-4">
            <input type="text" name="searchWin" placeholder="Поиск по вину" value="<?php echo htmlspecialchars($searchWin); ?>" class="border p-2 mr-2">
            <input type="text" name="searchModel" placeholder="Поиск по модели" value="<?php echo htmlspecialchars($searchModel); ?>" class="border p-2 mr-2">
            <input type="text" name="car_state_number" placeholder="Поиск по гос номеру" value="<?php echo htmlspecialchars($car_state_number); ?>" class="border p-2 mr-2"> <!-- Новое поле для гос номера -->
            <button type="submit" class="bg-blue-500 text-white p-2">Поиск</button>
            <label>
                <br>
                <input type="checkbox" name="filterSale" value="1" <?php echo $filterSale ? 'checked' : ''; ?>> На продаже
            </label>
        </form>
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">
                        <a href="?sort=model_name&order=<?php echo $sort === 'model_name' ? $order : 'asc'; ?>">Модель 
                            <?php echo $sort === 'model_name' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th class="border border-gray-300 p-2">
                        <a href="?sort=car_win_code&order=<?php echo $sort === 'car_win_code' ? $order : 'asc'; ?>">WinCod 
                            <?php echo $sort === 'car_win_code' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th class="border border-gray-300 p-2">
                        <a href="?sort=car_state_number&order=<?php echo $sort === 'car_state_number' ? $order : 'asc'; ?>">Госномер 
                            <?php echo $sort === 'car_state_number' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th class="border border-gray-300 p-2">
                        <a href="?sort=car_price&order=<?php echo $sort === 'car_price' ? $order : 'asc'; ?>">Цена 
                            <?php echo $sort === 'car_price' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th class="border border-gray-300 p-2">Фото</th>
                    <th class="border border-gray-300 p-2">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($car = $carsResult->fetch_assoc()): ?>
                    <tr>
                        <?php
                        $query_photo = "SELECT * FROM car_photo WHERE car_id = " . $car['car_id'] . " LIMIT 1;";
                        $res_photo= $conn->query($query_photo);
                        $photo = $res_photo->fetch_assoc();
                        ?>
                        <td class="border border-gray-300 p-2"><?php echo $car['model_name']; ?></td>
                        <td class="border border-gray-300 p-2"><?php echo $car['car_win_code']; ?></td>
                        <td class="border border-gray-300 p-2"><?php echo $car['car_state_number']; ?></td>
                        <form action="php/updatePrice.php" method="POST" style="display:inline;">
                        <td class="border border-gray-300 p-2">
                            <input type="text" name="new_price" placeholder="Новая цена" value="<?php echo $car['car_price'];?>">

                            </td>
                        <td class="border border-gray-300 p-2">
                            <img src="img/cars<?php echo $photo["car_photo_image_patch"]; ?>" alt="Фото" style="max-width: 150px;">
                        </td>
                        <td class="border border-gray-300 p-2">
                                <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                                <button type="submit" name="edit_price" class="bg-blue-500 text-white p-1 rounded">
                                    Изменить цену
                                </button>

                                <button type="submit" name="toggle_price" class="bg-blue-500 text-white p-1 rounded">
                                    <?php echo $car['car_in_price'] ? 'Снять с продажи' : 'Выставить на продажу'; ?>
                                </button>
                            </form>
                            <a href="editCar.php?id=<?php echo $car['car_id']; ?>" class="bg-green-500 text-white p-1 rounded">Редактировать</a>
                            <a href="carReport.php?id=<?php echo $car['car_id']; ?>" class="bg-green-500 text-white p-1 rounded">Отчёт по автомобилю</a>

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
