<?php
// Подключение к базе данных
include 'dbconnect.php';

// Проверка, что данные были отправлены методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $name = $_POST['name'];
    $carModel = $_POST['car-model'];

    // Подготовка SQL-запроса для вставки данных
$sql = "INSERT INTO redemption_request (redemption_request_name_client, redemption_request_model_car, redemption_request_date, redemption_request_employee) VALUES (?, ?, NOW(), 2)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $carModel);

    // Выполнение запроса и проверка на ошибки
    if ($stmt->execute()) {
        header("Location: ../ransomCars.php");
        exit();

        echo "Запись успешно добавлена!";
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    // Закрытие соединения
    $stmt->close();
    $conn->close();
    exit();
} else {
    echo "Неверный метод запроса.";
}
?>
