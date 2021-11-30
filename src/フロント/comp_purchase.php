<?php
session_start();
$user_number = $_SESSION['user']['user_number'];
$box_genre_id = @$_POST['box_genre_id']; // 配列で商品のジャンルIDを受け取る
$box_merchandise_id = @$_POST['box_merchandise_id'];// 配列で商品のIDを受け取る
$box_quantity = @$_POST['box_quantity'];// 配列で商品の個数を受け取る
$box_price = @$_POST['box_price'];// 配列で商品の金額を受け取る
$all_price = @$_POST['sum']; // 合計金額を受け取る
$sq= 0; // history_idの保管場所として利用する
$flag = 0; // 在庫があるかどうかを判断するための変数
$a = 0;//history_purchaseのinsertを1回しか行わせないための変数
$count = count($box_genre_id);
$message = '<p style="text-align: center;">購入完了しました。</p>';
$error_message = '';
require "data_base.php";
$pdo = data_base();

for($i = 0;$i < $count;$i++) {
    $number = $pdo->prepare(genre_merchandise());
    $number->execute([$box_genre_id[$i], $box_merchandise_id[$i]]);
    foreach ($number as $st) {
        if ($st['stock'] < 10) {
            $flag = 1;//フラグが1の場合は在庫が10未満の商品が含まれているという状態・フラグが0の場合は在庫が10より多くあるため購入可能
            if($i==0){
                $message = '';
            }
            while($i<$count){
                $all_price = $all_price - $box_quantity[$i];
                $i++;
            }
            $error_message = '<p style="text-align: center;">在庫が足りない商品があり購入完了していない商品があります。</p>';
            break 2;//在庫が10未満の商品を購入しようとしたら強制的にfor文を終わらせる
        }else{
            // この処理は1回しか行わないため$iで回数を保持している
            if($a==0) {
                date_default_timezone_set('Japan');
                $sql = 'INSERT INTO `history_purchase`
            (`user_number`, `purchase_day`,`all_price`)
            VALUES(?,?,?)';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user_number, date('Y-m-d'), $all_price]);
            }

            // 一番大きいhistory_idをとってdetailの方で利用する
            $stmt1 = $pdo->prepare('SELECT max(`history_id`)as history_id FROM `history_purchase` WHERE `user_number` = ?');
            $stmt1->execute([$user_number]);
            foreach ($stmt1 as $row){
                $sq = $row['history_id'];
                echo $sq;
            }

            $sql2 = 'INSERT INTO `history_detail`
            (`history_id`, `history_genre_id`, `history_merchandise_id`,`history_price`,`history_quantity`)
            VALUES(?,?,?,?,?)';
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([$sq, $box_genre_id[$i], $box_merchandise_id[$i], $box_price[$i], $box_quantity[$i]]);

            $sql3 = 'UPDATE `merchandise` SET `stock` = `stock` - ? WHERE `genre_id` = ? AND `merchandise_id` = ?';
            $stmt3 = $pdo->prepare($sql3);
            $stmt3->execute([$box_quantity[$i], $box_genre_id[$i], $box_merchandise_id[$i]]);

            // 購入されたらカートテーブルから購入された商品の情報を消す(1行)
            $sql4 = 'SELECT * FROM `cart` WHERE `user_number` = ? LIMIT 1';
            $stmt4 = $pdo->prepare($sql4);
            $stmt4->execute([$user_number]);
            foreach ($stmt4 as $item) {
                $cart_count = $item['cart_count'];
            }

            $sql5 = 'DELETE FROM `cart` WHERE `user_number` = ? AND `cart_count` = ?';
            $stmt5 = $pdo->prepare($sql5);
            $stmt5->execute([$user_number,$cart_count]);

            $a = 1;
        }
    }
}

// カートに何個商品あるか　$countが0:カートに何もない時に行う　1:1個以上カートに商品がある時に行う
if($count!=0) {
    // $flagが0:問題なくすべての商品の購入処理を行う 1:在庫が10未満の商品がある
    if ($flag == 1) {
        //在庫が10未満の商品分の金額を減らしたうえで売上として更新する
        $sql3 = 'UPDATE `history_purchase` SET `all_price` = ? WHERE `history_id` = ?';
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->execute([$all_price,$sq]);
    }
}else{
    //なにもカートの中に商品がない場合
    $message = '<p style="text-align: center;">カートに何も入っていません。</p>';
}

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
     <p><img src="img/header_name.png" alt="画像" class="header_name"></p>
     <p class="head_border"></p>
 </header>
 <?php echo $message; // 在庫があるかないかを出力する?>
 <?php echo $error_message; // 在庫があるかないかを出力する?>
 <form action="pencil.php" method="post"><p style="text-align: center;"><button type="submit" value="send" class="back_login">top</button></p></form>
 </body>
</html>
