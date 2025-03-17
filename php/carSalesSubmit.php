<?php
session_start();
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обработка добавления новой записи
    $car_sales_car_id = $_POST['car_sales_car_id'];
    $car_sales_client_id = $_POST['car_sales_client_id'];
    $car_sales_datetime = date('Y-m-d H:i:s'); // Текущая дата и время
    $car_sales_price = $_POST['car_sales_price'];
    $car_sales_employee = $_SESSION['user_id']; // ID текущего сотрудника

    // Запрос на добавление новой записи о выкупе
    $query = "INSERT INTO `car_sales`(`car_sales_client_id`, `car_sales_car_id`, `car_sales_datatime`, `car_sales_employee_id`, `car_sales_price`) 
              VALUES ('$car_sales_client_id', '$car_sales_car_id', '$car_sales_datetime', '$car_sales_employee','$car_sales_price')";

    if ($conn->query($query) === TRUE) {
        // Обновление поля car_in_price в таблице car
        $updateQuery = "UPDATE car SET car_in_price = 0 WHERE car_id = '$car_sales_car_id'";
        $conn->query($updateQuery);

        header("Location: ../viewCarSales.php?success=1");
        exit();
    } else {
        echo "Ошибка: " . $conn->error;
    }
} else {
    echo "Некорректный метод запроса.";
}
?>
