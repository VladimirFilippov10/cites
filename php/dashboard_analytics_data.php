<?php
include 'dbconnect.php';

$period = isset($_GET['period']) ? $_GET['period'] : 'all';

switch ($period) {
    case 'today':
        $interval = 'today';
        break;
    case 'week':
        $interval = '7 DAY';
        break;
    case 'month':
        $interval = '1 MONTH';
        break;
    case 'halfyear':
        $interval = '6 MONTH';
        break;
    case 'all':
    default:
        $interval = null;
        break;
}

// Helper function to get date format for grouping
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

// Prepare redemption query
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

// Prepare sales query
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

// Fetch redemption data
$redemptionResult = $conn->query($redemptionQuery);
$redemptions = [];
while ($row = $redemptionResult->fetch_assoc()) {
    $redemptions[$row['date']] = intval($row['count']);
}

// Fetch sales data
$salesResult = $conn->query($salesQuery);
$sales = [];
while ($row = $salesResult->fetch_assoc()) {
    $sales[$row['date']] = intval($row['count']);
}

// Merge dates from both datasets
$allDates = array_unique(array_merge(array_keys($redemptions), array_keys($sales)));
sort($allDates);

// Prepare data arrays for output
$redemptionCounts = [];
$salesCounts = [];

foreach ($allDates as $date) {
    $redemptionCounts[] = isset($redemptions[$date]) ? $redemptions[$date] : 0;
    $salesCounts[] = isset($sales[$date]) ? $sales[$date] : 0;
}

header('Content-Type: application/json');
echo json_encode([
    'dates' => $allDates,
    'redemptions' => [
        'dates' => $allDates,
        'counts' => $redemptionCounts
    ],
    'sales' => [
        'dates' => $allDates,
        'counts' => $salesCounts
    ]
]);
?>
