<?php
session_start();
//$user_number = $_SESSION['user']['user_number'];
$cart_user_number = $_POST['cart_user_number'];
$cart_count = @$_POST['cart_count'];

require "data_base.php";
$pdo = data_base();

//update cart set cart_count = cart_count - 1 where cart_count > 2;
    $sql = 'DELETE FROM `cart` WHERE `user_number` = ? AND `cart_count` = ?';
    $stmt = $pdo->prepare($sql);
    $stmt -> execute([$cart_user_number,$cart_count]);
    $cnt = $stmt -> rowCount(); //取得件数
    $sql1 = 'UPDATE `cart` SET `cart_count` = `cart_count` - 1 WHERE `cart_count` > ? AND `user_number` = ?';
$stmt = $pdo->prepare($sql);
$stmt -> execute([$cart_count,$cart_user_number]);
$cnt = $stmt -> rowCount(); //取得件数

?>
<!DOCTYPE html>
<html>
 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cart.css"><!-- cart.php、delete.php、comp_purchase.php、add_cart.php、 -->
     <link rel="stylesheet" href="css/mobile_cart.css" media="screen and (max-width:400px)">
 </head>
 <body>
 <header class="header">
     <p class="head_border"><img src="img/header_name.png" alt="画像" class="header_name"></p>
 </header>
 <p class="cart_delete">削除完了</p>
 <form action="cart.php" method="post"><p style="text-align: center;"><button type="submit" value="send" class="back_login">cart</button></p></form>
 </body>
</html>
