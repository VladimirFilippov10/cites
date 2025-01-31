<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_id = intval($_POST['car_id']);
    $price = floatval($_POST['price']);
    // Получение других данных из формы
    $title = $_POST['title'];
    $year = intval($_POST['year']);
    $bodywork = $_POST['bodywork'];
    $generation = $_POST['generation'];
    $mileage = intval($_POST['mileage']);
    $color = $_POST['color'];
    $owners = intval($_POST['owners']);
    $engine_volume = floatval($_POST['engine_volume']);
    $power = intval($_POST['power']);
    $drive = $_POST['drive'];
    $transmission = $_POST['transmission'];
    $fuel_type = $_POST['fuel_type'];
    $equipment_text = $_POST['equipment_text'];
    $description = $_POST['description'];
    $car_state_number = $_POST['car_state_number'];
    $car_link_specifications = $_POST['car_link_specifications'];
    $car_link_to_report = $_POST['car_link_to_report'];

    // Обновление данных автомобиля
    $updateQuery = "UPDATE cars SET cars_price = ?, cars_win = ?, cars_year_made = ?, cars_bodywork = ?, cars_generation = ?, cars_melage = ?, cars_color = ?, cars_drive = ?, cars_volume = ?, cars_power = ?, cars_transmission_box = ?, cars_type_oil = ?, cars_descriptions = ?, car_state_number = ?, car_link_specifications = ? WHERE cars_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssissssssissssii", $title, $price, $year, $bodywork, $generation, $mileage, $color, $owners, $engine_volume, $power, $drive, $transmission, $fuel_type, $equipment_text, $description, $car_state_number, $car_link_specifications, $car_id);
    
    if ($stmt->execute()) {
        // Перенаправление на страницу со списком автомобилей
        header("Location: ../viewAllCars.php");
        exit();
    } else {
        echo "Ошибка при обновлении данных: " . $stmt->error;
    }
}
?>
