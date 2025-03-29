<?php
session_start();
include 'php/auth.php';
checkAuth(); // Проверка аутентификации
if ($_SESSION['employee_role'] == 4) { // Если роль 3, перенаправляем на dashboard
    header('Location: dashboard.php');
    exit;
}
// Остальной код остается без изменений
?>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявки на выкуп</title>
    <script src="js/messageRansomCars.js"></script>
    <style>
        th {
            position: relative;
        }
        th:hover {
            cursor: col-resize;
        }
    </style>
</head>
<body class="bg-gray-100 font-roboto">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
        include 'php/dbconnect.php'; // Подключение к базе данных

        // Получение данных из таблицы redemption_request
        $query = "SELECT * FROM redemption_request";
        $result = mysqli_query($conn, $query);
    ?>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Заявки на выкуп авто</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="php/update_redemption_request.php" method="POST">
                <table class="min-w-full bg-white" id="applications">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ФИО заявителя</th>
                            <th class="py-2 px-4 border-b">Номер телефона</th>
                            <th class="py-2 px-4 border-b">Модель автомобиля</th>
                            <th class="py-2 px-4 border-b">Дата подачи</th>
                            <th class="py-2 px-4 border-b">Ответственный</th>
                            <th class="py-2 px-4 border-b">Время осмотра</th>
                            <th class="py-2 px-4 border-b">Место осмотра</th>
                            <th class="py-2 px-4 border-b">Статус заявки</th>
                            <th class="py-2 px-4 border-b">Закрыта</th>
                            <th class="py-2 px-4 border-b">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($request = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo $request['redemption_request_name_client']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $request['redemption_request_number_phone']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $request['redemption_request_model_car']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $request['redemption_request_date']; ?></td>
                            <td class="py-2 px-4 border-b">
                                <select class="border rounded p-2" name="employee">
                                    <?php
                                    $query_employees = "SELECT * FROM `employee` WHERE employee_role = 3";
                                    $result_employees = mysqli_query($conn, $query_employees);
                                    $current_employee = $request['redemption_request_employee'] ?? 0;
                                    while ($employee = mysqli_fetch_assoc($result_employees)): ?>
                                        <option value="<?php echo $employee['employee_id']; ?>" <?php echo ($employee['employee_id'] == $current_employee) ? 'selected' : ''; ?>>
                                            <?php echo $employee['employee_name']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                    <?php if ($current_employee == 0): ?>
                                        <option value="0" selected>Не назначен</option>
                                    <?php endif; ?>
                                </select>
                            </td>
                            <td class="py-2 px-4 border-b">
                                <input type="date" class="border rounded p-2" name="inspection_date[<?php echo $request['redemption_request_id']; ?>]">
                                <input type="time" class="border rounded p-2" name="inspection_time[<?php echo $request['redemption_request_id']; ?>]">
                            </td>
                            <td class="py-2 px-4 border-b">
                                <input type="text" class="border rounded p-2" name="inspection_place[<?php echo $request['redemption_request_id']; ?>]" placeholder="Введите место осмотра">
                            </td>
                            <td class="py-2 px-4 border-b">
                                <select class="border rounded p-2" name="status[<?php echo $request['redemption_request_id']; ?>]">
                                    <option>Открыта</option>
                                    <option>Специалист на осмотре</option>
                                    <option>Принятие решения</option>
                                    <option>Автомобиль готов к выкупу</option>
                                    <option>Отказ</option>
                                </select>
                            </td>
                            <td class="py-2 px-4 border-b text-center">
                                <input type="checkbox" class="form-checkbox h-5 w-5 text-green-600" name="closed">
                            </td>
                            <td class="py-2 px-4 border-b text-center">
                                <button type="submit" class="bg-blue-500 text-white rounded px-4 py-2" name="id" value="<?php echo $request['redemption_request_id']; ?>">Сохранить</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <?php
    include 'template/footer.php';
    ?>
    <script>
        const thElements = document.querySelectorAll('th');
        thElements.forEach(th => {
            th.addEventListener('mousedown', function(e) {
                const startX = e.pageX;
                const startWidth = th.offsetWidth;

                const mouseMoveHandler = (e) => {
                    const newWidth = startWidth + (e.pageX - startX);
                    th.style.width = `${newWidth}px`;
                };

                const mouseUpHandler = () => {
                    document.removeEventListener('mousemove', mouseMoveHandler);
                    document.removeEventListener('mouseup', mouseUpHandler);
                };

                document.addEventListener('mousemove', mouseMoveHandler);
                document.addEventListener('mouseup', mouseUpHandler);
            });
        });
    </script>
</body>
</html>
