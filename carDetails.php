<?php
session_start();
//include 'php/auth.php';
//checkAuth(); // Проверка аутентификации

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Детали автомобиля</title>
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
        .carousel img {
            height: 400px; /* Фиксированная высота для основной картинки */
            object-fit: cover; /* Обеспечиваем сохранение пропорций */
        }

        .image-container img {
            max-height: 100%; /* Ограничиваем максимальную высоту */
            width: auto; /* Автоматическая ширина для сохранения пропорций */
            object-fit: cover; /* Обеспечиваем сохранение пропорций */
        }
        .carousel {
            position: relative;
        }
        .carousel img {
            display: none;
        }
        .carousel img.active {
            display: block;
        }
        .thumbnail img {
            width: 150px; /* Фиксированная ширина для миниатюр */
            height: 100px; /* Фиксированная высота для миниатюр */
            object-fit: cover; /* Обеспечиваем сохранение пропорций */
            cursor: pointer;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const images = document.querySelectorAll('.carousel img');
            const thumbnails = document.querySelectorAll('.thumbnail img');
            let currentIndex = 0;

            function showImage(index) {
                images.forEach((img, i) => {
                    img.classList.toggle('active', i === index);
                });
            }

            document.getElementById('prev').addEventListener('click', function () {
                currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
                showImage(currentIndex);
            });

            document.getElementById('next').addEventListener('click', function () {
                currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
                showImage(currentIndex);
            });

            thumbnails.forEach((thumbnail, index) => {
                thumbnail.addEventListener('click', function () {
                    currentIndex = index;
                    showImage(currentIndex);
                });
            });

            showImage(currentIndex);
        });
    </script>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php
        include 'template/header.php';
        if (isset($_SESSION['user_id'])) {
            include 'template/nav_employees.php'; // Подключение навигации для аутентифицированных пользователей
        }
        include 'php/dbconnect.php'; // Подключение к базе данных

        // Проверка наличия ID автомобиля в параметрах URL
        if (!isset($_GET['id'])) {
            echo "<p>Ошибка: ID автомобиля не указан.</p>";
            exit();
        }
        // Получение ID автомобиля из параметров URL
        $car_id = intval($_GET['id']);
        // Запрос данных о конкретном автомобиле
        $query = "SELECT car.*, model.model_name, brand.brand_name, car_equipment.* FROM car 
                  JOIN model ON car.model_id = model.model_id
                  JOIN brand ON model.brand_id = brand.brand_id
                  LEFT JOIN car_equipment ON car_equipment.car_id = car.car_id 
                  WHERE car.car_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $car = $result->fetch_assoc();
        $queryEquipment = "SELECT * FROM car_equipment WHERE car_id =?";
        $stmtEquipment = $conn->prepare($queryEquipment);
        $stmtEquipment->bind_param("i", $car_id);
        $stmtEquipment->execute();
        $carEquipment = $stmtEquipment->get_result();
        $equipment = $carEquipment->fetch_assoc();

        if ($car) {
    ?>
    <div class="max-w-5xl mx-auto p-4 bg-white shadow-md">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold"><?php echo $car['brand_name'] . ' ' . $car['model_name']; ?>, <?php echo $car['car_year_made']; ?></h1>
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
                    <li class="flex items-center space-x-2"><i class="fas fa-road"></i><span><?php echo $car['car_mileage']; ?> км</span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-tint"></i><span><?php echo $car['car_color']; ?></span></li>
                    <li class="flex items-center space-x-2"><i class="fas fa-id-card"></i><span><?php echo $car['car_onwers']; ?> владельцев по ПТС</span></li>
                </ul>
                <br>  
                <div class="mt-4">
                    <a href="<?php echo $car['car_link_specifications']; ?>" class="bg-gray-200 text-gray-700 w-full text-center px-4 py-2 rounded">Характеристики модели</a>
                </div>
                <div class="mt-4">
                    <a href="<?php echo $car['car_link_to_report']; ?>" class="bg-gray-200 text-gray-700 w-full text-center px-4 py-2 rounded">Отчёт автотеки</a>
                </div>
            </div>
            <div class="w-2/3 pl-4">
                <div class="carousel">
                    <?php
                    // Запрос для получения изображений автомобиля
                    $query_photo = "SELECT * FROM car_photo WHERE car_id = " . $car_id;
                    $res_photo = $conn->query($query_photo);
                    if ($res_photo->num_rows > 0) {
                        while ($photo = $res_photo->fetch_assoc()) {
                            echo '<img  class="w-full rounded" height="100" alt="Изображение автомобиля" src="img/cars' . $photo['car_photo_image_patch'] . '" />';

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
                            echo '<img alt="Изображение автомобиля" class="rounded" height="100" width="150" style="object-fit: cover;" src="img/cars' . $photo['car_photo_image_patch'] . '" />';
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
                    <p class="text-gray-700"><?php echo isset($equipment['car_equipment_descriptions']) ? $equipment['car_equipment_descriptions'] : 'Описание комплектации отсутствует'; ?></p>
                </div>
                <h3 class="text-lg font-semibold mt-2">В комплектацию входит:</h3>
                <ul class="list-disc list-inside text-gray-700">
                    <?php
                    if (isset($equipment['car_equipment_id'])) {
                        $query = "SELECT * FROM car_equipment_element WHERE car_equipment_id = " . $equipment['car_equipment_id'];
                        echo "<script>console.log('Executing query: " . $query . "');</script>";

                        $res_comp = $conn->query($query);
                        if (!$res_comp) {
                            echo "<script>console.log('Query error: " . $conn->error . "');</script>";
                        } else {
                            if ($res_comp->num_rows > 0) {
                                while ($comp = $res_comp->fetch_assoc()) {
                                    echo '<li>' . $comp['car_equipment_element_text'] . '</li>';
                                }
                            } else {
                                echo '<li>Нет элементов комплектации.</li>';
                            }
                        }
                    } else {
                        echo '<li>Нет информации о комплектации.</li>';
                    }
                   /* if ($_SESSION['employee_role'] != 4)
                    {
                    $query = "SELECT * FROM car_buyback WHERE car_buyback_id = " . $car['car_id'] . " ORDER BY car_buyback_datetime ASC";
                        $result = $conn->query($query);
                         while ($buyback = $result->fetch_assoc()) {
                            echo '<div class="bg-gray-100 border border-gray-300 rounded-md p-3 mb-3 shadow-sm">';
                            echo '<p class="text-gray-700 font-semibold mb-1">Дата последнего выкупа: <span class="font-normal">' . $buyback['car_buyback_datetime'] . '</span></p>';
                            echo '<p class="text-gray-700 font-semibold">Цена выкупа: <span class="font-normal">' . number_format($buyback['car_buyback_price'], 0, ",", " ") . ' ₽</span></p>';
                            echo '</div>';
                         }

                    }*/
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

        // Получение панели похожих автомобилей
        $similarCars = [];
        $excludedIds = [$car_id];

        // 1. Получить автомобили с таким же типом кузова
        $querySimilarBody = "SELECT car.car_id, brand.brand_name, model.model_name, car.car_year_made, car.car_price, car.car_bodywork
                             FROM car
                             JOIN model ON car.model_id = model.model_id
                             JOIN brand ON model.brand_id = brand.brand_id
                             WHERE car.car_bodywork = ? AND car.car_id != ? AND car.car_in_price = 1
                             LIMIT 3";
        $stmtSimilarBody = $conn->prepare($querySimilarBody);
        $stmtSimilarBody->bind_param("si", $car['car_bodywork'], $car_id);
        $stmtSimilarBody->execute();
        $resultSimilarBody = $stmtSimilarBody->get_result();
        while ($row = $resultSimilarBody->fetch_assoc()) {
            $similarCars[] = $row;
            $excludedIds[] = $row['car_id'];
        }

        // 2. Если меньше 3, добавить автомобили той же марки, исключая уже выбранные
        if (count($similarCars) < 3) {
            $placeholders = implode(',', array_fill(0, count($excludedIds), '?'));
            $types = str_repeat('i', count($excludedIds));
            $querySimilarBrand = "SELECT car.car_id, brand.brand_name, model.model_name, car.car_year_made, car.car_price, car.car_bodywork
                                  FROM car
                                  JOIN model ON car.model_id = model.model_id
                                  JOIN brand ON model.brand_id = brand.brand_id
                                  WHERE brand.brand_name = ? AND car.car_id NOT IN ($placeholders) AND car.car_in_price = 1
                                  LIMIT ?";
            $stmtSimilarBrand = $conn->prepare($querySimilarBrand);
            $params = array_merge([$car['brand_name']], $excludedIds, [3 - count($similarCars)]);
            $bind_names[] = str_repeat('s', 1) . $types . 'i';
            $bind_names = [];
            $bind_names[] = str_repeat('s', 1) . $types . 'i';
            $bind_names[0] = 's' . $types . 'i';
            $stmtSimilarBrand->bind_param($bind_names[0], ...$params);
            $stmtSimilarBrand->execute();
            $resultSimilarBrand = $stmtSimilarBrand->get_result();
            while ($row = $resultSimilarBrand->fetch_assoc()) {
                $similarCars[] = $row;
                $excludedIds[] = $row['car_id'];
            }
        }

        // 3. Если все еще меньше 3, добавить любые автомобили, исключая уже выбранные
        if (count($similarCars) < 3) {
            $placeholders = implode(',', array_fill(0, count($excludedIds), '?'));
            $types = str_repeat('i', count($excludedIds));
            $queryAnyCars = "SELECT car.car_id, brand.brand_name, model.model_name, car.car_year_made, car.car_price, car.car_bodywork
                             FROM car
                             JOIN model ON car.model_id = model.model_id
                             JOIN brand ON model.brand_id = brand.brand_id
                             WHERE car.car_id NOT IN ($placeholders) AND car.car_in_price = 1
                             LIMIT ?";
            $stmtAnyCars = $conn->prepare($queryAnyCars);
            $params = array_merge($excludedIds, [3 - count($similarCars)]);
            $bind_names = [];
            $bind_names[0] = $types . 'i';
            $stmtAnyCars->bind_param($bind_names[0], ...$params);
            $stmtAnyCars->execute();
            $resultAnyCars = $stmtAnyCars->get_result();
            while ($row = $resultAnyCars->fetch_assoc()) {
                $similarCars[] = $row;
                $excludedIds[] = $row['car_id'];
            }
        }

        // Отобразить панель похожих автомобилей
        if (count($similarCars) > 0) {
            echo '<div class="max-w-5xl mx-auto p-4 bg-white shadow-md mt-8">';
            echo '<h2 class="text-2xl font-bold mb-4">Похожие автомобили</h2>';
            echo '<div class="grid grid-cols-3 gap-4">';
            foreach ($similarCars as $simCar) {
                // Получить одну фотографию для похожего автомобиля
                $photoPath = '';
                $queryPhoto = "SELECT car_photo_image_patch FROM car_photo WHERE car_id = ? LIMIT 1";
                $stmtPhoto = $conn->prepare($queryPhoto);
                $stmtPhoto->bind_param("i", $simCar['car_id']);
                $stmtPhoto->execute();
                $resultPhoto = $stmtPhoto->get_result();
                if ($resultPhoto->num_rows > 0) {
                    $photo = $resultPhoto->fetch_assoc();
                    $photoPath = $photo['car_photo_image_patch'];
                }
                echo '<div class="border rounded p-2 shadow hover:shadow-lg">';
                echo '<a href="carDetails.php?id=' . $simCar['car_id'] . '" class="block">';
                if ($photoPath) {
                    echo '<img src="img/cars' . $photoPath . '" alt="Фото автомобиля" class="w-full h-40 object-cover rounded mb-2">';
                } else {
                    echo '<div class="w-full h-40 bg-gray-200 flex items-center justify-center rounded mb-2">Нет фото</div>';
                }
                echo '<h3 class="text-lg font-semibold">' . htmlspecialchars($simCar['brand_name']) . ' ' . htmlspecialchars($simCar['model_name']) . '</h3>';
                echo '<p>' . htmlspecialchars($simCar['car_year_made']) . '</p>';
                echo '<p class="font-bold">' . number_format($simCar['car_price'], 0, ',', ' ') . ' ₽</p>';
                echo '</a>';
                echo '</div>';
            }
            echo '</div></div>';
        }
    ?>
</body>
<?php
include 'template/footer.php';
?>
