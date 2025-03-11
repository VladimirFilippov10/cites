<?php
session_start();
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обработка добавления новой записи
    $car_buyback_car_id = $_POST['car_buyback_car_id'];
    $car_buyback_client_id = $_POST['car_buyback_client_id'];
    $car_buyback_datetime = date('Y-m-d H:i:s'); // Текущая дата и время
    $car_buyback_price = $_POST['car_buyback_price'];
    $car_buyback_employee = $_SESSION['user_id']; // ID текущего сотрудника

    // Запрос на добавление новой записи о выкупе
    $query = "INSERT INTO car_buyback (car_buyback_car_id, car_buyback_client_id, car_buyback_datetime, car_buyback_price, car_buyback_employee) 
              VALUES ('$car_buyback_car_id', '$car_buyback_client_id', '$car_buyback_datetime', '$car_buyback_price', '$car_buyback_employee')";

    if ($conn->query($query) === TRUE) {
        // Обновление поля car_in_price в таблице car
        $updateQuery = "UPDATE car SET car_in_price = 0 WHERE car_id = '$car_buyback_car_id'";
        $conn->query($updateQuery);

        header("Location: ../view_car_buyback.php?success=1");
        exit();
    } else {
        echo "Ошибка: " . $conn->error;
    }
} else {
    echo "Некорректный метод запроса.";
}
?>
