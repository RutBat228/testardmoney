<?php
session_start(); // Начинаем сессию
include "inc/head.php"; // Подключаем head.php, где уже есть Bootstrap
AutorizeProtect();
access();
animate();
if ($_COOKIE['user'] != "RutBat") {
    echo 'Тебе тут не место!!!';
    exit;
}

// Подключение к базе данных
if (!isset($connect)) {
    die("Ошибка: переменная подключения к базе данных не определена.");
}

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? null;
    $color = $_POST['color'] ?? null;
    $razdel = $_POST['razdel'] ?? null;
    $icon = $_POST['icon'] ?? null;

    if (isset($_POST['create'])) {
        $stmt = $connect->prepare("INSERT INTO material (name, color, razdel, icon) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $color, $razdel, $icon);
        $stmt->execute();
        $_SESSION['message'] = "Материал добавлен!"; // Сохраняем сообщение в сессии
    } elseif (isset($_POST['update'])) {
        $stmt = $connect->prepare("UPDATE material SET name = ?, color = ?, razdel = ?, icon = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $color, $razdel, $icon, $id);
        $stmt->execute();
        $_SESSION['message'] = "Материал обновлен!"; // Сохраняем сообщение в сессии
    } elseif (isset($_POST['delete'])) {
        $stmt = $connect->prepare("DELETE FROM material WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $_SESSION['message'] = "Материал удален!"; // Сохраняем сообщение в сессии
    }
    header("Location: adm_material.php");
    exit;
}

// Получение данных для отображения
$result = $connect->query("SELECT * FROM material");
$materials = $result->fetch_all(MYSQLI_ASSOC);

// Список иконок для выбора
$icons = ["bi bi-asterisk", "bi bi-router", "bi bi-inbox-fill", "bi bi-usb-fill", "bi bi-usb-drive-fill", "bi bi-stop-circle-fill", "bi bi-film", "bi bi-border-width", "bi bi-motherboard-fill", "bi bi-plugin", "bi bi-plug-fill"];
$colors = ["red", "mediumseagreen", "darkorange", "blue", "brown", "deeppink", "black", "purple"];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Управление Материалами</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="padding: 0;">
    <div class="container-fluid" style="background: #00000070;">
        <a class="navbar-brand" href="#"></a>
        <div class="navbar-collapse" id="navbarNavDarkDropdown">
            <ul class="navbar-nav rut_nav">
                <?php
                if (!empty(htmlentities($_COOKIE['user']))) {
                ?>
                    <ul style="float: right;">
                        <li>
                            <a href="user.php">
                                <img src="/img/home.png" style="width: 40px;padding-bottom: 7px;">
                            </a>
                        </li>
                    </ul>
                <?php
                } ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <h1>Управление материалами</h1>

    <!-- Форма для создания/обновления -->
    <form method="POST" class="mb-4" id="materialForm">
        <input type="hidden" name="id" id="id">
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="color" class="form-label">Цвет</label>
            <select class="form-select" id="color" name="color">
                <?php foreach ($colors as $color) { ?>
                    <option value="<?php echo $color; ?>" style="background: <?php echo $color; ?>;"><?php echo $color; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="razdel" class="form-label">Раздел</label>
            <select class="form-select" id="razdel" name="razdel" required>
                <option value="Аккумуляторы">Аккумуляторы</option>
                <option value="Другое">Другое</option>
                <option value="Инверторы">Инверторы</option>
                <option value="Кабель">Кабель</option>
                <option value="Медики">Медики</option>
                <option value="Онушки">Онушки</option>
                <option value="Приставки">Приставки</option>
                <option value="Роутеры">Роутеры</option>
                <option value="Трансы">Трансы</option>
                <option value="Управляхи">Управляхи</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="icon" class="form-label">Иконка</label>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="iconDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i id="currentIcon" class="bi bi-asterisk"></i> Выберите иконку
                </button>
                <ul class="dropdown-menu" aria-labelledby="iconDropdown">
                    <?php foreach ($icons as $icon) { ?>
                        <li>
                            <a class="dropdown-item" href="#" onclick="selectIcon('<?php echo $icon; ?>', '<?php echo $icon; ?>')">
                                <i class="<?php echo $icon; ?>"></i> <?php echo $icon; ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <input type="hidden" id="icon" name="icon">
            </div>
        </div>
        
        <div class="d-grid gap-2">
            <button type="submit" name="create" class="btn btn-success btn-lg" id="addButton">Добавить</button> <!-- Кнопка добавления -->
            <button type="submit" name="update" class="btn btn-warning btn-lg" id="updateButton" style="display: none;">Обновить</button> <!-- Кнопка обновления -->
        </div>
        
        <!-- Кнопка для выхода из режима редактирования -->
        <div class="d-grid gap-2 mt-2">
            <button type="button" class="btn btn-secondary btn-lg" id="cancelEditButton" style="display: none;">Отменить редактирование</button>
        </div>
    </form>

    <!-- Таблица материалов -->
    <div class="table-responsive">
        <table class="table table-bordered" style="font-size: small;">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Цвет</th>
                <th>Раздел</th>
                <th>Иконка</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($materials as $material) { ?>
                <tr>
                    <td><?php echo $material['id']; ?></td>
                    <td><?php echo $material['name']; ?></td>
                    <td style="background: <?php echo $material['color']; ?>;">&nbsp;</td>
                    <td><?php echo $material['razdel']; ?></td>
                    <td><i class="<?php echo $material['icon']; ?>"></i></td>
                    <td>
                        <div class="d-flex justify-content-start">
                            <button style="color: green;" class="btn btn-link" onclick="editMaterial(<?php echo htmlspecialchars(json_encode($material)); ?>)" title="Редактировать">
                                <i class="bi bi-pencil" style="font-size: 1.5rem;"></i>
                            </button>
                            <button style="color: red;" class="btn btn-link" title="Удалить" onclick="openDeleteModal(<?php echo $material['id']; ?>)">
                                <i class="bi bi-trash" style="font-size: 1.5rem;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    let deleteId = null;

    $(document).ready(function() {
        // Инициализация DataTable с русской локализацией
        $('.table').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/2.2.1/i18n/ru.json" // Актуальный URL для русской локализации
            }
        });

        // Проверка наличия сообщения в сессии
        <?php if (isset($_SESSION['message'])): ?>
            Swal.fire({
                title: 'Уведомление',
                text: '<?php echo $_SESSION['message']; ?>',
                icon: 'success',
                confirmButtonText: 'Закрыть'
            });
            <?php unset($_SESSION['message']); // Сбрасываем сообщение после отображения ?>
        <?php endif; ?>

        // Обработчик события для кнопки подтверждения удаления
        document.getElementById('confirmDeleteButton').addEventListener('click', function() {
            $.ajax({
                type: 'POST',
                url: 'adm_material.php',
                data: {
                    id: deleteId,
                    delete: true // Указываем, что это запрос на удаление
                },
                success: function(response) {
                    // Отображаем уведомление об успешном удалении
                    Swal.fire({
                        title: 'Уведомление',
                        text: 'Материал удален!',
                        icon: 'success',
                        confirmButtonText: 'Закрыть'
                    }).then(() => {
                        location.reload(); // Перезагрузка страницы после закрытия уведомления
                    });
                },
                error: function() {
                    alert('Ошибка при удалении материала.');
                }
            });
        });

        // Обработчик события для кнопки отмены редактирования
        document.getElementById('cancelEditButton').addEventListener('click', function() {
            // Скрываем форму обновления и показываем форму добавления
            document.getElementById('updateButton').style.display = 'none';
            document.getElementById('addButton').style.display = 'block';
            document.getElementById('cancelEditButton').style.display = 'none';
            document.getElementById('name').value = ''; // Очищаем поля
            document.getElementById('color').value = ''; 
            document.getElementById('razdel').value = ''; 
            document.getElementById('icon').value = ''; 
            document.getElementById('currentIcon').className = 'bi bi-asterisk'; // Сбрасываем иконку
        });
    });

    function openDeleteModal(id) {
        deleteId = id; // Сохраняем ID материала для удаления
        $('#confirmDeleteModal').modal('show'); // Показываем модальное окно
    }

    function editMaterial(material) {
        document.getElementById('id').value = material.id;
        document.getElementById('name').value = material.name;
        document.getElementById('color').value = material.color;
        document.getElementById('razdel').value = material.razdel;
        document.getElementById('icon').value = material.icon;
        document.getElementById('currentIcon').className = material.icon;

        // Скрываем кнопку добавления и показываем кнопку обновления
        document.getElementById('addButton').style.display = 'none';
        document.getElementById('updateButton').style.display = 'block';
        document.getElementById('cancelEditButton').style.display = 'block'; // Показываем кнопку отмены

        // Прокрутка вверх
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function selectIcon(icon, iconName) {
        document.getElementById('icon').value = icon;
        document.getElementById('currentIcon').className = icon;
    }
</script>

<!-- Модальное окно для подтверждения удаления -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Подтверждение удаления</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Вы уверены, что хотите удалить этот материал?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Удалить</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
