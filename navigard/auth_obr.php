<?php
session_start();
include 'inc/db.php';
global $connect;

// Получаем и очищаем входные данные
$log = trim(htmlspecialchars(htmlentities($_POST['login'])));
$email = trim($_POST['email']);
$pass = trim(htmlspecialchars(htmlentities($_POST['pass'])));

// Проверяем наличие логина и пароля
if (!empty($log) && !empty($pass)) {
    $us = $connect->query("SELECT * FROM `navigard_user` WHERE `name` = '" . $log . "'");

    if ($us->num_rows != 0) {
        $user = $us->fetch_array(MYSQLI_ASSOC);
        
        // Хэшируем введенный пароль
        $pass_256 = hash('sha256', $pass);

        if ($pass_256 == $user['pass']) {
            // Устанавливаем куки авторизации
            setcookie('user', $log, time() + 60 * 60 * 24 * 365, '/');
            setcookie('email', $email, time() + 60 * 60 * 24 * 365, '/');
            setcookie('pass', $pass_256, time() + 60 * 60 * 24 * 365, '/');

            echo '<meta http-equiv="refresh" content="0;URL=index.php">';
        } else {
            echo '<meta http-equiv="refresh" content="0;URL=auth?err=Пароль указан неверно">';
        }
        exit();
    } else {
        echo '<meta http-equiv="refresh" content="0;URL=auth.php?err=Пользователь не найден">';
        exit();
    }
} else {
    echo '<meta http-equiv="refresh" content="0;URL=auth.php">';
    exit();
}

// Обработка выхода из системы
if (isset($_GET['off'])) {
    session_start();
    setcookie('user', '', 1);
    setcookie('pass', '', 1);
    session_destroy();
    session_unset();
    
    echo '<meta http-equiv="refresh" content="0;URL=auth.php">';
    exit();
}

include 'inc/foot.php';