<?php
include "inc/head.php";
AutorizeProtect();
?>

<head>
	<title>NavigArd - ваш навигатор доступа к оборудованию</title>
	<script type="text/javascript" src="searcher.js"></script>
</head>

<div class="hero-section">
	<div class="container">
		<div class="text-center">
			<h1 class="brand-name mb-2">NavigArd</h1>
			<p class="subtitle mb-4">ваш навигатор доступа к оборудованию</p>
			
			<div class="search-example mb-4">
				<p class="mb-0">Пример: <span class="example-text">Набережная 85</span> или просто <span class="example-text">Набережная</span></p>
			</div>

			<div class="search-container mx-auto ">
				<form id="navigard_search" method="GET" action="result.php" class="search-form">
					<div class="d-flex gap-2">
						<div class="flex-grow-1">
							<input type="text" 
								   autocomplete="off" 
								   id="search" 
								   name="adress" 
								   class="form-control search-input" 
								   required
								   title="Введите от 4 символов" 
								   placeholder="Введите адрес">
							<div id="display"></div>
						</div>
						<button type="submit" class="btn search-button px-3">
							<i class="fas fa-search"></i> Поиск
						</button>
					</div>
				</form>
			</div>

			<a href="ins.php" class="instruction-link d-inline-flex align-items-center mt-4">
				<i class="fas fa-info-circle me-2"></i> Инструкция пользования
			</a>
		</div>
	</div>
</div>

<?php include 'inc/foot.php';?>