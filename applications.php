<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявки на выкуп</title>
    <script src="js/messageRansomCars.js"></script>
</head>
<body class="bg-gray-100 font-roboto">
    <?php
        include 'template\header.php';
        include 'template\nav_employees.php';
    ?>
    <body class="bg-gray-100 font-roboto">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Заявки на выкуп авто</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">ФИО заявителя</th>
                        <th class="py-2 px-4 border-b">Номер телефона</th>
                        <th class="py-2 px-4 border-b">Модель автомобиля</th>
                        <th class="py-2 px-4 border-b">Дата подачи</th>
                        <th class="py-2 px-4 border-b">Ответственный</th>
                        <th class="py-2 px-4 border-b">Время осмотра</th>
                        <th class="py-2 px-4 border-b">Место осмотра</th>
                        <th class="py-2 px-4 border-b">Статус заявки</th>
                        <th class="py-2 px-4 border-b">Закрыта</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-2 px-4 border-b">Иванов Иван Иванович</td>
                        <td class="py-2 px-4 border-b">+7 123 456 78 90</td>
                        <td class="py-2 px-4 border-b">Toyota Camry</td>
                        <td class="py-2 px-4 border-b">2023-10-01</td>
                        <td class="py-2 px-4 border-b">
                            <select class="border rounded p-2">
                                <option>Сотрудник 1</option>
                                <option>Сотрудник 2</option>
                                <option>Сотрудник 3</option>
                            </select>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <input type="time" class="border rounded p-2">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <input type="text" class="border rounded p-2" placeholder="Введите место осмотра">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <select class="border rounded p-2">
                                <option>Открыта</option>
                                <option>Специалист на осмотре</option>
                                <option>Принятие решения</option>
                                <option>Автомобиль готов к выкупу</option>
                                <option>Отказ</option>
                            </select>
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-green-600">
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">Петров Петр Петрович</td>
                        <td class="py-2 px-4 border-b">+7 987 654 32 10</td>
                        <td class="py-2 px-4 border-b">Honda Accord</td>
                        <td class="py-2 px-4 border-b">2023-10-02</td>
                        <td class="py-2 px-4 border-b">
                            <select class="border rounded p-2">
                                <option>Сотрудник 1</option>
                                <option>Сотрудник 2</option>
                                <option>Сотрудник 3</option>
                            </select>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <input type="time" class="border rounded p-2">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <input type="text" class="border rounded p-2" placeholder="Введите место осмотра">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <select class="border rounded p-2">
                                <option>Открыта</option>
                                <option>Специалист на осмотре</option>
                                <option>Принятие решения</option>
                                <option>Автомобиль готов к выкупу</option>
                                <option>Отказ</option>
                            </select>
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-green-600">
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">Сидоров Сидор Сидорович</td>
                        <td class="py-2 px-4 border-b">+7 555 666 77 88</td>
                        <td class="py-2 px-4 border-b">BMW X5</td>
                        <td class="py-2 px-4 border-b">2023-10-03</td>
                        <td class="py-2 px-4 border-b">
                            <select class="border rounded p-2">
                                <option>Сотрудник 1</option>
                                <option>Сотрудник 2</option>
                                <option>Сотрудник 3</option>
                            </select>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <input type="time" class="border rounded p-2">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <input type="text" class="border rounded p-2" placeholder="Введите место осмотра">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <select class="border rounded p-2">
                                <option>Открыта</option>
                                <option>Специалист на осмотре</option>
                                <option>Принятие решения</option>
                                <option>Автомобиль готов к выкупу</option>
                                <option>Отказ</option>
                            </select>
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-green-600">
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">Кузнецов Кузьма Кузьмич</td>
                        <td class="py-2 px-4 border-b">+7 444 333 22 11</td>
                        <td class="py-2 px-4 border-b">Mercedes-Benz C-Class</td>
                        <td class="py-2 px-4 border-b">2023-10-04</td>
                        <td class="py-2 px-4 border-b">
                            <select class="border rounded p-2">
                                <option>Сотрудник 1</option>
                                <option>Сотрудник 2</option>
                                <option>Сотрудник 3</option>
                            </select>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <input type="time" class="border rounded p-2">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <input type="text" class="border rounded p-2" placeholder="Введите место осмотра">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <select class="border rounded p-2">
                                <option>Открыта</option>
                                <option>Специалист на осмотре</option>
                                <option>Принятие решения</option>
                                <option>Автомобиль готов к выкупу</option>
                                <option>Отказ</option>
                            </select>
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-green-600">
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">Смирнов Сергей Сергеевич</td>
                        <td class="py-2 px-4 border-b">+7 222 111 00 99</td>
                        <td class="py-2 px-4 border-b">Audi A6</td>
                        <td class="py-2 px-4 border-b">2023-10-05</td>
                        <td class="py-2 px-4 border-b">
                            <select class="border rounded p-2">
                                <option>Сотрудник 1</option>
                                <option>Сотрудник 2</option>
                                <option>Сотрудник 3</option>
                            </select>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <input type="time" class="border rounded p-2">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <input type="text" class="border rounded p-2" placeholder="Введите место осмотра">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <select class="border rounded p-2">
                                <option>Открыта</option>
                                <option>Специалист на осмотре</option>
                                <option>Принятие решения</option>
                                <option>Автомобиль готов к выкупу</option>
                                <option>Отказ</option>
                            </select>
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-green-600">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
include 'template/footer.php'
?>
</body>
</html>