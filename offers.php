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
        include 'template\header.php';
    ?>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-center mb-8">Вакансии</h1>
        
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

        <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
            <h2 class="text-2xl font-bold mb-2">Менеджер по продажам</h2>
            <p class="text-gray-700 mb-4">Мы ищем менеджера по продажам для работы с клиентами в автосалоне.</p>
            <h3 class="text-xl font-semibold mb-2">Требования:</h3>
            <ul class="list-disc list-inside mb-4">
                <li>Опыт работы от 1 года</li>
                <li>Знание техники продаж</li>
                <li>Коммуникабельность и умение работать с клиентами</li>
                <li>Ответственность и целеустремленность</li>
            </ul>
            <h3 class="text-xl font-semibold mb-2">Опыт:</h3>
            <p class="text-gray-700">Не менее 1 года работы в аналогичной должности.</p>
            <h3 class="text-xl font-semibold mb-2">Зарплата:</h3>
            <p class="text-gray-700">2% от продажи авто</p>
        </div>
    </div>
  <?php
include 'template/footer.php'
?>
</body>
</html>