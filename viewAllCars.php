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

        // Запрос для получения всех автомобилей с именами моделей
        $carsQuery = "SELECT cars.*, model.name_model FROM cars JOIN model ON cars.model_id = model.id_model";
        $carsResult = $conn->query($carsQuery);
    ?>
    <div class="max-w-7xl w-2/4 mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Список автомобилей</h1>
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">Модель</th>
                    <th class="border border-gray-300 p-2">WinCod</th>
                    <th class="border border-gray-300 p-2">Фото</th>
                    <th class="border border-gray-300 p-2">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($car = $carsResult->fetch_assoc()): ?>
                    <tr>
                        <td class="border border-gray-300 p-2"><?php echo $car['name_model']; ?></td>
                        <td class="border border-gray-300 p-2"><?php echo $car['cars_win']; ?></td>
                        <td class="border border-gray-300 p-2">
                            <img src="../img/cars/<?php echo $car['cars_id']; ?>_1.png" alt="Фото" style="max-width: 100px;">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <form action="php/updatePrice.php" method="POST" style="display:inline;">
                                <input type="hidden" name="car_id" value="<?php echo $car['cars_id']; ?>">
                                <button type="submit" name="toggle_price" class="bg-blue-500 text-white p-1 rounded">
                                    <?php echo $car['cars_in_price'] ? 'Снять с продажи' : 'Выставить на продажу'; ?>
                                </button>
                            </form>
                            <a href="editCar.php?id=<?php echo $car['cars_id']; ?>" class="bg-green-500 text-white p-1 rounded">Редактировать</a>
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
