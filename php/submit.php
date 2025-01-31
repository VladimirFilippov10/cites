<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    $model_id = intval($_POST['model_id']);
    $cars_win = $_POST['title'];
    $cars_year_made = $_POST['year'];
    $cars_generation = $_POST['generation'];
    $cars_mileage = intval($_POST['mileage']);
    $cars_color = $_POST['color'];
    $cars_drive = $_POST['drive'];
    $cars_volume = floatval(str_replace(',', '.', $_POST['engine_volume']));
    $cars_power = intval($_POST['power']);
    $cars_transmission_box = $_POST['transmission'];
    $cars_type_oil = $_POST['fuel_type'];
    $cars_description = $_POST['description'];
    $cars_price = floatval($_POST['price']);
    $cars_owners = intval($_POST['owners']);
    $cars_bodywork = $_POST['bodywork'];
    $cars_in_price = isset($_POST['for_sale']) ? 1 : 0;

    // Новые поля
    $car_state_number = $_POST['car_state_number'];
    $car_link_specifications = $_POST['car_link_specifications'];
    $car_link_to_report = $_POST['car_link_to_report'];

    // Проверка на редактирование или создание новой записи
    if (isset($_POST['car_id']) && !empty($_POST['car_id'])) {
        // Редактирование существующей записи
        $car_id = intval($_POST['car_id']);
        $updateQuery = "UPDATE cars SET model_id = ?, cars_win = ?, cars_year_made = ?, cars_generation = ?, cars_mileage = ?, cars_color = ?, cars_drive = ?, cars_volume = ?, cars_power = ?, cars_transmission_box = ?, cars_type_oil = ?, cars_description = ?, cars_bodywork = ?, cars_in_price = ?, cars_price = ?, owners = ?, car_state_number = ?, car_link_specifications = ?, car_link_to_report = ? WHERE cars_id = ?";
        
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ississdissssiiiissssi", $model_id, $cars_win, $cars_year_made, $cars_generation, $cars_mileage, $cars_color, $cars_drive, $cars_volume, $cars_power, $cars_transmission_box, $cars_type_oil, $cars_description, $cars_bodywork, $cars_in_price, $cars_price, $cars_owners, $car_state_number, $car_link_specifications, $car_link_to_report, $car_id);
        $stmt->execute();
    } else {
        // Создание новой записи
        $insertQuery = "INSERT INTO cars (model_id, cars_win, cars_year_made, cars_generation, cars_mileage, cars_color, cars_drive, cars_volume, cars_power, cars_transmission_box, cars_type_oil, cars_description, cars_bodywork, cars_in_price, cars_price, owners, car_state_number, car_link_specifications, car_link_to_report) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ississdissssiiiissss", $model_id, $cars_win, $cars_year_made, $cars_generation, $cars_mileage, $cars_color, $cars_drive, $cars_volume, $cars_power, $cars_transmission_box, $cars_type_oil, $cars_description, $cars_bodywork, $cars_in_price, $cars_price, $cars_owners, $car_state_number, $car_link_specifications, $car_link_to_report);
        $stmt->execute();
        $car_id = $stmt->insert_id; // Получаем ID добавленной записи
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
