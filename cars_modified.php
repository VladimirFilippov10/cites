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

        // Запрос для получения всех марок
        $marksQuery = "SELECT * FROM marks";
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
                                echo '<option value="' . $row['id_marks'] . '">' . $row['name_marks'] . '</option>';
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
                <div class="mt-4 flex justify-between items-center">
                    <button type="button" id="toggle-button" class="text-blue-500" onclick="toggleAdvancedSearch()">Расширенный поиск</button>
                    <div class="flex space-x-2">
                        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg" onclick="resetFilters(); window.location.href='cars_modified.php';">Сбросить фильтры</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Показать результаты</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="container mx-auto py-20 px-20">
            <div class="flex flex-col w-full p-5 space-y-5">
                <?php
                // Запрос для получения всех автомобилей с марками и моделями
                $query = "SELECT cars.*, marks.name_marks, model.name_model FROM cars 
                          JOIN marks ON cars.model_id = marks.id_marks 
                          JOIN model ON cars.model_id = model.id_model";

                // Если фильтры не заданы, выполняем запрос для получения всех автомобилей
                if (!$brand_id && !$model_id && !$price_from && !$price_to && !$year_from && !$year_to) {
                    $query = "SELECT cars.*, marks.name_marks, model.name_model FROM cars 
                              JOIN marks ON cars.model_id = marks.id_marks 
                              JOIN model ON cars.model_id = model.id_model";
                }

                $result = $conn->query($query);

                // Проверка наличия автомобилей и их отображение
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<a href="car.php?id=' . $row['cars_id'] . '" class="flex w-full bg-gray-200 h-350 rounded-lg overflow-hidden shadow-lg">';
                        echo '<div class="w-1/5">';
                        echo '<img alt="' . $row['cars_id'] . '1" class="h-full w-full object-cover" src="img/cars/' . $row['cars_id'] . '_1.png" />';
                        echo '</div>';
                        echo '<div class="w-2/3 pl-4 flex flex-col justify-between">';
                        echo '<div>';
                        echo '<h2 class="text-xl font-bold">' . $row['name_marks'] . ' ' . $row['name_model'] . '</h2>'; // Объединение марки и модели
                        echo '<p class="text-gray-600 text-sm">' . ($row['cars_volume'] ?? 'Неизвестно') . ' л/' . ($row['cars_power'] ?? 'Неизвестно') . ' л.с./' . ($row['cars_type_oil'] ?? 'Неизвестно') . '</p>';
                        echo '<p class="text-gray-600 text-sm">' . ($row['cars_drive'] ?? 'Неизвестно') . '</p>';
                        echo '<p class="text-gray-600 text-sm">' . ($row['cars_bodywork'] ?? 'Неизвестно') . '</p>';
                        echo '<div class="flex items-center mt-2">';
                        echo '<span class="text-green-600 text-lg font-bold">' . ($row['cars_price'] ?? 'Неизвестно') . ' ₽</span>';
                        echo '</div>';
                        echo '<div class="flex items-center mt-2">';
                        echo '<span class="text-gray-600 text-sm">' . ($row['cars_year_made'] ?? 'Неизвестно') . '</span>';
                        echo '<span class="ml-4 text-gray-600 text-sm">' . ($row['cars_melage'] ?? 'Неизвестно') . ' км</span>';
                        echo '</div>';
                        echo '<div class="flex items-center mt-2">';
                        echo '<span class="text-gray-600 text-sm">' . ($row['cars_drive'] ?? 'Неизвестно') . '</span>';
                        echo '<span class="ml-4 text-gray-600 text-sm">' . ($row['cars_color'] ?? 'Неизвестно') . '</span>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
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

        if (brandId) {
            fetch('php/getModels.php?id_marks=' + brandId)
                .then(response => response.json())
                .then(data => {
                    data.forEach(function(model) {
                        var option = document.createElement('option');
                        option.value = model.id_model;
                        option.textContent = model.name_model;
                        modelSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Ошибка:', error));
        }
    }
    </script>
</body>
</html>
