<?php
/*管理画面の商品詳細*/
$genre_id = @$_POST['genre_id'];
$merchandise_id = @$_POST['merchandise_id'];
$message = '';
try {
    require "data_base.php";
    $pdo = data_base();
    $sql = genre_merchandise();
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$genre_id, $merchandise_id]);
    $cnt = $stmt->rowCount(); //取得件数
    if($cnt == 0) throw new PDOException('データベースに登録されていません。');
}catch(PDOException $ex){
    $message = $ex->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <link rel="stylesheet" href="css/search_merchandise_detail.css">
    <link rel="stylesheet" href="css/mobile_search_merchandise_detail.css" media="screen and (max-width:400px)"><!--モバイル用-->
</head>
<body>
<header class="header">
    <a href="management.php"><img src="img/header_name.png" alt="画像" class="header_log"></a>
    <p class="head_border"></p>
</header>
<?php
foreach ($stmt as $row){
    echo '<h2 class="merchandise_name">',htmlspecialchars($row['merchandise_name']),'</h2>';
    echo '<p class="image"><img src="',htmlspecialchars($row['image']),'" alt="商品画像" class="image_img"></p>';
    echo '<p class="price">商品価格：　　　¥<span class="cart_price">', htmlspecialchars(number_format($row['price'])),'</span></p>';
    echo '<p class="detail">商品詳細：</p>';
    echo '<p class="detail_contents">',htmlspecialchars($row['merchandise_detail']),'</p>';
}
echo '<p style="text-align: center;">',$message,'</p>';
?>
<form action="management.php" method="post"><p style="text-align: center;"><button type="submit" value="send" class="top_btn">top</button></p></form>
</body>
</html>
