<?php
include "inc/db.php";
global $connect;

if (isset($_POST['search'])) {
    // Получаем и экранируем поисковый запрос
    $Name = htmlspecialchars(htmlentities($_POST['search']));
    
    // Выполняем поиск в базе данных
    $Query = "SELECT * FROM navigard_adress WHERE adress LIKE '%$Name%' LIMIT 5";
    $ExecQuery = mysqli_query($connect, $Query);
    
    echo '<ul class="list-group">';
    
    // Выводим результаты поиска
    while ($Result = mysqli_fetch_array($ExecQuery)) {
        echo '<li class="list-group-item" style="padding:0.35rem 0 0.25rem 0.75rem" onclick="fill(\''.$Result['adress'].'\')">';
        echo '<a>'.$Result['adress'].'</a>';
        echo '</li>';
    }
    
    echo '</ul>';
}
?>