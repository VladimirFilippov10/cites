<!DOCTYPE html>
<html lang="en">
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
   <img alt="Фоновое изображение с автомобилями" class="w-full h-96 object-cover" height="400" src="https://storage.googleapis.com/a1aa/image/WWRTTa4ZIMJjMxe94r7azF1MXAxQifOoo7PhdrPCfpo5LUynA.jpg" width="1200"/>
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
   
   <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
     </a>
</body>
</html>