<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = intval($_POST['employee_id']);
    $employee_name = mysqli_real_escape_string($conn, $_POST['name']);
    $employee_login = mysqli_real_escape_string($conn, $_POST['login']);
    $employee_role = isset($_POST['role']) ? intval($_POST['role']) : 0;
    $employee_number_phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $employee_password = mysqli_real_escape_string($conn, $_POST['password']);


    // SQL-запрос для обновления информации о сотруднике
    $query = "UPDATE employee SET employee_name = ?, employee_login = ?, employee_role = ?, employee_number_phone = ?, employee_password = ? WHERE employee_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $employee_name, $employee_login, $employee_role, $employee_id);
    $stmt->bind_param("ssssi", $employee_name, $employee_login, $employee_number_phone, $employee_password, $employee_id);


    if ($stmt->execute()) {
        // Перенаправление на страницу со списком сотрудников с сообщением об успешном обновлении
        header("Location: ../viewAllEmployees.php?success=true");
        exit();
    } else {
        // Обработка ошибки
        echo "Ошибка при обновлении: " . $stmt->error;
    }
}
?>
