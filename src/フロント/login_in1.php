<?php
session_start();
unset($_SESSION['user']);
$user_id = @$_POST['user_id'];
$user_pass = @$_POST['user_pass'];
$branch = 0; //IDとパスワードがデータベースにある場合
$login_flg = 1;

require "data_base.php";
$pdo = data_base();//別のファイルから呼び出し
$sql = user();//別のファイルから呼び出し
    $stmt = $pdo->prepare($sql);
    $stmt -> execute([$user_id,$user_pass]);
    $cnt = $stmt -> rowCount(); //取得件数
    if($cnt == 0){ $branch = 1; //IDとパスワードがデータベースにない場合
    }else {foreach ($stmt as $row) {
            $_SESSION['user'] = ['user_number'=>$row['user_number'],'user_name'=>$row['user_name']];
          }
          $login_flg = 0;
    }
    if($login_flg==1){
        $message = "ID、パスワードを間違っています。";
    }else{
        $message = "ログインに成功しました。";
    }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/information.css"><!-- login_in.php、login_in1.php、signup_in.php、signup_out.php、logout.php、information.php、information_out.php -->
    <link rel="stylesheet" href="css/mobile_information.css" media="screen and (max-width:400px)">
    <title>ログイン</title>
</head>
 <body>
 <header class="header">
     <p class="head_img"><img src="img/header_name.png" alt="画像" class="header_log"></p>
 </header>
 <?php if($branch==0){
     echo '<form action="pencil.php" method="post">';
     echo '<p class="login_message">',$message,'</p>';
     echo '<p class="message">沢山買いましょう!!</p>';
     echo '<p style="text-align: center;"><button type="submit" class="back_login">top</button></p>';
     echo '</form>';
     }else{
     echo '<div class="container_info">';
     echo '<form action="login_in.php" method="post">';
     echo '<h1 class="title">login</h1>';
     echo '<p class="error">',$message,'</p>';
     echo '<h2 class="sub">id</h2>';
     echo '<p class="txt"><input type="text" class="text" name="user_id"></p>';
     echo '<h2 class="sub">pass</h2>';
     echo '<p class="txt"><input type="text" class="text" name="user_pass"></p>';
     echo '<input type="hidden" name="login_flg" value="1">';
     echo '<p style="text-align: center;"><button type="submit" class="login_in">login</button></p>';
     echo '</form>';
     echo '</div>';
     echo '<p class="link_a"><a href="signup_in.php">sign up</a></p>';
     }
 ?>
 </body>
</html>
