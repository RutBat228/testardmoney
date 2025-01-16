<?php
session_start();
include('inc/db.php');

// Функция для вывода кнопки
function button($var) {
    echo '<div class="d-grid gap-2">';
    echo "$var";
    echo '</div>';
}

// Функция для вывода уведомлений
function alrt($text, $why, $tim) {
    ?>
    <script>
        setTimeout(function () {
            $('#hidenahoy').fadeOut();
        }, <?=$tim?>000)
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <div id="hidenahoy" role="alert">
        <div class="alert alert-<?= $why ?>">
            <?= $text ?>
        </div>
    </div>
    <?php
}

// Функции для безопасного вывода строк
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function h($string) {
    return htmlentities($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Функция для редиректа с анимацией
function redirect($url) {
    $url = htmlentities($url);
    echo '<div class="d-flex justify-content-center" style="padding: 25% 25%;height: 70%;">';
    echo '<div class="loader"></div>';
    echo '</div>';
    echo '<meta http-equiv="refresh" content="0;URL=' . "$url" . '">';
}

// Функция для моментального редиректа
function red_index($url) {
    $url = htmlentities($url);
    echo '<meta http-equiv="refresh" content="0;URL=' . "$url" . '">';
}

// Функция для редиректа с задержкой
function redir($url, $tim) {
    $url = htmlentities($url);
    $tim = htmlentities($tim);
    ?>
    <meta http-equiv="refresh" content="<?= $tim ?>;URL=<?= $url ?>">
    <?php
}

// Функция для отображения прелоадера
function preloader() {
    ?>
    <div id="p_prldr">
        <div class="contpre">
            <br>
            <img src="/navigard/img/logo.png" alt="Логотип загрузки">
            <br><br><br>
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status"></div>
            </div>
        </div>
    </div>
    <?php
}

$user = htmlentities($_COOKIE['user']);
global $connect;
$user = $connect->query("SELECT * FROM `" . TABLE_PREFIX . "user` WHERE `name` = '" . $user . "'");
if ($user->num_rows != 0)
    $usr = $user->fetch_array(MYSQLI_ASSOC);

// Функция проверки авторизации
function AutorizeProtect() {
    if (checkAccess() === false) {
        ?>
        <script type="text/javascript">
            document.location.replace("/navigard/auth.php");
        </script>
        <?php
        exit;
    }
}

// Функция проверки доступа пользователя
function checkAccess() {
    global $connect;
    $name = $_COOKIE['user'] ?? "TestUser123";
    $pass = $_COOKIE['pass'] ?? "TestPass123";
    
    $user = $connect->query("SELECT * FROM `" . TABLE_PREFIX . "user` WHERE `name` = '" . $name . "' and `pass` = '" . $pass . "' and `reger` = 1");
    return ($user->num_rows != 0);
}

// Функция вывода выпадающего списка
function out_sel($val1, $val2, $val3) {
    global $connect;
    $val1 = htmlentities($val1);
    $val2 = htmlentities($val2);
    $color = $val3 == "Регион" ? "text-danger" : "text-muted";
    
    $results = $connect->query("SELECT * FROM " . TABLE_PREFIX . "adress WHERE adress LIKE '$val2'");
    while ($row = $results->fetch_object()) {
        echo "<small class='form-text $color'>$val3</small><select name='$val1' class='form-select mr-sm-2'>";
        $krish = $connect->query("SELECT * FROM " . TABLE_PREFIX . "$val1");
        while ($krisha = $krish->fetch_object()) {
            $sel_krisha = ($row->$val1 == $krisha->name) ? "selected" : "";
            echo "<option $sel_krisha value='$krisha->name'>$krisha->name</option>";
        }
        echo '</select>';
    }
}

// Функция вывода полей ввода
function out_in($val1, $val2, $val3) {
    global $connect;
    $val1 = htmlentities($val1);
    $val2 = htmlentities($val2);
    $val3 = htmlentities($val3);
    
    $results = $connect->query("SELECT * FROM " . TABLE_PREFIX . "adress WHERE adress LIKE '$val2'");
    while ($row = $results->fetch_object()) {
        if($val1 == 'phone') {
            ?>
            <small class="form-text text-muted"><?= $val3 ?></small>
            <input name="<?= $val1 ?>" type="text" class="form-control bfh-phone" data-format="+7(ddd)ddd-dd-dd" value="<?= $row->$val1 ?>"
            <?php
        } else {
            ?>
            <small class="form-text text-muted"><?= $val3 ?></small>
            <input name="<?= $val1 ?>" type="text" class="form-control" value="<?= $row->$val1 ?>"
            <?php
        }
        $placeholder = empty($row->$val1) ? $val3 : $row->$val1;
        ?>
        placeholder="<?= $placeholder ?>"
        <?= ($val1 == "adress") ? 'style="display: -webkit-inline-box;width: 91%;">' : '>' ?>
        <?php
    }
}