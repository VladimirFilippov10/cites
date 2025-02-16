<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение ID элемента комплектации из POST-запроса
    $complectationId = $_POST['complectationId'];

    // Удаление записи о комплектации из базы данных
    $deleteQuery = "DELETE FROM car_equipment_element WHERE car_equipment_element_id = ?";

    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $complectationId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo 'Элемент комплектации успешно удален.';
    } else {
        echo 'Ошибка при удалении элемента комплектации из базы данных.';
    }
} else {
    echo 'Неверный метод запроса.';
}
?>
