<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты</title>
    <script src="js/messageRansomCars.js"></script>
</head>
<body class="bg-gray-100 font-roboto">
<?php
        session_start();
        include 'template/header.php';
        
        // Проверка авторизации и подключение меню
        if (isset($_SESSION['user_id'])) {
            include 'template/nav_employees.php'; // Подключение навигации для аутентифицированных пользователей
        }
        include 'php/dbconnect.php'; // Подключение к базе данных

    ?>
  <main class="container mx-auto p-4">
   <h2 class="text-3xl font-bold text-center my-8">
    Контакты
   </h2>
   <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div>
     <h3 class="text-2xl font-semibold mb-4">
      Наши контакты
     </h3>
     <p class="mb-2">
      <i class="fas fa-map-marker-alt mr-2">
      </i>
      Адрес: ул. Новосёлов, д. 1, с. Благословенка, Россия
     </p>
     <p class="mb-2">
      <i class="fas fa-phone-alt mr-2">
      </i>
      Телефон: +7 (123) 456-78-90
     </p>
     <p class="mb-2">
      <i class="fas fa-envelope mr-2">
      </i>
      Email: info@autosalon.ru
     </p>
     <p class="mb-2">
      <i class="fas fa-clock mr-2">
      </i>
      Часы работы: Пн-Пт 9:00 - 18:00
     </p>
    </div>
    <div>
     <h3 class="text-2xl font-semibold mb-4">
      Наше местоположение
     </h3>
     <iframe allowfullscreen="" class="w-full h-64 rounded-md shadow-md" loading="lazy" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2244.284086162073!2d37.6173!3d55.7558!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNTXCsDQ1JzIwLjgiTiAzN8KwMzcnMDMuOCJF!5e0!3m2!1sen!2sru!4v1633022821234!5m2!1sen!2sru">
     </iframe>
    </div>
   </div>
  </main>

<?php
include 'template/footer.php'
?>
</body>
</html>
