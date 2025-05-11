<?php
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require('tfpdf/tfpdf.php');

// Подключение шрифта Arial с поддержкой windows-1251
// Убедитесь, что файлы Arial.php, Arial.z и Arial.ttf находятся в папке fpdf/font
include 'dbconnect.php';

// Проверка авторизации администратора
session_start();
include './auth.php';
checkAuth();
if ($_SESSION['employee_role'] != 1 && $_SESSION['employee_role'] != 3) {
    http_response_code(403);
    exit;
}

// Получение параметра периода из POST
$period = isset($_POST['period']) ? $_POST['period'] : 'all';

// Получение изображений графиков из POST
$redemptionImageBase64 = isset($_POST['redemptionImage']) ? $_POST['redemptionImage'] : null;
$salesImageBase64 = isset($_POST['salesImage']) ? $_POST['salesImage'] : null;

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

// Функция для получения описания периода
function getPeriodDescription($period) {
    $descriptions = [
        'today' => 'сегодня',
        'week' => 'за неделю',
        'month' => 'за месяц',
        'halfyear' => 'за полгода',
        'all' => 'за все время'
    ];
    return isset($descriptions[$period]) ? $descriptions[$period] : $period;
}

$periodDescription = getPeriodDescription($period);

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

$redemptionResult = $conn->query($redemptionQuery);
$redemptions = [];
while ($row = $redemptionResult->fetch_assoc()) {
    $redemptions[$row['date']] = intval($row['count']);
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

// Создание PDF

$pdf = new tFPDF('P', 'mm', 'A4');
$pdf->AddFont('dejavuserif', '', 'DejaVuSerif.php'); // Заменяем шрифт на dejavuserif для поддержки кириллицы
$pdf->SetFont('dejavuserif', '', 12);
$pdf->AddPage();


$periodFileNameMap = [
    'today' => 'текущая_дата',
    'week' => 'за_неделю',
    'month' => 'за_месяц',
    'halfyear' => 'за_полгода',
    'all' => 'за_все_время'
];

$filePeriodName = isset($periodFileNameMap[$period]) ? $periodFileNameMap[$period] : $period;

$pdf->Cell(0, 10, 'Отчет по продажам и выкупам ' . $periodDescription, 0, 1, 'C');
$pdf->Ln(5);

// Вставка графика выкупов, если есть
if ($redemptionImageBase64) {
    $redemptionImageBase64 = preg_replace('#^data:image/\w+;base64,#i', '', $redemptionImageBase64);
    $redemptionImageData = base64_decode($redemptionImageBase64);
    $redemptionImagePath = tempnam(sys_get_temp_dir(), 'redemption_chart_') . '.png';
    file_put_contents($redemptionImagePath, $redemptionImageData);
    $pdf->Image($redemptionImagePath, 10, $pdf->GetY(), 180);
    $pdf->Ln(85);
}

// Таблица выкупов
$pdf->SetFont('dejavuserif', '', 12);
$pdf->Cell(0, 10, 'Отчет по выкупам', 0, 1, 'L');
$pdf->Cell(60, 10, 'Дата', 1);
$pdf->Cell(65, 10, 'Сумма выкупов (руб.)', 1);
$pdf->Ln();

foreach ($allDates as $index => $date) {
    $pdf->Cell(60, 10, $date, 1);
    $pdf->Cell(65, 10, strval($redemptionCounts[$index]), 1);
    $pdf->Ln();
}

// Вставка графика продаж, если есть
if ($salesImageBase64) {
    $salesImageBase64 = preg_replace('#^data:image/\w+;base64,#i', '', $salesImageBase64);
    $salesImageData = base64_decode($salesImageBase64);
    $salesImagePath = tempnam(sys_get_temp_dir(), 'sales_chart_') . '.png';
    file_put_contents($salesImagePath, $salesImageData);
    $pdf->Image($salesImagePath, 10, $pdf->GetY(), 180);
    $pdf->Ln(85);
}

// Таблица продаж
$pdf->SetFont('dejavuserif', '', 12);
$pdf->Cell(0, 10, 'Отчет по продажам', 0, 1, 'L');
$pdf->Cell(60, 10, 'Дата', 1);
$pdf->Cell(65, 10, 'Сумма продаж (руб.)', 1);
$pdf->Ln();

foreach ($allDates as $index => $date) {
    $pdf->Cell(60, 10, $date, 1);
    $pdf->Cell(65, 10, strval($salesCounts[$index]), 1);
    $pdf->Ln();
}

// Удаление временных файлов изображений

if (isset($redemptionImagePath) && file_exists($redemptionImagePath)) {
    unlink($redemptionImagePath);
}
if (isset($salesImagePath) && file_exists($salesImagePath)) {
    unlink($salesImagePath);
}


ob_clean();
$pdf->Output('D', 'отчет_о_продажах_и_выкупах_' . $filePeriodName . '.pdf');
exit;
?>