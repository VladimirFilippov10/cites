<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение ID фотографии из POST-запроса
    $photoID = intval($_POST['car_photo_id']);

    // Получение пути к фотографии из базы данных
    $select = "SELECT * FROM car_photo WHERE car_photo_id = ?";
    $stmt = $conn->prepare($select);
    $stmt->bind_param("i", $photoID); // Привязка параметра
    $stmt->execute(); // Выполнение запроса
    $result = $stmt->get_result(); // Получаем результат запроса
    $row = $result->fetch_assoc();

    if ($row) {
        $photoPath = '../img/cars' . $row['car_photo_image_patch']; // Исправленный путь к фотографии

        // Удаление файла с сервера
        if (file_exists($photoPath)) {
            if (unlink($photoPath)) {
                // Удаление записи о фотографии из базы данных
                $deleteQuery = "DELETE FROM car_photo WHERE car_photo_id = ?";
                $stmt = $conn->prepare($deleteQuery);
                $stmt->bind_param("i", $photoID);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => 'Фото успешно удалено из базы данных!']);
                } else {
                    echo json_encode(['error' => 'Ошибка при удалении фото из базы данных.']);
                }
            } else {
                echo json_encode(['error' => 'Ошибка при удалении файла: ' . $photoPath]);
            }
        } else {
            echo json_encode(['error' => 'Файл не существует: ' . $photoPath]);
        }
    } else {
        echo json_encode(['error' => 'Фото не найдено.']);
    }
} else {
    echo json_encode(['error' => 'Неверный метод запроса.']);
}
?>
