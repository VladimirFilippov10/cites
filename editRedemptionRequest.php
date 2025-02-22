<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование заявки на выкуп</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
        include 'php/dbconnect.php';

        // Проверка ID заявки
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            die("Ошибка: некорректный ID заявки.");
        }

        $id = intval($_GET['id']);

        // Получение данных заявки
        $query = "SELECT * FROM redemption_request WHERE redemption_request_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            die("Ошибка: заявка не найдена.");
        }

        $request = $result->fetch_assoc();

        // Получение списка сотрудников
        $employeesQuery = "SELECT employee_id, employee_name FROM employee WHERE employee_role = 3";

        $employeesResult = $conn->query($employeesQuery);
    ?>
    <div class="max-w-7xl w-2/4 mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Редактирование заявки #<?php echo $id; ?></h1>
        
        <form action="php/update_redemption_request.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <!-- Основная информация -->
            <div class="mb-6">
                <label for="name" class="block text-lg font-semibold mb-2">Имя клиента</label>
                <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded" 
                       value="<?php echo htmlspecialchars($request['redemption_request_name_client']); ?>" required readonly>

            </div>

            <div class="mb-6">
                <label for="model" class="block text-lg font-semibold mb-2">Модель автомобиля</label>
                <input type="text" id="model" name="model" class="w-full p-2 border border-gray-300 rounded" 
                       value="<?php echo htmlspecialchars($request['redemption_request_model_car']); ?>" required readonly>

            </div>

            <div class="mb-6">
                <label for="phone" class="block text-lg font-semibold mb-2">Телефон</label>
                <input type="text" id="phone" name="phone" class="w-full p-2 border border-gray-300 rounded" 
                       value="<?php echo htmlspecialchars($request['redemption_request_number_phone']); ?>" required readonly>

            </div>

            <!-- Дата и время осмотра -->
            <div class="mb-6">
                <label for="inspection_date" class="block text-lg font-semibold mb-2">Дата осмотра</label>
                <input type="date" id="inspection_date" name="inspection_date" class="w-full p-2 border border-gray-300 rounded" 
                       value="<?php echo date('Y-m-d', strtotime($request['redemption_request_inspection_time'])); ?>">
            </div>

            <div class="mb-6">
                <label for="inspection_time" class="block text-lg font-semibold mb-2">Время осмотра</label>
                <input type="time" id="inspection_time" name="inspection_time" class="w-full p-2 border border-gray-300 rounded" 
                       value="<?php echo date('H:i', strtotime($request['redemption_request_inspection_time'])); ?>">
            </div>

            <!-- Место осмотра -->
            <div class="mb-6">
                <label for="inspection_place" class="block text-lg font-semibold mb-2">Место осмотра</label>
                <input type="text" id="inspection_place" name="inspection_place[<?php echo $id; ?>]" class="w-full p-2 border border-gray-300 rounded" 
                       value="<?php echo htmlspecialchars($request['redemption_request_place']); ?>">

            </div>

            <!-- Статус -->
            <div class="mb-6">
                <label for="status" class="block text-lg font-semibold mb-2">Статус</label>
                <select id="status" name="status[<?php echo $id; ?>]" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="Открыта" <?php echo $request['redemption_request_status'] == 'Открыта' ? 'selected' : ''; ?>>Открыта</option>
                    <option value="Специалист на осмотре" <?php echo $request['redemption_request_status'] == 'Специалист на осмотре' ? 'selected' : ''; ?>>Специалист на осмотре</option>
                    <option value="Принятие решения" <?php echo $request['redemption_request_status'] == 'Принятие решения' ? 'selected' : ''; ?>>Принятие решения</option>
                    <option value="Автомобиль готов к выкупу" <?php echo $request['redemption_request_status'] == 'Автомобиль готов к выкупу' ? 'selected' : ''; ?>>Автомобиль готов к выкупу</option>
                    <option value="Отказ" <?php echo $request['redemption_request_status'] == 'Отказ' ? 'selected' : ''; ?>>Отказ</option>
                    <option value="Автомобиль выкуплен" <?php echo $request['redemption_request_status'] == 'Автомобиль выкуплен' ? 'selected' : ''; ?>>Автомобиль выкуплен</option>
                </select>

            </div>

            <!-- Ответственный сотрудник -->
            <div class="mb-6">
                <label class="block text-lg font-semibold mb-2">
                    <input type="checkbox" name="closed" value="1" 
                        <?php echo $request['redemption_request_closed'] ? 'checked' : ''; ?>>
                    Закрыть заявку
                </label>
            </div>

            <div class="mb-6">
                <label for="employee" class="block text-lg font-semibold mb-2">Ответственный сотрудник</label>
                <select id="employee" name="employee" class="w-full p-2 border border-gray-300 rounded" required>

                    <?php while ($employee = $employeesResult->fetch_assoc()): ?>
                        <option value="<?php echo $employee['employee_id']; ?>" 
                            <?php echo $employee['employee_id'] == $request['redemption_request_employee'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($employee['employee_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="bg-green-500 text-white p-2 rounded">Сохранить изменения</button>
        </form>
    </div>

    <?php
        include 'template/footer.php';
        $conn->close();
    ?>
</body>
</html>
