<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_id = intval($_POST['car_id']);
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

// Пытаемся обновить существующую запись
    $updateEquipment = $conn->prepare("UPDATE car_equipment SET car_equipment_descriptions = ? WHERE car_id = ?");
    $updateEquipment->bind_param("si", $car_equipment_descriptions, $car_id);
    $updateEquipment->execute();

    // Проверяем, была ли обновлена какая-либо запись
    if ($updateEquipment->affected_rows === 0) {
        // Если запись не была обновлена, вставляем новую запись
        $insertEquipment = $conn->prepare("INSERT INTO car_equipment (car_id, car_equipment_descriptions) VALUES (?, ?)");
        $insertEquipment->bind_param("is", $car_id, $car_equipment_descriptions);
        $insertEquipment->execute();
    }


    // Обновление данных автомобиля
    $updateQuery = "UPDATE car SET 
    car_onwers = '$car_onwers', 
    car_win_code = '$car_win_code', 
    car_year_made = $car_year_made, 
    car_generation = '$car_generation', 
    car_mileage = $car_mileage, 
    car_color = '$car_color', 
    car_volume = $car_volume, 
    car_power = $car_power, 
    car_transmission_box = '$car_transmission_box', 
    car_type_oil = '$car_type_oil', 
    car_descriptions = '$car_description', 
    car_bodywork = '$car_bodywork', 
   /* car_in_price = $car_in_price, */
    car_price = $car_price, 
    car_state_number = '$car_state_number', 
    car_link_specifications = '$car_link_specifications', 
    car_link_to_report = '$car_link_to_report', 
    car_drive = '$car_drive' 
    WHERE car_id = $car_id";

    if ($conn->query($updateQuery) === TRUE) {
        // Обработка загрузки новых фотографий
        // Обработка загрузки новых фотографий
    if (!empty($_FILES['car_photos'])) {
        $targetDir = "../img/cars/";

    // Создаем директорию, если она не существует
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Получаем текущее количество фотографий для этого автомобиля
    $photoCountQuery = "SELECT COUNT(*) as count FROM car_photo WHERE car_id = $car_id";
    $countResult = $conn->query($photoCountQuery);
    $countRow = $countResult->fetch_assoc();
    $currentPhotoCount = $countRow['count'];

    // Обрабатываем каждое загруженное фото
    foreach ($_FILES['car_photos']['name'] as $i => $photoName) {
        $photoTmpName = $_FILES['car_photos']['tmp_name'][$i];

        // Проверяем, нет ли ошибок при загрузке файла
        if ($_FILES['car_photos']['error'][$i] === UPLOAD_ERR_OK) {
            // Проверяем допустимый тип файла
            $fileType = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png'];
            if (in_array($fileType, $allowedTypes)) {
                // Формируем уникальное имя файла
                $newPhotoName = $car_id . "_" . ($currentPhotoCount + $i + 1) . ".png";
                $photoPath = $targetDir . $newPhotoName;

                // Перемещаем файл в целевую директорию
                if (move_uploaded_file($photoTmpName, $photoPath)) {
                    // Сохраняем путь к фото в базе данных
                    $insertPhotoQuery = "INSERT INTO car_photo (car_photo_image_patch, car_id) VALUES ('/$newPhotoName', $car_id)";
                    if ($conn->query($insertPhotoQuery) !== TRUE) {
                        echo "Ошибка при сохранении пути к фото в базе данных: " . $conn->error;
                    }
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

        // Перенаправление на страницу со списком автомобилей
        header("Location: ../viewAllCars.php");
        exit();
    } else {
        echo "Ошибка при обновлении данных: " . $conn->error;
    }
}
?>
