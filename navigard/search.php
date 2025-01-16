<?php
include("inc/db.php");

// Проверяем наличие поискового запроса
if (!empty($_POST["referal"])) {
    // Очищаем входные данные от вредоносного кода
    $referal = trim(strip_tags(stripcslashes(htmlspecialchars($_POST["referal"]))));
    
    // Выполняем поиск в базе данных
    $db_referal = $mysqli->query("SELECT * FROM navigard_adress WHERE adress LIKE '%$referal%'") 
        or die('Ошибка №'.__LINE__.'<br>Обратитесь к администратору сайта пожалуйста, сообщив номер ошибки.');
    
    // Выводим результаты поиска
    while ($row = $db_referal->fetch_array()) {
        echo "\n<li>".$row["adress"]."</li>";
    }
}
