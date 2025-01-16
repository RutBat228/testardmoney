<?php
session_start();
include("inc/function.php"); // Тут висят все функции сайта.
echo '<!doctype html><html lang="ru">';
include("inc/style.php"); // тег head в котором указываются все стили сайта
echo '<body style = "background: #ffffff url(img/background.webp) repeat;">';
echo '<div class="container-sm">';
?>
<main role="main">
    <div class="jumbotron">
        <div style = "display: grid;place-items: center;" >
			<?
if ($_COOKIE['first_auth'] != "new") {
	setcookie('first_auth', "new", time() + 60 * 60 * 24 * 3650, '/');
	red_index('hello.php');
}
if (isset($_GET['err'])) {
	//ОШИБКА АВТОРИЗАЦИИ
	$error = h(e($_GET['err']));
	//alrt("Ошибка $error", "danger", "2");
?>
	<script type="text/javascript">
		alert('Ошибка <?= $error ?>')
		document.location.replace("auth.php");
	</script>
<?php
	exit();
}

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
    </style>
<?
if (isset($_GET['reg'])) { ?>

	<head>
		<title>Регистрация</title>
	</head>
	</nav>
	<div style="text-align: center;">
		<img src="img/mail.png" width="25%" style="padding: 30px 0 0;" alt="mail">
		<div style="padding: 30px 30px 20px;color: black;font-weight: 500;">
			Введите свою рабочую почту которуя закреплена за вами в базе данных Ардинвест.<br>
			Это нужно что бы пользоватся могли только сотрудники СКС.<br>
			Если возникли проблемы то напишите администратору в <a href="https://rutbat.t.me">Telegram</a>.
		</div>
	</div>
	<form style="padding: 20px;" method="GET" action="doreg.php">
		<label for="mail"></label><input type="email" autocomplete="off" id="mail" name="email" class="form-control" required placeholder="Введите email">
		<div id="display"></div>
		<div class="d-grid gap-2">
			<button type="submit" style="margin: 20px 0 0;" class="btn bg-warning btn-lg">Регистрация</button>
		</div>
	</form>
	</div>
<?php
	include 'inc/foot.php';
	exit();
}
///////////////////////////////////////закончилась регистрация///////////////////////////////////////////////////////////
//////АВТОРИЗАЦИЯ
?>

<head>
	<title>Авторизация</title>
</head>
	<form method="POST" action="auth_obr.php">

	<div class="auth-container">
        <img src="img/logo.webp" alt="Логотип">
        <p>Авторизуйтесь или создайте новый профиль.</p>
        
            <div class="mb-3">
                <input name="login" type="text" class="form-control" placeholder="Введите логин" required>
            </div>
            <div class="mb-3">
                <input name="pass" type="password" class="form-control" placeholder="Пароль" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Войти</button>
        
        <a href="auth.php?reg" class="btn btn-secondary w-100 mt-3">Новый пользователь?</a>
        <a href="https://rutbat.t.me">Забыли пароль?</a>
    </div>





	</form>

	<?php ///низ сайта
	include 'inc/foot.php';
	?>