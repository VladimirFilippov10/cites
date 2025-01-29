<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $model_name = $_POST['model_name'];
    $brand_id = intval($_POST['brand']);

    // Вставка данных в таблицу model
    $insertQuery = "INSERT INTO model (name_model, id_marks) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("si", $model_name, $brand_id);
    $stmt->execute();

    // Перенаправление на страницу добавления марок и моделей
    header("Location: ../addBrandsAndModels.php?success=1");
    exit();
}
?>
