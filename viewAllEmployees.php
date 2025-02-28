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

    // SQL-запрос для получения всех сотрудников
    $query = "SELECT e.employee_id, e.employee_name, e.employee_login, r.role_name 
              FROM employee e 
              JOIN role r ON e.employee_role = r.role_id";
    $result = $conn->query($query);
    ?>

    <h1 class="text-3xl font-bold mb-4 text-center">Список сотрудников</h1>
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
            <?php while ($employee = $result->fetch_assoc()): ?>
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
