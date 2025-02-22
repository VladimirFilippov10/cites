<?php
include 'dbconnect.php';

// Включаем вывод ошибок для отладки
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Логируем все полученные данные
    error_log("Full POST data: " . print_r($_POST, true));

    // Проверяем наличие ID заявки
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        header("Location: ../applications.php?error=1&message=" . urlencode("ID заявки не получен"));
        exit();
    }

    // Получаем и проверяем ID заявки
    $id = intval($_POST['id']);
    if ($id <= 0) {
        header("Location: ../applications.php?error=1&message=" . urlencode("Неверный ID заявки"));
        exit();
    }

    // Проверяем существование записи
    $checkQuery = $conn->prepare("SELECT * FROM redemption_request WHERE redemption_request_id = ?");
    $checkQuery->bind_param("i", $id);
    $checkQuery->execute();
    $checkResult = $checkQuery->get_result();

    if ($checkResult->num_rows == 0) {
        header("Location: ../applications.php?error=1&message=" . urlencode("Запись с ID $id не найдена"));
        exit();
    }

    // Получаем данные из формы
    $inspection_date = isset($_POST['inspection_date']) && is_string($_POST['inspection_date']) ? 
        trim($_POST['inspection_date']) : '0000-00-00';
    $inspection_time = isset($_POST['inspection_time']) && is_string($_POST['inspection_time']) ? 
        trim($_POST['inspection_time']) : '00:00:00';
    $inspection_datetime = "$inspection_date $inspection_time";

    $inspection_place = isset($_POST['inspection_place'][$id]) && is_string($_POST['inspection_place'][$id]) ? 
        trim($_POST['inspection_place'][$id]) : '';
    $status = isset($_POST['status'][$id]) && is_string($_POST['status'][$id]) ? 
        trim($_POST['status'][$id]) : 'Открыта';
    $closed = isset($_POST['closed']) ? 1 : 0;

    $employee_id = isset($_POST['employee']) ? intval($_POST['employee']) : 0;


    // Подготавливаем SQL запрос с проверкой изменений
    $updateQuery = $conn->prepare("UPDATE redemption_request SET 
        redemption_request_inspection_time = ?,
        redemption_request_place = ?,
        redemption_request_status = ?,
        redemption_request_closed = ?,
        redemption_request_employee = ?
        WHERE redemption_request_id = ?");
    error_log("Preparing to update ID $id with:
        inspection_datetime: $inspection_datetime
        place: $inspection_place
        status: $status
        closed: $closed
        employee_id: $employee_id");


    // Привязываем параметры
    $updateQuery->bind_param("sssiii", 
        $inspection_datetime,
        $inspection_place,
        $status,
        $closed,
        $employee_id,
        $id
    );

    // Выполняем запрос
    if ($updateQuery->execute()) {
        $affected_rows = $updateQuery->affected_rows;
        error_log("Update query executed. Affected rows: $affected_rows");
        
        if ($affected_rows > 0) {
            error_log("Update successful for ID: $id");
            error_log("Updated values: 
                inspection_datetime: $inspection_datetime
                place: $inspection_place
                status: $status
                closed: $closed
                employee_id: $employee_id");
            header("Location: ../viewAllRedemptionRequests.php?success=1&updated_id=$id");

            exit();
        } else {
            error_log("No changes made for ID: $id. Possible reasons:
                - Data was identical to existing values
                - Record was not found");
            header("Location: ../viewAllRedemptionRequests.php?success=1&no_changes=1&id=$id");

            exit();
        }
    } else {
        $error = "Ошибка при обновлении: " . $updateQuery->error;
        error_log("Update failed for ID: $id. Error: " . $updateQuery->error);
        error_log("SQL State: " . $updateQuery->sqlstate);
        error_log("Error Code: " . $updateQuery->errno);
        header("Location: ../viewAllRedemptionRequests.php?error=1&message=" . urlencode($error));

        exit();
    }

    $updateQuery->close();
    $conn->close();
    
    // Логирование состояния соединения
    error_log("Database connection closed successfully");
} else {
    header("Location: ../viewAllRedemptionRequests.php?error=1&message=" . urlencode("Неверный метод запроса. Ожидается POST."));

    exit();
}
?>
