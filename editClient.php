<?php
session_start();
include 'php/auth.php'; // Включение проверки авторизации
checkAuth(); // Проверка авторизации
if ($_SESSION['employee_role'] == 3) { // Если роль 3, перенаправляем на dashboard
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать клиента</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php include 'template/header.php';
    include 'template/nav_employees.php'; ?>
        <h1 class="text-2xl font-bold mb-6 text-center">Редактировать клиента</h1>
        <div class="max-w-7xl w-2/4 mx-auto p-4 bg-white shadow-md mt-10">
        <?php
            include 'php/dbconnect.php'; // Подключение к базе данных
            $client_id = $_GET['id']; // Получение client_id из URL
            $query = "SELECT * FROM client WHERE client_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $client_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $client = $result->fetch_assoc();
        ?>
        <form action="php/client_update.php?action=edit" method="POST">
            <input type="hidden" id="client_id" name="client_id" value="<?php echo $client['client_id']; ?>">
            
            <div class="mb-6">
                <label for="client_name" class="block text-lg font-semibold mb-2">Имя клиента:</label>
                <input type="text" id="client_name" name="client_name" value="<?php echo $client['client_name']; ?>" required class="w-full p-2 border border-gray-300 rounded">
            </div>            
            <div class="mb-6">
                <label for="client_phone_number" class="block text-lg font-semibold mb-2">Номер телефона:</label>
                <input type="text" id="client_phone_number" name="client_phone_number" value="<?php echo $client['client_phone_number']; ?>" required class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-6">
                <label for="client_type_client" class="block text-lg font-semibold mb-2">Тип клиента:</label>
                <select id="client_type_client" name="client_type_client" required class="w-full p-2 border border-gray-300 rounded">
                <option value="физическое лицо" <?php echo ($client['client_type_client'] == 'физическое лицо') ? 'selected' : ''; ?>>Физическое лицо</option>
                <option value="юридическое лицо" <?php echo ($client['client_type_client'] == 'юридическое лицо') ? 'selected' : ''; ?>>Юридическое лицо</option>
            </select>
            
            <button type="submit" class="bg-green-500 text-white p-2 rounded mt-4">Сохранить изменения</button>
            <?php
                  $query = "SELECT cb.*, c.client_name, e.employee_name, cr.car_win_code FROM car_buyback cb 
                  JOIN client c ON cb.car_buyback_client_id = c.client_id
                  JOIN car cr ON cb.car_buyback_car_id = cr.car_id
                  JOIN employee e ON cb.car_buyback_employee = e.employee_id
                   WHERE client_id = ?";
                  $stmt = $conn->prepare($query);
                  $stmt->bind_param("i", $client_id);
                  $stmt->execute();
                  $result = $stmt->get_result();
            ?>
        </form>
        <h2 class="text-2xl font-bold mb-4 text-center">Продажи авто</h2>

       <table class="min-w-full border-collapse border border-gray-300 mt-4">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2 text-left">ID</th>
                    <th class="border border-gray-300 p-2">Номер заявки</th>
                    <th class="border border-gray-300 p-2">Дата</th>
                    <th class="border border-gray-300 p-2">WIN-код авто</th>
                    <th class="border border-gray-300 p-2">Цена</th>
                    <th class="border border-gray-300 p-2">Сотрудник</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="border border-gray-300 p-2 text-left"><?php echo $row['car_buyback_id']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['car_buyback_redemption_request']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['car_buyback_datetime']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['car_win_code']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['car_buyback_price']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['employee_name']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php
                  $query = "SELECT cb.*, c.client_name, e.employee_name, cr.car_win_code FROM car_sales cb 
                  JOIN client c ON cb.car_sales_client_id = c.client_id
                  JOIN car cr ON cb.car_sales_car_id = cr.car_id
                  JOIN employee e ON cb.car_sales_employee_id = e.employee_id
                   WHERE client_id = ?";
                  $stmt = $conn->prepare($query);
                  $stmt->bind_param("i", $client_id);
                  $stmt->execute();
                  $result = $stmt->get_result();
            ?>
        </form>
        <h2 class="text-2xl font-bold mb-4 text-center">Покупки авто</h2>

       <table class="min-w-full border-collapse border border-gray-300 mt-4">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2 text-left">ID</th>
                    <th class="border border-gray-300 p-2">Дата</th>
                    <th class="border border-gray-300 p-2">WIN-код авто</th>
                    <th class="border border-gray-300 p-2">Цена</th>
                    <th class="border border-gray-300 p-2">Сотрудник</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="border border-gray-300 p-2 text-left"><?php echo $row['car_sales_id']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['car_sales_datatime']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['car_win_code']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['car_sales_price']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['employee_name']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>            
    <?php include 'template/footer.php'; ?>
</body>
</html>
