<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вакансии</title>
    <script src="js/messageRansomCars.js"></script>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        session_start();
        include 'template/header.php';

        // Инициализация переменных сессии для видимости вакансий
        if (!isset($_SESSION['technical_specialist_visible'])) {
            $_SESSION['technical_specialist_visible'] = false;
        }
        if (!isset($_SESSION['field_appraiser_visible'])) {
            $_SESSION['field_appraiser_visible'] = false;
        }
        if (!isset($_SESSION['sales_manager_visible'])) {
            $_SESSION['sales_manager_visible'] = false;
        }

        // Обработка изменения состояния переключателей
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['toggleTechnicalSpecialist'])) {
                $_SESSION['technical_specialist_visible'] = !$_SESSION['technical_specialist_visible'];
            }
            if (isset($_POST['toggleFieldAppraiser'])) {
                $_SESSION['field_appraiser_visible'] = !$_SESSION['field_appraiser_visible'];
            }
            if (isset($_POST['toggleSalesManager'])) {
                $_SESSION['sales_manager_visible'] = !$_SESSION['sales_manager_visible'];
            }
        }
    ?>
    <div class="container mx-auto p-4">
        <?php if (isset($_SESSION['user_id']) && $_SESSION['employee_role'] == 1): ?>
            <form method="POST" class="mb-4">
                <div class="mb-4">
                    <label for="toggleTechnicalSpecialist" class="inline-flex items-center">
                        <input type="checkbox" id="toggleTechnicalSpecialist" name="toggleTechnicalSpecialist" class="form-checkbox" <?php echo $_SESSION['technical_specialist_visible'] ? 'checked' : ''; ?>>
                        <span class="ml-2">Показать Технического специалиста</span>
                    </label>
                </div>
                <div class="mb-4">
                    <label for="toggleFieldAppraiser" class="inline-flex items-center">
                        <input type="checkbox" id="toggleFieldAppraiser" name="toggleFieldAppraiser" class="form-checkbox" <?php echo $_SESSION['field_appraiser_visible'] ? 'checked' : ''; ?>>
                        <span class="ml-2">Показать Выездного оценщика</span>
                    </label>
                </div>
                <div class="mb-4">
                    <label for="toggleSalesManager" class="inline-flex items-center">
                        <input type="checkbox" id="toggleSalesManager" name="toggleSalesManager" class="form-checkbox" <?php echo $_SESSION['sales_manager_visible'] ? 'checked' : ''; ?>>
                        <span class="ml-2">Показать Менеджера по продажам</span>
                    </label>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Сохранить</button>
            </form>
        <?php endif; ?>

        <h1 class="text-3xl font-bold text-center mb-8">Вакансии</h1>
        
        <?php if (isset($_SESSION['technical_specialist_visible']) && $_SESSION['technical_specialist_visible']): ?>

            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-2xl font-bold mb-2">Технический специалист</h2>
                <p class="text-gray-700 mb-4">Мы ищем опытного технического специалиста для работы в нашем автосалоне.</p>
                <h3 class="text-xl font-semibold mb-2">Требования:</h3>
                <ul class="list-disc list-inside mb-4">
                    <li>Умение работать с автомобилями популярных марок</li>
                    <li>Знание устройства автомобилей</li>
                    <li>Умение работать с диагностическим оборудованием</li>
                    <li>Ответственность и внимательность</li>
                </ul>
                <h3 class="text-xl font-semibold mb-2">Опыт:</h3>
                <p class="text-gray-700">Не менее 3 лет работы </p>
                <h3 class="text-xl font-semibold mb-2">Зарплата:</h3>
                <p class="text-gray-700">Оплата по часовая в зависимости от выполняемых работ</p>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['field_appraiser_visible']) && $_SESSION['field_appraiser_visible']): ?>

            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-2xl font-bold mb-2">Выездной оценщик</h2>
                <p class="text-gray-700 mb-4">Мы ищем выездного оценщика для оценки автомобилей на месте.</p>
                <h3 class="text-xl font-semibold mb-2">Требования:</h3>
                <ul class="list-disc list-inside mb-4">
                    <li>Опыт работы от 2 лет</li>
                    <li>Знание рынка автомобилей</li>
                    <li>Умение проводить оценку состояния автомобиля</li>
                    <li>Коммуникабельность и мобильность</li>
                    <li>Наличие водительского удостоверения категории B</li>
                </ul>
                <h3 class="text-xl font-semibold mb-2">Опыт:</h3>
                <p class="text-gray-700">Не менее 2 лет работы в аналогичной должности.</p>
                <h3 class="text-xl font-semibold mb-2">Зарплата:</h3>
                <p class="text-gray-700">Оплата за каждый выезд от 500 рублей в зависимости от времени затраченного на выезд</p>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['sales_manager_visible']) && $_SESSION['sales_manager_visible']): ?>

            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-2xl font-bold mb-2">Менеджер по продажам</h2>
                <p class="text-gray-700 mb-4">Мы ищем менеджера по продажам для работы с клиентами в автосалоне.</p>
                <h3 class="text-xl font-semibold mb-2">Требования:</h3>
                <ul class="list-disc list-inside mb-4">
                    <li>Опыт работы от 1 года</li>
                    <li>Знание техника продаж</li>
                    <li>Коммуникабельность и умение работать с клиентами</li>
                    <li>Ответственность и целеустремленность</li>
                </ul>
                <h3 class="text-xl font-semibold mb-2">Опыт:</h3>
                <p class="text-gray-700">Не менее 1 года работы в аналогичной должности.</p>
                <h3 class="text-xl font-semibold mb-2">Зарплата:</h3>
                <p class="text-gray-700">2% от продажи авто</p>
            </div>
        <?php endif; ?>
    </div>
  <?php
include 'template/footer.php'
?>
</body>
</html>
