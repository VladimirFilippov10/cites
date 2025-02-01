<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_id = intval($_POST['car_id']);
    $price = floatval($_POST['price']);
    // Получение других данных из формы
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $year = intval($_POST['year']);
    $bodywork = mysqli_real_escape_string($conn, $_POST['bodywork']);
    $generation = mysqli_real_escape_string($conn, $_POST['generation']);
    $melage = intval($_POST['melage']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $owners = intval($_POST['owners']);
    $engine_volume = floatval($_POST['engine_volume']);
    $power = intval($_POST['power']);
    $drive = mysqli_real_escape_string($conn, $_POST['drive']);
    $transmission = mysqli_real_escape_string($conn, $_POST['transmission']);
    $fuel_type = mysqli_real_escape_string($conn, $_POST['fuel_type']);
    $equipment_text = mysqli_real_escape_string($conn, $_POST['equipment_text']);
    $car_state_number = mysqli_real_escape_string($conn, $_POST['car_state_number']);
    $car_link_specifications = mysqli_real_escape_string($conn, $_POST['car_link_specifications']);
    $car_link_to_report = mysqli_real_escape_string($conn, $_POST['car_link_to_report']);

    // Обновление данных автомобиля
    $updateQuery = "UPDATE cars SET cars_price = $price, cars_win = '$title', cars_year_made = $year, cars_bodywork = '$bodywork', cars_generation = '$generation', cars_melage = $melage, cars_color = '$color', cars_drive = $owners, cars_volume = $engine_volume, cars_power = $power, cars_transmission_box = '$transmission', cars_type_oil = '$fuel_type', cars_descriptions = '$equipment_text', car_state_number = '$car_state_number', car_link_specifications = '$car_link_specifications', car_link_to_report = '$car_link_to_report' WHERE cars_id = $car_id";
    
    if ($conn->query($updateQuery) === TRUE) {
        // Обработка загрузки новых фотографий
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
                            $conn->query($insertPhotoQuery);
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
