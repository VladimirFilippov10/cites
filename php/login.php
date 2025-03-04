<?php
include 'dbconnect.php'; // Подключение к базе данных

session_start(); // Начало сессии

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = mysqli_real_escape_string($conn, $_POST['login']);
    } else {
        echo "Username is required.";
        exit();
    }

    $password = mysqli_real_escape_string($conn, trim($_POST['password']));


    // Проверка логина
    $query = "SELECT * FROM employee WHERE employee_login = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Debugging output for password verification
       // echo "Hashed password from DB: " . $row['employee_password'] . "<br>";
      //  echo "Password being verified: " . $password . "<br>";

        // Проверка пароля
        if ($password ==$row['employee_password']) {

            // Успешная авторизация
            $_SESSION['user_id'] = $row['employee_id'];
            $_SESSION['username'] = $row['employee_name'];
            header("Location: ../dashboard.php"); // Перенаправление на главную страницу
            exit(); // Завершение скрипта после перенаправления
        } else {
            echo "Неверный пароль."; // Отладочное сообщение
            exit(); // Завершение скрипта

        }
    } else {
        echo "Пользователь не найден.";
    }
}
?>
