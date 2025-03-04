<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_GET['action'];

    if ($action == 'add') {
        // Добавление нового клиента
        $client_name = $_POST['client_name'];
        $client_phone_number = $_POST['client_phone_number'];
        $client_type_client = $_POST['client_type_client'];

        $query = "INSERT INTO client (client_name, client_phone_number, client_type_client) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $client_name, $client_phone_number, $client_type_client);
        $stmt->execute();

        header("Location: ../viewAllClients.php"); // Перенаправление на страницу со списком клиентов
    } elseif ($action == 'edit') {
        // Редактирование существующего клиента
        $client_id = $_POST['client_id'];
        $client_name = $_POST['client_name'];
        $client_phone_number = $_POST['client_phone_number'];
        $client_type_client = $_POST['client_type_client'];

        $query = "UPDATE client SET client_name = ?, client_phone_number = ?, client_type_client = ? WHERE client_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $client_name, $client_phone_number, $client_type_client, $client_id);
        $stmt->execute();

        header("Location: ../viewAllClients.php"); // Перенаправление на страницу со списком клиентов
    }
}
?>
