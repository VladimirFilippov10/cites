<?php
session_start();
include 'php/auth.php';
checkAuth(); // Проверка аутентификации
if ($_SESSION['employee_role'] == 3 || $_SESSION['employee_role'] == 4) { // Если роль 3, перенаправляем на dashboard
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить марки и модели</title>
    <script>
        function showTab(tabIndex) {
            const tab1 = document.getElementById('tab1');
            const tab2 = document.getElementById('tab2');
            const tab1Btn = document.getElementById('tab1-btn');
            const tab2Btn = document.getElementById('tab2-btn');

            if (tabIndex === 1) {
                tab1.classList.remove('hidden');
                tab2.classList.add('hidden');
                tab1Btn.classList.add('border-green-500', 'text-gray-700', 'font-semibold');
                tab1Btn.classList.remove('border-transparent', 'text-gray-500');
                tab2Btn.classList.remove('border-green-500', 'text-gray-700', 'font-semibold');
                tab2Btn.classList.add('border-transparent', 'text-gray-500');
            } else {
                tab1.classList.add('hidden');
                tab2.classList.remove('hidden');
                tab1Btn.classList.remove('border-green-500', 'text-gray-700', 'font-semibold');
                tab1Btn.classList.add('border-transparent', 'text-gray-500');
                tab2Btn.classList.add('border-green-500', 'text-gray-700', 'font-semibold');
                tab2Btn.classList.remove('border-transparent', 'text-gray-500');
            }
        }

        // Initialize with first tab visible
        document.addEventListener('DOMContentLoaded', function() {
            showTab(1);
        });
    </script>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template/header.php';
        include 'template/nav_employees.php';
        include 'php/dbconnect.php'; // Подключение к базе данных
    ?>
    <div class="max-w-7xl w-2/4 mx-auto p-4 bg-white shadow-md mt-10 border border-gray-300 rounded">
        <h1 class="text-2xl font-bold mb-6">Добавить марки и модели</h1>

        <div>
            <!-- Tab buttons -->
            <div class="flex border-b border-gray-300 mb-4">
                <button id="tab1-btn" type="button" class="py-2 px-4 text-gray-700 border-b-2 border-green-500 font-semibold focus:outline-none" onclick="showTab(1)">Добавление марок и моделей</button>
                <button id="tab2-btn" type="button" class="py-2 px-4 text-gray-500 hover:text-gray-700 border-b-2 border-transparent focus:outline-none" onclick="showTab(2)">Список марок и моделей</button>
            </div>

            <!-- Tab contents -->
            <div id="tab1" class="tab-content">
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

            <div id="tab2" class="tab-content hidden">
                <?php
                $marksQuery = "SELECT * FROM brand ORDER BY brand_name";
                $marksResult = $conn->query($marksQuery);
                if ($marksResult->num_rows > 0) {
                    while ($row = $marksResult->fetch_assoc()) {
                        echo '<div class="mb-6"><h3 class="text-lg font-semibold mb-2">'.$row['brand_name'].'</h3>';
                        $modelQuery = 'SELECT * FROM model WHERE brand_id = '.$row['brand_id'].' ORDER BY model_name ASC';
                        $modelResult = $conn->query($modelQuery);
                        
                        if ($modelResult->num_rows > 0) {
                            echo '<ul class="ml-4">';
                            while ($rowModel = $modelResult->fetch_assoc()) {
                                echo '<li class="mb-1">'.$rowModel['model_name'].'</li>';
                            }
                            echo '</ul>';
                        } else {
                            echo '<p class="text-gray-500 ml-4">Нет моделей для этой марки</p>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<p class="text-gray-500">Нет доступных марок</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <?php include 'template/footer.php'; ?>
</body>
</html>