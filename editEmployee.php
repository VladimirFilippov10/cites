<?php
session_start();
include 'php/auth.php';
checkAuth(); // Проверка аутентификации
if ($_SESSION['employee_role'] != 1) { 
    header('Location: dashboard.php');
    exit;
}
// Остальной код остается без изменений
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование сотрудника</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Укажите путь к вашим стилям -->
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
    include 'template/header.php';
    include 'template/nav_employees.php';
    include 'php/dbconnect.php'; // Подключение к базе данных
    checkAuth(); // Проверка аутентификации


    // Получение ID сотрудника из URL
    $employee_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // SQL-запрос для получения информации о сотруднике
    $query = "SELECT e.employee_id, e.employee_name, e.employee_login, e.employee_role, e.employee_number_phone, r.role_name 
              FROM employee e 
              JOIN role r ON e.employee_role = r.role_id 
              WHERE e.employee_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();
    ?>

    <div class="max-w-7xl w-2/4 mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Редактирование сотрудника: <?php echo $employee['employee_name']; ?></h1>
        <form action="php/updateEmployee.php" method="POST" class="space-y-4">
            <input type="hidden" name="employee_id" value="<?php echo $employee['employee_id']; ?>">
            <div class="mb-4">
                <label class="block text-lg font-semibold mb-2" for="name">Имя</label>
                <input class="mt-1 block w-full p-2 border border-gray-300 rounded" type="text" id="name" name="name" value="<?php echo $employee['employee_name']; ?>" required>
            </div>
            <div class="mb-4">
                <label class="block text-lg font-semibold mb-2" for="login_employee">Логин</label>
                <input class="mt-1 block w-full p-2 border border-gray-300 rounded" type="text" id="login_employee" name="login_employee" value="<?php echo $employee['employee_login']; ?>" required>
            </div>
            <div class="mb-4">
                <label class="block text-lg font-semibold mb-2" for="password_employee">Пароль</label>
                <input class="mt-1 block w-full p-2 border border-gray-300 rounded" type="password" id="password_employee" name="password_employee" required>
            </div>
            <div class="mb-4">
                <label class="block text-lg font-semibold mb-2" for="phone">Номер телефона</label>
                <input class="mt-1 block w-full p-2 border border-gray-300 rounded" type="text" id="phone" name="phone" value="<?php echo $employee['employee_number_phone']; ?>" required>
            </div>
            <div class="mb-4">
                <label class="block text-lg font-semibold mb-2" for="role">Роль</label>
                <select id="role" name="role" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                   <option value="<?php echo $employee['employee_role']; ?>"><?php echo $employee['role_name']; ?></option>
                    <?php
                    // SQL-запрос для получения всех ролей
                    $roleQuery = "SELECT role_id, role_name FROM role";
                    $roleResult = $conn->query($roleQuery);
                    while ($role = $roleResult->fetch_assoc()): ?>
                        <option value="<?php echo $role['role_id']; ?>"><?php echo $role['role_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="mt-4 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">Сохранить изменения</button>
        </form>
    </div>

    <?php include 'template/footer.php'; ?>
</body>
</html>
