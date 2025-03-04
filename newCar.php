
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
    <title>Добавить авто</title>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
        include 'php/dbconnect.php'; // Подключение к базе данных
    ?>
    <script src="js/newCarPhoto.js"></script>
    <script src="js/newCarComplect.js"></script>
    <script src="js/newCarLimitation.js"></script>

<body class="bg-gray-100 text-gray-800">
<div class="max-w-7xl w-2/4 mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Добавить новую запись</h1>
        <form action="php/submit.php" method="POST" enctype="multipart/form-data" id="carForm">
            <!-- Основная информация -->
            <div class="mb-6">
                <label for="title" class="block text-lg font-semibold mb-2">WinCod</label>
                <input type="text" id="title" name="title" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <!-- Скрытое поле для model_id -->
            <input type="hidden" id="model_id" name="model_id" value="">

            <!-- Марка и модель автомобиля -->
            <div class="mb-6">
                <label for="brand" class="block text-lg font-semibold mb-2">Марка автомобиля</label>
                <select id="brand" name="brand" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="">Выберите марку</option>
                    <?php
                    $marksQuery = "SELECT * FROM brand";
                    $marksResult = $conn->query($marksQuery);
                    if ($marksResult->num_rows > 0) {
                        while($row = $marksResult->fetch_assoc()) {
                            echo '<option value="' . $row['brand_id'] . '">' . $row['brand_name'] . '</option>';
                        }
                    } else {
                        echo '<option value="">Нет доступных марок</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-6">
                <label for="model" class="block text-lg font-semibold mb-2">Модель автомобиля</label>
                <select id="model" name="model" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="">Выберите модель</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="year" class="block text-lg font-semibold mb-2">Год производства</label>
                <select id="year" name="year" class="w-full p-2 border border-gray-300 rounded" required>
                    <?php
                    $currentYear = date('Y');
                    for ($i = 1886; $i <= $currentYear; $i++) {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-6">
                <label for="generation" class="block text-lg font-semibold mb-2">Поколение</label>
                <input type="text" id="generation" name="generation" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <!-- Новые поля -->
            <div class="mb-6">
                <label for="car_state_number" class="block text-lg font-semibold mb-2">Госномер</label>
                <input type="text" id="car_state_number" name="car_state_number" class="w-full p-2 border border-gray-300 rounded" maxlength="9" required>
            </div>
            <div class="mb-6">
                <label for="car_link_specifications" class="block text-lg font-semibold mb-2">Ссылка на характеристики</label>
                <input type="text" id="car_link_specifications" name="car_link_specifications" class="w-full p-2 border border-gray-300 rounded" maxlength="100" required>
            </div>
            <div class="mb-6">
                <label for="car_link_to_report" class="block text-lg font-semibold mb-2">Ссылка на отчет</label>
                <input type="text" id="car_link_to_report" name="car_link_to_report" class="w-full p-2 border border-gray-300 rounded" maxlength="100" required>
            </div>

            <!-- Остальные поля формы -->
            <div class="mb-6">
                <label for="bodywork" class="block text-lg font-semibold mb-2">Тип кузова</label>
                <select id="bodywork" name="bodywork" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="">Выберите тип кузова</option>
                    <option value="Седан">Седан</option>
                    <option value="Хэтчбек">Хэтчбек</option>
                    <option value="Универсал">Универсал</option>
                    <option value="Кроссовер">Кроссовер</option>
                    <option value="Внедорожник">Внедорожник</option>
                    <option value="Купе">Купе</option>
                    <option value="Кабриолет">Кабриолет</option>
                    <option value="Минивен">Минивен</option>
                    <option value="Фургон">Фургон</option>

                </select>
            </div>

            <!-- Галочка "На продажу" -->
            <div class="mb-6">
                <label for="for_sale" class="block text-lg font-semibold mb-2">
                    <input type="checkbox" id="for_sale" name="for_sale" value="1"> На продажу
                </label>
                <div id="price_container" style="display: none;">
                    <label for="price" class="block text-lg font-semibold mb-2">Цена (₽)</label>
                    <input type="number" id="price" name="price" class="w-full p-2 border border-gray-300 rounded">
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Дополнительная информация</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="mileage" class="block text-lg font-semibold mb-2">Пробег (км)</label>
                        <input type="number" id="mileage" name="mileage" class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label for="color" class="block text-lg font-semibold mb-2">Цвет</label>
                        <input type="text" id="color" name="color" class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label for="owners" class="block text-lg font-semibold mb-2">Количество владельцев</label>
                        <input type="number" id="owners" name="owners" class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label for="engine_volume" class="block text-lg font-semibold mb-2">Объем двигателя (л)</label>
                        <input type="number" step="0.1" id="engine_volume" name="engine_volume" class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label for="power" class="block text-lg font-semibold mb-2">Мощность (л.с.)</label>
                        <input type="number" id="power" name="power" class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label for="drive" class="block text-lg font-semibold mb-2">Привод</label>
                        <select id="drive" name="drive" class="w-full p-2 border border-gray-300 rounded" required>
                            <option value="передний">Передний</option>
                            <option value="задний">Задний</option>
                            <option value="полный">Полный</option>
                        </select>
                    </div>
                </div>
            </div>
            <div>
                <label for="transmission" class="block text-lg font-semibold mb-2">Коробка передач</label>
                <select id="transmission" name="transmission" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="Механическая">Механическая</option>
                    <option value="Автоматическая">Автоматическая</option>
                    <option value="Роботизированная">Роботизированная</option>
                </select>
            </div>
            <!-- Тип топлива -->
            <div class="mb-6">
                <label for="fuel_type" class="block text-lg font-semibold mb-2">Тип топлива</label>
                <select id="fuel_type" name="fuel_type" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="Бензин">Бензин</option>
                    <option value="Дизель">Дизель</option>
                    <option value="Электричество">Электричество</option>
                    <option value="Гибрид">Гибрид</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="description" class="block text-lg font-semibold mb-2">Описание автомобиля</label>
                <textarea id="description" name="description" class="w-full p-2 border border-gray-300 rounded" rows="4" required></textarea>
            </div>
            <!-- Описание автомобиля -->
            <div class="mb-6">
                <label for="equipment_text" class="block text-lg font-semibold mb-2">Описание комплектации</label>
                <textarea id="equipment_text" name="equipment_text" class="w-full p-2 border border-gray-300 rounded" rows="4" required></textarea>
            </div>

            <!-- Загрузка изображений автомобиля -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Фотографии автомобиля</h2>
                <div id="photoContainer">
                    <div class="photo-item mb-4">
                        <input type="file" name="car_photos[]" class="mb-2" required>
                        <button type="button" class="delete-photo bg-red-500 text-white p-1 rounded">Удалить</button>
                    </div>
                </div>
                <button type="button" id="addPhoto" class="bg-blue-500 text-white p-2 rounded">Добавить фото</button>
            </div>  
            <!-- Комплектация -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Комплектация</h2>
                <div id="complectationContainer">
                    <div class="complectation-item mb-4">
                        <input type="text" name="complectation[]" class="w-3/4 p-2 border border-gray-300 rounded mb-2" placeholder="Элемент комплектации" required>
                        <button type="button" class="delete-complectation bg-red-500 text-white p-1 rounded">Удалить</button>
                    </div>
                </div>
                <button type="button" id="addComplectation" class="bg-blue-500 text-white p-2 rounded">Добавить элемент комплектации</button>
            </div>
            <!-- Ограничения
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Ограничения и обременения</h2>
                <div id="limitationContainer">
                    <div class="limitation-item mb-4">
                        <input type="text" name="limitation[]" class="w-3/4 p-2 border border-gray-300 rounded mb-2" placeholder="Ограничение" required>
                        <button type="button" class="delete-limitation bg-red-500 text-white p-1 rounded">Удалить</button>
                    </div>
                </div>
                <button type="button" id="addLimitation" class="bg-blue-500 text-white p-2 rounded">Добавить ограничения</button>
            </div>      -->                                 

            <button type="submit" name="save" class="bg-green-500 text-white p-2 rounded">Сохранить</button>
        </form>
    </div>

    <script>
    document.getElementById('carForm').addEventListener('submit', function(event) {
        var engineVolumeInput = document.getElementById('engine_volume');
        engineVolumeInput.value = engineVolumeInput.value.replace(',', '.'); // Replace comma with dot
    });

    document.getElementById('brand').addEventListener('change', function() {
        var brandId = this.value;
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
    });

    document.getElementById('model').addEventListener('change', function() {
        var modelId = this.value;
        document.getElementById('model_id').value = modelId; // Устанавливаем model_id в скрытое поле
    });

    // Показать/скрыть поле для ввода цены в зависимости от галочки "На продажу"
    document.getElementById('for_sale').addEventListener('change', function() {
        var priceContainer = document.getElementById('price_container');
        if (this.checked) {
            priceContainer.style.display = 'block';
        } else {
            priceContainer.style.display = 'none';
        }
    });

    // Очистка полей формы и отображение сообщения
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            alert('Запись успешно добавлена!');
            document.querySelector('form').reset(); // Очистка полей формы
        }
    };
    </script>
    
<?php
include 'template/footer.php';
?>
</body>
</html>
