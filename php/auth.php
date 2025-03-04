<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!function_exists('checkAuth')) {
    function checkAuth() { 


    if (!isset($_SESSION['user_id'])) {
        header("Location: auto.php"); // Перенаправление на страницу авторизации
        exit();
    }
}
}
?>
