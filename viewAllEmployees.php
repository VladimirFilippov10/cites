<?php
session_start();
include 'php/auth.php';
checkAuth(); // Проверка аутентификации
if ($_SESSION['employee_role'] != 1) { // Если роль 3, перенаправляем на dashboard
    header('Location: dashboard.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список сотрудников</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Укажите путь к вашим стилям -->
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
    include 'template/header.php';
    include 'template/nav_employees.php';
    include 'php/dbconnect.php'; // Подключение к базе данных

    // SQL-запрос для получения всех сотрудников и ролей
    $roleQuery = "SELECT role_id, role_name FROM role";
    $rolesResult = $conn->query($roleQuery);

    // Обработка параметров сортировки
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'employee_name';
    $order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'asc' : 'desc'; // Изменение порядка сортировки
    $valid_columns = ['employee_id', 'employee_name', 'employee_number_phone', 'role_name'];

    if (!in_array($sort, $valid_columns)) {
        $sort = 'employee_name'; // Default sorting column
    }

    $query = "SELECT e.employee_id, e.employee_name, e.employee_number_phone, r.role_name 
              FROM employee e 
              JOIN role r ON e.employee_role = r.role_id
              ORDER BY $sort $order";

    $result = $conn->query($query);
    ?>

    <h1 class="text-3xl font-bold mb-4 text-center">Список сотрудников</h1>
    <form method="POST" action="" class="mb-4">
        <label for="role" class="block text-lg font-semibold mb-2">Выберите роль:</label>
        <select name="role" id="role" class="mt-1 block w-full p-2 border border-gray-300 rounded">
            <?php while ($role = $rolesResult->fetch_assoc()): ?>
                <option value="<?php echo $role['role_id']; ?>"><?php echo $role['role_name']; ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Сгенерировать код</button>
    </form>
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr>
                <th class="border px-4 py-2">
                    <a href="?sort=employee_id&order=<?php echo $sort === 'employee_id' ? $order : 'asc'; ?>">ID 
                        <?php echo $sort === 'employee_id' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                    </a>
                </th>
                <th class="border px-4 py-2">
                    <a href="?sort=employee_name&order=<?php echo $sort === 'employee_name' ? $order : 'asc'; ?>">Имя 
                        <?php echo $sort === 'employee_name' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                    </a>
                </th>
                <th class="border px-4 py-2">
                    <a href="?sort=employee_number_phone&order=<?php echo $sort === 'employee_number_phone' ? $order : 'asc'; ?>">Номер телефона 
                        <?php echo $sort === 'employee_number_phone' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                    </a>
                </th>
                <th class="border px-4 py-2">
                    <a href="?sort=role_name&order=<?php echo $sort === 'role_name' ? $order : 'asc'; ?>">Роль 
                        <?php echo $sort === 'role_name' ? ($order === 'asc' ? '↑' : '↓') : ''; ?>
                    </a>
                </th>

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
            echo "<p class='text-green-500'>Код успешно сгенерирован: $registrationCode</p>";
        } else {
            echo "<p class='text-red-500'>Сгенерированный код не уникален. Пожалуйста, попробуйте снова.</p>";
        }
    }
    while ($employee = $result->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="border px-4 py-2"><?php echo $employee['employee_id']; ?></td>
                    <td class="border px-4 py-2"><?php echo $employee['employee_name']; ?></td>
                    <td class="border px-4 py-2"><?php echo $employee['employee_number_phone']; ?></td>
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
