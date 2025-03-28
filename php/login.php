<?php
include 'dbconnect.php'; // Подключение к базе данных

session_start(); // Начало сессии

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = mysqli_real_escape_string($conn, $_POST['login']);
    } else {
        exit();
    }

    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    // Проверка логина
    $query = "SELECT * FROM employee WHERE employee_login = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password == $row['employee_password']) {
            // Успешная авторизация
            $_SESSION['user_id'] = $row['employee_id'];
            $_SESSION['username'] = $row['employee_name'];
            $_SESSION['employee_role'] = $row['employee_role'];
            // Установка куки для сохранения авторизованного профиля
            setcookie("user_id", $row['employee_id'], time() + (86400 * 30), "/"); // Кука на 30 дней
            setcookie("username", $row['employee_name'], time() + (86400 * 30), "/");
            
            header("Location: ../dashboard.php");

            exit();
        } else {
            $_SESSION['outputMessage'] = "Неверный пароль."; // Установка сообщения об ошибке
            header("Location: ../auto.php");
            exit();
        }
    } else {
        $_SESSION['outputMessage'] = "Пользователь не найден."; // Установка сообщения об ошибке
        header("Location: ../auto.php");
        exit();
    }
}
?>
