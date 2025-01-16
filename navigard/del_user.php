<?php
include "inc/head.php";
AutorizeProtect();
global $connect;
global $usr;

// Получаем ID пользователя для удаления
$id = e(h($_GET['id']));

// Получаем данные пользователя
$user123 = $connect->query("SELECT * FROM `navigard_user` WHERE `id` = '" . $id . "'");
if ($user123->num_rows != 0) {
    $usr_del = $user123->fetch_array(MYSQLI_ASSOC);
} else {
    $usr_del = "";
}

// Формируем данные для лога
$email_del = $usr_del['email'];
$fio_del = $usr_del['fio']; 
$date = date("d.m.Y H:i:s");
$user = $usr['fio'];
$log = "Пользователь $user удалил пользователя $fio_del / $email_del";

// Записываем в лог
$zap = "INSERT INTO navigard_log (kogda, log) VALUES ('$date', '$log')";
if ($connect->query($zap) === false) {
    echo $connect->error;
}

// Удаляем пользователя
$sql = "DELETE FROM navigard_user WHERE id = '$id'";
if (mysqli_query($connect, $sql)) {
    red_index("adm_setting?del_ok");
    exit;
} else {
    echo "Error deleting record: " . mysqli_error($connect);
}

mysqli_close($connect);
include 'inc/foot.php';