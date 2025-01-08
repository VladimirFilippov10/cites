<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <script src="js/carFotoSelect.js"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php
        include 'template\header.php';
    ?>
 <div class="max-w-5xl mx-auto p-4 bg-white shadow-md">
   <div class="flex justify-between items-center">
    <div>
     <h1 class="text-2xl font-bold">
      Haval Jolion, 2024
     </h1>
    </div>
    <div class="text-3xl font-bold text-gray-800">
     2 399 000 ₽
    </div>
   </div>
   <div class="mt-4 flex">
    <div class="w-1/3">
     <h2 class="text-xl font-semibold mb-2">
      Характеристики
     </h2>
     <ul class="space-y-2 text-gray-700">
      <li class="flex items-center space-x-2">
       <i class="fas fa-warehouse">
       </i>
       <span>
        В наличии
       </span>
      </li>
      <li class="flex items-center space-x-2">
       <i class="fas fa-sync-alt">
       </i>
       <span>
        Рестайлинг
       </span>
      </li>
      <li class="flex items-center space-x-2">
       <i class="fas fa-calendar-alt">
       </i>
       <span>
        2024
       </span>
      </li>
      <li class="flex items-center space-x-2">
       <i class="fas fa-car">
       </i>
       <span>
        Внедорожник 5 дв.
       </span>
      </li>
      <li class="flex items-center space-x-2">
       <i class="fas fa-cogs">
       </i>
       <span>
        Elite
       </span>
      </li>
      <li class="flex items-center space-x-2">
       <i class="fas fa-tachometer-alt">
       </i>
       <span>
        1.5 л / 143 л.с. / бензин
       </span>
      </li>
      <li class="flex items-center space-x-2">
       <i class="fas fa-cogs">
       </i>
       <span>
        Роботизированная
       </span>
      </li>
      <li class="flex items-center space-x-2">
       <i class="fas fa-road">
       </i>
       <span>
        Передний
       </span>
      </li>
      <li class="flex items-center space-x-2">
       <i class="fas fa-tint">
       </i>
       <span>
        Голубой
       </span>
      </li>
      <li class="flex items-center space-x-2">
       <i class="fas fa-id-card">
       </i>
       <span>
        XZG**************
       </span>
      </li>
     </ul>
     <button class="mt-4 bg-gray-200 text-gray-700 px-4 py-2 rounded">
      Характеристики модели
     </button>
    </div>
    <div class="w-2/3 pl-4">
     <div class="carousel">
      <img alt="Front view of Haval Jolion 2024 model in blue color" class="w-full h-auto rounded active" height="400" src="https://storage.googleapis.com/a1aa/image/yQOsUOsMBMqRLpjv3fyIkNxzA9lMJjDWNVCXtStO1cEbkM9JA.jpg" width="600"/>
      <img alt="Side view of Haval Jolion 2024 model in blue color" class="w-full h-auto rounded" height="400" src="https://storage.googleapis.com/a1aa/image/F6EIXdyfflnCv0N1VfOe0ajzY8fgah0ukPC0Wt5garIIHJTfE.jpg" width="600"/>
      <img alt="Rear view of Haval Jolion 2024 model in blue color" class="w-full h-auto rounded" height="400" src="https://storage.googleapis.com/a1aa/image/SJONl12dXC4CNtpVbkFfoMqJkLfHTDbwpeuALgI1ykb4Ry0nA.jpg" width="600"/>
      <img alt="Interior view of Haval Jolion 2024 model" class="w-full h-auto rounded" height="400" src="https://storage.googleapis.com/a1aa/image/S7NUiLgeImR3AS0qpfQAHejzz9p7dqCOPS4XxhbeOftFHJTfE.jpg" width="600"/>
      <img alt="Dashboard view of Haval Jolion 2024 model" class="w-full h-auto rounded" height="400" src="https://storage.googleapis.com/a1aa/image/FPKUeH78wQ3obCLcuCfKwS9t1tXkGCm5FEbgl2k2q5x7IZ6TA.jpg" width="600"/>
      <img alt="Rear view of Haval Jolion 2024 model in blue color" class="w-full h-auto rounded" height="400" src="https://storage.googleapis.com/a1aa/image/SJONl12dXC4CNtpVbkFfoMqJkLfHTDbwpeuALgI1ykb4Ry0nA.jpg" width="600"/>
      <img alt="Interior view of Haval Jolion 2024 model" class="w-full h-auto rounded" height="400" src="https://storage.googleapis.com/a1aa/image/S7NUiLgeImR3AS0qpfQAHejzz9p7dqCOPS4XxhbeOftFHJTfE.jpg" width="600"/>
      <img alt="Dashboard view of Haval Jolion 2024 model" class="w-full h-auto rounded" height="400" src="https://storage.googleapis.com/a1aa/image/FPKUeH78wQ3obCLcuCfKwS9t1tXkGCm5FEbgl2k2q5x7IZ6TA.jpg" width="600"/>
     </div>
     <div class="flex justify-between mt-2">
      <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded" id="prev">
       <i class="fas fa-chevron-left">
       </i>
      </button>
      <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded" id="next">
       <i class="fas fa-chevron-right">
       </i>
      </button>
     </div>
     <div class="grid grid-cols-5 gap-2 mt-4 thumbnail justify-center">
      <img alt="Front view of Haval Jolion 2024 model in blue color" class="w-full h-auto rounded" height="100" src="https://storage.googleapis.com/a1aa/image/yQOsUOsMBMqRLpjv3fyIkNxzA9lMJjDWNVCXtStO1cEbkM9JA.jpg" width="150"/>
      <img alt="Side view of Haval Jolion 2024 model in blue color" class="w-full h-auto rounded" height="100" src="https://storage.googleapis.com/a1aa/image/F6EIXdyfflnCv0N1VfOe0ajzY8fgah0ukPC0Wt5garIIHJTfE.jpg" width="150"/>
      <img alt="Rear view of Haval Jolion 2024 model in blue color" class="w-full h-auto rounded" height="100" src="https://storage.googleapis.com/a1aa/image/SJONl12dXC4CNtpVbkFfoMqJkLfHTDbwpeuALgI1ykb4Ry0nA.jpg" width="150"/>
      <img alt="Interior view of Haval Jolion 2024 model" class="w-full h-auto rounded" height="100" src="https://storage.googleapis.com/a1aa/image/S7NUiLgeImR3AS0qpfQAHejzz9p7dqCOPS4XxhbeOftFHJTfE.jpg" width="150"/>
      <img alt="Dashboard view of Haval Jolion 2024 model" class="w-full h-auto rounded" height="100" src="https://storage.googleapis.com/a1aa/image/FPKUeH78wQ3obCLcuCfKwS9t1tXkGCm5FEbgl2k2q5x7IZ6TA.jpg" width="150"/>
      <img alt="Rear view of Haval Jolion 2024 model in blue color" class="w-full h-auto rounded" height="100" src="https://storage.googleapis.com/a1aa/image/SJONl12dXC4CNtpVbkFfoMqJkLfHTDbwpeuALgI1ykb4Ry0nA.jpg" width="150"/>
      <img alt="Interior view of Haval Jolion 2024 model" class="w-full h-auto rounded" height="100" src="https://storage.googleapis.com/a1aa/image/S7NUiLgeImR3AS0qpfQAHejzz9p7dqCOPS4XxhbeOftFHJTfE.jpg" width="150"/>
      <img alt="Dashboard view of Haval Jolion 2024 model" class="w-full h-auto rounded" height="100" src="https://storage.googleapis.com/a1aa/image/FPKUeH78wQ3obCLcuCfKwS9t1tXkGCm5FEbgl2k2q5x7IZ6TA.jpg" width="150"/>
     </div>
     <div class="mt-4">
      <h2 class="text-xl font-semibold mb-2">
       Описание авто
      </h2>
      <p class="text-gray-700">
       Новый Haval Jolion 2024 года выпуска - это современный внедорожник с передним приводом, оснащенный мощным 1.5-литровым бензиновым двигателем мощностью 143 л.с. Автомобиль выполнен в голубом цвете и имеет роботизированную коробку передач. Это идеальный выбор для тех, кто ценит комфорт и надежность.
      </p>
     </div>
     <div class="mt-4">
      <h2 class="text-xl font-semibold mb-2">
       Описание комплектации
      </h2>
      <p class="text-gray-700">
       Комплектация Elite включает в себя все необходимые опции для комфортного и безопасного вождения. В нее входят современные системы безопасности, качественная аудиосистема, климат-контроль, а также множество других полезных функций, которые сделают каждую поездку приятной и удобной.
      </p>
      <h3 class="text-lg font-semibold mt-2">
       В комплектацию входит:
      </h3>
      <ul class="list-disc list-inside text-gray-700">
       <li>
        Современные системы безопасности (ABS, ESP, подушки безопасности)
       </li>
       <li>
        Качественная аудиосистема с поддержкой Bluetooth и USB
       </li>
       <li>
        Двухзонный климат-контроль
       </li>
       <li>
        Кожаный салон
       </li>
       <li>
        Электропривод сидений
       </li>
       <li>
        Подогрев передних и задних сидений
       </li>
       <li>
        Мультимедийная система с сенсорным экраном
       </li>
       <li>
        Камера заднего вида
       </li>
       <li>
        Парктроники
       </li>
       <li>
        Легкосплавные диски
       </li>
      </ul>
      </div>
     <div class="mt-4 flex items-center space-x-4">
      <a class="bg-blue-500 text-white px-4 py-2 rounded" href="TG:+71234567890">
       Связаться в Telergam
      </a>
      <div class="text-lg font-semibold text-gray-700">
       Телефон для связи: +7 (123) 456-78-90
      </div>
      </div>
    </div>
   </div>
  </div>
</body>
<?php
include 'template/footer.php'
?>
</html>
