<?php
ob_start();
require('fpdf/fpdf.php');
include 'php/dbconnect.php';

// Проверка авторизации администратора
session_start();
include 'php/auth.php';
checkAuth();
if ($_SESSION['employee_role'] != 1 && $_SESSION['employee_role'] != 3) {
    http_response_code(403);
    echo "Доступ запрещён";
    exit;
}

// Получение параметров POST
$period = isset($_POST['period']) ? $_POST['period'] : 'all';
$redemptionImage = isset($_POST['redemptionImage']) ? $_POST['redemptionImage'] : null;
$salesImage = isset($_POST['salesImage']) ? $_POST['salesImage'] : null;

// Функция для получения интервала по периоду
function getInterval($period) {
    switch ($period) {
        case 'today': return 'today';
        case 'week': return '7 DAY';
        case 'month': return '1 MONTH';
        case 'halfyear': return '6 MONTH';
        case 'all':
        default: return null;
    }
}

$interval = getInterval($period);

// Функция для формата даты группировки
function getDateFormat($interval) {
    if ($interval === '7 DAY' || $interval === '1 MONTH') {
        return '%Y-%m-%d';
    } elseif ($interval === '6 MONTH') {
        return '%Y-%m';
    } else {
        return '%Y-%m-%d';
    }
}

$dateFormat = getDateFormat($interval);

// Запросы для данных выкупов
if ($interval && $interval !== 'today') {
    $redemptionQuery = "
        SELECT DATE_FORMAT(car_buyback_datetime, '$dateFormat') as date, SUM(car_buyback_price) as count
        FROM car_buyback
        WHERE car_buyback_datetime >= DATE_SUB(NOW(), INTERVAL $interval)
        GROUP BY date
        ORDER BY date ASC
    ";
} elseif ($interval === 'today') {
    $redemptionQuery = "
        SELECT DATE_FORMAT(car_buyback_datetime, '$dateFormat') as date, SUM(car_buyback_price) as count
        FROM car_buyback
        WHERE DATE(car_buyback_datetime) = CURDATE()
        GROUP BY date
        ORDER BY date ASC
    ";
} else {
    $redemptionQuery = "
        SELECT DATE_FORMAT(car_buyback_datetime, '$dateFormat') as date, SUM(car_buyback_price) as count
        FROM car_buyback
        GROUP BY date
        ORDER BY date ASC
    ";
}

// Запросы для данных продаж
if ($interval && $interval !== 'today') {
    $salesQuery = "
        SELECT DATE_FORMAT(car_sales_datatime, '$dateFormat') as date, SUM(car_sales_price) as count
        FROM car_sales
        WHERE car_sales_datatime >= DATE_SUB(NOW(), INTERVAL $interval)
        GROUP BY date
        ORDER BY date ASC
    ";
} elseif ($interval === 'today') {
    $salesQuery = "
        SELECT DATE_FORMAT(car_sales_datatime, '$dateFormat') as date, SUM(car_sales_price) as count
        FROM car_sales
        WHERE DATE(car_sales_datatime) = CURDATE()
        GROUP BY date
        ORDER BY date ASC
    ";
} else {
    $salesQuery = "
        SELECT DATE_FORMAT(car_sales_datatime, '$dateFormat') as date, SUM(car_sales_price) as count
        FROM car_sales
        GROUP BY date
        ORDER BY date ASC
    ";
}

// Получение данных из базы
$redemptionResult = $conn->query($redemptionQuery);
$redemptions = [];
while ($row = $redemptionResult->fetch_assoc()) {
    $redemptions[$row['date']] = intval($row['count']);
}

$salesResult = $conn->query($salesQuery);
$sales = [];
while ($row = $salesResult->fetch_assoc()) {
    $sales[$row['date']] = intval($row['count']);
}

// Объединение дат
$allDates = array_unique(array_merge(array_keys($redemptions), array_keys($sales)));
sort($allDates);

// Подготовка массивов для таблицы
$redemptionCounts = [];
$salesCounts = [];
foreach ($allDates as $date) {
    $redemptionCounts[] = isset($redemptions[$date]) ? $redemptions[$date] : 0;
    $salesCounts[] = isset($sales[$date]) ? $sales[$date] : 0;
}

// Очистка буфера вывода и установка заголовков
ob_end_clean();
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="sales_buyback_report_' . $period . '.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Создание PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, "Отчет по продажам и выкупам за период: " . ucfirst($period), 0, 1, 'C');
$pdf->Ln(5);

// Вставка графиков из base64
if ($redemptionImage) {
    $redemptionImage = str_replace('data:image/png;base64,', '', $redemptionImage);
    $redemptionImage = base64_decode($redemptionImage);
    $redemptionImagePath = tempnam(sys_get_temp_dir(), 'redemption') . '.png';
    file_put_contents($redemptionImagePath, $redemptionImage);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'График выкупов', 0, 1, 'L');
    $pdf->Image($redemptionImagePath, null, null, 180);
    unlink($redemptionImagePath);
    $pdf->Ln(10);
}

if ($salesImage) {
    $salesImage = str_replace('data:image/png;base64,', '', $salesImage);
    $salesImage = base64_decode($salesImage);
    $salesImagePath = tempnam(sys_get_temp_dir(), 'sales') . '.png';
    file_put_contents($salesImagePath, $salesImage);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'График продаж', 0, 1, 'L');
    $pdf->Image($salesImagePath, null, null, 180);
    unlink($salesImagePath);
    $pdf->Ln(10);
}

// Таблица с данными
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Дата', 1);
$pdf->Cell(75, 10, 'Сумма выкупов (руб.)', 1);
$pdf->Cell(75, 10, 'Сумма продаж (руб.)', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
foreach ($allDates as $index => $date) {
    $pdf->Cell(40, 10, $date, 1);
    $pdf->Cell(75, 10, $redemptionCounts[$index], 1);
    $pdf->Cell(75, 10, $salesCounts[$index], 1);
    $pdf->Ln();
}

$pdf->Output('D', 'sales_buyback_report_' . $period . '.pdf');
exit;
?>
