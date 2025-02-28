<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Автомобили</title>
    <script src="js/carsOpenTheAdvancedSearch.js"></script>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'php/dbconnect.php'; // Подключение к базе данных

        // Обработка данных из формы поиска
        $brand_id = isset($_GET['brand']) ? intval($_GET['brand']) : null;
        $model_id = isset($_GET['model']) ? intval($_GET['model']) : null;
        $price_from = isset($_GET['price_from']) ? floatval($_GET['price_from']) : null;
        $price_to = isset($_GET['price_to']) ? floatval($_GET['price_to']) : null;
        $year_from = isset($_GET['year_from']) ? intval($_GET['year_from']) : null;
        $year_to = isset($_GET['year_to']) ? intval($_GET['year_to']) : null;
        $power_from = isset($_GET['power_from']) ? intval($_GET['power_from']) : null; // Новое поле для минимальной мощности
        $power_to = isset($_GET['power_to']) ? intval($_GET['power_to']) : null; // Новое поле для максимальной мощности
        $drive_type = isset($_GET['drive_type']) ? $_GET['drive_type'] : null; // Новое поле для привода
        $transmission = isset($_GET['transmission']) ? $_GET['transmission'] : null; // Новое поле для коробки передач

        // Запрос для получения всех марок
        $marksQuery = "SELECT * FROM brand";
        $marksResult = $conn->query($marksQuery);
    ?>
    <main class="flex-grow">
        <h2 class="text-3xl font-bold mb-8 text-center">Поиск автомобилей</h2>
        <div class="bg-gray-100 p-6 rounded-lg shadow-md w-full max-w-4xl mx-auto">
            <!-- Форма поиска -->
            <form method="GET" action="">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <select name="brand" id="brand" class="border border-gray-300 rounded-lg p-2" onchange="loadModels()">
                        <option value="">Марка</option>
                        <?php
                        if ($marksResult->num_rows > 0) {
                            while($row = $marksResult->fetch_assoc()) {
                                echo '<option value="' . $row['id_marks'] . '">' . $row['brand_name'] . '</option>';
                            }
                        } else {
                            echo '<option value="">Нет доступных марок</option>';
                        }
                        ?>
                    </select>
                    <select name="model" id="model" class="border border-gray-300 rounded-lg p-2">
                        <option value="">Модель</option>
                    </select>
                    <div class="flex space-x-2">
                        <input type="text" name="price_from" placeholder="Цена от, ₽" class="border border-gray-300 rounded-lg p-2 w-full">
                        <input type="text" name="price_to" placeholder="до" class="border border-gray-300 rounded-lg p-2 w-full">
                    </div>
                    <div class="flex space-x-2">
                        <select name="year_from" id="year" class="border border-gray-300 rounded-lg p-2">
                            <option>Год от</option>
                            <?php
                            $currentYear = date('Y');
                            for ($i = 1886; $i <= $currentYear; $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>
                        <select name="year_to" class="border border-gray-300 rounded-lg p-2">
                            <option>до</option>
                            <?php
                            for ($i = 1886; $i <= $currentYear; $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div id="advanced-search" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                        <input type="text" name="power_from" placeholder="Мощность от, л.с." class="border border-gray-300 rounded-lg p-2 w-full">
                        <input type="text" name="power_to" placeholder="Мощность до, л.с." class="border border-gray-300 rounded-lg p-2 w-full">
                        <select name="drive_type" class="border border-gray-300 rounded-lg p-2">
                            <option value="">Тип привода</option>
                            <option value="front">Передний</option>
                            <option value="rear">Задний</option>
                            <option value="all">Полный</option>
                        </select>
                        <select name="transmission" class="border border-gray-300 rounded-lg p-2">
                            <option value="">Коробка передач</option>
                            <option value="manual">Механическая</option>
                            <option value="automatic">Автоматическая</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <button type="button" id="toggle-button" class="text-blue-500" onclick="toggleAdvancedSearch()">Расширенный поиск</button>
                    <div class="flex space-x-2">
                        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg" onclick="resetFilters(); window.location.href='cars.php';">Сбросить фильтры</button>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Показать результаты</button>
                    </div>
                </div>
            </form>
            <div id="advanced-search" class="hidden">
                <!-- Здесь можно добавить дополнительные поля для расширенного поиска -->
                <input type="text" placeholder="Дополнительный фильтр" class="border border-gray-300 rounded-lg p-2 w-full">
            </div>
        </div>
        <div class="container mx-auto py-20 px-20">
            <div class="flex flex-col w-full p-5 space-y-5">
                <?php
                // Запрос для получения всех автомобилей с марками и моделями
                $query = "SELECT car.*, model.model_name, brand.brand_name FROM car 
                          JOIN model ON car.model_id = model.model_id
                          JOIN brand ON model.brand_id = brand.brand_id
                          WHERE car.car_in_price = true";

                // Если фильтры не заданы, выполняем запрос для получения всех автомобилей
                if (!$brand_id && !$model_id && !$price_from && !$price_to && !$year_from && !$year_to && !$power_from && !$power_to && !$drive_type && !$transmission) {
                    $query = "SELECT car.*, model.model_name, brand.brand_name FROM car 
                              JOIN model ON car.model_id = model.model_id
                              JOIN brand ON model.brand_id = brand.brand_id
                              WHERE car.car_in_price = true";
                }


                // Добавление фильтров к запросу
                if ($brand_id) {
                    $query .= " AND brand.id_marks = $brand_id";
                }
                if ($model_id) {
                    $query .= " AND model.id_model = $model_id";
                }
                if ($price_from) {
                    $query .= " AND car.cars_price >= $price_from";
                }
                if ($price_to) {
                    $query .= " AND car.cars_price <= $price_to";
                }
                if ($year_from) {
                    $query .= " AND car.cars_year_made >= $year_from";
                }
                if ($year_to) {
                    $query .= " AND car.cars_year_made <= $year_to";
                }
                if ($power_from) {
                    $query .= " AND car.car_power >= $power_from";
                }
                if ($power_to) {
                    $query .= " AND car.car_power <= $power_to";
                }
                if ($drive_type) {
                    $query .= " AND car.car_drive = '$drive_type'";
                }
                if ($transmission) {
                    $query .= " AND car.car_transmission = '$transmission'";
                }

                $result = $conn->query($query);

                // Проверка наличия автомобилей и их отображение
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $car_id = $row['car_id'] ?? null; // Проверка на существование ключа
                        if ($car_id) {
                            $query_photo = "SELECT * FROM car_photo WHERE car_id = " . $car_id . " LIMIT 1;";
                            $res_photo= $conn->query($query_photo);
                            $photo = $res_photo->fetch_assoc();
                            echo '<a href="carDetails.php?id=' . $car_id . '" class="flex w-full bg-gray-200 h-350 rounded-lg overflow-hidden shadow-lg">';
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
                ?>
            </div>
        </div>
    </main>
    <?php
    include 'template/footer.php';
    ?>
    <script>
function loadModels() {
    var brandId = document.getElementById('brand').value;
    var modelSelect = document.getElementById('model');
    modelSelect.innerHTML = '<option value="">Выберите модель</option>'; // Очистить предыдущие модели

    console.log("Selected brand ID:", brandId); // Отладочное сообщение
    if (!brandId) {
        console.error("No brand ID selected.");
        return;
    }

    fetch('php/getModels.php?id_marks=' + brandId)
        .then(response => response.json())
        .then(data => {
            console.log("Models data received:", data); // Отладочное сообщение
            if (data.error) {
                console.error("Error received from server:", data.error);
                return;
            }

            data.forEach(function(model) {
                var option = document.createElement('option');
                option.value = model.id_model;
                option.textContent = model.model_name;
                modelSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Ошибка:', error));
}

    </script>
</body>
</html>
