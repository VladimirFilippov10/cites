<?php
session_start();
include 'php/auth.php'; // Включение проверки авторизации
checkAuth(); // Проверка авторизации
if ($_SESSION['employee_role'] == 3 || $_SESSION['employee_role'] == 4) { // Если роль 3, перенаправляем на dashboard
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/photoMover.js"></script>
    <script src="js/editCarComplect.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать авто</title>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
        include 'php/dbconnect.php'; // Подключение к базе данных

        // Проверка ID автомобиля
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            die("Ошибка: некорректный ID автомобиля.");
        }

        // Получение ID автомобиля из URL
        $car_id = intval($_GET['id']);

        // Запрос для получения данных автомобиля
        $carQuery = "SELECT * FROM car WHERE car_id = ?";
        $stmt = $conn->prepare($carQuery);
        if (!$stmt) {
            die("Ошибка: не удалось подготовить запрос.");
        }
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $carResult = $stmt->get_result();
        if ($carResult->num_rows === 0) {
            die("Ошибка: автомобиль не найден.");
        }
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
        $equipment = $equipmentResult->fetch_assoc();

        // Проверка наличия car_equipment_id
        if (isset($equipment['car_equipment_id'])) {
            $car_equipment_id = $equipment['car_equipment_id']; // Получаем car_equipment_id

            // Запрос для получения элементов комплектации
            $equipmentElementQuery = "SELECT * FROM car_equipment_element WHERE car_equipment_id = ?";
            $equipmentElementStmt = $conn->prepare($equipmentElementQuery);
            $equipmentElementStmt->bind_param("i", $car_equipment_id);
            $equipmentElementStmt->execute();
            $equipmentElementResult = $equipmentElementStmt->get_result();

        } else {
            $car_equipment_id = null; // Если нет записи, устанавливаем в null
            $equipmentElementResult = null; // Устанавливаем результат в null
        }

    ?>
    <div class="max-w-7xl w-2/4 mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Редактировать запись</h1>
            <form action="php/update.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">

            <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
            <!-- Основная информация -->
            <div class="mb-6">
                <label for="title" class="block text-lg font-semibold mb-2">WinCod</label>
                <input type="text" id="title" name="title" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['car_win_code']) ? $car['car_win_code'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="year" class="block text-lg font-semibold mb-2">Год производства</label>
                <select id="year" name="year" class="w-full p-2 border border-gray-300 rounded" required>
                    <?php
                    $currentYear = date('Y');
                    for ($i = 1886; $i <= $currentYear; $i++) {
                        echo '<option value="' . $i . '"' . (isset($car['car_year_made']) && $i == $car['car_year_made'] ? ' selected' : '') . '>' . $i . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-6">
                <label for="bodywork" class="block text-lg font-semibold mb-2">Тип кузова</label>
                <select id="bodywork" name="bodywork" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="Седан" <?php echo (isset($car['car_bodywork']) && $car['car_bodywork'] == 'Седан') ? 'selected' : ''; ?>>Седан</option>
                    <option value="Хэтчбек" <?php echo (isset($car['car_bodywork']) && $car['car_bodywork'] == 'Хэтчбек') ? 'selected' : ''; ?>>Хэтчбек</option>
                    <option value="Универсал" <?php echo (isset($car['car_bodywork']) && $car['car_bodywork'] == 'Универсал') ? 'selected' : ''; ?>>Универсал</option>
                    <option value="Кроссовер" <?php echo (isset($car['car_bodywork']) && $car['car_bodywork'] == 'Кроссовер') ? 'selected' : ''; ?>>Кроссовер</option>
                    <option value="Внедорожник" <?php echo (isset($car['car_bodywork']) && $car['car_bodywork'] == 'Внедорожник') ? 'selected' : ''; ?>>Внедорожник</option>
                    <option value="Купе" <?php echo (isset($car['car_bodywork']) && $car['car_bodywork'] == 'Купе') ? 'selected' : ''; ?>>Купе</option>
                    <option value="Минивен" <?php echo (isset($car['car_bodywork']) && $car['car_bodywork'] == 'Минивен') ? 'selected' : ''; ?>>Минивен</option>
                    <option value="Фургон" <?php echo (isset($car['car_bodywork']) && $car['car_bodywork'] == 'Фургон') ? 'selected' : ''; ?>>Фургон</option>

                    <option value="Кабриолет" <?php echo (isset($car['car_bodywork']) && $car['car_bodywork'] == 'Кабриолет') ? 'selected' : ''; ?>>Кабриолет</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="generation" class="block text-lg font-semibold mb-2">Поколение</label>
                <input type="text" id="generation" name="generation" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['car_generation']) ? $car['car_generation'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="mileage" class="block text-lg font-semibold mb-2">Пробег (км)</label>
                <input type="number" id="mileage" name="mileage" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['car_mileage']) ? $car['car_mileage'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="color" class="block text-lg font-semibold mb-2">Цвет</label>
                <input type="text" id="color" name="color" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['car_color']) ? $car['car_color'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="owners" class="block text-lg font-semibold mb-2">Количество владельцев</label>
                <input type="number" id="owners" name="owners" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['car_onwers']) ? $car['car_onwers'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="engine_volume" class="block text-lg font-semibold mb-2">Объем двигателя (л)</label>
                <input type="number" step="0.1" id="engine_volume" name="engine_volume" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['car_volume']) ? $car['car_volume'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="power" class="block text-lg font-semibold mb-2">Мощность (л.с.)</label>
                <input type="number" id="power" name="power" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['car_power']) ? $car['car_power'] : ''; ?>" required>
            </div>

            <div class="mb-6">
                <label for="car_in_price" class="block text-lg font-semibold mb-2">На продаже</label>
                <input type="checkbox" id="car_in_price" name="for_sale" value="1" <?php echo $car['car_in_price'] ? 'checked' : ''; ?>>
            </div>
            <div class="mb-6">
                <label for="price" class="block text-lg font-semibold mb-2">Цена</label>
                <input type="number" id="price" name="price" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($car['car_price']) ? $car['car_price'] : ''; ?>" required>
            </div>
           
            <label for="drive" class="block text-lg font-semibold mb-2">Привод</label>
            <select id="car_drive" name="car_drive" class="w-full p-2 border border-gray-300 rounded" required>
                <option value="передний" <?php echo (isset($car['car_drive']) && $car['car_drive'] == 'передний') ? 'selected' : ''; ?>>Передний</option>
                <option value="задний" <?php echo (isset($car['car_drive']) && $car['car_drive'] == 'задний') ? 'selected' : ''; ?>>Задний</option>
                <option value="полный" <?php echo (isset($car['car_drive']) && $car['car_drive'] == 'полный') ? 'selected' : ''; ?>>Полный</option>
            </select>

            <div class="mb-6">
                <label for="transmission" class="block text-lg font-semibold mb-2">Коробка передач</label>
                <select id="transmission" name="transmission" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="Механическая" <?php echo (isset($car['car_transmission_box']) && $car['car_transmission_box'] == 'Механическая') ? 'selected' : ''; ?>>Механическая</option>
                    <option value="Автоматическая" <?php echo (isset($car['car_transmission_box']) && $car['car_transmission_box'] == 'Автоматическая') ? 'selected' : ''; ?>>Автоматическая</option>
                    <option value="Роботизированная" <?php echo (isset($car['car_transmission_box']) && $car['car_transmission_box'] == 'Роботизированная') ? 'selected' : ''; ?>>Роботизированная</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="fuel_type" class="block text-lg font-semibold mb-2">Тип топлива</label>
                <select id="fuel_type" name="fuel_type" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="Бензин" <?php echo (isset($car['car_type_oil']) && $car['car_type_oil'] == 'Бензин') ? 'selected' : ''; ?>>Бензин</option>
                    <option value="Дизель" <?php echo (isset($car['car_type_oil']) && $car['car_type_oil'] == 'Дизель') ? 'selected' : ''; ?>>Дизель</option>
                    <option value="Электричество" <?php echo (isset($car['car_type_oil']) && $car['car_type_oil'] == 'Электричество') ? 'selected' : ''; ?>>Электричество</option>
                    <option value="Гибрид" <?php echo (isset($car['car_type_oil']) && $car['car_type_oil'] == 'Гибрид') ? 'selected' : ''; ?>>Гибрид</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="equipment_text" class="block text-lg font-semibold mb-2">Описание автомобиля</label>
                <textarea id="equipment_text" name="equipment_text" class="w-full p-2 border border-gray-300 rounded" rows="4" required><?php echo isset($car['car_descriptions']) ? $car['car_descriptions'] : ''; ?></textarea>
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
                    <button type="button" class="move-up bg-yellow-500 text-white p-1 rounded" onclick="movePhotoUp(this)">↑</button>
                    <button type="button" class="move-down bg-yellow-500 text-white p-1 rounded" onclick="movePhotoDown(this)">↓</button>

                <img src="http://localhost/cites/img/cars<?php echo ltrim($photo['car_photo_image_patch']); ?>" alt="Фото" class="mb-2" style="max-width: 100px;">

                <input type="hidden" name="car_photo_id" value="<?php echo $photo['car_photo_id']; ?>">
                <button type="button" class="delete-photo bg-red-500 text-white p-1 rounded" onclick="deletePhoto('<?php echo $photo['car_photo_id']; ?>')">Удалить</button>
                        </div>
                    <?php endwhile; ?>
                </div>
                <input type="file" name="car_photos[]" multiple class="mb-2" accept="image/*">

                <button type="button" id="addPhoto" class="bg-blue-500 text-white p-2 rounded hidden">Добавить фото</button>

            </div>

            <div class="mb-6">
                <label for="car_equipment_descriptions" class="block text-lg font-semibold mb-2">Описание комплектации</label>
                <textarea id="car_equipment_descriptions" name="car_equipment_descriptions" class="w-full p-2 border border-gray-300 rounded" rows="4" required><?php echo isset($equipment['car_equipment_descriptions']) && !empty($equipment['car_equipment_descriptions']) ? $equipment['car_equipment_descriptions'] : ''; ?></textarea>

            </div>
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Элементы комплектации</h2>
                <div id="complectationContainer">
                    <?php if (isset($equipmentElementResult) && $equipmentElementResult): ?>

                        <?php while ($element = $equipmentElementResult->fetch_assoc()): ?>
                            <div class="complectation-item mb-4">
                                <input type="text" name="complectation[]" class="w-3/4 p-2 border border-gray-300 rounded mb-2" value="<?php echo $element['car_equipment_element_text']; ?>" required>
                                <button type="button" class="delete-complectation bg-red-500 text-white p-1 rounded">Удалить</button>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
                <button type="button" id="addComplectation" class="bg-blue-500 text-white p-2 rounded">Добавить элемент комплектации</button>
            </div>

            <button type="submit" name="save" class="bg-green-500 text-white p-2 rounded">Сохранить изменения</button>

        </form>

    <script>
    function validateForm() {
        // Проверка, что все обязательные поля заполнены
        const requiredFields = ['title', 'year', 'bodywork', 'generation', 'mileage', 'color', 'owners', 'engine_volume', 'power', 'transmission', 'drive', 'fuel_type', 'equipment_text'];
        for (let field of requiredFields) {
            if (!document.getElementById(field).value) {
                alert('Пожалуйста, заполните все обязательные поля.');
                return false;
            }
        }
        return true;
    }

    document.querySelector('input[type="file"]').addEventListener('change', function(e) {

        const files = e.target.files;
        const photoContainer = document.getElementById('photoContainer');
        
        // Сохраняем существующие превью
        const existingPreviews = photoContainer.innerHTML;
        
        // Очищаем контейнер и восстанавливаем существующие превью
        photoContainer.innerHTML = existingPreviews;

        // Добавляем новые превью
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100px';
                img.className = 'mb-2';
                
                const div = document.createElement('div');
                div.className = 'photo-item mb-4';
                div.appendChild(img);
                
                photoContainer.appendChild(div);
            }
            
            reader.readAsDataURL(file);
        }

        // Валидация файлов
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (!file.type.match('image.*')) {
                alert('Пожалуйста, выберите только изображения');
                e.target.value = '';
                photoContainer.innerHTML = existingPreviews;
                return;
            }
            if (file.size > 2 * 1024 * 1024) { // 2MB
                alert('Размер файла не должен превышать 2MB');
                e.target.value = '';
                photoContainer.innerHTML = existingPreviews;
                return;
            }
        }
    });

    function deletePhoto(photoID) {
        if (confirm('Вы уверены, что хотите удалить это фото?')) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'php/deletePhoto.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('car_photo_id=' + encodeURIComponent(photoID)); // Передача ID фотографии
            console.log('Отправка запроса на удаление фото с ID: ' + photoID); // Отладочное сообщение
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('Фото успешно удалено.');
                    console.log('Ответ сервера: ' + xhr.responseText); // Отладочное сообщение
                    location.reload();
                } else {
                    alert('Ошибка при удалении фото.');
                }
            };
        }
    }

    function deleteComplectation(complectationId) {
        if (confirm('Вы уверены, что хотите удалить этот элемент комплектации?')) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'php/deleteComplectation.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('Элемент комплектации успешно удален.');
                    location.reload();
                } else {
                    alert('Ошибка при удалении элемента комплектации.');
                }
            };
            xhr.send('complectationId=' + encodeURIComponent(complectationId));
        }
    }
    </script>

    <?php
    include 'template/footer.php';
    ?>
</body>
</html>
