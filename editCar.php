<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать авто</title>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
        include 'php/dbconnect.php'; // Подключение к базе данных

        // Получение ID автомобиля из URL
        $car_id = intval($_GET['id']);

        // Запрос для получения данных автомобиля
        $carQuery = "SELECT * FROM cars WHERE cars_id = ?";
        $stmt = $conn->prepare($carQuery);
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $carResult = $stmt->get_result();
        $car = $carResult->fetch_assoc();

        // Запрос для получения фотографий
        $photoQuery = "SELECT * FROM car_photo WHERE car_id = ?";
        $photoStmt = $conn->prepare($photoQuery);
        $photoStmt->bind_param("i", $car_id);
        $photoStmt->execute();
        $photoResult = $photoStmt->get_result();

        // Запрос для получения комплектации
        $equipmentQuery = "SELECT * FROM car_equipment WHERE car_id = ?";
        $equipmentStmt = $conn->prepare($equipmentQuery);
        $equipmentStmt->bind_param("i", $car_id);
        $equipmentStmt->execute();
        $equipmentResult = $equipmentStmt->get_result();
    ?>
    <div class="max-w-7xl w-2/4 mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Редактировать запись</h1>
        <form action="php/update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="car_id" value="<?php echo $car['cars_id']; ?>">
            <!-- Основная информация -->
            <div class="mb-6">
                <label for="title" class="block text-lg font-semibold mb-2">WinCod</label>
                <input type="text" id="title" name="title" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['cars_win']) ? $car['cars_win'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="price" class="block text-lg font-semibold mb-2">Цена</label>
                <input type="number" id="price" name="price" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['cars_price']) ? $car['cars_price'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="year" class="block text-lg font-semibold mb-2">Год производства</label>
                <select id="year" name="year" class="w-full p-2 border border-gray-300 rounded" required>
                    <?php
                    $currentYear = date('Y');
                    for ($i = 1886; $i <= $currentYear; $i++) {
                        echo '<option value="' . $i . '"' . (isset($car['cars_year_made']) && $i == $car['cars_year_made'] ? ' selected' : '') . '>' . $i . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-6">
                <label for="bodywork" class="block text-lg font-semibold mb-2">Тип кузова</label>
                <select id="bodywork" name="bodywork" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="Седан" <?php echo (isset($car['cars_bodywork']) && $car['cars_bodywork'] == 'Седан') ? 'selected' : ''; ?>>Седан</option>
                    <option value="Хэтчбек" <?php echo (isset($car['cars_bodywork']) && $car['cars_bodywork'] == 'Хэтчбек') ? 'selected' : ''; ?>>Хэтчбек</option>
                    <option value="Универсал" <?php echo (isset($car['cars_bodywork']) && $car['cars_bodywork'] == 'Универсал') ? 'selected' : ''; ?>>Универсал</option>
                    <option value="Кроссовер" <?php echo (isset($car['cars_bodywork']) && $car['cars_bodywork'] == 'Кроссовер') ? 'selected' : ''; ?>>Кроссовер</option>
                    <option value="Внедорожник" <?php echo (isset($car['cars_bodywork']) && $car['cars_bodywork'] == 'Внедорожник') ? 'selected' : ''; ?>>Внедорожник</option>
                    <option value="Купе" <?php echo (isset($car['cars_bodywork']) && $car['cars_bodywork'] == 'Купе') ? 'selected' : ''; ?>>Купе</option>
                    <option value="Кабриолет" <?php echo (isset($car['cars_bodywork']) && $car['cars_bodywork'] == 'Кабриолет') ? 'selected' : ''; ?>>Кабриолет</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="generation" class="block text-lg font-semibold mb-2">Поколение</label>
                <input type="text" id="generation" name="generation" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['cars_generation']) ? $car['cars_generation'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="melage" class="block text-lg font-semibold mb-2">Пробег (км)</label>
                <input type="number" id="melage" name="melage" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['cars_melage']) ? $car['cars_melage'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="color" class="block text-lg font-semibold mb-2">Цвет</label>
                <input type="text" id="color" name="color" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['cars_color']) ? $car['cars_color'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="owners" class="block text-lg font-semibold mb-2">Количество владельцев</label>
                <input type="number" id="owners" name="owners" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['cars_drive']) ? $car['cars_drive'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="engine_volume" class="block text-lg font-semibold mb-2">Объем двигателя (л)</label>
                <input type="number" step="0.1" id="engine_volume" name="engine_volume" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['cars_volume']) ? $car['cars_volume'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="power" class="block text-lg font-semibold mb-2">Мощность (л.с.)</label>
                <input type="number" id="power" name="power" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['cars_power']) ? $car['cars_power'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="drive" class="block text-lg font-semibold mb-2">Привод</label>
                <select id="drive" name="drive" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="front" <?php echo (isset($car['cars_drive']) && $car['cars_drive'] == 'front') ? 'selected' : ''; ?>>Передний</option>
                    <option value="rear" <?php echo (isset($car['cars_drive']) && $car['cars_drive'] == 'rear') ? 'selected' : ''; ?>>Задний</option>
                    <option value="all" <?php echo (isset($car['cars_drive']) && $car['cars_drive'] == 'all') ? 'selected' : ''; ?>>Полный</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="transmission" class="block text-lg font-semibold mb-2">Коробка передач</label>
                <select id="transmission" name="transmission" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="Механическая" <?php echo (isset($car['cars_transmission_box']) && $car['cars_transmission_box'] == 'Механическая') ? 'selected' : ''; ?>>Механическая</option>
                    <option value="Автоматическая" <?php echo (isset($car['cars_transmission_box']) && $car['cars_transmission_box'] == 'Автоматическая') ? 'selected' : ''; ?>>Автоматическая</option>
                    <option value="Роботизированная" <?php echo (isset($car['cars_transmission_box']) && $car['cars_transmission_box'] == 'Роботизированная') ? 'selected' : ''; ?>>Роботизированная</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="fuel_type" class="block text-lg font-semibold mb-2">Тип топлива</label>
                <select id="fuel_type" name="fuel_type" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="Бензин" <?php echo (isset($car['cars_type_oil']) && $car['cars_type_oil'] == 'Бензин') ? 'selected' : ''; ?>>Бензин</option>
                    <option value="Дизель" <?php echo (isset($car['cars_type_oil']) && $car['cars_type_oil'] == 'Дизель') ? 'selected' : ''; ?>>Дизель</option>
                    <option value="Электричество" <?php echo (isset($car['cars_type_oil']) && $car['cars_type_oil'] == 'Электричество') ? 'selected' : ''; ?>>Электричество</option>
                    <option value="Гибрид" <?php echo (isset($car['cars_type_oil']) && $car['cars_type_oil'] == 'Гибрид') ? 'selected' : ''; ?>>Гибрид</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="equipment_text" class="block text-lg font-semibold mb-2">Описание автомобиля</label>
                <textarea id="equipment_text" name="equipment_text" class="w-full p-2 border border-gray-300 rounded" rows="4" required><?php echo isset($car['cars_descriptions']) ? $car['cars_descriptions'] : ''; ?></textarea>
            </div>

            <!-- Новые поля для редактирования -->
            <div class="mb-6">
                <label for="car_state_number" class="block text-lg font-semibold mb-2">Госномер</label>
                <input type="text" id="car_state_number" name="car_state_number" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['car_state_number']) ? $car['car_state_number'] : ''; ?>" maxlength="9">
            </div>
            <div class="mb-6">
                <label for="car_link_specifications" class="block text-lg font-semibold mb-2">Ссылка на характеристики</label>
                <input type="text" id="car_link_specifications" name="car_link_specifications" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['car_link_specifications']) ? $car['car_link_specifications'] : ''; ?>" maxlength="100">
            </div>
            <div class="mb-6">
                <label for="car_link_to_report" class="block text-lg font-semibold mb-2">Ссылка на отчет</label>
                <input type="text" id="car_link_to_report" name="car_link_to_report" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['car_link_to_report']) ? $car['car_link_to_report'] : ''; ?>" maxlength="100">
            </div>

            <!-- Загрузка изображений автомобиля -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Фотографии автомобиля</h2>
                <div id="photoContainer">
                    <?php while ($photo = $photoResult->fetch_assoc()): ?>
                        <div class="photo-item mb-4">
                            <img src="http://localhost/cites/php/<?php echo $photo['image_patch']; ?>" alt="Фото" class="mb-2" style="max-width: 100px;">
                            <input type="hidden" name="existing_photos[]" value="<?php echo $photo['image_patch']; ?>">
                            <button type="button" class="delete-photo bg-red-500 text-white p-1 rounded" onclick="deletePhoto('<?php echo $photo['image_patch']; ?>')">Удалить</button>
                        </div>
                    <?php endwhile; ?>
                </div>
                <input type="file" name="car_photos[]" multiple class="mb-4">
                <button type="button" id="addPhoto" class="bg-blue-500 text-white p-2 rounded">Добавить фото</button>

            </div>

            <!-- Комплектация -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Комплектация</h2>
                <div id="complectationContainer">
                    <?php
                    $equipmentItemsQuery = "SELECT * FROM car_equipment_element WHERE car_equipment_id = ?"; // Предполагается, что $equipment_id определен
                    $equipmentItemsStmt = $conn->prepare($equipmentItemsQuery);
                    $equipmentItemsStmt->bind_param("i", $equipment_id);
                    $equipmentItemsStmt->execute();
                    $equipmentItemsResult = $equipmentItemsStmt->get_result();

                    while ($equipmentItem = $equipmentItemsResult->fetch_assoc()): ?>
                        <div class="complectation-item mb-4">
                            <input type="text" name="complectation[]" class="w-3/4 p-2 border border-gray-300 rounded mb-2" value="<?php echo $equipmentItem['car_equipment_text']; ?>" required>
                            <button type="button" class="delete-complectation bg-red-500 text-white p-1 rounded">Удалить</button>
                        </div>
                    <?php endwhile; ?>
                </div>
                <button type="button" id="addComplectation" class="bg-blue-500 text-white p-2 rounded">Добавить элемент комплектации</button>
            </div>

            <button type="submit" class="bg-green-500 text-white p-2 rounded">Сохранить изменения</button>
        </form>
    </div>

    <script>
    // JavaScript для добавления новых фотографий и комплектаций
    document.getElementById('addPhoto').addEventListener('click', function() {
        const photoContainer = document.getElementById('photoContainer');
        const newPhotoDiv = document.createElement('div');
        newPhotoDiv.classList.add('photo-item', 'mb-4');
        newPhotoDiv.innerHTML = '<input type="file" name="car_photos[]" class="mb-2"><button type="button" class="delete-photo bg-red-500 text-white p-1 rounded">Удалить</button>';
        photoContainer.appendChild(newPhotoDiv);
    });

    function deletePhoto(photoPath) {
        if (confirm('Вы уверены, что хотите удалить это фото?')) {
            // Отправка запроса на удаление фотографии
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'php/deletePhoto.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('Фото успешно удалено.');
                    location.reload(); // Перезагрузить страницу для обновления
                } else {
                    alert('Ошибка при удалении фото.');
                }
            };
            xhr.send('photoPath=' + encodeURIComponent(photoPath));
        }
    }
    </script>

    <?php
    include 'template/footer.php';
    ?>
</body>
</html>
