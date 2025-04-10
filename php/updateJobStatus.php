<?php
session_start();
include 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_job']) && isset($_POST['job_id'])) {
    // Проверка прав доступа
    if (!isset($_SESSION['user_id']) || $_SESSION['employee_role'] != 1) {
        header('Location: ../offers.php');
        exit;
    }

    $jobId = (int)$_POST['job_id'];
    
    // Получаем текущий статус вакансии
    $query = "SELECT jobOpenings_in_open FROM jobopenings WHERE jobOpenings_id = $jobId";
    $result = $conn->query($query);
    $currentStatus = $result->fetch_assoc()['jobOpenings_in_open'];
    
    // Инвертируем статус
    $newStatus = $currentStatus ? 0 : 1;
    
    // Обновляем статус в БД
    $updateQuery = "UPDATE jobopenings SET jobOpenings_in_open = $newStatus WHERE jobOpenings_id = $jobId";
    $conn->query($updateQuery);
}

header('Location: ../offers.php');
exit;
?>
