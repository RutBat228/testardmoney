<?php
include "inc/head.php";
AutorizeProtect();
global $connect;
global $usr;
?>
<ul class="list-group">
    <li class="list-group-item">
        <?php
        $uploaddir = 'img/';
        $apend = "$usr[name].png";
        $uploadfile = "$uploaddir$apend";

        // Проверяем тип файла и его размер
        if (($_FILES['userfile']['type'] == 'image/gif' || 
             $_FILES['userfile']['type'] == 'image/jpeg' || 
             $_FILES['userfile']['type'] == 'image/png') && 
            ($_FILES['userfile']['size'] != 0 && $_FILES['userfile']['size'] <= 1024000000)) {

            // Функция для изменения размера изображения
            function resize($image, $w_o = false, $h_o = false) {
                if (($w_o < 0) || ($h_o < 0)) {
                    echo "Некорректные входные параметры";
                    return false;
                }
                list($w_i, $h_i, $type) = getimagesize($image);
                $types = array("jpg", "gif", "jpeg", "png");
                $ext = $types[$type];
                if ($ext) {
                    $func = 'imagecreatefrom'.$ext;
                    $img_i = $func($image);
                } else {
                    echo 'Некорректное изображение';
                    return false;
                }
                if (!$h_o) $h_o = $w_o / ($w_i / $h_i);
                if (!$w_o) $w_o = $h_o / ($h_i / $w_i);
                
                $img_o = imagecreatetruecolor($w_o, $h_o);
                imagecopyresampled($img_o, $img_i, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);
                $func = 'image'.$ext;
                return $func($img_o, $image);
            }

            // Загружаем и обрабатываем файл
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                $uploadfile = resize("$uploadfile", 128, 128);
                $size = getimagesize($uploadfile);

                if ($size[0] < 12800 && $size[1] < 12500) {
                    alrt("Файл загружен", "success", "1");
                    redir("user", "1");
                } else {
                    echo "Загружаемое изображение превышает допустимые нормы (ширина не более - 800; высота не более 1500)";
                    unlink($uploadfile);
                }
            } else {
                echo "Файл не загружен, вернитеcь и попробуйте еще раз";
            }
        } else {
            echo "Размер файла не должен превышать 1mb";
        }
        ?>
    </li>
</ul>
<?php include 'inc/foot.php';?>