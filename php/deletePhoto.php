<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение пути к фотографии из POST-запроса
    $photoPath = $_POST['photoPath'];

    // Удаление файла с сервера
    if (file_exists($photoPath)) {
        unlink($photoPath);
    }

    // Удаление записи о фотографии из базы данных
    $deleteQuery = "DELETE FROM car_photo WHERE image_patch = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("s", $photoPath);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo 'Фото успешно удалено.';
    } else {
        echo 'Ошибка при удалении фото из базы данных.';
    }
} else {
    echo 'Неверный метод запроса.';
}
?>
