<?php
include "inc/function.php";
AutorizeProtect();
global $connect;
global $usr;

// Получаем данные из GET параметров
$id = h($_GET['id']); 
$name = h($_GET['name']);
$region = h($_GET['region']);
$email = h($_GET['email']);
$admin = empty(h($_GET['admin'])) ? 0 : h($_GET['admin']);

if($_GET['admin'] == 'on'){
    $admin = 1;
}

// Получаем текущие данные пользователя
$results = $connect->query("SELECT * FROM navigard_user WHERE id LIKE '$id' LIMIT 1");
$this_user = $results->num_rows == 1 ? $results->fetch_array(MYSQLI_ASSOC) : '';

// Если данные не переданы, используем текущие значения
$region = empty(h($_GET['region'])) ? $this_user['region'] : h($_GET['region']);
$email = empty(h($_GET['email'])) ? $this_user['email'] : h($_GET['email']);
$name = empty(h($_GET['name'])) ? $this_user['name'] : h($_GET['name']);

if (empty($_GET['region'])) {
    echo 'Укажите регион! Это обязательный пункт';
    exit();
}

// Формируем данные для лога
$user = $usr['name'];
$date = date("d.m.Y H:i:s");
$text2 = "отредактировал пользователя - $email";
$log = "Пользователь $user $text2";

// Записываем в лог
$zap = "INSERT INTO navigard_log (kogda, log) VALUES ('$date','$log')";
if ($connect->query($zap) === false) {
    echo "Ошибка: " . $zap . "<br>" . $connect->error;
}

// Обновляем данные пользователя
$sql = "UPDATE navigard_user SET 
name = '$name',
email = '$email', 
region = '$region',
admin = '$admin'
WHERE id = '$id'";

if ($connect->query($sql) === true) {
    red_index("adm_setting.php?success&id=$email");
    exit;
} else {
    echo "Ошибка: " . $sql . "<br>" . $connect->error;
}

include 'inc/foot.php';