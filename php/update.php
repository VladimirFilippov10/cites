<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 //   echo "<script>alert('Debug: Code reached the POST request.');</script>"; // Отладочное сообщение
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

    // Проверка наличия записи в таблице car_equipment
    $equipmentCheckQuery = "SELECT * FROM car_equipment WHERE car_id = ?";
    $equipmentCheckStmt = $conn->prepare($equipmentCheckQuery);
    $equipmentCheckStmt->bind_param("i", $car_id);
    $equipmentCheckStmt->execute();
    $equipmentCheckResult = $equipmentCheckStmt->get_result();

    if ($equipmentCheckResult->num_rows > 0) {
        // Если запись существует, обновляем её
        $updateEquipment = $conn->prepare("UPDATE car_equipment SET car_equipment_descriptions = ? WHERE car_id = ?");
        $updateEquipment->bind_param("si", $car_equipment_descriptions, $car_id);
        $updateEquipment->execute();
        $car_equipment_id = $equipmentCheckResult->fetch_assoc()['car_equipment_id']; // Получаем car_equipment_id
    } else {
        // Если записи нет, создаем новую
        $insertEquipment = $conn->prepare("INSERT INTO car_equipment (car_id, car_equipment_descriptions) VALUES (?, ?)");
        $insertEquipment->bind_param("is", $car_id, $car_equipment_descriptions);
        $insertEquipment->execute();
        $car_equipment_id = $conn->insert_id; // Получаем ID новой записи
    }

    $equipmentCheckStmt = $conn->prepare($equipmentCheckQuery);
    $equipmentCheckStmt->bind_param("i", $car_id);
    $equipmentCheckStmt->execute();
    $equipmentCheckResult = $equipmentCheckStmt->get_result();

    if ($equipmentCheckResult->num_rows > 0) {
        // Если запись существует, обновляем её
        $updateEquipment = $conn->prepare("UPDATE car_equipment SET car_equipment_descriptions = ? WHERE car_id = ?");
        $updateEquipment->bind_param("si", $car_equipment_descriptions, $car_id);
        $updateEquipment->execute();
        $car_equipment_id = $equipmentCheckResult->fetch_assoc()['car_equipment_id']; // Получаем car_equipment_id
    } else {
        // Если записи нет, создаем новую
        $insertEquipment = $conn->prepare("INSERT INTO car_equipment (car_id, car_equipment_descriptions) VALUES (?, ?)");
        $insertEquipment->bind_param("is", $car_id, $car_equipment_descriptions);
        $insertEquipment->execute();
        $car_equipment_id = $conn->insert_id; // Получаем ID новой записи
    }

    // Получаем массив элементов комплектации
    $equipmentElementTexts = $_POST['complectation'];

    // Удаляем существующие элементы комплектации для данного car_equipment_id
    // $deleteQuery = "DELETE FROM car_equipment_element WHERE car_equipment_id = ?";
    // $deleteStmt = $conn->prepare($deleteQuery);
    // $deleteStmt->bind_param("i", $car_equipment_id);
    // $deleteStmt->execute();


    // Вставляем новые элементы комплектации, если они не существуют
    foreach ($equipmentElementTexts as $text) {
        $insertElementQuery = "INSERT INTO car_equipment_element (car_equipment_element_text, car_equipment_id) VALUES (?, ?)";
        $insertElementStmt = $conn->prepare($insertElementQuery);
        $insertElementStmt->bind_param("si", $text, $car_equipment_id);
        $insertElementStmt->execute();
    }
    
    // Обновляем существующие элементы комплектации
    foreach ($equipmentElementTexts as $text) {
        $updateElementQuery = "UPDATE car_equipment_element SET car_equipment_element_text = ? WHERE car_equipment_id = ? AND car_equipment_element_text = ?";
        $updateElementStmt = $conn->prepare($updateElementQuery);
        $updateElementStmt->bind_param("sis", $text, $car_equipment_id, $text);
        $updateElementStmt->execute();
    }

    // Обработка фотографий
    if (!empty($_FILES['car_photos'])) {
        $fileCount = count($_FILES['car_photos']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['car_photos']['error'][$i] !== UPLOAD_ERR_OK) {
                echo "<script>alert('Ошибка загрузки файла: " . $_FILES['car_photos']['error'][$i] . "');</script>";
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
                    echo "<script>alert('Ошибка перемещения файла: $fileName');</script>";
                }
            }
        }
    }

    // Закомментирую перенаправление на viewAllCars.php
    echo "<script>alert('Данные успешно обновлены!');</script>";
    header("Location: ../viewAllCars.php");
    exit();

     exit();

    echo "<script>alert('Данные успешно обновлены!');</script>";
}
?>
