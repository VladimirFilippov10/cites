<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    $model_id = intval($_POST['model_id']);
    $cars_win = $_POST['title'];
    $cars_year_made = $_POST['year'];
    $cars_generation = $_POST['generation'];
    $cars_melage = intval($_POST['mileage']); // Обновлено на cars_melage
    $cars_color = $_POST['color'];
    $cars_drive = $_POST['drive'];
    $cars_volume = floatval(str_replace(',', '.', $_POST['engine_volume'])); // Исправлено для корректного преобразования
    $cars_power = intval($_POST['power']);
    $cars_transmission_box = $_POST['transmission'];
    $cars_type_oil = $_POST['fuel_type'];
    $cars_description = $_POST['description'];
    $cars_price = floatval($_POST['price']); // Получение цены

    // Получение типа кузова и информации о продаже
    $cars_bodywork = $_POST['bodywork'];
    $cars_in_price = isset($_POST['for_sale']) ? 1 : 0; // 1 если на продажу, иначе 0

    // Вставка данных в таблицу cars
    $insertQuery = "INSERT INTO cars (model_id, cars_win, cars_year_made, cars_generation, cars_melage, cars_color, cars_drive, cars_volume, cars_power, cars_transmission_box, cars_type_oil, cars_descriptions, cars_bodywork, cars_in_price, cars_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ississdissssiii", $model_id, $cars_win, $cars_year_made, $cars_generation, $cars_melage, $cars_color, $cars_drive, $cars_volume, $cars_power, $cars_transmission_box, $cars_type_oil, $cars_description, $cars_bodywork, $cars_in_price, $cars_price);
    $stmt->execute();
    $car_id = $stmt->insert_id; // Получаем ID добавленной записи

    // Обработка загрузки фотографий
    if (!empty($_FILES['car_photos'])) {
        $photoCount = count($_FILES['car_photos']['name']);
        for ($i = 0; $i < $photoCount; $i++) {
            $photoName = $_FILES['car_photos']['name'][$i];
            $photoTmpName = $_FILES['car_photos']['tmp_name'][$i];
            $photoPath = "img/cars/" . $car_id . "_" . ($i + 1) . ".png"; // Формируем имя файла

            // Проверка на ошибки загрузки
            if ($_FILES['car_photos']['error'][$i] === UPLOAD_ERR_OK) {
                // Проверка формата файла
                $fileType = pathinfo($photoName, PATHINFO_EXTENSION);
                $allowedTypes = ['jpg', 'jpeg', 'png'];
                if (in_array($fileType, $allowedTypes)) {
                    if (move_uploaded_file($photoTmpName, $photoPath)) {
                        // Вставка пути к фотографии в таблицу car_photo
                        $insertPhotoQuery = "INSERT INTO car_photo (image_patch, car_id) VALUES (?, ?)";
                        $photoStmt = $conn->prepare($insertPhotoQuery);
                        $photoStmt->bind_param("si", $photoPath, $car_id);
                        $photoStmt->execute();
                    } else {
                        echo "Ошибка при загрузке файла: " . $photoName;
                    }
                } else {
                    echo "Недопустимый формат файла: " . $photoName;
                }
            } else {
                echo "Ошибка загрузки файла: " . $photoName;
            }
        }
    }

    // Обработка комплектации
    if (!empty($_POST['complectation'])) {
        foreach ($_POST['complectation'] as $equipment) {
            $insertEquipmentQuery = "INSERT INTO car_equipment (equipment_text, car_id) VALUES (?, ?)";
            $equipmentStmt = $conn->prepare($insertEquipmentQuery);
            $equipmentStmt->bind_param("si", $equipment, $car_id);
            $equipmentStmt->execute();
        }
    }

    // Перенаправление или сообщение об успешном добавлении
}
?>
