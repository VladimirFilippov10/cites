<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить авто</title>
    <script src="js/messageRansomCars.js"></script>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template\header.php';
        include 'template\nav_employees.php';
    ?>
<body class="bg-gray-100 text-gray-800">
    <div class="max-w-5xl mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Добавить новую запись</h1>
        <form action="submit.php" method="POST" enctype="multipart/form-data">
            <!-- Основная информация -->
            <div class="mb-6">
                <label for="title" class="block text-lg font-semibold mb-2">Название автомобиля</label>
                <input type="text" id="title" name="title" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <div class="mb-6">
                <label for="price" class="block text-lg font-semibold mb-2">Цена</label>
                <input type="text" id="price" name="price" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-lg font-semibold mb-2">Описание автомобиля</label>
                <textarea id="description" name="description" class="w-full p-2 border border-gray-300 rounded" rows="4" required></textarea>
            </div>

            <!-- Дополнительная информация -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Дополнительная информация</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="generation" class="block text-lg font-semibold mb-2">Поколение</label>
                        <input type="text" id="generation" name="generation" class="w-full p-2 border border-gray-300 rounded">
                    </div>
                    <div>
                        <label for="mileage" class="block text-lg font-semibold mb-2">Пробег (км)</label>
                        <input type="number" id="mileage" name="mileage" class="w-full p-2 border border-gray-300 rounded">
                    </div>
                    <div>
                        <label for="owners" class="block text-lg font-semibold mb-2">Количество владельцев</label>
                        <input type="number" id="owners" name="owners" class="w-full p-2 border border-gray-300 rounded">
                    </div>
                    <div>
                        <label for="vin" class="block text-lg font-semibold mb-2">VIN код</label>
                        <input type="text" id="vin" name="vin" class="w-full p-2 border border-gray-300 rounded">
                    </div>
                    <div>
                        <label for="power" class="block text-lg font-semibold mb-2">Мощность (л.с.)</label>
                        <input type="number" id="power" name="power" class="w-full p-2 border border-gray-300 rounded">
                    </div>
                    <div>
                        <label for="engine_volume" class="block text-lg font-semibold mb-2">Объем двигателя (л)</label>
                        <input type="number" step="0.1" id="engine_volume" name="engine_volume" class="w-full p-2 border border-gray-300 rounded">
                    </div>
                </div>
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
                        <input type="text" name="complectation[]" class="w-full p-2 border border-gray-300 rounded mb-2" placeholder="Комплектация" required>
                        <button type="button" class="delete-complectation bg-red-500 text-white p-1 rounded">Удалить</button>
                    </div>
                </div>
                <button type="button" id="addComplectation" class="bg-blue-500 text-white p-2 rounded">Добавить комплектацию</button>
            </div>

            <button type="submit" class="bg-green-500 text-white p-2 rounded">Сохранить</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Добавление нового фото
            $('#addPhoto').click(function() {
                $('#photoContainer').append(`
                    <div class="photo-item mb-4">
                        <input type="file" name="car_photos[]" class="mb-2" required>
                        <button type="button" class="delete-photo bg-red-500 text-white p-1 rounded">Удалить</button>
                    </div>
                `);
            });

            // Удаление фото
            $(document).on('click', '.delete-photo', function() {
                $(this).closest('.photo-item').remove();
            });

            // Добавление нового элемента комплектации
            $('#addComplectation').click(function() {
                $('#complectationContainer').append(`
                    <div class="complectation-item mb-4">
                        <input type="text" name="complectation[]" class="w-full p-2 border border-gray-300 rounded mb-2" placeholder="Комплектация" required>
                        <button type="button" class="delete-complectation bg-red-500 text-white p-1 rounded">Удалить</button>
                    </div>
                `);
            });

            // Удаление элемента комплектации
            $(document).on('click', '.delete-complectation', function() {
                $(this).closest('.complectation-item').remove();
            });
        });
    </script>
<?php
include 'template/footer.php'
?>
</body>
</html>