<nav class="bg-white shadow-md rounded-lg mb-4">
<ul class="flex p-4 justify-between w-full relative">


        <li class="group relative">
            <a href="#" class="text-gray-700 hover:text-blue-700">Автомобили</a>
            <ul class="hidden absolute bg-white shadow-md rounded-lg mt-2 z-10">
                <li><a href="viewAllCars.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Список автомобилей</a></li>
                <li><a href="newCar.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Добавить автомобиль</a></li>
            </ul>
        </li>
        <li class="group relative">
            <a href="#" class="text-gray-700 hover:text-blue-700">Клиенты</a>
            <ul class="hidden absolute bg-white shadow-md rounded-lg mt-2 z-10">
                <li><a href="viewAllClients.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Список клиентов</a></li>
                <li><a href="newClient.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Добавить клиента</a></li>
            </ul>
        </li>
        <li><a href="viewAllRedemptionRequests.php" class="text-blue-500 hover:text-blue-700">Заявки</a></li>
        <li><a href="#" class="text-gray-700 hover:text-blue-700">Выкупы</a></li>
        <li><a href="#" class="text-gray-700 hover:text-blue-700">Продажи</a></li>
        <li><a href="#" class="text-gray-700 hover:text-blue-700">Склад</a></li>
        <li><a href="viewAllEmployees.php" class="text-gray-700 hover:text-blue-700">Сотрудники</a></li>
        <li><a href="addBrandsAndModels.php" class="text-gray-700 hover:text-blue-700">Марки и модели</a></li>
        <div class="flex items-center">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="mr-4">Привет, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Гость'; ?></span>
                <a href="php/logout.php" class="text-red-500 hover:text-red-700">Выход</a>
            <?php endif; ?>
        </div>
    </ul>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Находим все элементы с классом "group"
    const dropdowns = document.querySelectorAll('.group');

    // Для каждого элемента добавляем обработчики событий
    dropdowns.forEach(dropdown => {
        const menu = dropdown.querySelector('ul'); // Находим выпадающий список

        // Показываем меню при наведении на родительский элемент
        dropdown.addEventListener('mouseenter', () => {
            menu.classList.remove('hidden');
        });

        // Скрываем меню при уходе курсора с родительского элемента или меню
        dropdown.addEventListener('mouseleave', () => {
            // Проверяем, находится ли курсор внутри меню
            setTimeout(() => {
                if (!dropdown.matches(':hover') && !menu.matches(':hover')) {
                    menu.classList.add('hidden');
                }
            }, 100); // Небольшая задержка для плавности
        });

        // Скрываем меню при уходе курсора с самого меню
        menu.addEventListener('mouseleave', () => {
            menu.classList.add('hidden');
        });
    });
});
    </script>
