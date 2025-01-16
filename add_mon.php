<?php
include("inc/head.php");
access();
AutorizeProtect();
global $connect;
global $usr;
echo '<div class="contadiner">';
$adress = $region = $technik1 = $technik2 =  $technik3 =  $technik4 =  $technik5 =  $technik6 =  $technik7 =  $technik8 = $text = 0;

if(isset($_GET['adress'])){$adress   = h($_GET['adress'], ENT_QUOTES, "utf-8");}
if(isset($_GET['region'])){$region   = $_GET['region'];}
if(isset($_GET['technik']['0'])){$technik1 = $_GET['technik']['0'];}
if(isset($_GET['technik']['1'])){$technik2 = $_GET['technik']['1'];}
if(isset($_GET['technik']['2'])){$technik3 = $_GET['technik']['2'];}
if(isset($_GET['technik']['3'])){$technik4 = $_GET['technik']['3'];}
if(isset($_GET['technik']['4'])){$technik5 = $_GET['technik']['4'];}
if(isset($_GET['technik']['5'])){$technik6 = $_GET['technik']['5'];}
if(isset($_GET['technik']['6'])){$technik7 = $_GET['technik']['6'];}
if(isset($_GET['technik']['7'])){$technik8 = $_GET['technik']['7'];}
if(isset($_GET['text'])){$text     = h($_GET['text'], ENT_QUOTES, "utf-8");}
$date = date("Y-m-d H:i:s");

$dogovor    = 0;
if (empty($adress)) {
	echo 'Введите адрес монтажа';
	exit;
}
$user = $usr['name'];

$sql = "INSERT INTO montaj (adress, technik1, technik2, technik3, technik4, technik5, technik6, technik7, technik8, text, region, date, dogovor)
			VALUES (
			'$adress',
			'$technik1',
			'$technik2',
			'$technik3',
			'$technik4',
			'$technik5',
            '$technik6',
            '$technik7',
            '$technik8',
			'$text',
            '$region',
			'$date',
            '$dogovor'
			)";
if ($connect->query($sql) === true) {
} else {
	echo $connect->error;
}
$montaj = $connect->query("SELECT * FROM `montaj` ORDER BY id DESC limit 1");
if ($montaj->num_rows != 0)
	$mon = $montaj->fetch_array(MYSQLI_ASSOC);
$str = $mon['id'];
$id = base64_encode($str);



?>
<meta http-equiv="refresh" content="0;URL='result.php?vid_id=<?= $id ?>'">
</div>
<?php
include('inc/foot.php');
