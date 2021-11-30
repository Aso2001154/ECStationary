<?php
session_start();
$user_number = $_SESSION['user']['user_number'];
$cart_quantity = @$_POST['cart_quantity'];
$cart_genre_id = @$_POST['genre_id'];
$cart_merchandise = @$_POST['merchandise_id'];
$from_btn_message = 'top';
$su = 0;

require "data_base.php";
$pdo = data_base();


$sql = 'SELECT * FROM `cart` WHERE `user_number` = ?';
    $stmt = $pdo->prepare($sql);
    $stmt -> execute([$user_number]);
    $cnt = $stmt -> rowCount(); //取得件数
    if($cnt == 0){
        $su = 1;
    }else {
        foreach ($stmt as $row){
            $su = $row['cart_count'];
        }
        $su++;
    }
        $sql1 = add_cart();//別のファイルから呼び出す
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute([$user_number, $su, $cart_genre_id, $cart_merchandise,$cart_quantity]);

?>
<!DOCTYPE html>
<html la="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/mobile_cart.css" media="screen and (max-width:400px)">
    <title>カートに追加</title>
</head>
<body>
<header class="header">
    <p class="head_border"><img src="img/header_name.png" alt="画像" class="header_name"></p>
</header>
<p class="cart_add" style="margin-bottom: 50px;">カートに追加しました</p>
<?php echo '<form action="pencil.php" method="post"><p style="text-align: center;"><button type="submit" value="send" class="back_login">',$from_btn_message,'</button></p></form>'; ?>
</body>
</html>
