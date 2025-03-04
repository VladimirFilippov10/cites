<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $registrationCode = mysqli_real_escape_string($conn, $_POST['registration-code']);

    // Проверка уникальности логина
    $checkUsernameQuery = "SELECT * FROM employee WHERE employee_login = '$username'";
    $usernameResult = $conn->query($checkUsernameQuery);

    if ($usernameResult->num_rows > 0) {
        echo "Логин уже занят. Пожалуйста, выберите другой.";
        exit;
    }

    // Проверка кода регистрации
    $checkCodeQuery = "SELECT * FROM employee_code_registration WHERE employee_code_registration_value = '$registrationCode'";
    $codeResult = $conn->query($checkCodeQuery);

    if ($codeResult->num_rows == 0) {
        echo "Недействительный код регистрации.";
        exit;
    }

    // Извлечение роли из кода
    $roleId = substr($registrationCode, -2, 1); // Получаем цифру перед ND

    // Хранение пароля в зашифрованном виде
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Вставка данных в таблицу employee
    $insertQuery = "INSERT INTO employee (employee_name, employee_login, employee_password, employee_role) VALUES ('$fullname', '$username', '$hashedPassword', '$roleId')";
    if ($conn->query($insertQuery) === TRUE) {
        echo "Регистрация прошла успешно!";
    } else {
        echo "Ошибка: " . $conn->error;
    }
}
?>
