<?php
include 'dbconnect.php'; // Подключение к базе данных

if (isset($_GET['id_marks'])) {
    $brand_id = intval($_GET['id_marks']);
    
    $query = "SELECT * FROM model WHERE brand_id = ?";
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

    echo json_encode($models);
}
?>
