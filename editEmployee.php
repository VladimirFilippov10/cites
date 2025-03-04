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
    <title>Редактирование сотрудника</title>
    <link rel="stylesheet" href="path/to/your/styles.css"> <!-- Укажите путь к вашим стилям -->
</head>
<body>
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

    <h1 class="text-3xl font-bold mb-4">Редактирование сотрудника: <?php echo $employee['employee_name']; ?></h1>

    <form action="php/updateEmployee.php" method="POST" class="space-y-4">

        <input type="hidden" name="employee_id" value="<?php echo $employee['employee_id']; ?>">
        <div class="mb-4">
            <label class="block text-sm font-medium" for="name">Имя</label>
            <input class="mt-1 block w-full p-2 border border-gray-300 rounded-md" type="text" id="name" name="name" value="<?php echo $employee['employee_name']; ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium" for="login">Логин</label>
            <input class="mt-1 block w-full p-2 border border-gray-300 rounded-md" type="text" id="login" name="login" value="<?php echo $employee['employee_login']; ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium" for="password">Пароль</label>
            <input class="mt-1 block w-full p-2 border border-gray-300 rounded-md" type="password" id="password" name="password" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium" for="phone">Номер телефона</label>
            <input class="mt-1 block w-full p-2 border border-gray-300 rounded-md" type="text" id="phone" name="phone" value="<?php echo $employee['employee_number_phone']; ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium" for="role">Роль</label>
            <select id="role" name="role" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
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


        <button type="submit" class="mt-4 bg-blue-500 text-white py-2 px-4 rounded">Сохранить изменения</button>

    </form>

    <?php include 'template/footer.php'; ?>
</body>
</html>
