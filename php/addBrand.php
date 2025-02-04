<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brand_name = $_POST['brand_name'];

    // Вставка данных в таблицу brand
    $insertQuery = "INSERT INTO brand (brand_name) VALUES (?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("s", $brand_name);
    $stmt->execute();

    // Перенаправление на страницу добавления марок и моделей
    header("Location: ../addBrandsAndModels.php?success=1");
    exit();
}
?>
