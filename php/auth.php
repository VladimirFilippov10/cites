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

        // Проверка таймаута сессии (10 минут)
        if (isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > 600) { // 600 секунд = 10 минут
                // Таймаут сессии, уничтожаем сессию и перенаправляем на авторизацию
                session_unset();
                session_destroy();
                header("Location: authorization.php");
                exit();
            }
        }
        // Обновляем время последней активности
        $_SESSION['last_activity'] = time();

        // Обновление employye_last_activity для текущего пользователя
        if (isset($_SESSION['user_id'])) {
            include 'dbconnect.php';
            $employee_id = intval($_SESSION['user_id']);
            $update_query = "UPDATE employee SET employye_last_activity = NOW() WHERE employee_id = $employee_id";
            $conn->query($update_query);
        }
    }
}
?>
