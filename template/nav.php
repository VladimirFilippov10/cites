<nav>
<div class="container mx-auto flex justify-between items-center py-4 px-6">
     <ul class="flex space-x-6">
      <li>
       <a class="hover:text-gray-300" href="index.php">
        Главная
       </a>
      </li>
      <li>
       <a class="hover:text-gray-300" href="cars.php">
        Автомобили
       </a>
      </li>
      <li>
       <a class="hover:text-gray-300" href="ransomCars.php">
        Выкуп автомобилей
       </a>
      </li>
      <li>
       <a class="hover:text-gray-300" href="aboutUs.php">
        О нас
       </a>
      </li>
      <li>
       <a class="hover:text-gray-300" href="contacts.php">
        Контакты
       </a>
      </li>
      <li>
       <a class="hover:text-gray-300" href="offers.php">
        Вакансии
       </a>
      </li>
      <!-- Links for authenticated users will be replaced with nav_employees.php -->
      <?php if (!isset($_SESSION['user_id'])): ?>
        <li>
         <a class="hover:text-gray-300" href="auto.php">
          Вход
         </a>
        </li>
        <li>
         <a class="hover:text-gray-300" href="registr.php">
          Регистрация
         </a>
        </li>
      <?php endif; ?>
     </ul>
</div>
</nav>
