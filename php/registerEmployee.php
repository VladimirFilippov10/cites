<?php
include 'dbconnect.php'; // Подключение к базе данных

session_start(); // Инициализация сессии

$outputMessage = ""; // Переменная для хранения сообщений

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $registrationCode = mysqli_real_escape_string($conn, $_POST['registration-code']);

    // Проверка уникальности логина
    $checkUsernameQuery = "SELECT * FROM employee WHERE employee_login = '$username'";
    $usernameResult = $conn->query($checkUsernameQuery);

    if ($usernameResult->num_rows > 0) {
        $_SESSION['outputMessage'] = "Логин уже занят. Пожалуйста, выберите другой.<br>"; // Отладочное сообщение
        header("Location: ../registr.php"); // Исправленный путь
        exit;
    }

    // Проверка кода регистрации
    $checkCodeQuery = "SELECT * FROM employee_code_registration WHERE employee_code_registration_value = '$registrationCode'";
    $codeResult = $conn->query($checkCodeQuery);
    if (!$codeResult) {
        $_SESSION['outputMessage'] = "Ошибка запроса: " . $conn->error . "<br>";
        header("Location: ../registr.php"); // Исправленный путь
        exit;
    }

    if ($codeResult->num_rows == 0) {
        $_SESSION['outputMessage'] = "Недействительный код регистрации. Пожалуйста, проверьте введенный код.<br>"; // Отладочное сообщение
        header("Location: ../registr.php"); // Исправленный путь
        exit;
    } else {
        $registr = $codeResult->fetch_assoc();
        if (!$registr) {
            $_SESSION['outputMessage'] = "Недействительный код регистрации. Пожалуйста, проверьте введенный код.<br>";
            header("Location: ../registr.php"); // Исправленный путь
            exit;
        }
        $id = $registr['employee_code_registration_id'];
    }

    // Извлечение роли из кода
    $roleId = substr($registrationCode, -2, 1); // Получаем цифру перед ND

    // Хранение пароля в зашифрованном виде
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Вставка данных в таблицу employee
    if ($id > 0) {
        $insertQuery = "INSERT INTO employee (employee_name, employee_login, employee_password, employee_role, employee_code_registaretion) VALUES ('$fullname', '$username', '$hashedPassword', '$roleId', '$id')"; // Исправлено имя столбца

        if ($conn->query($insertQuery) === TRUE) {
            $_SESSION['outputMessage'] = "Регистрация прошла успешно!";
            header("Location: ../registr.php"); // Исправленный путь
            exit();
        }
    }
}
?>
