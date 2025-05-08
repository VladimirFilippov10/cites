<?php
require_once 'php/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

include 'php/dbconnect.php';

// Проверка авторизации администратора
session_start();
include 'php/auth.php';
checkAuth();
if ($_SESSION['employee_role'] != 1 && $_SESSION['employee_role'] != 3) {
    http_response_code(403);
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

// Формирование HTML для PDF
$html = '<h1 style="text-align:center;">Отчет по продажам и выкупам за период: ' . htmlspecialchars(ucfirst($period)) . '</h1>';

// Вставка графиков
if ($redemptionImage) {
    $html .= '<h2>График выкупов</h2>';
    $html .= '<img src="' . htmlspecialchars($redemptionImage) . '" style="width:100%; max-width:700px;"/>';
}
if ($salesImage) {
    $html .= '<h2>График продаж</h2>';
    $html .= '<img src="' . htmlspecialchars($salesImage) . '" style="width:100%; max-width:700px;"/>';
}

// Таблица с данными
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse; margin-top:20px;">';
$html .= '<thead><tr><th>Дата</th><th>Сумма выкупов (руб.)</th><th>Сумма продаж (руб.)</th></tr></thead><tbody>';
foreach ($allDates as $index => $date) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($date) . '</td>';
    $html .= '<td>' . htmlspecialchars($redemptionCounts[$index]) . '</td>';
    $html .= '<td>' . htmlspecialchars($salesCounts[$index]) . '</td>';
    $html .= '</tr>';
}
$html .= '</tbody></table>';

// Инициализация Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Вывод PDF в браузер
$dompdf->stream('sales_buyback_report_' . $period . '.pdf', ['Attachment' => true]);
exit;
?>
