<?php
session_start();
include("inc/function.php"); // Тут висят все функции сайта.
echo '<!doctype html><html lang="ru">';
include("inc/style.php"); // тег head в котором указываются все стили сайта
echo '<body style = "background: #ffffff url(img/background.webp) repeat;">';
echo '<div class="container-sm">';
?>
<main role="main">
    <div class="jumbotron" style = "padding: 9% 0;" >
        <div style="display: grid;place-items: center;">
            <?
AutorizeProtect();
access();
global $connect;
global $usr;
?>
    <style>
        body {
            background: linear-gradient(133deg, #122f18ed, #323331c2);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .auth-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 90px rgba(0, 0, 0, 0.5);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .auth-container img {
            width: 100%;
            margin-bottom: 1.5rem;
        }
        .auth-container h1 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .auth-container .btn-primary {
            background-color: #FFA726;
            border: none;
            padding: 0.6rem 1.2rem;
        }
        .auth-container .btn-secondary {
            background-color: #EF5350;
            border: none;
            color:white;
            padding: 0.6rem 1.2rem;
        }
        .auth-container a {
            display: block;
            margin-top: 1rem;
            color: #616161;
            text-decoration: none;
        }
        .auth-container a:hover {
            color: #000;
        }
        .pizdec {
    margin: auto;
    width: 95%;
    text-align: justify;
    padding: 0px 0px 0.1rem 1rem;
}
.montaj_input {
    padding: 1rem 0 0 0;
}
.montaj_textarea {
    width: 100%;
}
    </style>
<head>
    <title>Добавить работу</title>
</head>
<form method="GET" action="add_mon.php" style="
    font-family: system-ui;
">
    <div class="auth-container" >
    <a href = "/" ><img src="img/logo.webp" alt="Логотип"></a>

        <hr style = "margin: -1rem 0;">
        <div class="montaj_input">
            <input style = "    background: #3b46321f;
    border-radius: 0.5rem;
    border: 2px solid #36412fa6;
    margin: 1rem 0 0 0;
    color: #000;" autofocus list="provlist" id="search" type="text" name="adress" class="form-control" required title="Введите от 4 символов" placeholder="Введите адрес">
            <div id="display"></div>
        </div>
        <script type="text/javascript" src="/js/searcher.js"></script>
        <br>
        <div class="mb-3" >
            <textarea style = "    background: #3b46321f;
    border-radius: 0.5rem;
    border: 2px solid #36412fa6;
    margin: 1rem 0 0 0;
    color: #000;" placeholder="Что там делал(кратко)" name="text" class="form-control montaj_textarea" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>
        <ul id="search-results" class="list-group" style = 'text-align: justify;width: 95%;    margin: auto;    padding: 0rem 0rem 0.5rem 0.5rem;' ></ul>










        <script>
            $(document).ready(function() {
                // Функция поиска
                function search() {
                    var searchTerm = $("#exampleFormControlTextarea1").val();
                    $.ajax({
                        url: "/search_4todelal.php", // путь к обработчику запросов
                        method: "POST",
                        data: {
                            search: searchTerm
                        },
                        success: function(data) {
                            // Очищаем список результатов
                            $("#search-results").empty();
                            // Если есть результаты поиска, выводим их в виде списка
                            if (data.length > 0) {
                                var results = JSON.parse(data);
                                for (var i = 0; i < results.length; i++) {
                                    $("#search-results").append("<li class='search-result list-group-item ' >" + results[i].text + "</li>");
                                }
                                // При нажатии на результат, записываем его в поле ввода
                                $(".search-result").click(function() {
                                    var selectedText = $(this).text();
                                    $("#exampleFormControlTextarea1").val(selectedText);
                                    $("#search-results").empty();
                                });
                            }
                        }
                    });
                }

                // Выполняем поиск при каждом вводе в поле ввода
                $("#exampleFormControlTextarea1").on("input", function() {
                    search();
                });
            });
        </script>







        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Varela+Round&amp;display=swap'>
        <link rel="stylesheet" href="css/checbox.css">
        <br>
        <div class='form-text text-center fw-bold pb-4'>Кто был?</div>
        <?
        $sql = "SELECT * FROM `user` WHERE `region` = '" . $usr['region'] . "' ORDER BY `brigada` ";
        $res_data = mysqli_query($connect, $sql);
        while ($tech = mysqli_fetch_array($res_data)) {
        ?>
            <div class="form-check">
                <div id="checklist" class="form-check">
                    <input type="checkbox" value="<?= $tech['fio'] ?>" name="technik[]" id="flexCheckDefault<?= $tech['id'] ?>">
                    <label for="flexCheckDefault<?= $tech['id'] ?>"> <?= $tech['fio'] ?></label>

                </div>
            </div>
        <?
        }
        ?>
        <input type="hidden" value="<?= $usr['region'] ?>" name="region">
        <div class="d-grid gap-2 ">
            <button type="submit" class="btn btn-lg" style="background: #445e3b;
    border-radius: 1rem;
    border: 2px solid #2c3c26d1; margin: 3rem 0rem 1rem 0rem;color:#fff">Добавить монтаж</button>
        </div>
    </div>
    <br>
    </div>
    <div data-role="footer">



</form>
<?php include 'inc/foot.php';
