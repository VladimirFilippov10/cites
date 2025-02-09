<?php
include 'dbconnect.php'; // Подключение к базе данных

// Получение данных из формы с проверкой на существование ключей
$id = isset($_POST['id']) ? $_POST['id'] : null;
$name_client = isset($_POST['name_client']) ? $_POST['name_client'] : null;
$model_car = isset($_POST['model_car']) ? $_POST['model_car'] : null;
$date = isset($_POST['date']) ? $_POST['date'] : null;
$inspection_time = isset($_POST['inspection_time']) ? $_POST['inspection_time'] : null;
$inspection_date = isset($_POST['inspection_date']) ? $_POST['inspection_date'] : null;
$inspection_place = isset($_POST['inspection_place']) ? $_POST['inspection_place'] : null;
$status = isset($_POST['status']) ? $_POST['status'] : null;
$closed = isset($_POST['closed']) ? 1 : 0;
$employee_id = isset($_POST['employee']) ? $_POST['employee'] : null;

// Проверка на наличие всех необходимых данных
if ($id && $name_client && $model_car && $date && $inspection_time && $inspection_place && $status) {
    // Обновление данных заявки
    $query = "UPDATE redemption_request SET 
        redemption_request_name_client = '$name_client', 
        redemption_request_employee = '$employee_id', 
        redemption_request_model_car = '$model_car', 
        redemption_request_date = '$date', 
        redemption_request_inspection_time = '$inspection_time', 
        redemption_request_inspection_place = '$inspection_place', 
        redemption_request_status = '$status', 
        redemption_request_closed = '$closed' 
        WHERE redemption_request_id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "Запись успешно обновлена.";
    } else {
        echo "Ошибка обновления записи: " . mysqli_error($conn);
    }
} else {
    echo "Ошибка: Не все необходимые данные были предоставлены.";
}

mysqli_close($conn);
?>
