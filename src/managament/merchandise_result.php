<?php
/*管理画面の商品情報登録完了、失敗*/
$message='商品の登録完了';
$flg=0;
require "data_base.php";
$pdo = data_base();

$sql= $pdo->prepare('SELECT * FROM merchandise WHERE genre_id = ?');
$sql->execute([$_POST['genre']]);
foreach ($sql as $row){
    $max_merchandise = $row['merchandise_id'];
}
$max_merchandise = $max_merchandise+ 1;
$sql = $pdo->prepare('SELECT * FROM merchandise WHERE merchandise_name = ?');
$sql->execute([$_POST['goods']]);
$cnt=$sql->rowCount();
if($cnt == 0) {
    //フロントに画像を保存
    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
        if (!file_exists('img')) {
            mkdir('img');
        }
        $file = '../img/'.basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
            $file = 'img/'.basename($_FILES['file']['name']);
            $sql = $pdo->prepare('INSERT INTO merchandise(genre_id,merchandise_id,merchandise_name,price,image,merchandise_detail,stock)
                                            VALUES (?,?,?,?,?,?,?)');
            $sql->bindValue(1, htmlspecialchars($_POST['genre']), PDO::PARAM_STR);
            $sql->bindValue(2, htmlspecialchars($max_merchandise), PDO::PARAM_STR);
            $sql->bindValue(3, htmlspecialchars($_POST['goods']), PDO::PARAM_STR);
            $sql->bindValue(4, htmlspecialchars($_POST['price']), PDO::PARAM_STR);
            $sql->bindValue(5, htmlspecialchars($file), PDO::PARAM_STR);
            $sql->bindValue(6, htmlspecialchars($_POST['detail']), PDO::PARAM_STR);
            $sql->bindValue(7, htmlspecialchars(500), PDO::PARAM_STR);
            $sql->execute();
        } else {
            $message='商品の登録失敗';
            $flg=1;
        }
    } else {
        $message='商品の登録失敗';
        $flg=1;
    }

    //管理画面に画像を保存
    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
        if (!file_exists('img')) {
            mkdir('img');
        }
        $file = 'img/'.basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
        } else {
            $message='商品の登録失敗';
            $flg=1;
        }
    } else {
        $message='商品の登録失敗';
        $flg=1;
    }
}else{
    $message='商品の登録失敗';
    $flg=1;
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/merchandise_signup.css">
    <title>商品情報登録</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
<header class="header">
    <a href="management.php"><img src="img/header_name.png" alt="ヘッダー画像" class="header_name"></a>
    <p class="head_border"></p>
</header>
<?php

if($flg == 0){
    // 無事に登録完了
    echo '<h2 class="message">',$message,'</h2>';
    echo '<form action="management.php" method="post">';
    echo '<button type="submit" value="send" class="result_btn">top</button>';
    echo '</form>';
}else if($flg == 1){
// 登録失敗
    echo '<h2 class="message">',$message,'</h2>';
    echo '<form action="merchandise_signup.php" method="post">';
    echo '<button type="submit" value="send" class="product_btn">product</button>';
    echo '</form>';
}
//DBの接続解除
$pdo = null;
?>
</body>
</html>