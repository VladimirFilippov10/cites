<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <script src="js/carsOpenTheAdvancedSearch.js"></script>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template\header.php';
    ?>
     <main class="flex-grow">
    <div class="bg-gray-100 p-6 rounded-lg shadow-md w-full max-w-4xl mx-auto">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <select class="border border-gray-300 rounded-lg p-2">
                <option>Марка</option>
                <option>BMW</option>
                <option>Audi</option>
                <option>Mercedes</option>
            </select>
            <select class="border border-gray-300 rounded-lg p-2">
                <option>Модель</option>
                <option>3 Series</option>
                <option>A4</option>
                <option>C-Class</option>
            </select>
            <div class="flex space-x-2">
                <input type="text" placeholder="Цена от, ₽" class="border border-gray-300 rounded-lg p-2 w-full">
                <input type="text" placeholder="до" class="border border-gray-300 rounded-lg p-2 w-full">
            </div>
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-lg p-2 w-full">
                    <option>Год от</option>
                    <option>2000</option>
                    <option>2005</option>
                    <option>2010</option>
                </select>
                <select class="border border-gray-300 rounded-lg p-2 w-full">
                    <option>до</option>
                    <option>2015</option>
                    <option>2020</option>
                    <option>2023</option>
                </select>
            </div>
        </div>
        <div id="advanced-search" class="hidden mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <select class="border border-gray-300 rounded-lg p-2">
                    <option>Кузов</option>
                    <option>Седан</option>
                    <option>Хэтчбек</option>
                    <option>Универсал</option>
                </select>
                <select class="border border-gray-300 rounded-lg p-2">
                    <option>Коробка</option>
                    <option>Автомат</option>
                    <option>Механика</option>
                </select>
                <select class="border border-gray-300 rounded-lg p-2">
                    <option>Двигатель</option>
                    <option>Бензин</option>
                    <option>Дизель</option>
                </select>
                <select class="border border-gray-300 rounded-lg p-2">
                    <option>Привод</option>
                    <option>Передний</option>
                    <option>Задний</option>
                    <option>Полный</option>
                </select>
                <div class="flex space-x-2">
                    <input type="text" placeholder="Пробег от, км" class="border border-gray-300 rounded-lg p-2 w-full">
                    <input type="text" placeholder="до" class="border border-gray-300 rounded-lg p-2 w-full">
                </div>
                <div class="flex space-x-2">
                    <input type="text" placeholder="Объем от, л" class="border border-gray-300 rounded-lg p-2 w-full">
                    <input type="text" placeholder="до" class="border border-gray-300 rounded-lg p-2 w-full">
                </div>
            </div>
        </div>
        <div class="mt-4 flex justify-between items-center">
            <button id="toggle-button" class="text-blue-500" onclick="toggleAdvancedSearch()">Расширенный поиск</button>
            <button class="bg-blue-500 text-white px-4 py-2 rounded-lg">Показать результаты</button>
        </div>
    </div>
  <div class="container mx-auto py-20 px-20">
   <div class="flex flex-col w-full p-5 space-y-5">
    <a href="car.php?id=1" class="flex w-full bg-gray-200 h-350 rounded-lg overflow-hidden shadow-lg">
        <div class="w-1/5">
            <img alt="Silver Skoda Rapid I in a showroom" class="h-full w-full object-cover" src="img/cars/testBMW.png" />
        </div>
        <div class="w-2/3 pl-4 flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-bold">Skoda Rapid I</h2>
                <p class="text-gray-600 text-sm">1.4 л/125 л.с./Бензин</p>
                <p class="text-gray-600 text-sm">Робот</p>
                <p class="text-gray-600 text-sm">Лифтбек</p>
                <div class="flex items-center mt-2">
                    <span class="text-green-600 text-lg font-bold">1 098 000 ₽</span>
                </div>
                <div class="flex items-center mt-2">
                    <span class="text-gray-600 text-sm">2016</span>
                    <span class="ml-4 text-gray-600 text-sm">205 433 км</span>
                </div>
                <div class="flex items-center mt-2">
                    <span class="text-gray-600 text-sm">Передний</span>
                    <span class="ml-4 text-gray-600 text-sm">Серебристый</span>
                </div>
            </div>
        </div>
    </a>

    <!-- Добавьте дополнительные блоки аналогичным образом -->
    <a href="car.php?id=2" class="flex w-full bg-gray-200 h-350 rounded-lg overflow-hidden shadow-lg">
        <div class="w-1/5">
            <img alt="Another Car" class="h-full w-full object-cover" src="img/cars/testBMW.png" />
        </div>
        <div class="w-2/3 pl-4 flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-bold">Another Car</h2>
                <p class="text-gray-600 text-sm">1.6 л/150 л.с./Бензин</p>
                <p class="text-gray-600 text-sm">Механика</p>
                <p class="text-gray-600 text-sm">Седан</p>
                <div class="flex items-center mt-2">
                    <span class="text-green-600 text-lg font-bold">1 200 000 ₽</span>
                </div>
                <div class="flex items-center mt-2">
                    <span class="text-gray-600 text-sm">2018</span>
                    <span class="ml-4 text-gray-600 text-sm">150 000 км</span>
                </div>
                <div class="flex items-center mt-2">
                    <span class="text-gray-600 text-sm">Передний</span>
                    <span class="ml-4 text-gray-600 text-sm">Черный</span>
                </div>
            </div>
        </div>
    </a>

    <!-- Добавьте больше блоков по мере необходимости -->
</div>
</main>
<?php
include 'template/footer.php'
?>
</body>
</html>