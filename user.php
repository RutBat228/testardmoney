<?php
include "inc/head.php";
access();
AutorizeProtect();
global $connect;
global $usr;
?>

<head>
    <title>Страница пользователя</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user.css">
</head>
<?
// Константы для конфигурации
const CURRENT_YEAR = 2025;
const DAYS_BEFORE_ZP_SHOW = 7;
const USER_IMAGES = [
    'RutBat' => 'user_RutBat.png',
    'Игорь' => 'user_Игорь.png',
    'kovalev' => 'user_Вова.png',
    'grisnevskijp@gmail.com' => 'user_Паша.png',
    'Юра' => 'user_Юра.png'
];



// Оптимизация вывода изображения пользователя
function getUserImage(string $username): string {
    return isset(USER_IMAGES[$username])
        ? "img/" . USER_IMAGES[$username]
        : "img/user_logo.webp?123";
}



$year = date('y');
if($usr['nav_position'] == 1){

    $nav_change = "down_panel dropup";
} else{
    $nav_change = "";
}

// Логика обработки даты из olduser.php
$month = date_view($_GET['date']);
$date_blyat = "$_GET[date]";
if (!isset($_GET['date'])) {
    $month = date('m');
    $year = date('y');
    $month = month_view(date('m'));
    $date = date("Y-m-d");
    $date_blyat = substr($date, 0, -3);
}
$year = date('y');
$year_cur = date('Y');
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark <?=$nav_change?> nav-custom">
    <div class="container-fluid navbar-container">
        <a class="navbar-brand" href="#"></a>
        <div class="navbar-collapse" id="navbarNavDarkDropdown">
            <ul class="navbar-nav navbar-nav-custom">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $month ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="navbarDarkDropdownMenuLink" style="position: absolute;">
                        <li><a class="dropdown-item" href="?date=<?= $year ?>-01">Январь</a></li>
                        <li><a class="dropdown-item" href="?date=<?= $year ?>-02">Февраль</a></li>
                        <li><a class="dropdown-item" href="?date=<?= $year ?>-03">Март</a></li>
                        <li><a class="dropdown-item" href="?date=<?= $year ?>-04">Апрель</a></li>
                        <li><a class="dropdown-item" href="?date=<?= $year ?>-05">Май</a></li>
                        <li><a class="dropdown-item" href="?date=<?= $year ?>-06">Июнь</a></li>
                        <li><a class="dropdown-item" href="?date=<?= $year ?>-07">Июль</a></li>
                        <li><a class="dropdown-item" href="?date=<?= $year ?>-08">Август</a></li>
                        <li><a class="dropdown-item" href="?date=<?= $year ?>-09">Сентябрь</a></li>
                        <li><a class="dropdown-item" href="?date=<?= $year ?>-10">Октябрь</a></li>
                        <li><a class="dropdown-item" href="?date=<?= $year ?>-11">Ноябрь</a></li>
                        <li><a class="dropdown-item" href="?date=<?= $year ?>-12">Декабрь</a></li>
                    </ul>
                </li>
                <?php
                if (!empty(htmlentities($_COOKIE['user']))) {
                ?>
                    <ul style="float: right;">
                        <li>
                            <a href="user.php">
                                <img src="/img/home.png" alt="Домой" style="width: 40px;padding-bottom: 7px;">
                            </a>
                            <a href="search_montaj.php">
                                <img src="/img/search.png" alt="Поиск" style="width: 40px;padding-bottom: 7px;">
                </a>
                        </li>
                    </ul>
            </ul>
        <?php
                } ?>
        </div>
    </div>
</nav>
<!-- <div class="d-grid gap-2">
        <a class="btn btn-danger" href="ins.php">Инструкция</a>
    </div> -->
