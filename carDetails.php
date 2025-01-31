<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Детали автомобиля</title>
    <script src="js/carFotoSelect.js"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php
        include 'template/header.php';
        include 'php/dbconnect.php'; // Подключение к базе данных

        // Получение ID автомобиля из параметров URL
        $car_id = intval($_GET['id']);

        // Запрос данных о конкретном автомобиле
        $query = "SELECT cars.*, model.name_model, marks.name_marks, car_equipment.* FROM cars 
                  JOIN model ON cars.model_id = model.id_model
                  JOIN marks ON model.id_marks = marks.id_marks
                  LEFT JOIN car_equipment ON car_equipment.car_id = cars.cars_id 
                  WHERE cars.cars_id = ?";
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
                <h1 class="text-2xl font-bold"><?php echo $car['name_marks'] . ' ' . $car['name_model']; ?>, <?php echo $car['cars_year_made']; ?></h1>
            </div>
            <div class="text-3xl font-bold text-gray-800"><?php echo number_format($car['cars_price'], 0, ',', ' '); ?> ₽</div>
        </div>
        <div class="mt-4 flex">
            <div class="w-1/3">
                <h2 class="text-xl font-semibold mb-2">Характеристики</h2>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-center space-x-2"><i class="fas fa-warehouse"></i><span>В наличии</span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-sync-alt"></i><span>Поколение <?php echo $car['cars_generation']; ?></span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-calendar-alt"></i><span><?php echo $car['cars_year_made']; ?></span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-car"></i><span><?php echo $car['cars_bodywork']; ?></span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-cogs"></i><span><?php echo $car['cars_transmission_box']; ?></span></span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-tachometer-alt"></i><span><?php echo $car['cars_volume']; ?> л / <?php echo $car['cars_power']; ?> л.с. / бензин</span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-road"></i><span><?php echo $car['cars_melage']; ?> км</span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-tint"></i><span><?php echo $car['cars_color']; ?></span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-id-card"></i><span><?php echo $car['cars_drive']; ?> владельцев по ПТС</span></li>
                </ul>
                <button class="mt-4 bg-gray-200 text-gray-700 px-4 py-2 rounded">Характеристики модели</button>
            </div>
            <div class="w-2/3 pl-4">
                <div class="carousel">
                    <?php
                    // Запрос для получения изображений автомобиля
                    $query_photo = "SELECT * FROM car_photo WHERE car_id = " . $car_id;
                    $res_photo = $conn->query($query_photo);
                    if ($res_photo->num_rows > 0) {
                        while ($photo = $res_photo->fetch_assoc()) {
                            echo '<img alt="Изображение автомобиля" class="w-full h-auto rounded" height="400" width="600" src="http://localhost/cites/php/' . $photo['image_patch'] . '" />';
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
                    <p class="text-gray-700"><?php echo isset($car['cars_descriptions']) ? $car['cars_descriptions'] : 'Описание отсутствует'; ?></p>
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
                $query = "SELECT * FROM car_equipment_element WHERE car_equipment_id = " . $car['id_equipment'];
                $res_comp = $conn->query($query);
                    if ($res_comp->num_rows)
                        while ($comp = $res_comp->fetch_assoc()) {
                          echo '<li>'.$comp['car_equipment_text'].'</li>';          
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
