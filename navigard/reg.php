<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
global $connect;

// Обработка успешной регистрации
if (isset($_GET['success'])) {
    $email = $_SESSION['email'];
    $user = $_SESSION['user']; 
    $pass = $_SESSION['pass']; // Хэш по алгоритму sha256
    setcookie('user', $user, time() + 60 * 60 * 24 * 365);
    setcookie('pass', $pass, time() + 60 * 60 * 24 * 365);
    setcookie('email', $email, time() + 60 * 60 * 24 * 365);
    header('Location: /');
    exit();
}

include "inc/db.php";

// Получение и очистка входных данных
$email  = trim(htmlspecialchars(htmlentities($_GET['email'])));
$login  = trim(htmlspecialchars(htmlentities($_GET['login'])));
$pass   = trim(htmlspecialchars(htmlentities($_GET['pass'])));
$pass_256 = hash('sha256', $pass); // Хэш по алгоритму sha256

// Валидация входных данных
if (empty($login)) {
    echo '<script>alert("Ошибка! Проверьте логин. Не используйте пробелы и спец символы.");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=/auth.php?reg">';
    exit();
} elseif (empty($pass)) {
    echo '<script>alert("Ошибка! Проверьте пароль. Не используйте пробелы и спец символы.");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=/auth.php?reg">';
    exit();
} elseif (empty($email)){
    echo '<script>alert("Ошибка! Проверьте Почту");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=/auth.php?reg">';
    exit();
}

// Проверка существования пользователя
$user = $connect->query("SELECT * FROM navigard_user WHERE name = '$login'");
if ($user->num_rows >= 1) {
    echo '<script>alert("Ошибка! Логин есть в базе. Придумайте новый логин");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=/auth.php?reg">';
    exit();
}

// Логирование регистрации
$date = date("d.m.Y H:i:s");
$user = $login;
$text = 'успешно зарегистрировался <br>';
$log = "Пользователь $user $text";
$zap = "INSERT INTO navigard_log (kogda, log) VALUES ('$date', '$log')";
if ($connect->query($zap) === false) {
    echo $connect->error;
}

// Обновление данных пользователя
$sql = "UPDATE navigard_user SET name = '$login', pass = '$pass_256', reger = '1' WHERE email = '$email'";

// Создание сессии при успешной регистрации
if ($connect->query($sql) === true) {
    session_start();
    $_SESSION['user'] = $login;
    $_SESSION['pass'] = $pass_256;
    $_SESSION['email'] = $email;
    echo '<meta http-equiv="refresh" content="0;URL=/reg.php?success">';
    exit();
}

include 'inc/foot.php';