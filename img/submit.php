<?php
include 'php/dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    $model_id = intval($_POST['model_id']);
    $cars_win = $_POST['title'];
    $cars_year_made = $_POST['year'];
    $cars_generation = $_POST['generation'];
    $cars_melage = intval($_POST['meleage']);
    $cars_color = $_POST['color'];
    $cars_drive = $_POST['drive'];
    $cars_volume = floatval($_POST['engine_volume']);
    $cars_power = intval($_POST['power']);
    $cars_transmission_box = $_POST['transmission'];
    $cars_type_oil = $_POST['fuel_type'];
    $cars_description = $_POST['description'];

    // Проверка на дублирование VIN-кода
    $checkVinQuery = "SELECT * FROM cars WHERE cars_win = ?";
    $checkStmt = $conn->prepare($checkVinQuery);
    $checkStmt->bind_param("s", $cars_win);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Ошибка: Автомобиль с таким VIN-кодом уже существует.'); window.location.href='../newCar.php';</script>";
        exit();
    }

    // Вставка данных в таблицу cars
    $insertQuery = "INSERT INTO cars (model_id, cars_win, cars_year_made, cars_generation, cars_melage, cars_color, cars_drive, cars_volume, cars_power, cars_transmission_box, cars_type_oil, cars_descriptions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ississdissss", $model_id, $cars_win, $cars_year_made, $cars_generation, $cars_melage, $cars_color, $cars_drive, $cars_volume, $cars_power, $cars_transmission_box, $cars_type_oil, $cars_description);
    $stmt->execute();
    $car_id = $stmt->insert_id;

    // Обработка загрузки фотографий
    if (!empty($_FILES['car_photos'])) {
        $photoCount = count($_FILES['car_photos']['name']);
        for ($i = 0; $i < $photoCount; $i++) {
            $photoName = $_FILES['car_photos']['name'][$i];
            $photoTmpName = $_FILES['car_photos']['tmp_name'][$i];
            $photoPath = "img/cars/" . $car_id . "_" . ($i + 1) . ".jpg";

            move_uploaded_file($photoTmpName, $photoPath);

            // Вставка пути к фотографии в таблицу car_photo
            $insertPhotoQuery = "INSERT INTO car_photo (image_patch, car_id) VALUES (?, ?)";
            $photoStmt = $conn->prepare($insertPhotoQuery);
            $photoStmt->bind_param("si", $photoPath, $car_id);
            $photoStmt->execute();
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

    // Перенаправление на newCar.php с сообщением об успешном добавлении
    echo "<script>alert('Запись успешно добавлена!'); window.location.href='../newCar.php';</script>";
}
?>
