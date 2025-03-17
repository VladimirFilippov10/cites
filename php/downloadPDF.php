 <?php
require_once('tcpdf/examples/index.php'); // Include TCPDF autoload

// Подключение к базе данных
include 'dbconnect.php';

// Установка кодировки
header('Content-Type: text/html; charset=windows-1251');

// Извлечение идентификатора автомобиля из URL
$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Запрос для получения информации о выбранном автомобиле
$carQuery = "SELECT car.*, model.model_name, brand.brand_name FROM car 
             JOIN model ON car.model_id = model.model_id
             JOIN brand ON model.brand_id = brand.brand_id
             WHERE car.car_id = $car_id";
$carResult = $conn->query($carQuery);
if (!$carResult) {
    die("Ошибка выполнения запроса: " . $conn->error);
}
$carData = $carResult->fetch_assoc();

// Запрос для получения данных из таблицы car_buyback с именами клиентов и сотрудников
$buybackQuery = "SELECT cb.*, cbc.client_name, e.employee_name FROM car_buyback cb
                 JOIN client cbc ON cb.car_buyback_client_id = cbc.client_id
                 JOIN employee e ON cb.car_buyback_employee = e.employee_id
                 WHERE cb.car_buyback_car_id = $car_id";
$buybackResult = $conn->query($buybackQuery);
if (!$buybackResult) {
    die("Ошибка выполнения запроса: " . $conn->error);
}

// Запрос для получения данных из таблицы car_sales с именами клиентов и сотрудников
$salesQuery = "SELECT cs.*, csc.client_name, e.employee_name FROM car_sales cs
               JOIN client csc ON cs.car_sales_client_id = csc.client_id
               JOIN employee e ON cs.car_sales_employee_id = e.employee_id
               WHERE cs.car_sales_car_id = $car_id";
$salesResult = $conn->query($salesQuery);
if (!$salesResult) {
    die("Ошибка выполнения запроса: " . $conn->error);
}

// Создание PDF
$pdf = new \Com\Tecnick\Pdf\Tcpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Установка информации о документе
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Отчет по автомобилю');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Добавление страницы
$pdf->AddPage();

// Установка шрифта
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Отчет по автомобилю: ' . $carData['brand_name'] . ' ' . $carData['model_name'], 0, 1, 'C');
$pdf->Ln(10);

// Информация о автомобиле
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Код автомобиля: ' . $carData['car_win_code'], 0, 1);
$pdf->Ln(5);

// Записи о выкупе
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Записи о выкупе', 0, 1);
$pdf->SetFont('helvetica', '', 12);
while ($row = $buybackResult->fetch_assoc()) {
    $pdf->Cell(0, 10, 'ID: ' . $row['car_buyback_id'] . ', Клиент: ' . $row['client_name'] . ', Дата: ' . $row['car_buyback_datetime'] . ', Цена: ' . $row['car_buyback_price'] . ', Сотрудник: ' . $row['employee_name'], 0, 1);
}
$pdf->Ln(5);

// Записи о продаже
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Записи о продаже', 0, 1);
$pdf->SetFont('helvetica', '', 12);
while ($row = $salesResult->fetch_assoc()) {
    $pdf->Cell(0, 10, 'ID: ' . $row['car_sales_id'] . ', Клиент: ' . $row['client_name'] . ', Дата: ' . $row['car_sales_datatime'] . ', Цена: ' . $row['car_sales_price'] . ', Сотрудник: ' . $row['employee_name'], 0, 1);
}

// Вывод PDF
$pdf->Output('D', 'car_report_' . $car_id . '.pdf');

?>
