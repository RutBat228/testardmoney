<?php
include "inc/head.php";
AutorizeProtect();
access();
animate();

?>

<head>
    <title>Список домов архив</title>

</head>
<?php
// Инициализируем переменные
$view_complete = ''; // Инициализация переменной view_complete

// Получаем текущий год и месяц
$current_year = date('Y');
$current_month = date('m');

// Используем текущий год и месяц по умолчанию
$default_year = $current_year;
$default_month = $current_month;

if (isset($_GET['date']) && preg_match('/^\d{4}-\d{2}$/', $_GET['date'])) {
    $date_current = $_GET['date'];
    list($selected_year, $selected_month) = explode('-', $date_current);
} else {
    $selected_year = $default_year;
    $selected_month = $default_month;
    $date_current = $selected_year . '-' . $selected_month;
}

$month = date_view($date_current);

// Заменяем старую логику удаления
if (isset($_GET['delete'])) {
    delete_mon($id);
}

if (isset($_GET['complete'])) {
    $view_complete = " AND `status` = '0'";
}

if (isset($_GET['id']) && $_GET['id'] == "ok") {
    alrt("Успешно удаленно", "success", "2");
}

// Добавить после инициализации переменных
if (isset($_GET['status']) && $_GET['status'] === 'success') {
    echo '<div class="alert text-center alert-success alert-dismissible fade show" role="alert" id="successAlert">
            Монтаж успешно подтвержден!
          </div>';
    
    echo '<script>
        const alert = document.getElementById("successAlert");
        const bsAlert = new bootstrap.Alert(alert);
        setTimeout(() => {
            bsAlert.close();
        }, 2000);
    </script>';
}

?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="padding: 0;">
    <div class="container-fluid" style="background: #00000070;">
        <div class="navbar-collapse d-flex justify-content-between align-items-center" id="navbarNavDarkDropdown">
            <!-- Левая часть навбара с годом и месяцем -->
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <select class="form-select form-select-sm" id="year" name="year" style="width: auto;" onchange="loadArchiveData()">
                        <?php
                        $start_year = 2022; // Начальный год
                        $current_year = (int)date('Y');
                        
                        for ($year = $start_year; $year <= max($current_year, $start_year); $year++) {
                            echo "<option value=\"$year\">$year</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="d-flex align-items-center">
                    <select class="form-select form-select-sm" id="month" name="month" style="width: auto;" onchange="loadArchiveData()">
                        <option value="01">Январь</option>
                        <option value="02">Февраль</option>
                        <option value="03">Март</option>
                        <option value="04">Апрель</option>
                        <option value="05">Май</option>
                        <option value="06">Июнь</option>
                        <option value="07">Июль</option>
                        <option value="08">Август</option>
                        <option value="09">Сентябрь</option>
                        <option value="10">Октябрь</option>
                        <option value="11">Ноябрь</option>
                        <option value="12">Декабрь</option>
                    </select>
                </div>
            </div>

            <!-- Центральная часть с чекбоксом админа -->
            <div class="d-flex align-items-center">
                <?php
                if ($usr['admin'] == 1) {
                    admin_checkbox($usr['id']);
                }
                ?>
            </div>

            <!-- Правая часть с иконками -->
            <div class="d-flex align-items-center gap-2">
                <?php if (!empty(htmlentities($_COOKIE['user']))) { ?>
                    <a href="search_montaj.php" class="text-light">
                        <img src="/img/search.png" style="width: 40px; padding-bottom: 7px;">
                    </a>
                    <a href="user.php" class="text-light">
                        <img src="/img/home.png" style="width: 40px; padding-bottom: 7px;">
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/mark.js/7.0.0/jquery.mark.min.js"></script>
<div class="input-group mt-3">
    <span class="input-group-text">Поиск</span>
    <input id="spterm" type="text" aria-label="адрес" class="form-control" oninput="liveSearch()">
</div>

<div id="archiveContent">
    <!-- Здесь будет контент -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Получаем текущий год и месяц
    let currentDate = new Date();
    let currentYear = currentDate.getFullYear();
    let currentMonth = currentDate.getMonth() + 1; // +1 так как getMonth() возвращает 0-11
    
    // Устанавливаем значения в селекты
    document.getElementById('year').value = currentYear.toString();
    document.getElementById('month').value = String(currentMonth).padStart(2, '0');
    
    // Загружаем данные
    loadArchiveData();
});

function loadArchiveData() {
    let year = document.getElementById('year').value;
    let month = document.getElementById('month').value;
    let archiveContent = document.getElementById('archiveContent');
    
    // Показываем индикатор загрузки
    archiveContent.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Загрузка...</span></div></div>';
    
    // Используем jQuery для AJAX запроса
    $.ajax({
        url: 'obr_index.php',
        method: 'GET',
        data: {
            date: year + '-' + month
        },
        success: function(response) {
            archiveContent.innerHTML = response;
        },
        error: function() {
            archiveContent.innerHTML = '<div class="alert alert-danger">Ошибка загрузки данных</div>';
        }
    });
}

function liveSearch() {
    let searchTerm = document.getElementById('spterm').value.toLowerCase();
    let items = document.querySelectorAll('#skrivat');
    
    items.forEach(item => {
        let searchValue = item.querySelector('.search_view').getAttribute('data-value').toLowerCase();
        if (searchValue.includes(searchTerm)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>

<?php
include 'inc/foot.php';
?>