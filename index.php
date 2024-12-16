<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php
        include 'template\header.php';
    ?>
     <div class="relative">
   <img alt="Фоновое изображение с автомобилями" class="w-full h-96 object-cover" height="400" src="img/dop/header.png"/>
   <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-white">
    <h1 class="text-4xl md:text-6xl font-bold">
     Добро пожаловать в наш автосалон
    </h1>
    <p class="text-xl md:text-2xl mt-4">
     Лучшие автомобили по лучшим ценам
    </p>
   </div>
  </div>
  <div class="container mx-auto py-12 px-6">
   <h2 class="text-3xl font-bold mb-8 text-center">
    Список автомобилей в продаже
   </h2>
   <div class="flex flex-col w-full p-5 space-y-5">
    <a href="car.php?id=1" class="flex w-full bg-gray-200 h-350 rounded-lg overflow-hidden shadow-lg">
        <div class="w-1/3">
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
        <div class="w-1/3">
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
  <!-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <a href="#">
    <img alt="" class="w-full h-48 object-cover" src="img/cars/testBMW.png"/>
    
    <div class="p-6">
    <h3 class="text-xl font-bold mb-2">
       BMW M5 540d
      </h3>
      <p class="text-gray-600 mb-4">
       Мощный и стильный автомобиль для любителей скорости.
      </p>
      <div class="text-lg font-bold text-blue-900">
      <p class = "sale"> 2 500 000 ₽ </p>
      </div>
     </div>
     </a> -->
</body>
<?php
include 'template/footer.php'
?>
</html>

<!--добавь цену напротив названия, добавь больше информации о комплектации, сделай чуть больше текст, а так же после описания добавь блок, где отдельно описывается комплектация, например пневмоподвеска, подушки безопасности,