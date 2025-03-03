<?php
include 'dbconnect.php'; // Подключение к базе данных
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_id = intval($_POST['car_id']);
    $car_win_code = mysqli_real_escape_string($conn, $_POST['title']);
    $car_year_made = intval($_POST['year']);
    $car_generation = mysqli_real_escape_string($conn, $_POST['generation']);
    $car_mileage = intval($_POST['mileage']);
    $car_color = mysqli_real_escape_string($conn, $_POST['color']);
    $car_drive = mysqli_real_escape_string($conn, $_POST['car_drive']);
    $car_bodywork = mysqli_real_escape_string($conn, $_POST['bodywork']);
    $car_volume = isset($_POST['engine_volume']) ? floatval(str_replace(',', '.', $_POST['engine_volume'])) : 0.0;

    $car_power = intval($_POST['power']);
    $car_transmission_box = mysqli_real_escape_string($conn, $_POST['transmission']);
    $car_type_oil = mysqli_real_escape_string($conn, $_POST['fuel_type']);
    $car_description = mysqli_real_escape_string($conn, $_POST['equipment_text']);
    $car_price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;

    $car_onwers = isset($_POST['owners']) ? intval($_POST['owners']) : null;

    $car_in_price = isset($_POST['for_sale']) ? 1 : 0;
    $car_state_number = isset($_POST['car_state_number']) ? mysqli_real_escape_string($conn, $_POST['car_state_number']) : null;

    $car_link_specifications = isset($_POST['car_link_specifications']) ? mysqli_real_escape_string($conn, $_POST['car_link_specifications']) : null;

    $car_link_to_report = isset($_POST['car_link_to_report']) ? mysqli_real_escape_string($conn, $_POST['car_link_to_report']) : null;

    $car_equipment_descriptions = isset($_POST['car_equipment_descriptions']) ? mysqli_real_escape_string($conn, $_POST['car_equipment_descriptions']) : null;

    // Обновление данных в таблице car
    $updateCarQuery = "UPDATE car SET car_win_code = ?,
    car_year_made = ?, car_generation = ?, 
    car_mileage = ?, car_color = ?, 
    car_drive = ?, 
    car_volume = ?, 
    car_power = ?,
    car_transmission_box = ?, 
    car_type_oil = ?, 
    car_descriptions = ?,
    car_price = ?, 
    car_onwers = ?, 
    car_bodywork = ?, 
    car_in_price = ?, 
    car_state_number = ?, 
    car_link_specifications = ?, 
    car_link_to_report = ? 
    WHERE car_id = ?";

    $updateCarStmt = $conn->prepare($updateCarQuery);
    $updateCarStmt->bind_param(
        "siisssddssssdsisssi", 
        $car_win_code, 
        $car_year_made, 
        $car_generation, 
        $car_mileage, 
        $car_color, 
        $car_drive, 
        $car_volume, 
        $car_power, 
        $car_transmission_box, 
        $car_type_oil, 
        $car_description, 
        $car_price, 
        $car_onwers, 
        $car_bodywork, 
        $car_in_price, 
        $car_state_number, 
        $car_link_specifications, 
        $car_link_to_report, 
        $car_id
    );
    $updateCarStmt->execute();

    // Получаем массив элементов комплектации и удаляем дубликаты
    $equipmentElementTexts = isset($_POST['complectation']) && is_array($_POST['complectation']) ? array_unique($_POST['complectation']) : [];

    // Удаляем все существующие элементы комплектации для данного автомобиля
    $equipmentQuery = "SELECT * FROM car_equipment WHERE car_id = ?";
    $equipmentStmt = $conn->prepare($equipmentQuery);
    $equipmentStmt->bind_param("i", $car_id);
    $equipmentStmt->execute();
    $equipmentResult = $equipmentStmt->get_result();
    $equipment = $equipmentResult->fetch_assoc();
    $car_equipment_id = $equipment['car_equipment_id']; 

    $deleteElementsQuery = "DELETE FROM car_equipment_element WHERE car_equipment_id = ?";
    $deleteElementsStmt = $conn->prepare($deleteElementsQuery);
    $deleteElementsStmt->bind_param("i", $car_equipment_id);
    $deleteElementsStmt->execute();

    // Вставляем новые элементы комплектации
    foreach ($equipmentElementTexts as $text) {
        $insertElementQuery = "INSERT INTO car_equipment_element (car_equipment_element_text, car_equipment_id) VALUES (?, ?)";
        $insertElementStmt = $conn->prepare($insertElementQuery);
        $insertElementStmt->bind_param("si", $text, $car_equipment_id);
        $insertElementStmt->execute();
    }

    // Обработка фотографий
    if (!empty($_FILES['car_photos'])) {
        $fileCount = count($_FILES['car_photos']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['car_photos']['error'][$i] !== UPLOAD_ERR_OK) {
                return; // Прерываем выполнение, если есть ошибка
            }
        }

        // Создаем папку для фотографий, если ее нет
        $uploadDir = '../img/cars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Получаем максимальный номер существующего фото
        $maxNumberQuery = "SELECT MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(car_photo_image_patch, '_', -1), '.', 1) AS UNSIGNED)) as max_num 
                          FROM car_photo WHERE car_id = ?";
        $maxNumberStmt = $conn->prepare($maxNumberQuery);
        $maxNumberStmt->bind_param("i", $car_id);
        $maxNumberStmt->execute();
        $maxNumberResult = $maxNumberStmt->get_result();
        $maxNumber = $maxNumberResult->fetch_assoc()['max_num'] ?? 0;

        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['car_photos']['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['car_photos']['tmp_name'][$i];
                $maxNumber++;
                $fileName = $car_id . '_' . $maxNumber . '.png';
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($tmpName, $filePath)) {
                    // Файл успешно перемещен
                    $insertPhotoQuery = "INSERT INTO car_photo (car_photo_image_patch, car_id) VALUES (?, ?)";
                    $photoStmt = $conn->prepare($insertPhotoQuery);
                    $photoPath = '/' . $fileName;
                    $photoStmt->bind_param("si", $photoPath, $car_id);
                    $photoStmt->execute();
                } else {
                    echo "<script>console.log('Failed to move file: " . $fileName . "');</script>"; // Debugging output
                }
            }
        }
    }

    echo "<script>alert('Данные успешно обновлены!'); window.location.href = '../viewAllCars.php';</script>";
    exit();

}
?>
