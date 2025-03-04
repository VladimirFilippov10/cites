<?php
session_start();
include 'php/auth.php';
checkAuth(); // Проверка аутентификации

// Остальной код остается без изменений
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список сотрудников</title>
    <link rel="stylesheet" href="path/to/your/styles.css"> <!-- Укажите путь к вашим стилям -->
</head>
<body>
    <?php
    include 'template/header.php';
    include 'template/nav_employees.php';
    include 'php/dbconnect.php'; // Подключение к базе данных

    // SQL-запрос для получения всех сотрудников и ролей
    $roleQuery = "SELECT role_id, role_name FROM role";
    $rolesResult = $conn->query($roleQuery);

    $query = "SELECT e.employee_id, e.employee_name, e.employee_login, r.role_name 
              FROM employee e 
              JOIN role r ON e.employee_role = r.role_id";
    $result = $conn->query($query);
    ?>

    <h1 class="text-3xl font-bold mb-4 text-center">Список сотрудников</h1>
    <form method="POST" action="">
        <label for="role">Выберите роль:</label>
        <select name="role" id="role">
            <?php while ($role = $rolesResult->fetch_assoc()): ?>
                <option value="<?php echo $role['role_id']; ?>"><?php echo $role['role_name']; ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Сгенерировать код</button>
    </form>
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr>
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Имя</th>
                <th class="border px-4 py-2">Логин</th>
                <th class="border px-4 py-2">Роль</th>
                <th class="border px-4 py-2">Действия</th>
            </tr>
        </thead>
        <tbody>
    <?php 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $roleId = $_POST['role'];
        $randomNumber = rand(1000, 9999);
        $codeSuffix = ($roleId == 1) ? 'ND11' : 'ND10';
        $registrationCode = $randomNumber . $roleId . $codeSuffix;

        // Проверка уникальности кода
        $checkQuery = "SELECT * FROM employee_code_registration WHERE employee_code_registration_value = '$registrationCode'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows == 0) {
            // Вставка кода в таблицу
            $insertQuery = "INSERT INTO employee_code_registration (employee_code_registration_value) VALUES ('$registrationCode')";
            $conn->query($insertQuery);
            echo "<p>Код успешно сгенерирован: $registrationCode</p>";
        } else {
            echo "<p>Сгенерированный код не уникален. Пожалуйста, попробуйте снова.</p>";
        }
    }
    while ($employee = $result->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="border px-4 py-2"><?php echo $employee['employee_id']; ?></td>
                    <td class="border px-4 py-2"><?php echo $employee['employee_name']; ?></td>
                    <td class="border px-4 py-2"><?php echo $employee['employee_login']; ?></td>
                    <td class="border px-4 py-2"><?php echo $employee['role_name']; ?></td>
                    <td class="border px-4 py-2">
                        <a class="text-blue-500 hover:underline" href="editEmployee.php?id=<?php echo $employee['employee_id']; ?>">Редактировать</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php include 'template/footer.php'; ?>
</body>
</html>
