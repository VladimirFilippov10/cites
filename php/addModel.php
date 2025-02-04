<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $model_name = $_POST['model_name'];
    $brand_id = intval($_POST['brand']);

    // Проверка на существование бренда
    $brandCheckQuery = "SELECT brand_id FROM brand WHERE brand_id = ?";
    $stmt = $conn->prepare($brandCheckQuery);
    $stmt->bind_param("i", $brand_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Вставка данных в таблицу model
        $insertQuery = "INSERT INTO model (model_name, brand_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("si", $model_name, $brand_id);
        $stmt->execute();

        // Перенаправление на страницу добавления марок и моделей
        header("Location: ../addBrandsAndModels.php?success=1");
        exit();
    } else {
        echo "Ошибка: Бренд с указанным ID не существует.";
    }
}
?>
