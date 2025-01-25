<?php
include 'dbconnect.php'; // Подключение к базе данных

// Включение отображения ошибок
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id_marks'])) {
    $id_marks = intval($_GET['id_marks']);
    $modelsQuery = "SELECT * FROM model WHERE id_marks = $id_marks";
    $modelsResult = $conn->query($modelsQuery);

    $models = array();
    if ($modelsResult->num_rows > 0) {
        while($row = $modelsResult->fetch_assoc()) {
            $models[] = $row;
        }
    }
    echo json_encode($models);
} else {
    echo json_encode(array("error" => "No id_marks provided"));
}
?>
