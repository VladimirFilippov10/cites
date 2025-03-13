<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php
        include 'template/header.php';
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
            <?php
            // SQL-запрос для получения трех самых дорогих автомобилей
            include 'php/dbconnect.php'; // Подключение к базе данных
            $query = "SELECT car.*, model.model_name, brand.brand_name FROM car 
                      JOIN model ON car.model_id = model.model_id
                      JOIN brand ON model.brand_id = brand.brand_id
                      ORDER BY car.car_price DESC LIMIT 3";
            $result = $conn->query($query);
            while ($car = $result->fetch_assoc()):
            ?>
            <a href="carDetails.php?id=<?php echo $car['car_id']; ?>" class="flex w-full bg-gray-200 h-350 rounded-lg overflow-hidden shadow-lg">
                <div class="w-1/3">
                    <img alt="<?php echo $car['car_win_code']; ?>" class="h-full w-full object-cover" src="img/cars/<?php echo $car['car_id']; ?>_1.png" />
                </div>
                <div class="w-2/3 pl-4 flex flex-col justify-between">
                    <div>
                        <h2 class="text-2xl font-bold"><?php echo $car['brand_name'] . ' ' . $car['model_name']; ?></h2>
                        <p class="text-gray-600 text-base"><?php echo $car['car_volume']; ?> л/<?php echo $car['car_power']; ?> л.с./<?php echo $car['car_type_oil']; ?></p>
                        <div class="flex items-center mt-2">
                            <span class="text-green-600 text-xl font-bold"><?php echo number_format($car['car_price'], 0, ',', ' '); ?> ₽</span>
                        </div>
                        <div class="flex items-center mt-2">
                            <span class="text-gray-600 text-base"><?php echo $car['car_year_made']; ?></span>
                            <span class="ml-4 text-gray-600 text-base"><?php echo number_format($car['car_mileage'], 0, ',', ' '); ?> км</span>
                        </div>
                    </div>
                </div>
            </a>
            <?php endwhile; ?>
            <div class="text-center mt-4">
                <a href="cars.php" class="text-blue-500 font-bold text-xl">Посмотрите и другие наши автомобили</a>
            </div>
        </div>
    </div>
    <?php
        include 'template/footer.php';
    ?>
</body>
</html>
