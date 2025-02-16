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
    $deleteQuery = "DELETE FROM car_equipment_element WHERE car_equipment_id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $car_equipment_id);
    $deleteStmt->execute();

    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $car_equipment_id);
    $deleteStmt->execute();

    // Вставляем новые элементы комплектации
    foreach ($equipmentElementTexts as $text) {
        $insertElementQuery = "INSERT INTO car_equipment_element (car_equipment_element_text, car_equipment_id) VALUES (?, ?)";
        $insertElementStmt = $conn->prepare($insertElementQuery);
        $insertElementStmt->bind_param("si", $text, $car_equipment_id);
        $insertElementStmt->execute();
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
    car_in_price = $car_in_price, 
    car_price = $car_price, 
    car_state_number = '$car_state_number', 
    car_link_specifications = '$car_link_specifications', 
    car_link_to_report = '$car_link_to_report', 
    car_drive = '$car_drive' 
    WHERE car_id = $car_id";

    if ($conn->query($updateQuery) === TRUE) {
        // Перенаправление на страницу со списком автомобилей
        header("Location: ../viewAllCars.php");
        exit();
    } else {
        echo "Ошибка при обновлении данных: " . $conn->error;
    }
}
?>
