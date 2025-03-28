<?php
require('fpdf/fpdf.php');


// Подключение к базе данных
include 'dbconnect.php';

// Извлечение идентификатора автомобиля из URL
$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Запросы к базе данных (оставить без изменений)
$carQuery = "SELECT car.*, model.model_name, brand.brand_name FROM car 
             JOIN model ON car.model_id = model.model_id
             JOIN brand ON model.brand_id = brand.brand_id
             WHERE car.car_id = $car_id";
$carResult = $conn->query($carQuery);
if (!$carResult) die("Ошибка выполнения запроса: " . $conn->error);
$carData = $carResult->fetch_assoc();

$buybackQuery = "SELECT cb.*, cbc.client_name, e.employee_name FROM car_buyback cb
                 JOIN client cbc ON cb.car_buyback_client_id = cbc.client_id
                 JOIN employee e ON cb.car_buyback_employee = e.employee_id
                 WHERE cb.car_buyback_car_id = $car_id";
$buybackResult = $conn->query($buybackQuery);
if (!$buybackResult) die("Ошибка выполнения запроса: " . $conn->error);

$salesQuery = "SELECT cs.*, csc.client_name, e.employee_name FROM car_sales cs
               JOIN client csc ON cs.car_sales_client_id = csc.client_id
               JOIN employee e ON cs.car_sales_employee_id = e.employee_id
               WHERE cs.car_sales_car_id = $car_id";
$salesResult = $conn->query($salesQuery);
if (!$salesResult) die("Ошибка выполнения запроса: " . $conn->error);

$pdf = new FPDF('L'); // Set to landscape orientation
$pdf->AddFont('DejaVuSans', '', 'DejaVuSans.php'); // Add a font that supports Cyrillic
$pdf->SetFont('DejaVuSans', '', 12); // Set the font for Cyrillic support
$pdf->AddPage(); // Add a new page

// Основное содержимое
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1251//IGNORE', 'Отчет по автомобилю: ' . $carData['brand_name'] . ' ' . $carData['model_name']), 0, 1);
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1251//IGNORE', 'Код автомобиля: ' . $carData['car_win_code']), 0, 1);

// Секция выкупа
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1251//IGNORE', 'Записи о выкупе'), 0, 1);
while ($row = $buybackResult->fetch_assoc()) {
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1251//IGNORE', 'ID: ' . $row['car_buyback_id'] . ', Клиент: ' . $row['client_name'] 
               . ', Дата: ' . $row['car_buyback_datetime'] . ', Цена: ' . $row['car_buyback_price'] 
               . ', Сотрудник: ' . $row['employee_name']), 1, 1); // Added border
}

// Секция продаж
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1251//IGNORE', 'Записи о продаже'), 0, 1);
while ($row = $salesResult->fetch_assoc()) {
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1251//IGNORE', 'ID: ' . $row['car_sales_id'] . ', Клиент: ' . $row['client_name'] 
               . ', Дата: ' . $row['car_sales_datatime'] . ', Цена: ' . $row['car_sales_price'] 
               . ', Сотрудник: ' . $row['employee_name']), 1, 1); // Added border
}

// Вывод PDF
$pdf->Output('car_report_' . $car_id . '.pdf', 'I'); // Output the PDF to the browser
exit;
?>
