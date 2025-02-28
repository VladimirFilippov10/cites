<?php
$directory = 'img/cars/'; // Путь к папке с изображениями


// Получение данных из запроса
$data = json_decode(file_get_contents('php://input'), true);
$fileNames = $data['fileNames'];

// Переименование файлов в папке
foreach ($fileNames as $index => $fileName) {
    $oldFileName = $directory . $fileName;
    $newFileName = $directory . preg_replace('/(\d+)_\d+/', '$1_' . ($index + 1), $fileName);
    
    if (file_exists($oldFileName)) {
        if (!rename($oldFileName, $newFileName)) {
            echo json_encode(['status' => 'error', 'message' => 'Не удалось переименовать файл: ' . $oldFileName]);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Файл не найден: ' . $oldFileName]);
        exit;
    }

}

echo json_encode(['status' => 'success']);
?>
