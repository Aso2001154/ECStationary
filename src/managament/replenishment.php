<?php
/*管理画面の商品補充*/
$genre_id = @$_POST['genre_id'];
$merchandise_id = @$_POST['merchandise_id'];
$login_message = '';
require "data_base.php";
$pdo = data_base();
$sql = 'update `merchandise` set `stock` =  `stock` + 500 WHERE `genre_id` = ? and `merchandise_id` = ?';
$stmt = $pdo->prepare($sql);
$stmt -> execute([$genre_id,$merchandise_id]);

?>
<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>在庫情報</title>
    <link rel="stylesheet" href="css/merchandise_stock.css">

</head>
<body>
<header>
    <a href="management.php"><img src="img/header_name.png" alt="画像" class="header_name"></a>
</header>
<p class="replenishment">商品を補充しました</p>
<?php
echo '<form action="stock.php" method="post">';
echo '<p style="text-align: center;"><button type="submit" value="send" class="top">top</button></p></form>';
?>
</body>
</html>