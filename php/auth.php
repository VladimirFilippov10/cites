<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!function_exists('checkAuth')) {
    function checkAuth() { 


    if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
        // Восстановление сессии из куки, если она существует
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['username'] = $_COOKIE['username'];

        header("Location: auto.php"); // Перенаправление на страницу авторизации
        exit();
    } else if (isset($_COOKIE['user_id']) && !isset($_SESSION['user_id'])) {
        // Если user_id в куки, но сессия не восстановлена, перенаправляем на авторизацию
        header("Location: authorization.php");
        exit();

        exit();
    }
}
}
?>
