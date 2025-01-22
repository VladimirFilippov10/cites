<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить авто</title>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template\header.php';
        include 'template\nav_employees.php';
    ?>
        <script src="js/newCarPhoto.js"></script>
        <script src="js/newCarComplect.js"></script>
        <script src="js/newCarLimitation.js"></script>



<body class="bg-gray-100 text-gray-800">
<div class="max-w-7xl w-2/4 mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Добавить новую запись</h1>
        <form action="submit.php" method="POST" enctype="multipart/form-data">
            <!-- Основная информация -->
            <div class="mb-6">
                <label for="title" class="block text-lg font-semibold mb-2">WinCod</label>
                <input type="text" id="title" name="title" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <!-- Марка и модель автомобиля -->
            <div class="mb-6">
                <label for="brand" class="block text-lg font-semibold mb-2">Марка автомобиля</label>
                <select id="brand" name="brand" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="">Выберите марку</option>
                    <!-- Здесь будут загружаться марки из базы данных -->
                </select>
            </div>

            <div class="mb-6">
                <label for="model" class="block text-lg font-semibold mb-2">Модель автомобиля</label>
                <select id="model" name="model" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="">Выберите модель</option>
                    <!-- Здесь будут загружаться модели из базы данных -->
                </select>
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
                            <option value="front">Передний</option>
                            <option value="rear">Задний</option>
                            <option value="all">Полный</option>
                        </select>
                    </div>
                </div>
            </div>
            <div>
                        <label for="transmission" class="block text-lg font-semibold mb-2">Коробка передач</label>
                        <select id="transmission" name="transmission" class="w-full p-2 border border-gray-300 rounded" required>
                            <option value="manual">Механическая</option>
                            <option value="automatic">Автоматическая</option>
                            <option value="semi-automatic">Полуавтоматическая</option>
                        </select>
                    </div>
            <!-- Тип топлива -->
            <div class="mb-6">
                <label for="fuel_type" class="block text-lg font-semibold mb-2">Тип топлива</label>
                <select id="fuel_type" name="fuel_type" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="petrol">Бензин</option>
                    <option value="diesel">Дизель</option>
                    <option value="electric">Электричество</option>
                    <option value="hybrid">Гибрид</option>
                </select>
            </div>
    
            <!-- Описание автомобиля -->
            <div class="mb-6">
                <label for="description" class="block text-lg font-semibold mb-2">Описание автомобиля</label>
                <textarea id="description" name="description" class="w-full p-2 border border-gray-300 rounded" rows="4" required></textarea>
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
            <!-- Ограничения -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Ограничения и обременения</h2>
                <div id="limitationContainer">
                    <div class="limitation-item mb-4">
                        <input type="text" name="limitation[]" class="w-3/4 p-2 border border-gray-300 rounded mb-2" placeholder="Ограничение" required>
                        <button type="button" class="delete-limitation bg-red-500 text-white p-1 rounded">Удалить</button>
                    </div>
                </div>
                <button type="button" id="addLimitation" class="bg-blue-500 text-white p-2 rounded">Добавить ограничения</button>
            </div>                                        
            <button type="submit" class="bg-green-500 text-white p-2 rounded">Сохранить</button>
        </form>
    </div>

<?php
include 'template/footer.php'
?>
</body>
</html>