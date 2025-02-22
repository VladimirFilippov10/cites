<?php
include 'php/dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $car_id = isset($_POST['car_id']) ? intval($_POST['car_id']) : 0;

    // Если car_id больше 0, это обновление, иначе добавление
    if ($car_id > 0) {
        // Логика обновления автомобиля
        // Здесь должна быть ваша логика обновления
    } else {


    // Получение данных из формы
    // Убедимся, что car_id не передается для новой записи
    $car_id = isset($_POST['car_id']) ? intval($_POST['car_id']) : 0;

    $car_win_code = mysqli_real_escape_string($conn, $_POST['title']);
    $car_year_made = intval($_POST['year']);
    $car_generation = mysqli_real_escape_string($conn, $_POST['generation']);
    $car_mileage = intval($_POST['mileage']);
    $car_color = mysqli_real_escape_string($conn, $_POST['color']);
    $car_drive = mysqli_real_escape_string($conn, $_POST['drive']);
    $car_volume = floatval(str_replace(',', '.', $_POST['engine_volume']));
    $car_power = intval($_POST['power']);
    $car_transmission_box = mysqli_real_escape_string($conn, $_POST['transmission']);
    $car_type_oil = mysqli_real_escape_string($conn, $_POST['fuel_type']);
    $car_description = mysqli_real_escape_string($conn, $_POST['equipment_text']);
    $car_price = floatval($_POST['price']);
    $car_onwers = intval($_POST['owners']);
    $car_bodywork = mysqli_real_escape_string($conn, $_POST['bodywork']);
    $car_in_price = isset($_POST['for_sale']) ? 1 : 0; // Обработка поля car_in_price
    $car_state_number = mysqli_real_escape_string($conn, $_POST['car_state_number']);
    $car_link_specifications = mysqli_real_escape_string($conn, $_POST['car_link_specifications']);
    $car_link_to_report = mysqli_real_escape_string($conn, $_POST['car_link_to_report']);
    $car_equipment_descriptions = mysqli_real_escape_string($conn, $_POST['car_equipment_descriptions']);

    // Проверка на дублирование VIN-кода и отладка

    $checkVinQuery = "SELECT * FROM car WHERE car_win_code = ?";
    $checkStmt = $conn->prepare($checkVinQuery);
    $checkStmt->bind_param("s", $car_win_code);

    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Ошибка: Автомобиль с таким VIN-кодом уже существует.'); window.location.href='../newCar.php';</script>";
        exit();
    }

        // Вставка данных в таблицу car


    $insertQuery = "INSERT INTO car (model_id, car_win_code, car_year_made, car_generation, car_mileage, car_color, car_drive, car_volume, car_power, car_transmission_box, car_type_oil, car_descriptions, car_price, car_onwers, car_bodywork, car_in_price, car_state_number, car_link_specifications, car_link_to_report) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Убедимся, что мы добавляем новую запись

    
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ississdissss", $model_id, $car_win_code, $car_year_made, $car_generation, $car_mileage, $car_color, $car_drive, $car_volume, $car_power, $car_transmission_box, $car_type_oil, $car_description);

    if (!$stmt->execute()) {
        echo "<script>alert('Ошибка при добавлении автомобиля: " . $stmt->error . "'); window.location.href='../newCar.php';</script>";
        exit();
    }
    
    $car_id = $stmt->insert_id;

    
    $car_id = $stmt->insert_id;

    // Обработка загрузки фотографий и отладка

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

    // Обработка комплектации и элементов комплектации
    if (!empty($_POST['complectation'])) {
        foreach ($_POST['complectation'] as $equipment) {
            $insertEquipmentQuery = "INSERT INTO car_equipment (equipment_text, car_id) VALUES (?, ?)";
            $equipmentStmt = $conn->prepare($insertEquipmentQuery);
            $equipmentStmt->bind_param("si", $equipment, $car_id);
            $equipmentStmt->execute();
        }
    }

    // Обработка элементов комплектации
    if (!empty($_POST['complectation_elements'])) {
        foreach ($_POST['complectation_elements'] as $element) {
            $insertElementQuery = "INSERT INTO car_equipment_element (car_equipment_element_text, car_equipment_id) VALUES (?, (SELECT car_equipment_id FROM car_equipment WHERE car_id = ? LIMIT 1))";

            $elementStmt = $conn->prepare($insertElementQuery);
            $elementStmt->bind_param("si", $element, $car_id);
            $elementStmt->execute();
        }
    }

        // Перенаправление на newCar.php с сообщением об успешном добавлении


    header("Location: ../newCar.php");
    exit();

}
?>
