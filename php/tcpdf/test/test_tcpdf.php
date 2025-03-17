<?php
require_once('../resources/autoload.php');

try {
    $pdf = new \Com\Tecnick\Pdf\Tcpdf();
    echo "TCPDF class instantiated successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
