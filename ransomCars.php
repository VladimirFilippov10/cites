<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выкуп авто</title>
    <script src="js/messageRansomCars.js"></script>
</head>
<body class="bg-white flex flex-col min-h-screen">
    <?php
        include 'template\header.php';
    ?>
<body class="bg-gray-100 font-roboto">

<div class="modal" id="thankYouModal">
   <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2 class="text-2xl font-bold mb-4">Спасибо за заявку!</h2>
    <p>С вами свяжутся в ближайшее время.</p>
    <div class="text-center mt-4">
     <button class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="closeModal()">Закрыть</button>
    </div>
   </div>
  </div>

  <div class="container mx-auto p-4">
   <h2 class="text-2xl font-bold text-center mb-4">
    Преимущества выкупа
   </h2>
   <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
     <img alt="Иконка с изображением часов, символизирующая быстрый процесс выкупа" class="mx-auto mb-4" height="100" src="https://storage.googleapis.com/a1aa/image/0PtnsYletlQjTaxsWsO4LGc3Xo5e361V3bWzLsbJQVkVEf2nA.jpg" width="100"/>
     <h3 class="text-xl font-bold mb-2">
      Выкуп в один день
     </h3>
     <p>
      Мы выкупим ваш автомобиль в течение одного дня, без лишних задержек и ожиданий.
     </p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
     <img alt="Иконка с изображением автомобиля, символизирующая бесплатный выезд" class="mx-auto mb-4" height="100" src="https://storage.googleapis.com/a1aa/image/5jWRBAfVj6X9UCDARtf2WZcpiWVJ6aLtXlFlzutcL1RZEf2nA.jpg" width="100"/>
     <h3 class="text-xl font-bold mb-2">
      Бесплатный выезд
     </h3>
     <p>
      Наши специалисты приедут к вам бесплатно для осмотра и оценки автомобиля.
     </p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
     <img alt="Иконка с изображением диагностического оборудования, символизирующая бесплатную диагностику" class="mx-auto mb-4" height="100" src="https://storage.googleapis.com/a1aa/image/x12b15WhwA5WNFVS0pQ7H3WOFbKif2EcoGAs9qNOqMcMiv9JA.jpg" width="100"/>
     <h3 class="text-xl font-bold mb-2">
      Бесплатная диагностика
     </h3>
     <p>
      Мы проведем бесплатную диагностику вашего автомобиля для точной оценки его состояния.
     </p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
     <img alt="Иконка с изображением автомобиля с проблемами, символизирующая возможность выкупа проблемных автомобилей" class="mx-auto mb-4" height="100" src="https://storage.googleapis.com/a1aa/image/HhQsZ83EV1plPpHnOLVI9IzUGXmrlH5fIaFqNRtMzyYLiv9JA.jpg" width="100"/>
     <h3 class="text-xl font-bold mb-2">
      Выкуп проблемных автомобилей
     </h3>
     <p>
      Мы выкупаем автомобили с любыми проблемами, включая технические неисправности и юридические вопросы.
     </p>
    </div>
   </div>
   <h1 class="text-3xl font-bold text-center mb-6">
    Заявка на выкуп автомобиля
   </h1>
   <p class="text-center mb-6">
    Здесь вы можете подать заявку на выкуп вашего автомобиля или оставить заявку на предоставление вашего автомобиля на комиссию. В данном случае мы гарантируем частоту сделку с оформлением договора, продажа и передача автомобиля происходит при участии вас или вашего представителя.
   </p>
   <div class="bg-white p-6 rounded-lg shadow-lg">
    <form action="#" class="space-y-4" method="POST">
     <div>
      <label class="block text-sm font-medium text-gray-700" for="name">
       ФИО
      </label>
      <input class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="name" name="name" required="" type="text"/>
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700" for="phone">
       Номер телефона
      </label>
      <input class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="phone" name="phone" required="" type="tel"/>
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700" for="car-model">
       Модель авто
      </label>
      <input class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="car-model" name="car-model" required="" type="text"/>
     </div>
     <div class="text-center">
     <button class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" type="submit">
       Отправить заявку
      </button>
     </div>
    </form>
   </div>
  </div>
 <?php
include 'template/footer.php'
?>
</body>
</html>