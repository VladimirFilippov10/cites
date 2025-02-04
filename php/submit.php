<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    $model_id = intval($_POST['model_id']);
    $car_win_code = mysqli_real_escape_string($conn, $_POST['title']);
    $car_year_made = intval($_POST['year']);
    $car_generation = mysqli_real_escape_string($conn, $_POST['generation']);
    $car_mileage = intval($_POST['mileage']);
    $car_color = mysqli_real_escape_string($conn, $_POST['color']);
    $car_onwers = intval($_POST['drive']);
    $car_volume = floatval(str_replace(',', '.', $_POST['engine_volume']));
    $car_power = intval($_POST['power']);
    $car_transmission_box = mysqli_real_escape_string($conn, $_POST['transmission']);
    $car_type_oil = mysqli_real_escape_string($conn, $_POST['fuel_type']);
    $car_description = mysqli_real_escape_string($conn, $_POST['description']);
    $car_price = floatval($_POST['price']);
    $car_owners = intval($_POST['owners']);
    $car_bodywork = mysqli_real_escape_string($conn, $_POST['bodywork']);
    $car_in_price = isset($_POST['for_sale']) ? 1 : 0;

    // Новые поля
    $car_state_number = mysqli_real_escape_string($conn, $_POST['car_state_number']);
    $car_link_specifications = mysqli_real_escape_string($conn, $_POST['car_link_specifications']);
    $car_link_to_report = mysqli_real_escape_string($conn, $_POST['car_link_to_report']);

    // Проверка на редактирование или создание новой записи
    if (isset($_POST['car_id']) && !empty($_POST['car_id'])) {
        // Редактирование существующей записи
        $car_id = intval($_POST['car_id']);
        $updateQuery = "UPDATE car SET model_id = $model_id, car_win_code = '$car_win_code', car_year_made = $car_year_made, car_generation = '$car_generation', car_mileage = $car_mileage, car_color = '$car_color', car_onwers = $car_onwers, car_volume = $car_volume, car_power = $car_power, car_transmission_box = '$car_transmission_box', car_type_oil = '$car_type_oil', car_descriptions = '$car_description', car_bodywork = '$car_bodywork', car_in_price = $car_in_price, car_price = $car_price, car_state_number = '$car_state_number', car_link_specifications = '$car_link_specifications', car_link_to_report = '$car_link_to_report' WHERE car_id = $car_id";
        
        $stmt = $conn->query($updateQuery);
    } else {
        // Создание новой записи
        $insertQuery = "INSERT INTO car (car_onwers, model_id, car_win_code, car_year_made, car_generation, car_mileage, car_color, car_volume, car_power, car_transmission_box, car_type_oil, car_descriptions, car_bodywork, car_in_price, car_price, car_state_number, car_link_specifications, car_link_to_report) VALUES ('$car_onwers', $model_id, '$car_win_code', $car_year_made, '$car_generation', $car_mileage, '$car_color', $car_volume, $car_power, '$car_transmission_box', '$car_type_oil', '$car_description', '$car_bodywork', $car_in_price, $car_price, '$car_state_number', '$car_link_specifications', '$car_link_to_report')";
        
        $stmt = $conn->query($insertQuery);
        $car_id = $conn->insert_id; // Получаем ID добавленной записи
    }

    // Обработка загрузки фотографий
    if (!empty($_FILES['car_photos'])) {
        $targetDir = "../img/cars/";

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $photoCount = count($_FILES['car_photos']['name']);
        for ($i = 0; $i < $photoCount; $i++) {
            $photoName = $_FILES['car_photos']['name'][$i];
            $photoTmpName = $_FILES['car_photos']['tmp_name'][$i];

            $photoPath = $targetDir . $car_id . "_" . ($i + 1) . ".png";

            if ($_FILES['car_photos']['error'][$i] === UPLOAD_ERR_OK) {
                $fileType = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png'];
                if (in_array($fileType, $allowedTypes)) {
                    if (move_uploaded_file($photoTmpName, $photoPath)) {
                        $insertPhotoQuery = "INSERT INTO car_photo (image_patch, car_id) VALUES ('$photoPath', $car_id)";
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
        $insertEquipmentQuery = "INSERT INTO car_equipment (car_id) VALUES (?)";
        $equipmentStmt = $conn->prepare($insertEquipmentQuery);
        $equipmentStmt->bind_param("i", $car_id);
        $equipmentStmt->execute();
        $equipment_id = $equipmentStmt->insert_id;

        foreach ($_POST['complectation'] as $equipment) {
            $insertEquipmentElementQuery = "INSERT INTO car_equipment_element (car_equipment_id, car_equipment_text) VALUES (?, ?)";
            $equipmentElementStmt = $conn->prepare($insertEquipmentElementQuery);
            $equipmentElementStmt->bind_param("is", $equipment_id, $equipment);
            $equipmentElementStmt->execute();
        }
    }

    // Перенаправление на страницу с сообщением об успешном добавлении
    header("Location: ../newCar.php");
    exit();
}
?>