<ul class="list-group">
    <li class="list-group-item" style="padding: 0; border: none;">

        <?
        $imagePath = isset(USER_IMAGES[$usr['name']]) 
            ? "img/" . USER_IMAGES[$usr['name']]
            : "img/user_logo.webp?123";

        echo '<img class="mx-auto d-block w-100" src="' . $imagePath . '">';

        echo '<div class="alert alert-success text-center" role="alert">
            <b><a href="itogi.php" style="color:black;text-decoration:none">ИТОГИ ГОДА</a></b>
        </div>';
        ?>




        <?
        if ($usr['admin'] == "1" || $usr['name'] == "RutBat") {
        ?>
            <table class="table user-table">
                <thead>
                    <tr>
                        <th scope="col">Техник</th>
                        <th scope="col">Монтажи</th>
                        <th scope="col">Сумма денег</th>
                    </tr>
                </thead>
                <tbody class="user-table-gradient">
                    <?


                    $stmt = $connect->prepare("SELECT * FROM `user` WHERE `region` = ? ORDER BY `id` DESC");
                    $stmt->bind_param('s', $usr['region']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($tech = $result->fetch_assoc()) {


                    ?>
                        <tr>
                            <td><a style="color: black;font-weight: 700;font-size: small;" href="index.php?current_user=<?= $tech['fio'] ?>"><?= $tech['fio'] ?></a></td>
                            <td><?
                                num_montaj("$tech[fio]", "$month", $year_cur);
                                ?></td>
                            <td><?
                                summa_montaj("$tech[fio]", "$month", $year_cur);
                                ?> р.
                                <?php
                                // Получаем текущую дату
                                $currentDate = new DateTime();

                                // Получаем текущий месяц в числовом формате
                                $currentMonth = intval($currentDate->format('n')); // n - формат месяца без ведущего нуля

                                // Массив, свзывающий текстовые названия месяцев с их числовыми представлениями
                                $monthNames = [
                                    'Январь' => 1,
                                    'Февраль' => 2,
                                    'Март' => 3,
                                    'Апрель' => 4,
                                    'Май' => 5,
                                    'Июнь' => 6,
                                    'Июль' => 7,
                                    'Август' => 8,
                                    'Сентябрь' => 9,
                                    'Октябрь' => 10,
                                    'Ноябрь' => 11,
                                    'Декабрь' => 12,
                                ];

                                // Получаем числовое представление выбранного месяца (предполагается, что $month это текстовое название месяца)
                                $selectedMonth = $monthNames[$month] ?? 0; // Если месяц не найден в массиве, по умолчанию 0

                                // Получаем последний день текущего месяца
                                $lastDayOfMonth = new DateTime('last day of this month');

                                // Вычисляем разницу между текущей датой и последним днем месяца в днях
                                $daysUntilEndOfMonth = $currentDate->diff($lastDayOfMonth)->days;

                                // Если $month не является текущим месяцем и осталось менее ил равно 7 дням до конца месяца, либо $month отличается от текущего месяца, выполняем функцию prim_zp
                                if ($selectedMonth !== $currentMonth || $daysUntilEndOfMonth <= 7) {
                                    echo "<u><a style = 'color: #1ba11b;' href = 'zp.php?fio=$tech[fio]' >";
                                    prim_zp("$tech[fio]", "$month", 2024);
                                    // Вызов функции для расчёта зарплаты за год

                                    echo '</a></u>';
                                }
                                ?>


                            </td>
                        </tr>
                    <?
                    }
                    ?>
                </tbody>
            </table>
        <?
        } else {
        ?>
            <table class="table" style="margin-bottom: 0rem;">
                <thead>
                    <tr>
                        <th scope="col">Техник</th>
                        <th scope="col">Монтажи</th>
                        <th scope="col">Сумма денег</th>
                    </tr>
                </thead>
                <tbody class="td_user">
                    <tr>
                        <td><?= $usr['fio']; ?></td>
                        <td style="color:red;"><?
                                                num_montaj("$usr[fio]", "$month", 2024);
                                                ?></td>
                        <td><?
                            summa_montaj("$usr[fio]", "$month", 2024);
                            ?> р.</td>
                    </tr>
                </tbody>
            </table>
        <?
        }

        if ($usr['name'] == 'RutBat') {
            
            echo'
            <div class="alert alert-info" role="alert">
            <b><a href="gm.php">GM ПАНЕЛЬ</a></b>
            </div>'; 
        }




        // Получение текущего значения nav_position для пользователя
$user_id = $usr['id']; // Замените на реальный ID пользователя
$sql = "SELECT nav_position FROM user WHERE id = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nav_position = $row['nav_position'];

// Обработка AJAX-запроса при изменении чекбокса
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_position = $_POST['position'];
    $sql = "UPDATE user SET nav_position = ? WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("si", $new_position, $user_id);
    $stmt->execute();
    echo $new_position;
    exit;
}

