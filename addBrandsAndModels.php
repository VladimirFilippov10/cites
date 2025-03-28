<?php
session_start();
include 'php/auth.php';
checkAuth(); // Проверка аутентификации
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить марки и модели</title>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
        include 'php/dbconnect.php'; // Подключение к базе данных
    ?>
    <div class="max-w-7xl w-2/4 mx-auto p-4 bg-white shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Добавить марки и модели</h1>
        
        <!-- Форма для добавления марок -->
        <div class="mb-10">
            <h2 class="text-xl font-semibold mb-4">Добавить марку</h2>
            <form action="php/addBrand.php" method="POST">
                <div class="mb-6">
                    <label for="brand_name" class="block text-lg font-semibold mb-2">Название марки</label>
                    <input type="text" id="brand_name" name="brand_name" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <button type="submit" class="bg-green-500 text-white p-2 rounded">Добавить марку</button>
            </form>
        </div>

        <!-- Форма для добавления моделей -->
        <div>
            <h2 class="text-xl font-semibold mb-4">Добавить модель</h2>
            <form action="php/addModel.php" method="POST">
                <div class="mb-6">
                    <label for="brand" class="block text-lg font-semibold mb-2">Выберите марку</label>
                    <select id="brand" name="brand" class="w-full p-2 border border-gray-300 rounded" required>
                        <option value="">Выберите марку</option>
                        <?php
                        $marksQuery = "SELECT * FROM brand";
                        $marksResult = $conn->query($marksQuery);
                        if ($marksResult->num_rows > 0) {
                            while($row = $marksResult->fetch_assoc()) {
                                echo '<option value="' . $row['brand_id'] . '">' . $row['brand_name'] . '</option>';
                            }
                        } else {
                            echo '<option value="">Нет доступных марок</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-6">
                    <label for="model_name" class="block text-lg font-semibold mb-2">Название модели</label>
                    <input type="text" id="model_name" name="model_name" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <button type="submit" class="bg-green-500 text-white p-2 rounded">Добавить модель</button>
            </form>
        </div>
    </div>
    <?php include 'template/footer.php'; ?>

</body>
</html>
