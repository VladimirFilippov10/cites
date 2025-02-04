<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Детали автомобиля</title>
    <script src="js/carFotoSelect.js"></script>
    <style>
        .image-container {
            position: relative;
            width: 100%;
            height: 400px; /* Задаем фиксированную высоту */
            overflow: hidden; /* Скрываем переполнение */
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.1); /* Размытый фон */
        }
        .image-container img {
            max-height: 100%; /* Ограничиваем максимальную высоту */
            width: auto; /* Автоматическая ширина для сохранения пропорций */
            object-fit: cover; /* Обеспечиваем сохранение пропорций */
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php
        include 'template/header.php';
        include 'php/dbconnect.php'; // Подключение к базе данных

        // Проверка наличия ID автомобиля в параметрах URL
        if (!isset($_GET['id'])) {
            echo "<p>Ошибка: ID автомобиля не указан.</p>";
            exit();
        }

        // Получение ID автомобиля из параметров URL
        $car_id = intval($_GET['id']);

        // Запрос данных о конкретном автомобиле
        $query = "SELECT car.*, model.name_model, brand.brand_name, car_equipment.* FROM car 
                  JOIN model ON car.model_id = model.model_id
                  JOIN brand ON model.brand_id = brand.brand_id
                  LEFT JOIN car_equipment ON car_equipment.car_id = car.car_id 
                  WHERE car.car_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $car = $result->fetch_assoc();

        if ($car) {
    ?>
    <div class="max-w-5xl mx-auto p-4 bg-white shadow-md">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold"><?php echo $car['brand_name'] . ' ' . $car['name_model']; ?>, <?php echo $car['car_year_made']; ?></h1>
            </div>
            <div class="text-3xl font-bold text-gray-800"><?php echo number_format($car['car_price'], 0, ',', ' '); ?> ₽</div>
        </div>
        <div class="mt-4 flex">
            <div class="w-1/3">
                <h2 class="text-xl font-semibold mb-2">Характеристики</h2>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-center space-x-2"><i class="fas fa-warehouse"></i><span>В наличии</span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-sync-alt"></i><span>Поколение <?php echo $car['car_generation']; ?></span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-calendar-alt"></i><span><?php echo $car['car_year_made']; ?></span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-car"></i><span><?php echo $car['car_bodywork']; ?></span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-cogs"></i><span><?php echo $car['car_transmission_box']; ?></span></span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-tachometer-alt"></i><span><?php echo $car['car_volume']; ?> л / <?php echo $car['car_power']; ?> л.с. / бензин</span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-road"></i><span><?php echo $car['car_melage']; ?> км</span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-tint"></i><span><?php echo $car['car_color']; ?></span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-id-card"></i><span><?php echo $car['car_onwers']; ?> владельцев по ПТС</span></li>
                </ul>
                <br>  
                <a href="<?php echo $car['car_link_specifications']; ?>" class="mt-6 bg-gray-200 text-gray-700 px-4 py-2 rounded">Характеристики модели</a>
            </div>
            <div class="w-2/3 pl-4">
                <div class="carousel">
                    <?php
                    // Запрос для получения изображений автомобиля
                    $query_photo = "SELECT * FROM car_photo WHERE car_id = " . $car_id;
                    $res_photo = $conn->query($query_photo);
                    if ($res_photo->num_rows > 0) {
                        while ($photo = $res_photo->fetch_assoc()) {
                            echo '<img  class="w-full h-auto rounded" height="100" alt="Изображение автомобиля" src="http://localhost/cites/php/' . $photo['image_patch'] . '" />';
                        }
                            echo '</div><div class="flex justify-between mt-2">
                        <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded" id="prev">
                         <i class="fas fa-chevron-left">
                         </i>
                        </button>
                        <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded" id="next">
                         <i class="fas fa-chevron-right">
                         </i>
                        </button>
                       </div>
                       <div class="grid grid-cols-5 gap-2 mt-4 thumbnail justify-center">';
                       $res_photo = $conn->query($query_photo);
                        while ($photo = $res_photo->fetch_assoc()) {
                            echo '<img alt="Изображение автомобиля" class="w-full h-auto rounded" height="100" width="150" src="http://localhost/cites/php/' . $photo['image_patch'] . '" />';
                        }
                        echo '</div>';
                    } else {
                        echo '<p>Изображения отсутствуют.</p>';
                    }
                    ?>
                <div class="mt-4">
                    <h2 class="text-xl font-semibold mb-2">Описание авто</h2>
                    <p class="text-gray-700"><?php echo isset($car['car_descriptions']) ? $car['car_descriptions'] : 'Описание отсутствует'; ?></p>
                </div>
                <div class="mt-4">
                    <h2 class="text-xl font-semibold mb-2">Описание комплектации</h2>
                    <p class="text-gray-700"><?php echo isset($car['equipment_text']) ? $car['equipment_text'] : 'Описание комплектации отсутствует'; ?></p>
                </div>
                <h3 class="text-lg font-semibold mt-2">
                    В комплектацию входит:
                </h3>
                <ul class="list-disc list-inside text-gray-700">
                    <?php
                    if (isset($car['id_equipment'])) {
                        $query = "SELECT * FROM car_equipment_element WHERE car_equipment_id = " . $car['id_equipment'];
                        $res_comp = $conn->query($query);
                        if ($res_comp->num_rows)
                            while ($comp = $res_comp->fetch_assoc()) {
                                echo '<li>' . $comp['car_equipment_text'] . '</li>';
                            }
                    }
                    ?>
                </ul>

                <div class="mt-4 flex items-center space-x-4">
                    <a class="bg-blue-500 text-white px-4 py-2 rounded" href="TG:+71234567890">Связаться в Telergam</a>
                    <div class="text-lg font-semibold text-gray-700">Телефон для связи: +7 (123) 456-78-90</div>
                </div>
            </div>
        </div>
    </div>
    <?php
        } else {
            echo "<p>Автомобиль не найден.</p>";
        }
    ?>
</body>
<?php
include 'template/footer.php';
?>
</html>
