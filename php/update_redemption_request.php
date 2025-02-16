<?php
include 'dbconnect.php'; // Подключение к базе данных
  
// Получение данных из формы
$id = intval($_POST['id']);
$inspection_time =  mysqli_real_escape_string($conn, $_POST['inspection_time']);
$inspection_date = mysqli_real_escape_string($_POST['inspection_date']) ? $_POST['inspection_date'] : null;
$inspection_place = mysqli_real_escape_string($_POST['inspection_place']) ? $_POST['inspection_place'] : null;
$status = mysqli_real_escape_string($_POST['status']) ? $_POST['status'] : null;
$closed = mysqli_real_escape_string($_POST['closed']) ? 1 : 0;
$employee_id = ($_POST['employee'] ?? null) ? true : false;

// Объединение даты и времени в одну строку
if ($inspection_date && $inspection_time) {
    $inspection_datetime = $inspection_date . ' ' . $inspection_time; // Формат: 'YYYY-MM-DD HH:MM'
} else {
    $inspection_datetime = null; // Или установите значение по умолчанию
}

// Экранирование объединенной строки
$inspection_datetime = mysqli_real_escape_string($conn, $inspection_datetime);

// Обновление данных заявки
$query = "UPDATE redemption_request SET 
    redemption_request_inspection_time = '$inspection_datetime', 
    redemption_request_place = '$inspection_place', 
    redemption_request_status = '$status', 
    redemption_request_closed = '$closed' 
    WHERE redemption_request_id = '$id'";

if (mysqli_query($conn, $query)) {
    echo "Запись успешно обновлена.";
} else {
    echo "Ошибка обновления записи: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