$connect->close();
echo'
<div class="alert alert-danger" role="alert">
<b><a href="404.html">404 test</a></b>
</div>'; 



        // if ($usr['admin_view'] == 0) {
        ?>
























<div class="container mt-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="navToggle" <?php echo $nav_position ? 'checked' : ''; ?>>
            <label class="form-check-label" for="navToggle">Зафиксировать навбар внизу</label>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        const navToggle = $('#navToggle');
        const mainNav = $('#mainNav');
        
        function updateNavPosition(fixed) {
            const cssProps = fixed ? {
                position: 'fixed',
                left: '0',
                right: '0',
                bottom: '0',
                zIndex: '10'
            } : {};
            
            mainNav.css(cssProps);
        }

        updateNavPosition(<?= $nav_position ? 'true' : 'false' ?>);

        navToggle.on('change', function() {
            const isFixed = $(this).is(':checked');
            
            $.post(window.location.href, {
                position: isFixed ? 1 : 0
            })
            .done(response => {
                updateNavPosition(isFixed);
            })
            .fail(error => {
                console.error('Ошибка обновления позиции:', error);
                navToggle.prop('checked', !isFixed); // откат при ошибке
            });
        });
    });
    </script>













        <div class="alert alert-success" style="    padding: 0rem 25%;border-radius: 0;" role="alert">
            За прошлый год
        </div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <b><a href="user_arhiv.php" style="color:black;">Суммы монтажей</a></b>
                </div>
                <div class="col">
                    <b><a href="arhiv.php" style="color:black;">Архив монтажей</a></b>
                </div>

                <div class="col">
                    Регион: <b><?= $usr['region'] ?></b>
                </div>

            </div>
        </div>
        <?
        //}
        ?>
        
        <div class="alert alert-info" role="alert">
            Ваш логин: <b><?= $usr['name'] ?></b>
        </div>
        <div class="alert alert-success" role="alert" style="padding: 0px 20px 0px;">
            Приложение для Android <a href="ardmoney.apk" class="alert-link"><img src="img/android.png" style="width: 32px;padding-bottom: 18px;">ArdMoney</a>.
        </div>
        <div style="background: #000000cc;">
            <b><a href="/navigard">
                    <img src="img/navigard.png" style="
    width: 50%;
    padding: 10px;
"></a></b>
        </div>


        <br>
        <b>
            <div class="d-grid gap-2">
                <a href="/exit.php" class="btn btn-outline-success btn-sm">Выход</a>
            </div>
        </b>
    </li>
</ul>
</div>
<?php include 'inc/foot.php';
?>

<script>
function shouldShowPrimZp($selectedMonth, $currentMonth, $daysUntilEndOfMonth) {
    return $selectedMonth !== $currentMonth || $daysUntilEndOfMonth <= 7;
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Отложенная загрузка изображений
    const images = document.querySelectorAll('img[data-src]');
    images.forEach(img => {
        img.src = img.dataset.src;
        img.removeAttribute('data-src');
    });
});
</script>