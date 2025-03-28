<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = intval($_POST['employee_id']);
    $employee_name = mysqli_real_escape_string($conn, $_POST['name']);
    $employee_login = mysqli_real_escape_string($conn, $_POST['login_employee']);
    $employee_role = intval($_POST['role']);

    // Debugging: Output the role being checked
    echo "Checking role: " . $employee_role . "<br>";
    $employee_number_phone = mysqli_real_escape_string($conn, $_POST['phone']);


    $employee_password = mysqli_real_escape_string($conn, $_POST['password_employee']);

    // Debugging: Output the employee ID and values being passed to the update query
    echo "Updating employee with ID: " . $employee_id . "<br>";
    echo "Values: Name: " . $employee_name . ", Login: " . $employee_login . ", Role: " . $employee_role . ", Phone: " . $employee_number_phone . "<br>";
    
    // Debugging: Output the employee ID and values being passed to the update query




    // Debugging: Output the values
    echo "Employee ID: " . $employee_id . "<br>";
    echo "Employee Name: " . $employee_name . "<br>";
    echo "Employee Login: " . $employee_login . "<br>";
    echo "Employee Role: " . $employee_role . "<br>";
    echo "Employee Phone: " . $employee_number_phone . "<br>";
    echo "Employee Password: " . $employee_password . "<br>";

    // Validate the employee role
    $roleCheckQuery = "SELECT COUNT(*) FROM role WHERE role_id = ?";
    $roleCheckStmt = $conn->prepare($roleCheckQuery);
    $roleCheckStmt->bind_param("i", $employee_role);
    $roleCheckStmt->execute();
    $roleCheckStmt->bind_result($roleExists);
    $roleCheckStmt->fetch();
    $roleCheckStmt->close();

    if ($roleExists == 0) {
        echo "Ошибка: Указанная роль не существует.";
        exit();
    }

    // SQL-запрос для обновления информации о сотруднике
    $query = "UPDATE employee SET employee_name = '$employee_name', employee_login = '$employee_login', employee_role = $employee_role, employee_number_phone = '$employee_number_phone', employee_password = '$employee_password' WHERE employee_id = $employee_id";

    echo $query;
    // $stmt = $conn->prepare($query);


    // Debugging: Check if the statement was prepared successfully
    //if (!$stmt) {
   //     echo "Ошибка при подготовке запроса: " . $conn->error;
    //    exit();
  //  }

    // $stmt->bind_param("ssssii", $employee_name, $employee_login, $employee_number_phone, $employee_password, $employee_role, $employee_id);

    if ($conn->query($query)) {

        // Перенаправление на страницу со списком сотрудников с сообщением об успешном обновлении
        header("Location: ../viewAllEmployees.php?success=true");
        exit();
    } else {
        // Обработка ошибки
        echo "Ошибка при обновлении: " . $stmt->error;
    }
}
?>
