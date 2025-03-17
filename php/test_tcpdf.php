<?php
$autoloadPath = 'tcpdf/resources/autoload.php';

if (!file_exists($autoloadPath)) {
    die('Autoload file not found. Please check the path: ' . $autoloadPath);
}
require $autoloadPath;

if (class_exists('Com\Tecnick\Pdf\Tcpdf')) {
    echo "TCPDF class loaded successfully.";
    $pdf = new \Com\Tecnick\Pdf\Tcpdf();
    echo "TCPDF instance created.";
} else {
    die('TCPDF class not found. Please ensure TCPDF is installed correctly.');
}
?>
