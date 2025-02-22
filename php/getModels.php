<?php
include 'dbconnect.php'; // Подключение к базе данных

if (isset($_GET['id_marks'])) {
    $brand_id = intval($_GET['id_marks']);
    
    $query = "SELECT * FROM model WHERE brand_id = ? ORDER BY model_name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $brand_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $models = [];
    while ($row = $result->fetch_assoc()) {
        $models[] = [
            'id_model' => $row['model_id'],
            'name_model' => $row['model_name']
        ];
    }

    // Отладочное сообщение для отладки
    if (empty($models)) {
        error_log("No models found for brand ID: " . $brand_id);
    } else {
        error_log("Models found: " . json_encode($models));
    }

    echo json_encode($models);
}
?>
