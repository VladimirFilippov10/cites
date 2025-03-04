<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать клиента</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'template/header.php';
    include 'template/nav_employees.php'; ?>
    <div class="container">
        <h1>Редактировать клиента</h1>
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
            
            <label for="client_name">Имя клиента:</label>
            <input type="text" id="client_name" name="client_name" value="<?php echo $client['client_name']; ?>" required>
            
            <label for="client_phone_number">Номер телефона:</label>
            <input type="text" id="client_phone_number" name="client_phone_number" value="<?php echo $client['client_phone_number']; ?>" required>
            
            <label for="client_type_client">Тип клиента:</label>
            <select id="client_type_client" name="client_type_client" required>
                <option value="физическое лицо" <?php echo ($client['client_type_client'] == 'физическое лицо') ? 'selected' : ''; ?>>Физическое лицо</option>
                <option value="юридическое лицо" <?php echo ($client['client_type_client'] == 'юридическое лицо') ? 'selected' : ''; ?>>Юридическое лицо</option>
            </select>
            
            <button type="submit">Сохранить изменения</button>
        </form>
    </div>
    <?php include 'template/footer.php'; ?>
</body>
</html>
