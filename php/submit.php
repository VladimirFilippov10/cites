<?php
 include 'dbconnect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $model_id = isset($_POST['model_id']) ? intval($_POST['model_id']) : 0; // Инициализация переменной model_id
    if ($model_id <= 0) {
        die("Ошибка: Не выбрана модель.");
    }
    $car_id = isset($_POST['car_id']) ? intval($_POST['car_id']) : 0;
    // Получение данных из формы и отладка
    error_log("Данные для вставки: " . json_encode($_POST)); // Логируем данные перед вставкой
    $car_win_code = mysqli_real_escape_string($conn, $_POST['title']); // VIN-код автомобиля
    $car_year_made = intval($_POST['year']) ?: 0; // Год выпуска автомобиля
    $car_generation = mysqli_real_escape_string($conn, $_POST['generation']); // Поколение автомобиля
    $car_mileage = intval($_POST['mileage']) ?: 0; // Пробег автомобиля
    $car_power = intval($_POST['power']) ?: 0; // Мощность автомобиля
    $car_onwers = intval($_POST['owners']) ?: 0; // Количество владельцев
    $car_volume = floatval(str_replace(',', '.', $_POST['engine_volume'])) ?: 0.0; // Объем двигателя
    $car_price = floatval($_POST['price']) ?: 0.0; // Цена автомобиля
    $car_bodywork = mysqli_real_escape_string($conn, $_POST['bodywork']); // Кузов автомобиля
    $car_in_price = isset($_POST['for_sale']) ? true : false; // Обработка поля car_in_price
    $car_state_number = mysqli_real_escape_string($conn, $_POST['car_state_number']); // Госномер автомобиля
    $car_link_specifications = mysqli_real_escape_string($conn, $_POST['car_link_specifications']); // Ссылка на спецификации
    $car_color = mysqli_real_escape_string($conn, $_POST['color']); // Цвет автомобиля
    $car_drive = mysqli_real_escape_string($conn, $_POST['drive']); // Привод автомобиля
    $car_transmission_box = mysqli_real_escape_string($conn, $_POST['transmission']); // Коробка передач
    $car_fuel_type = isset($_POST['fuel_type']) ? mysqli_real_escape_string($conn, $_POST['fuel_type']) : ''; // Тип топлива

    $car_description = mysqli_real_escape_string($conn, $_POST['description']); // Описание автомобиля

    $car_link_to_report = mysqli_real_escape_string($conn, $_POST['car_link_to_report']); // Ссылка на отчет
    $car_equipment_descriptions = mysqli_real_escape_string($conn, $_POST['equipment_text']); // Описание комплектации
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
    $insertQuery = "INSERT INTO car (model_id, car_win_code, car_year_made, car_generation, car_mileage, car_color, car_drive, car_volume, car_power, car_transmission_box, car_type_oil, car_descriptions, car_price, car_onwers, car_bodywork, car_in_price, car_state_number, car_link_specifications, car_link_to_report) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("issssssddissssssiss", $model_id, $car_win_code, $car_year_made, $car_generation, $car_mileage, $car_color, $car_drive, $car_volume, $car_power, $car_transmission_box, $car_fuel_type, $car_description, $car_price, $car_onwers, $car_bodywork, $car_in_price, $car_state_number, $car_link_specifications, $car_link_to_report);
    if (!$stmt->execute()) {
        echo "<script>alert('Ошибка при добавлении автомобиля: " . $stmt->error . "'); window.location.href='../newCar.php';</script>"; // Добавление более детализированного сообщения об ошибке
        exit();
    }

    $car_id = $stmt->insert_id;

    // Обработка загрузки фотографий и отладка
    if (!empty($_FILES['car_photos'])) {
        $photoCount = count($_FILES['car_photos']['name']);
        for ($i = 0; $i < $photoCount; $i++) {
            $photoName = $_FILES['car_photos']['name'][$i];
            $photoTmpName = $_FILES['car_photos']['tmp_name'][$i];
            $photoPath = "../img/cars/" . $car_id . "_" . ($i + 1) . ".png";
            move_uploaded_file($photoTmpName, $photoPath);
            // Вставка пути к фотографии в таблицу car_photo
            $insertPhotoQuery = "INSERT INTO car_photo (car_photo_image_patch, car_id) VALUES (?, ?)";


            $photoStmt = $conn->prepare($insertPhotoQuery);
            $photoPath = "/".$car_id . "_" . ($i + 1) . ".png";
            $photoStmt->bind_param("si", $photoPath, $car_id);


            $photoStmt->execute();
        }
    }

    // Обработка комплектации и элементов комплектации
    if (!empty($_POST['complectation'])) {
        foreach ($_POST['complectation'] as $equipment) {
            $insertEquipmentQuery = "INSERT INTO car_equipment (car_equipment_descriptions, car_id) VALUES (?, ?)";
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

    // Возвращаем JSON-ответ с успешным добавлением
    echo json_encode(['success' => true]);
    exit();
}

?>
